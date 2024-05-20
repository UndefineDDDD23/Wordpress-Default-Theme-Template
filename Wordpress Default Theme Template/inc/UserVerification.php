<?php 
require_once __DIR__ . "/User.php";
require_once __DIR__ . "/UserPassword.php";
require_once __DIR__ . "/MailManager.php";
require_once __DIR__ . "/Logger.php";
require_once __DIR__ . "/Info.php";

class UserVerification {
    public function __construct() {
    }

    /**
     * Creates a user verification key.
     *
     * @return string - The created user verification key
    */
    public function createEmailVerificationKey() {
        $emailVerificationKey = wp_generate_password( 16, false, false );
        return $emailVerificationKey;
    }

    /**
     * Sends a verification key to the user.
     *
     * @return void
    */
    public function send_verification_key(string $userEmail = "") {
        try {
            // Checking where the request came from
            if(!empty($userEmail)) {
                $user = get_user_by( "email", $userEmail );
            }
            else if(!is_user_logged_in()) {
                $data = [
                    "redirect_url" => site_url("/login"),
                ];
                wp_send_json_error($data);
            }
            else {                
                $user = wp_get_current_user();
                $userEmail = $user -> user_email;
            }
    
            $user_verified = get_user_meta( user_id: $user -> ID, key:"verified", single: true );
    
            if($user_verified) {
                $data = [
                    "already_verified" => true,
                ];
                wp_send_json_error($data);
            }
            
            $mailManager = new MailManager();
            $emailVerificationKey = $this -> createEmailVerificationKey();
                
            $emailVerificationSessionDuration = 600;                
            session_start([
                'cookie_lifetime' => $emailVerificationSessionDuration,
            ]);
            $_SESSION["emailVerificationKey"] = $emailVerificationKey;
            $_SESSION["userID"] = $user -> ID;
    
            $subject = 'Подтверждение почты';
            $message = 'Перейдите по ссылке для подтверждения вашей почты: ' . site_url("/verify-email");
            $success = $mailManager -> send( $userEmail, $subject, $message ); 
    
            if($success) {
                $successKeyUpdate = update_user_meta($user -> ID, "emailVerificationKey", $emailVerificationKey);
                if($successKeyUpdate !== false) {
                    $data = [
                        "mail_successfully_sent" => true,
                        "redirect_url" => site_url("/verify-notice"),
                    ];
                    wp_send_json_success( $data );
                }
                else {          
                    $data = [
                        "server_error" => true
                    ];     
                    wp_send_json_error( $data );
                }
            }
            else {
                $data = [
                        "mail_successfully_sent" => false
                ];                
                wp_send_json_error( $data );
            }
        } 
        catch (\Throwable $exception) {
            Logger::log($exception);
            $data = [
                "server_error" => true
            ];
            wp_send_json_error( $data );
        }
    }
    
}

?>