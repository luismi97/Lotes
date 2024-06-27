<?php

class ReportData {
    private static $instance;
    private $data;
    private $disponible;
    private $reservado;
    private $vendido;

    private function __construct() {
        $this->data = [];
        $this->disponible = 'Disponible';
        $this->reservado = 'Reservado';
        $this->vendido = 'Vendido';
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getData() {
        return $this->data;
    }

    public function setData($key, $data) {
        $this->data[$key] = $data;
    }

    public static function get_total_lotes_by_state() {
        global $wpdb;
        $raw_query = "
            SELECT COUNT(posts.ID) AS cantidad, postmeta.meta_value
            FROM {$wpdb->prefix}posts AS posts
            INNER JOIN {$wpdb->prefix}postmeta AS postmeta ON posts.ID = postmeta.post_id
            WHERE post_type = %s AND postmeta.meta_key = %s
            GROUP BY postmeta.meta_value
        ";
        $params = ['lote', 'estado'];
        $results = $wpdb->get_results($wpdb->prepare($raw_query, $params));
        $results = self::reformat_result($results);
        self::getInstance()->setData('total_lotes_by_state', $results);
    }

    public static function get_total_lotes() {
        $args = [
            'post_type' => 'lote',
            'posts_per_page' => -1
        ];
        $the_query = new WP_Query($args);
        self::getInstance()->setData('post_count', $the_query->post_count);
    }

    public static function get_total_lotes_by_state_by_block() {
        global $wpdb;
        $raw_query = "
            SELECT terms.name, pm.meta_value AS bloque_id,
            COUNT(CASE WHEN pm2.meta_value = %s THEN 1 END) AS reserved_count,
            COUNT(CASE WHEN pm2.meta_value = %s THEN 1 END) AS available_count,
            COUNT(CASE WHEN pm2.meta_value = %s THEN 1 END) AS sold_count
            FROM {$wpdb->prefix}posts p
            JOIN {$wpdb->prefix}postmeta pm ON p.ID = pm.post_id
            JOIN {$wpdb->prefix}postmeta pm2 ON p.ID = pm2.post_id
            JOIN {$wpdb->prefix}terms terms ON pm.meta_value = terms.term_id
            WHERE pm.meta_key = %s AND pm2.meta_key = %s AND p.post_status = %s
            GROUP BY pm.meta_value, pm.meta_value
            ORDER BY terms.name
        ";
        $params = ['reservado', 'disponible', 'vendido', 'bloque', 'estado', 'publish'];
        $results = $wpdb->get_results($wpdb->prepare($raw_query, $params));
        self::getInstance()->setData('total_lotes_by_state_by_block', $results);
    }

    public static function get_all_lotes() {
        global $wpdb;
        $raw_query = "
            SELECT p.post_title lote, terms.name bloque, pm2.meta_value estado
            FROM {$wpdb->prefix}postmeta pm
            LEFT JOIN {$wpdb->prefix}posts p ON p.ID = pm.post_id
            LEFT JOIN {$wpdb->prefix}postmeta pm2 ON p.ID = pm2.post_id
            LEFT JOIN {$wpdb->prefix}terms terms ON pm.meta_value = terms.term_id
            WHERE pm.meta_key = %s AND p.post_status = %s AND pm2.meta_key = %s
            ORDER BY bloque, lote
        ";
        $params = ['bloque', 'publish', 'estado'];
        $results = $wpdb->get_results($wpdb->prepare($raw_query, $params));
        self::getInstance()->setData('all_lotes', $results);
    }

    private static function reformat_result($results) {
        $values = [];
        foreach ($results as $result) {
            $values[$result->meta_value] = $result->cantidad;
        }
        $disponibles = $values[self::getInstance()->disponible] ?? 0;
        $reservados = $values[self::getInstance()->reservado] ?? 0;
        $vendidos = $values[self::getInstance()->vendido] ?? 0;

        return [
            [self::getInstance()->disponible => $disponibles],
            [self::getInstance()->reservado => $reservados],
            [self::getInstance()->vendido => $vendidos]
        ];
    }
}
