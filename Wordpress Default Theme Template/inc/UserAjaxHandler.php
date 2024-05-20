<?php 
require_once __DIR__ . "/User.php";
require_once __DIR__ . "/UserPassword.php";
require_once __DIR__ . "/UserVerification.php";

class UserAjaxHandler {
    public function __construct() {
        $user = new User();
        $user_password = new UserPassword();
        $user_verification = new UserVerification();
        add_action("wp_ajax_nopriv_registration", [$user, "register"]);
        add_action("wp_ajax_nopriv_user_login", [$user, "login"]);
        add_action("wp_ajax_nopriv_user_reset_password", [$user,"reset_password"]);
        add_action("wp_ajax_nopriv_forgot_password", [$user, "forgot_password"]);

        add_action("wp_ajax_send_verification_key", [$user_verification, "send_verification_key"]);
        add_action("wp_ajax_user_change_password", [$user, "change_password"]);

        //wp_ajax_nopriv_(action) not authorized
        //wp_ajax_(action) authorized
    }
}
$user_ajax_handler = new UserAjaxHandler();
?>