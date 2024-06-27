<?php
class FormatLotes {
    public static function LotesFormatted() {
        $args = array(
            'post_type' => 'lote',
            'posts_per_page' => -1,
        );

        $query = new WP_Query($args);
        $lotes_data = [];

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $custom_fields = get_fields();
                $lote_number = $custom_fields['numero__de_lote'] ?? '';
                $lote_section = $custom_fields['bloque']->name ?? '';
                $lote_id = $lote_number . $lote_section;
                $none_value = "Sin valor";
                $precio_m2 = $custom_fields['precio_por_metro_cuadrado'] ?? "₡225 000";
                $superficie_total = !empty($custom_fields['medida_total_en_metros_cuadrados_m2']) ? $custom_fields['medida_total_en_metros_cuadrados_m2'] . "m²" : '';

                $lotes_data[] = [
                    "ID" => $lote_id,
                    "seccion" => $lote_section,
                    "lote" => $lote_number,
                    "precio_m2" => $precio_m2,
                    "superficie_total" => $superficie_total ?: $none_value,
                    "metros_fondo" => $custom_fields['fondo_en_metros'] ?? $none_value,
                    "metros_frente" => $custom_fields['frente_en_metros'] ?? $none_value,
                    "filial" => $custom_fields['filial'] ?? $none_value,
                    "estado" => $custom_fields['estado']['value'] ?? $none_value
                ];
            }
            wp_reset_postdata();
        }

        return $lotes_data;
    }
}
