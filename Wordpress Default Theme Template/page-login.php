<?php
if(is_user_logged_in()) {
    wp_redirect( home_url() );
} 
get_header();
get_template_part( 'template-parts/forms/form-login' );
get_footer();
?>