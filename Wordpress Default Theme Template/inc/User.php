<?php 
require_once __DIR__ . "/UserPassword.php";
require_once __DIR__ . "/UserVerification.php";
require_once __DIR__ . "/Logger.php";

class User {
    public function __construct() {
    }

    /**
     * Registers a new user via userdata from AJAX request.
     * 
     * @return void     
     */
    public function register() {
        try {
            $userdata = [
                "user_login" => $_POST['user_login'],
                "user_email" => $_POST['user_email'],
                "user_pass" => $_POST['user_password'],
            ]; 
            // Array to which possible errors will be added to be sent to the js client file.
            $errors = [];
    
            if(username_exists( $userdata["user_login"] )) {
                $errors["login_error"] = true;
            }
            if(email_exists( $userdata["user_email"] )) {
                $errors['email_error'] = true;
            }    
        
            // Generating user verification key.
            // The conditional expression equals false if user login or user email exists in the database.
            if (count($errors) === 0) {
                $user_id = wp_insert_user($userdata);      
                
                // Checking whether the request to create a user was successful and 
                // sending the verification key to user email from userdata.
                // The conditional expression equals false if the userdata is invalid.
                if(!is_wp_error($user_id)) {
                    update_user_meta( $user_id, "verified", false );
                    
                    wp_set_auth_cookie( $user_id, true );
    
                    $user_verification = new UserVerification();
                    $user_verification -> send_verification_key($userdata["user_email"]);
                }
                else {
                    $data = [
                        "input_data_error" => true
                    ];
                    wp_send_json_error( $data );
                }
            }    
            else {
                wp_send_json_error( $errors );
            }
        } 
        catch (\Throwable $th) {     
            Logger::log($exception);
            $data = [
                "server_error" => true
            ];
            wp_send_json_error( $data );
        }        
    }

    /**
     * Signs in a user from via userdata from AJAX request.
     *
     * @return void
    */
    public function login() {
        try {
            $user_login = $_POST["user_login"];
            $user_password = $_POST["user_password"];
            $user_remember_me = $_POST["user_remember_me"];
            $user = wp_authenticate( $user_login, $user_password );
            
            if(is_wp_error( $user )) {
                $data = [
                    $user->get_error_code() => true
                ];
                wp_send_json_error( $data );
            }
            else {                
                nocache_headers();
                wp_clear_auth_cookie();
                wp_set_auth_cookie( $user->ID, $user_remember_me );        
                wp_send_json_success( ["redirect_url" => home_url()] );           
            }
        } 
        catch (\Throwable $th) {     
            Logger::log($exception);
            $data = [
                "server_error" => true
            ];
            wp_send_json_error( $data );
        }        
    }
    
    /**
     * Starting a user password recovery action.
     * Sends reset password key.
     *
     * @return void
    */
    public function forgot_password() {
        try {
            $user_email = $_POST["user_email"];
            $user = get_user_by( field:"email", value:$user_email );
        
            if( $user ) {
                $userPassword = new UserPassword();
                $userPassword -> sendResetPasswordKey($user -> ID, $user_email);   
            }
            else {
                wp_send_json_error( data: ["email_not_exist" => true] );
            }
        } 
        catch (\Throwable $th) {     
            Logger::log($exception);
            $data = [
                "server_error" => true
            ];
            wp_send_json_error( $data );
        }        
    }
    
    /**
     * Re.
     *
     * @return void
    */
    public function reset_password() {
        try {
            session_start();
            $user_new_password = $_POST["user_new_password"];
            $user_id = $_POST["user_id"];
            wp_set_password( $user_new_password, $user_id );

            delete_user_meta( $user_id, "resetPasswordKey" );                
            $_SESSION["resetPasswordKey"] = null;
            $_SESSION["userID"] = null;

            wp_send_json_success( ["redirect_url" => site_url("/login")] );
        } 
        catch (\Throwable $th) {
            Logger::log($exception);
            $data = [
                "server_error" => true
            ];
            wp_send_json_error( $data );
        }
         
    }

    public function change_password() {      
        try {
            nocache_headers();
            $user_old_password = $_POST["user_old_password"];
            $user_new_password = $_POST["user_new_password"];
            $user = wp_get_current_user();
            if(wp_check_password( password: $user_old_password, hash: $user -> data -> user_pass )) {
                $user -> data -> user_pass = $user_new_password;
                $result = wp_update_user( $user );
                if(is_wp_error( $result )) {
                    $data = [
                        "server_error" => true
                    ];
                    wp_send_json_error( $data );
                }
                $data = [
                    "password_changed" => true
                ];
                wp_send_json_success( $data );
            }
            else {
                $data = [
                    "old_password_incorrect" => true,
                ];
                wp_send_json_error( $data );
            }
        } 
        catch (\Throwable $th) {     
            Logger::log($exception);
            $data = [
                "server_error" => true
            ];
            wp_send_json_error( $data );
        }
    }
}

?>