(function ($) {
    "use strict";

    const rangevalue =
        document.querySelector(".iterel-product-filter-slider-container .iterel-product-filter-price-slider");
    const rangeInputvalue =
        document.querySelectorAll(".iterel-product-filter-price-range-input input");
    const priceInputvalue =
        document.querySelectorAll(".iterel-product-filter-price-input input");

    // Set the price gap
    let priceGap = 500;

    priceInputvalue[0].value = 10000;
    priceInputvalue[1].value = 50000;
    rangeInputvalue[0].value = 10000;
    rangeInputvalue[1].value = 50000;
    let minVal =
        parseInt(rangeInputvalue[0].value);
    let maxVal =
        parseInt(rangeInputvalue[1].value);

    let diff = maxVal - minVal

    // Update price inputs and range progress
    priceInputvalue[0].value = minVal;
    priceInputvalue[1].value = maxVal;
    rangeInputvalue[0].value = minVal;
    rangeInputvalue[1].value = maxVal;
    rangevalue.style.left =
        `${(minVal / rangeInputvalue[0].max) * 100}%`;
    rangevalue.style.right =
        `${100 - (maxVal / rangeInputvalue[1].max) * 100}%`;


    // Adding event listners to price input elements
    for (let i = 0; i < priceInputvalue.length; i++) {
        priceInputvalue[i].addEventListener("input", e => {
            // Parse min and max values of the range input
            let minp = parseInt(priceInputvalue[0].value);
            let maxp = parseInt(priceInputvalue[1].value);
            let diff = maxp - minp

            if (minp < 0) {
                alert("minimum price cannot be less than 0");
                priceInputvalue[0].value = 0;
                minp = 0;
            }

            // Validate the input values
            if (maxp > 100000) {
                alert("maximum price cannot be greater than 100000");
                priceInputvalue[1].value = 100000;
                maxp = 100000;
            }

            if (minp > maxp - priceGap) {
                priceInputvalue[0].value = maxp - priceGap;
                minp = maxp - priceGap;

                if (minp < 0) {
                    priceInputvalue[0].value = 0;
                    minp = 0;
                }
            }

            // Check if the price gap is met 
            // and max price is within the range
            if (diff >= priceGap && maxp <= rangeInputvalue[1].max) {
                if (e.target.className === "iterel-product-filter-price-min-input") {
                    rangeInputvalue[0].value = minp;
                    let value1 = rangeInputvalue[0].max;
                    rangevalue.style.left = `${(minp / value1) * 100}%`;
                }
                else {
                    rangeInputvalue[1].value = maxp;
                    let value2 = rangeInputvalue[1].max;
                    rangevalue.style.right =
                        `${100 - (maxp / value2) * 100}%`;
                }
            }
        });

        // Add event listeners to range input elements
        for (let i = 0; i < rangeInputvalue.length; i++) {
            rangeInputvalue[i].addEventListener("input", e => {
                let minVal =
                    parseInt(rangeInputvalue[0].value);
                let maxVal =
                    parseInt(rangeInputvalue[1].value);

                let diff = maxVal - minVal

                // Check if the price gap is exceeded
                if (diff < priceGap) {

                    // Check if the input is the min range input
                    if (e.target.className === "iterel-product-filter-price-min-range") {
                        rangeInputvalue[0].value = maxVal - priceGap;
                    }
                    else {
                        rangeInputvalue[1].value = minVal + priceGap;
                    }
                }
                else {

                    // Update price inputs and range progress
                    priceInputvalue[0].value = minVal;
                    priceInputvalue[1].value = maxVal;
                    rangevalue.style.left =
                        `${(minVal / rangeInputvalue[0].max) * 100}%`;
                    rangevalue.style.right =
                        `${100 - (maxVal / rangeInputvalue[1].max) * 100}%`;
                }
            });
        }
    }

    // AJAX Functionality for product filter
    const product_filter = $('.iterel-product-filter');
    product_filter.on("submit", function (event) {
        event.preventDefault();

        const search_query = $('[name="iterel-product-filter-search"]').get(0).value;
        const filters = {
            search_query: "",
            attributes: {},
            price_range_min: 10000,
            price_range_max: 100000,
        };
        if (search_query) {
            filters['search_query'] = search_query;
        }

        const price_range_min = $('input[name="iterel-product-filter-price-min-input"]').get(0).value;
        const price_range_max = $('input[name="iterel-product-filter-price-max-input"]').get(0).value;
        filters['price_range_min'] = price_range_min;
        filters['price_range_max'] = price_range_max;

        $('input[class="iterel-product-filter"]:checked').each(function () {
            const group = this.getAttribute('data-group');
            if (filters["attributes"][group]) {
                filters["attributes"][group].push(this.value);
            } else {
                filters["attributes"][group] = [this.value];
            }
        });

        console.log(filters);

        $.ajax({
            type: "POST",
            url: iterel_product_filter_ajax_obj.ajax_url,
            data: {
                action: 'iterel_product_filter',
                nonce: iterel_product_filter_ajax_obj.nonce,
                filters: filters,
            },
            success: function (data, status) {
                $('.woocommerce-result-count').html(`Showing all ${data.data.count} results`);
                $('.products').html(data.data.html);
            },
        });
    });
})(jQuery);