<?php 
session_start();

if(is_user_logged_in()) {
    wp_redirect( home_url() );
} 

get_header();

if (isset($_SESSION["resetPasswordKey"]) && isset($_SESSION["userID"])) {
    $sessionResetPasswordKey = sanitize_text_field($_SESSION["resetPasswordKey"]);
    $userID = intval($_SESSION["userID"]);

    $userMetaResetPasswordKey = get_user_meta( $userID, "resetPasswordKey", true);
    if ($sessionResetPasswordKey === $userMetaResetPasswordKey) {
        get_template_part( 'template-parts/forms/form-reset-password' );
    }
    else {
        wp_redirect( home_url() );
    }
}

get_footer(); 
?>