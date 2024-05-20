<?php get_header(); ?>

<?php
      if(is_user_logged_in()){
        get_template_part( "template-parts/forms/form-change-password" );
      } 
      if(is_user_logged_in()){
        $user = wp_get_current_user();
        $userVerified = get_user_meta( $user -> ID, "verified", true );
        if(!$userVerified) {
          get_template_part( "template-parts/forms/form-verify-email" );
        }        
      } 
?>

<?php get_footer(); ?>