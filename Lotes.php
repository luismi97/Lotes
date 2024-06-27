<?php
/**
* Plugin Name: Lotes Reporter and SVG Parser
* Plugin URI: https://www.techbridgecr.com/
* Description: Lotes Reporter and SVG Parser
* Version: 0.1
* Author: Luis Miguel Molina Rodriguez
* Author URI: https://www.techbridgecr.com/
**/

// Load logic
require_once __DIR__ . '/Manager.php';
require_once __DIR__ . '/Admin/ReportData.php';
require_once __DIR__ . '/Admin/Report.php';
require_once __DIR__ . '/Admin/create_excel.php';
require_once __DIR__ . '/Admin/Rest.php';

/**
 * Enqueue scripts and styles for the interactive map.
 */
function interactive_map_handler() {
    wp_enqueue_style('map_style1', plugins_url('includes/style/style.css', __FILE__), [], 'version4');
    wp_enqueue_style('map_style2', plugins_url('includes/style/map.css', __FILE__), [], 'version4');

    wp_enqueue_script('map_script1', plugins_url('includes/script/svg-pan-zoom.min.js', __FILE__), ['jquery'], 'version4', true);
    wp_enqueue_script('map_script2', plugins_url('includes/script/hammer.js', __FILE__), ['jquery'], 'version4', true);
    wp_enqueue_script('map_script3', plugins_url('includes/script/script.js', __FILE__), ['jquery'], 'version4', true);
    wp_enqueue_script('lotesmodal', plugins_url('includes/script/lotesModal.js', __FILE__), ['jquery'], 'version4', true);

    ob_start();
    include(plugin_dir_path(__FILE__) . 'includes/main.php');
    return ob_get_clean();
}

add_shortcode('interactive_map', 'interactive_map_handler');

/**
 * Enqueue scripts selectively for the admin page.
 *
 * @param string $hook The current admin page.
 */
function wpdocs_selectively_enqueue_admin_script($hook) {
    if ('toplevel_page_reporte-de-lotes' !== $hook) {
        return;
    }
    wp_enqueue_script('chartjs', 'https://cdn.jsdelivr.net/npm/chart.js', [], '1.0', false);
    wp_enqueue_script('custom_chartjs', plugins_url('includes/script/customChart.js', __FILE__), ['chartjs'], time(), true);
}

add_action('admin_enqueue_scripts', 'wpdocs_selectively_enqueue_admin_script');
