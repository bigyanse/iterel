<?php

$iterel_options = get_option('iterel_options');
$iterel_keys = array_keys($iterel_options);

if(!empty($iterel_options)) {
    if(isset($iterel_options['iterel_field_display_search']) && $iterel_options['iterel_field_display_search'] === 'on') {
        $iterel_display_search = true;
    }
    if(isset($iterel_options['iterel_field_display_price_range']) && $iterel_options['iterel_field_display_price_range'] === 'on') {
        $iterel_display_price_range = true;
    }
    ?>
    <form class="iterel-product-filter d-flex flex-column">
        <?php if($iterel_display_search) { ?>
        <div class="py-2">
            <h3 class="h6"><?php _e('Search', 'iterel'); ?></h3>
            <div class="d-flex">
                <input name="iterel-product-filter-search" style="width: 90%;" type="text" placeholder="<?php _e('Search...', 'iterel'); ?>" />
                <button type="submit"><?php _e('Search', 'iterel'); ?></button>
            </div>
        </div>
        <?php }

        if($iterel_display_price_range) {
            ?>
        <div class="py-2">
            <h3 class="h6"><?php _e('Price Range', 'iterel'); ?></h3>
            <div class="iterel-product-filter-price-input-container">
                <div class="iterel-product-filter-price-input">
                    <div class="iterel-product-filter-price-field">
                        <span>Min:</span>
                        <input type="number" 
                                name="iterel-product-filter-price-min-input" 
                                class="iterel-product-filter-price-min-input" 
                                value="0">
                    </div>
                    <div class="iterel-product-filter-price-field">
                        <span>Max:</span>
                        <input type="number" 
                                name="iterel-product-filter-price-max-input" 
                                class="iterel-product-filter-price-max-input" 
                                value="100000">
                    </div>
                </div>
                <div class="iterel-product-filter-slider-container">
                    <div class="iterel-product-filter-price-slider">
                    </div>
                </div>

                <!-- Slider -->
                <div class="iterel-product-filter-price-range-input">
                    <input type="range" 
                        class="iterel-product-filter-price-min-range" 
                        min="0" 
                        max="100000" 
                        value="0" 
                        step="1">
                    <input type="range" 
                        class="iterel-product-filter-price-max-range" 
                        min="0" 
                        max="100000" 
                        value="100000" 
                        step="1">
                </div>
            </div>
        </div>
            <?php
        }

        $iterel_attributes = wc_get_attribute_taxonomies();
        foreach($iterel_attributes as $iterel_attribute) {
            if(isset($iterel_options['iterel_field_filter_by_'.$iterel_attribute->attribute_name]) && $iterel_options['iterel_field_filter_by_'.$iterel_attribute->attribute_name] === 'on') {
                $iterel_attrs = get_terms(
                    array(
                    'taxonomy' => 'pa_' . $iterel_attribute->attribute_name,
                    'hide_empty' => false,
                    )
                );
                if ($iterel_attrs) {
                    ?>
                <div class="py-2">
                    <h3 class="h6"><?php echo __('Filter by', 'iterel') . ' ' . $iterel_attribute->attribute_label; ?></h3>
                    <?php foreach ($iterel_attrs as $iterel_attr) { ?>
                        <div>
                            <input class="iterel-product-filter" data-group="<?php echo $iterel_attribute->attribute_name; ?>" name="iterel-product-filter-<?php echo $iterel_attr->slug; ?>" type="checkbox" value="<?php echo esc_attr($iterel_attr->slug); ?>" />
                            <label for="<?php echo esc_attr($iterel_attr->name); ?>"><?php echo esc_html($iterel_attr->name); ?></label>
                        </div>
                    <?php } ?>
                </div>
                <?php }
            }
        }
        ?> 
    </form>
    <?php
}
?>
