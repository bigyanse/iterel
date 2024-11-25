<?php

if (!defined('ABSPATH')) {
    exit;
}

$iterel_options = get_option('iterel_options');

if(!empty($iterel_options) && (isset($iterel_options['iterel_field_enable_product_filter']) && $iterel_options['iterel_field_enable_product_filter'] === 'on') && !(count($iterel_options) === 1)) {
    /**
     * Add custom scripts
     */
    function iterel_scripts()
    {
        $version = get_plugin_data(__DIR__ . '/iterel.php')['Version'] ?? '0.1.0';

        wp_enqueue_style('iterel-product-filter', plugins_url('/css/product-filter.css', __DIR__ . '/iterel.php'), array(), $version);

        // Custom ajax functionality for product filter.
        wp_enqueue_script(
            'iterel-product-filter', plugins_url('/js/product-filter.js', __DIR__ . '/iterel.php'), array('jquery'), $version, array(
            'strategy' => 'defer',
            )
        );
        wp_localize_script(
            'iterel-product-filter', 'iterel_product_filter_ajax_obj', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('iterel_product_filter'),
            )
        );
    }
    add_action('wp_enqueue_scripts', 'iterel_scripts');

    /**
     * Actions for custom ajax functionality for product filter.
     */
    function iterel_product_filter()
    {
        check_ajax_referer('iterel_product_filter', 'nonce');
        $filters = $_POST['filters'];
        if(!empty($filters) && isset($filters['attributes'])) {
            $filters_cols = array_keys($filters['attributes']);
        }
        $tax_query = array(
        'relation' => 'AND',
        ); 
        foreach($filters_cols as $filter) {
            $tax_query[] = array(
            'taxonomy' => 'pa_' . $filter,
            'field' => 'slug',
            'terms' => $filters['attributes'][$filter],
            'operator' => 'IN',
            );
        }
        $args = array(
        'post_type' => 'product',
        'posts_per_page' => 12,
        'tax_query' => $tax_query,
        );
        if (isset($filters['search_query']) && !empty($filters['search_query'])) {
            $args['s'] = $filters['search_query'];
            $args['search_columns'] = array('post_title');
        }
        $price_range_min = intval($filters['price_range_min'] ?? 0);
        $price_range_max = intval($filters['price_range_max'] ?? 100000);
        $args['meta_query'] = array(
        'relation' => 'AND',
        [
        'key'     => '_price',
        'value'   => [$price_range_min, $price_range_max],
        'compare' => 'BETWEEN',
        'type'    => 'NUMERIC',
        ],
        );
        $loop = new WP_Query($args);
        $count = 0;
        if ($loop->have_posts()) {
            ob_start();
            while ($loop->have_posts()) {
                $loop->the_post();
                wc_get_template_part('content', 'product');
                ++$count;
            }
            $result = ob_get_contents();
            ob_end_clean();
            wp_reset_postdata();
        }
        wp_send_json(
            array(
            'success' => true,
            'data' => array(
            'count' => $count,
            'html' => $result,
            ),
            )
        );
        wp_die();
    }
    add_action('wp_ajax_iterel_product_filter', 'iterel_product_filter');
    add_action('wp_ajax_nopriv_iterel_product_filter', 'iterel_product_filter');

    /**
     * Extends woocommerce functionalites to add product filter to the left of the packages list
     *
     * @return void
     */
    function iterel_wc_before_shop_loop()
    {
        echo '<div class="row">';
        echo '<div class="col-md-3">';
        ob_start();
        include __DIR__ . '/template-parts/content-package-filter.php';
        $template_content = ob_get_contents();
        ob_end_clean();
        echo $template_content;
        echo '</div>';
        echo '<div class="pl-5 col-md-9">';
    }
    add_action('woocommerce_before_shop_loop', 'iterel_wc_before_shop_loop');

    /**
     * Extends woocommerce functionalites to add product filter to the left of the packages list
     *
     * @return void
     */
    function iterel_wc_after_shop_loop()
    {
        echo '</div></div>';
    }
    add_action('woocommerce_after_shop_loop', 'iterel_wc_after_shop_loop');
}
