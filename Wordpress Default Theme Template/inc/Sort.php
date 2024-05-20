<?php 

/**
 * Sort Class
 *
 * This class provides methods to generate a sorting block for WooCommerce products
 * and to modify WP_Query arguments based on selected sorting options.
 */
class Sort {
    public function __construct() {}

    /**
    * Get Sort Block
    *
    * This method generates an HTML block for sorting WooCommerce products.
    *
    * @param array $args {
    *     Optional. Arguments to customize the sort block.
    *
    *     @type string $sort_container_tag    HTML tag for the sort container. Default 'div'.
    *     @type string $sort_container_class  CSS class for the sort container. Default 'sort-products'.
    *     @type string $sort_container_id     HTML id for the sort container. Default empty.
    *     @type string $select_element_class  CSS class for the select element. Default 'orderby'.
    *     @type string $select_element_id     HTML id for the select element. Default 'orderby'.
    *     @type string $select_element_name   Name attribute for the select element. Default 'orderby'.
    * }
    */
    static public function get_sort_block(array $args = []) {       
        // Set default values
        $defaults = [
            'sort_container_tag'    => 'div',
            'sort_container_class'  => 'sort-products',
            'sort_container_id'     => '',
            'select_element_class'  => 'orderby',
            'select_element_id'     => 'orderby',
            'select_element_name'   => 'orderby',
        ];

        // Merge default values with provided arguments
        $args = wp_parse_args($args, $defaults);

        // Escape output to prevent XSS attacks
        $sort_container_tag = esc_attr($args['sort_container_tag']);
        $sort_container_class = esc_attr($args['sort_container_class']);
        $sort_container_id = esc_attr($args['sort_container_id']);
        $select_element_class = esc_attr($args['select_element_class']);
        $select_element_id = esc_attr($args['select_element_id']);
        $select_element_name = esc_attr($args['select_element_name']);

        // Output the sort block HTML
        ?>
        <<?= $sort_container_tag; ?> class="<?= $sort_container_class; ?>" id="<?= $sort_container_id; ?>">
            <form method="get">
                <select name="<?= $select_element_name; ?>" id="<?= $select_element_id; ?>" class="<?= $select_element_class; ?>">
                    <option value="date_desc" <?php selected( isset($_GET['orderby']) ? $_GET['orderby'] : '', 'date_desc' ); ?>>Newest First</option>
                    <option value="price_asc" <?php selected( isset($_GET['orderby']) ? $_GET['orderby'] : '', 'price_asc' ); ?>>Price (Low to High)</option>
                    <option value="price_desc" <?php selected( isset($_GET['orderby']) ? $_GET['orderby'] : '', 'price_desc' ); ?>>Price (High to Low)</option>
                    <option value="popularity" <?php selected( isset($_GET['orderby']) ? $_GET['orderby'] : '', 'popularity' ); ?>>Popularity</option>
                </select>
            </form>
        </<?= $sort_container_tag; ?>>
        <?php
    }

    /**
    * Change WP Query Args by Sort Parameters
    *
    * This method modifies the WP_Query arguments based on the selected sort option.
    *
    * @param array $args The original WP_Query arguments.
    * @return array Modified WP_Query arguments.
    */
    static public function change_wp_query_args_by_sort_parameters(array $args) {
        // Check if a sort option is selected
        if ( isset( $_GET['orderby'] ) ) {
            switch ( $_GET['orderby'] ) {
                case 'date_desc':
                    $args['orderby'] = 'date';
                    $args['order'] = 'DESC';
                    break;
                case 'price_asc':
                    $args['meta_key'] = '_price';
                    $args['orderby'] = 'meta_value_num';
                    $args['order'] = 'ASC';
                    break;
                case 'price_desc':
                    $args['meta_key'] = '_price';
                    $args['orderby'] = 'meta_value_num';
                    $args['order'] = 'DESC';
                    break;
                case 'popularity':
                    $args['meta_key'] = 'total_sales';
                    $args['orderby'] = 'meta_value_num';
                    $args['order'] = 'DESC';
                break;
            }
        }
        return $args;
    }
}

?>