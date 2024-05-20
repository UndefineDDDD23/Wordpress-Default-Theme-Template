$(document).ready(function() {
    // Initialize Isotope
    var $grid = $('.grid').isotope({
        itemSelector: '.product',
        layoutMode: 'fitRows',
        getSortData: {
            price: '[data-price] parseInt'
        }
    });

    // Store filter values
    var filters = {
        checkbox: [],
        radio: [],
        select: [],
        minPrice: 0,
        maxPrice: 1000,
        range: 1000
    };

    function getCombinedFilter() {
        var filterValue = '';

        // Combine checkbox filters
        if (filters.checkbox.length) {
            filterValue += filters.checkbox.join('');
        } else {
            filterValue = '*';
        }

        // Combine radio filters
        if (filters.radio.length && filters.radio[0] !== '*') {
            filterValue += filters.radio.join('');
        }

        // Combine select filters
        if (filters.select.length && filters.select[0] !== '*') {
            filterValue += filters.select.join('');
        }

        return filterValue;
    }

    function applyFilters() {
        var filterValue = getCombinedFilter();

        $grid.isotope({
            filter: function() {
                var $this = $(this);
                var price = $this.data('price');

                var passCheckbox = filterValue === '*' || $this.is(filterValue);
                var passRadio = filters.radio.length === 0 || $this.is(filters.radio.join(''));
                var passSelect = filters.select.length === 0 || $this.is(filters.select.join(''));
                var passPriceRange = price >= filters.minPrice && price <= filters.maxPrice;
                var passRangeSlider = price <= filters.range;

                return passCheckbox && passRadio && passSelect && passPriceRange && passRangeSlider;
            }
        });
    }

    // Checkbox filter
    $('.filters').on('change', 'input[type="checkbox"]', function() {
        filters.checkbox = [];
        $('.filters input[type="checkbox"]:checked').each(function() {
            filters.checkbox.push($(this).attr('value'));
        });
        applyFilters();
    });

    // Radio filter
    $('.filters').on('change', 'input[type="radio"]', function() {
        filters.radio = [];
        $('.filters input[type="radio"]:checked').each(function() {
            filters.radio.push($(this).val());
        });
        applyFilters();
    });

    // Select filter
    $('.select-filter').on('change', function() {
        filters.select = [];
        $('.select-filter').each(function() {
            if ($(this).val() !== '*') {
                filters.select.push($(this).val());
            }
        });
        applyFilters();
    });

    // Range filter using input fields
    $('.price-filter-button').on('click', function() {
        var minPrice = parseFloat($(this).siblings('.min-price').val()) || 0;
        var maxPrice = parseFloat($(this).siblings('.max-price').val()) || 1000;
        filters.minPrice = minPrice;
        filters.maxPrice = maxPrice;
        applyFilters();
    });

    // Range slider filter
    $('.price-range').on('input', function() {
        filters.range = parseFloat($(this).val());
        $('.range-value').text(filters.range);
        applyFilters();
    });

    // Button filter
    $('.filters').on('click', 'button[data-filter]', function() {
        var filterValue = $(this).attr('data-filter');
        filters.checkbox = [];
        filters.radio = [];
        filters.select = [];
        filters.minPrice = 0;
        filters.maxPrice = 1000;
        filters.range = 1000;

        if (filterValue === '*') {
            applyFilters();
        } else {
            $grid.isotope({ filter: filterValue });
        }
    });
});
