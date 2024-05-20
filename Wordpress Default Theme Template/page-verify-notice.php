<?php 
session_start();  

if(!is_user_logged_in()) {
    wp_redirect( site_url("/login") );
}

get_header();
get_template_part( "template-parts/forms/form-verify-notice" );

get_footer();
?>