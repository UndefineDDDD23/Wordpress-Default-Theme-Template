<?php 

/**
 * Attributes Class
 *
 * This class provides methods to retrieve WooCommerce product attributes and format them for isotope filters.
 */
class Attributes {
    public function __construct() {}

    /**
     * Get All Attribute Terms
     *
     * This method retrieves all WooCommerce product attributes along with their terms.
     *
     * @return array Associative array where keys are attribute taxonomies and values are arrays of term names.
     */
    static public function get_all_attribute_terms() {
        $result = [];

        // Loop through each WooCommerce attribute taxonomy
        foreach (wc_get_attribute_taxonomies() as $attribute) {
            // Get the array of term names for each product attribute
            $term_names = get_terms(array('taxonomy' => 'pa_' . $attribute -> attribute_name, 'fields' => 'names'));
            // Store the terms in the result array with the attribute taxonomy as the key
            $result["pa_" . $attribute -> attribute_name] = $term_names;
        }
        return $result;
    }
    
    /**
     * Formats Attribute Term for Isotope Filter
     *
     * This method formats a given attribute term to be used as a class selector in Isotope filter.
     * It replaces spaces with dashes and trims any leading or trailing whitespace.
     *
     * @param string $attribute_term The attribute term to be formatted.
     * @return string The formatted attribute term ready for use as a class selector.
     */
    static public function formatting_attribute_term_for_isotope_filter($attribute_term) {
        // Add a period (.) to indicate a class selector, then replace spaces with dashes and trim whitespace
        return "." . str_replace(" ", "-", trim($attribute_term));
    }

    /**
     * Get Class String from Attribute Values for Isotope Filter
     *
     * This method gets the product attribute values from the attribute names 
     * that are passed into the array $attribute_names parameter and 
     * converts the resulting values into a string of attribute values for the isotope filter.
     * After which the result is embedded in the class tag.
     * 
     * @param array $attribute_names Attribute names to convert to string.
     * @return string List of product attributes formatted as a string.
     */
    static public function get_class_string_from_attribute_terms_for_isotope_filter(array $attribute_names) {
        global $product;
        $result_string = "";

        // Loop through each attribute name and format its value
        foreach ($attribute_names as $attribute_name) {
            $result_string .= " " . str_replace(",-", " ", str_replace(" ", "-", $product->get_attribute($attribute_name)));
        }

        // Return the trimmed result string
        return trim($result_string);
    }
}

?>