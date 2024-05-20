<footer class="footer">
</footer>

<?php wp_footer(); ?>

<!-- 
  Pages:
    forgot-password
    login
    registration
    reset-password
    verify-email
    verify-notice
-->
<?php 
  $pagesWithRecaptcha  = ['forgot-password', 'login', 'registration', 'reset-password', 'verify-email', 'verify-notice'];
  if(is_page( $pagesWithRecaptcha )): 
?>
  <script src="https://www.google.com/recaptcha/api.js?render=6Lcdla4kAAAAAAY5MTiP06-ydALHk-jPnMJoR-pl"></script>
  <!-- recapcha <script src="https://www.google.com/recaptcha/api.js?render=reCAPTCHA_site_key"></script> -->
<?php endif; ?>
</body>

</html>