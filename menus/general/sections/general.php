<?php
/**
 * General section
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * General section callback
 */
function iterel_section_general_cb($args) {
    //
}

/**
 * Custom fields for displaying search or not
 */
function iterel_field_display_search_cb($args) {
    $options = get_option('iterel_options');
    ?>
    <div>
        <input type="checkbox" name="iterel_options[<?php echo esc_attr($args['label_for']); ?>]" 
            <?php 
            // Check if the option is set and display the checkbox as checked
            if ( isset($options[$args['label_for']]) && $options[$args['label_for']] ) { 
                echo 'checked="checked"';
            }
            ?> />
    </div>
    <?php
}

/**
 * Custom fields whether to display filterer to filter by different attributes or not
 * @param mixed $args
 * @return void
 */
function iterel_field_filter_by_attribute_cb($args) {
    $options = get_option('iterel_options');
    ?>
    <div>
        <input type="checkbox" name="iterel_options[<?php echo esc_attr($args['label_for']); ?>]" 
            <?php 
            // Check if the option is set and display the checkbox as checked
            if ( isset($options[$args['label_for']]) && $options[$args['label_for']] ) { 
                echo 'checked="checked"';
            }
            ?> />
    </div>
    <?php
}

/**
 * Custom fields for displaying product price range selector
 * @param mixed $args
 * @return void
 */
function iterel_field_display_price_range_cb($args) {
    $options = get_option('iterel_options');
    ?>
    <div>
        <input type="checkbox" name="iterel_options[<?php echo esc_attr($args['label_for']); ?>]" 
            <?php 
            // Check if the option is set and display the checkbox as checked
            if ( isset($options[$args['label_for']]) && $options[$args['label_for']] ) { 
                echo 'checked="checked"';
            }
            ?> />
    </div>
    <?php
}

/**
 * Custom fields whether to enable product filter or not
 * @param mixed $args
 * @return void
 */
function iterel_field_enable_product_filter_cb($args) {
    $options = get_option('iterel_options');
    ?>
    <div>
        <input type="checkbox" name="iterel_options[<?php echo esc_attr($args['label_for']); ?>]" 
            <?php 
            // Check if the option is set and display the checkbox as checked
            if ( isset($options[$args['label_for']]) && $options[$args['label_for']] ) { 
                echo 'checked="checked"';
            }
            ?> />
    </div>
    <?php
}

/**
 * custom options and settings
 */
function iterel_settings_general_init() {
    // General section
    add_settings_section(
        'iterel_section_general',
        __('General',
        'iterel'),
        'iterel_section_general_cb',
        'iterel'
    );
    add_settings_field(
        'iterel_field_enable_product_filter',
        __('Enable Product Filter', 'iterel'),
        'iterel_field_enable_product_filter_cb',
        'iterel',
        'iterel_section_general',
        array(
            'label_for' => 'iterel_field_enable_product_filter',
            'class' => 'iterel_row',
        )
    );
    add_settings_field(
        'iterel_field_display_search',
        __('Display Search', 'iterel'),
        'iterel_field_display_search_cb',
        'iterel',
        'iterel_section_general',
        array(
            'label_for' => 'iterel_field_display_search',
            'class' => 'iterel_row',
        )
    );
    add_settings_field(
        'iterel_field_display_price_range',
        __('Display Price Range', 'iterel'),
        'iterel_field_display_price_range_cb',
        'iterel',
        'iterel_section_general',
        array(
            'label_for' => 'iterel_field_display_price_range',
            'class' => 'iterel_row',
        )
    );
    $attributes = wc_get_attribute_taxonomies();
    foreach($attributes as $attribute) {
        add_settings_field(
            'iterel_field_filter_by_ ' . $attribute->attribute_name,
            __('Filter by', 'iterel') . ' ' .  $attribute->attribute_label,
            'iterel_field_filter_by_attribute_cb',
            'iterel',
            'iterel_section_general',
            array(
                'label_for' => 'iterel_field_filter_by_' . $attribute->attribute_name,
                'class' => 'iterel_row',
            )
        );
    } 
}
add_action('admin_init', 'iterel_settings_general_init');