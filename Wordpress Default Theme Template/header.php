<?php 
 // обязательная верификация
  // if(is_user_logged_in()) {
  //   $user = wp_get_current_user();
  //   $userVerified = get_user_meta( $user -> ID, "verified", true );
  //   if(!$userVerified && !is_page( "verify-notice" )) {
  //     wp_redirect( "/verify-notice" );
  //   }
  // }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration</title>

  <?php wp_head(); ?>
</head>

<body>

  <header class="header">
    <?php if(!is_user_logged_in()): ?>    
      <a href="<?= !empty(get_page_by_path( "login" )) ? get_permalink( get_page_by_path( "login" ) ) : "#" ?>">Login</a>
      <a href="<?= !empty(get_page_by_path( "registration" )) ? get_permalink( get_page_by_path( "registration" ) ) : "#" ?>">Registration</a>
    <?php else: ?>
      <!-- my profile -->
      <a href="<?= wp_logout_url( "/" ); ?>">Logout</a>
    <?php endif; ?>
  </header>