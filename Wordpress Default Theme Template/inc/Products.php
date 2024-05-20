<?php 

/**
 * Products Class
 *
 * This class provides methods to retrieve various details about WooCommerce products,
 * such as maximum and minimum prices, best-selling products, recommended products, 
 * recently added products, and other utility functions.
 */
class Products {
    public function __construct() {}

    /**
     * Get Maximum Price
     *
     * This method retrieves the maximum price of published WooCommerce products.
     *
     * @global wpdb $wpdb WordPress database abstraction object.
     * @return float The maximum price of published products.
     */
    static public function get_max_price() {
        global $wpdb;
        
        // Query to get the maximum price of published products
        $max_price = $wpdb -> get_var( "
            SELECT MAX(meta_value + 0)
            FROM {$wpdb -> prefix}postmeta
            WHERE meta_key = '_price'
            AND post_id IN ( SELECT ID FROM {$wpdb -> prefix}posts WHERE post_type = 'product' AND post_status = 'publish' )
        " );

        return $max_price;
    }

    
    /**
     * Get Minimum Price
     *
     * This method retrieves the minimum price of published WooCommerce products.
     *
     * @global wpdb $wpdb WordPress database abstraction object.
     * @return float The minimum price of published products.
     */
    static public function get_min_price() {
        global $wpdb;

        // Query to get the minimum price of published products
        $min_price = $wpdb -> get_var( "
            SELECT MIN(meta_value + 0)
            FROM {$wpdb -> prefix}postmeta
            WHERE meta_key = '_price'
            AND post_id IN ( SELECT ID FROM {$wpdb -> prefix}posts WHERE post_type = 'product' AND post_status = 'publish' )
        " );

        return $min_price;
    }

    
    /**
     * Get Best Selling Products
     *
     * This method retrieves the best selling products based on the specified limit.
     *
     * @param int $limit The maximum number of best selling products to retrieve. Defaults to 5.
     * @return WP_Query The WP_Query object containing the best selling products.
     */
    static public function get_best_selling_products($limit = 5) {
        // Define query arguments
        $args = [
            'post_type'      => 'product',                       // Retrieve products
            'posts_per_page' => $limit,                          // Limit the number of results
            'meta_key'       => 'total_sales',                   // Meta key for total sales
            'orderby'        => 'meta_value_num',                // Order by meta value (numeric)
            'order'          => 'DESC',                          // Order in descending order (highest sales first)
            'post_status'    => 'publish',                        // Retrieve only published products
        ];

        // Execute the query
        $query = new WP_Query($args);

        // Return the query object containing the best selling products
        return $query;
    }

    
    /**
     * Get Featured Products
     *
     * This method retrieves featured products based on the specified limit.
     *
     * @param int $limit The maximum number of featured products to retrieve. Defaults to 5.
     * @return WP_Query The WP_Query object containing the featured products.
     */
    static public function get_featured_products($limit = 5) {
        // Define query arguments
        $args = [
            'post_type'      => 'product',                       // Retrieve products
            'posts_per_page' => $limit,                          // Limit the number of results
            'meta_query'     => [                                 // Specify meta query to filter featured products
                [
                    'key'     => '_featured',                     // Meta key for featured products
                    'value'   => 'yes',                           // Value indicating a product is featured
                    'compare' => '=',                             // Compare feature status with '=' (equal)
                ]
            ],
            'post_status'    => 'publish',                        // Retrieve only published products
        ];

        // Execute the query
        $query = new WP_Query($args);

        // Return the query object containing the featured products
        return $query;
    }

    /**
     * Get Recently Added Products
     *
     * This method retrieves recently added products based on the specified limit.
     *
     * @param int $limit The maximum number of recently added products to retrieve. Defaults to 5.
     * @return WP_Query The WP_Query object containing the recently added products.
     */
    static public function get_recently_added_products($limit = 5) {
        // Define query arguments
        $args = [
            'post_type'      => 'product',       // Retrieve products
            'posts_per_page' => $limit,          // Limit the number of results
            'orderby'        => 'date',          // Order by date
            'order'          => 'DESC',          // Order in descending order (recent first)
            'post_status'    => 'publish',       // Retrieve only published products
        ];

        // Execute the query
        $query = new WP_Query($args);

        // Return the query object containing the recently added products
        return $query;
    }

    
    /**
     * Get Products on Sale
     *
     * This method retrieves products that are currently on sale based on the specified limit.
     *
     * @param int $limit The maximum number of products on sale to retrieve. Defaults to 5.
     * @return WP_Query The WP_Query object containing the products on sale.
     */
    static public function get_products_on_sale($limit = 5) {
        // Define query arguments
        $args = [
            'post_type'      => 'product',                       // Retrieve products
            'posts_per_page' => $limit,                          // Limit the number of results
            'meta_query'     => [                                 // Specify meta query to filter products on sale
                [
                    'key'     => '_sale_price',                   // Meta key for sale price
                    'value'   => 0,                               // Sale price greater than 0 (indicating a sale)
                    'compare' => '>',                             // Compare sale price with '>'
                    'type'    => 'NUMERIC'                        // Specify the type of comparison as numeric
                ]
            ],
            'post_status'    => 'publish',                        // Retrieve only published products
        ];

        // Execute the query
        $query = new WP_Query($args);

        // Return the query object containing the products on sale
        return $query;
    }

    
    /**
     * Get Products by Category
     *
     * This method retrieves products belonging to a specific category based on the category slug and the specified limit.
     *
     * @param string $category_slug The slug of the category from which to retrieve products.
     * @param int $limit The maximum number of products to retrieve. Defaults to 5.
     * @return WP_Query The WP_Query object containing the products belonging to the specified category.
     */
    static public function get_products_by_category($category_slug, $limit = 5) {
        // Define query arguments
        $args = [
            'post_type'      => 'product',                       // Retrieve products
            'posts_per_page' => $limit,                          // Limit the number of results
            'tax_query'      => [                                 // Specify taxonomy query to filter products by category
                [
                    'taxonomy' => 'product_cat',                  // Taxonomy for product categories
                    'field'    => 'slug',                         // Compare field is category slug
                    'terms'    => $category_slug,                 // Specific category slug to filter by
                ]
            ],
            'post_status'    => 'publish',                        // Retrieve only published products
        ];

        // Execute the query
        $query = new WP_Query($args);

        // Return the query object containing the products belonging to the specified category
        return $query;
    }

    
    /**
     * Get Products by Attribute
     *
     * This method retrieves products based on a specific attribute slug and value, with an optional limit.
     *
     * @param string $attribute_slug The slug of the attribute taxonomy.
     * @param string $attribute_value The value of the attribute.
     * @param int $limit The maximum number of products to retrieve. Defaults to 10.
     * @return WP_Query The WP_Query object containing the products matching the attribute.
     */
    static public function get_products_by_attribute($attribute_slug, $attribute_value, $limit = 10) {
        // Define query arguments
        $args = [
            'post_type'      => 'product',                       // Retrieve products
            'posts_per_page' => $limit,                          // Limit the number of results
            'tax_query'      => [                                 // Specify taxonomy query to filter products by attribute
                [
                    'taxonomy' => $attribute_slug,               // Taxonomy for the attribute
                    'field'    => 'slug',                         // Compare field is attribute slug
                    'terms'    => $attribute_value,               // Specific attribute value to filter by
                ]
            ],
            'post_status'    => 'publish',                        // Retrieve only published products
        ];

        // Execute the query
        $query = new WP_Query($args);

        // Return the query object containing the products matching the attribute
        return $query;
    }


   /**
     * Display Pagination
     *
     * This method generates and displays pagination links based on the provided WP_Query object and additional settings.
     *
     * @param WP_Query $wp_query The WP_Query object for which pagination links are generated.
     * @param array $args Additional settings for customizing pagination display (optional).
     *               @type string $pagination_container_classes: Classes for the pagination container (default: 'pagination').
     *               @type string $pagination_container_id: ID attribute for the pagination container (default: '').
     *               @type string $pagination_active_element_classes: Classes for active pagination elements (default: 'active').
     *               @type string $pagination_element_classes: Additional classes for pagination elements (default: '').
     *               @type string $prev_text: Text for the previous page link (default: '&larr; Previous').
     *               @type string $next_text: Text for the next page link (default: 'Next &rarr;').
     */
    static public function display_pagination(WP_Query $wp_query, array $args = []) {
        // Default settings for pagination
        $defaults = [
            'pagination_container_classes'      => 'pagination',
            'pagination_container_id'           => '',
            'pagination_active_element_classes' => 'active',
            'pagination_element_classes'        => '',
            'prev_text'                         => '&larr; Previous',
            'next_text'                         => 'Next &rarr;',
        ];

        // Merge provided arguments with defaults
        $args = wp_parse_args($args, $defaults);

        // Escaping attributes for safe output
        $pagination_container_classes = esc_attr($args['pagination_container_classes']);
        $pagination_container_id = esc_attr($args['pagination_container_id']);
        $pagination_element_classes = esc_attr($args['pagination_element_classes']);
        $pagination_active_element_classes = esc_attr($args['pagination_active_element_classes']);
        $prev_text = esc_attr($args['prev_text']);
        $next_text = esc_attr($args['next_text']);

        // Generate pagination links
        $pages = paginate_links(array(
            'base'      => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
            'format'    => '?product-page=%#%',
            'current'   => max(1, get_query_var('paged')),
            'total'     => $wp_query->max_num_pages,
            'prev_next' => false,
            'type'      => 'array',
            'prev_next' => true,
            'prev_text' => $prev_text,
            'next_text' => $next_text,
        ));

        // Output pagination links
        if (is_array($pages)) {
            $current_page = (get_query_var('paged') == 0) ? 1 : get_query_var('paged');
            echo "<ul class='$pagination_container_classes' id='$pagination_container_id'>";
            foreach ($pages as $i => $page) {
                if ($current_page == 1 && $i == 0) {
                    echo "<li class='$pagination_active_element_classes $pagination_element_classes'>$page</li>";
                } else {
                    if ($current_page != 1 && $current_page == $i) {
                        echo "<li class='$pagination_active_element_classes $pagination_element_classes'>$page</li>";
                    } else {
                        echo "<li class='$pagination_element_classes'>$page</li>";
                    }
                }
            }
            echo '</ul>';
        }
    }
}

?>