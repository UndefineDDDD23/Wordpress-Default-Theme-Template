<?php 

class Categories {
    public function __construct() {}

    static public function get_categories_hierarchy($parent_id = 0) {
        $categories = get_categories( array(
            'taxonomy'     => 'product_cat',
            'orderby'      => 'name',
            'order'        => 'ASC',
            'hide_empty'   => 1,
            'hierarchical' => 1,
            'parent'       => $parent_id,
        ) );
    
        $result = array();
    
        foreach ($categories as $category) {
            $category_id = $category->term_id;
            $category_name = $category->name;
            $category_link = get_term_link($category);
    
            $child_categories = self::get_categories_hierarchy($category_id);
    
            $result[] = array(
                'category_id'   => $category_id,
                'category_name' => $category_name,
                'category_link' => $category_link,
                'child_categories' => $child_categories,
            );
        }
    
        return $result;
    }
}

?>