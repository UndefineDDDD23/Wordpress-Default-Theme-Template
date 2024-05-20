<?php 
require_once __DIR__ . "/User.php";
require_once __DIR__ . "/UserVerification.php";
require_once __DIR__ . "/MailManager.php";
require_once __DIR__ . "/Logger.php";

class UserPassword {
    public function __construct() {
    }

    public function createResetPasswordKey() {
        $resetPasswordKey = wp_generate_password( 16, false, false);
        return $resetPasswordKey;
    }    

    public function sendResetPasswordKey($userID, $userEmail) {
        try {
            $mailManager = new MailManager();
            $resetPasswordKey = $this -> createResetPasswordKey();

            $resetPasswordSessionDuration = 600;                
            session_start([
                'cookie_lifetime' => $resetPasswordSessionDuration,
            ]);
            $_SESSION["resetPasswordKey"] = $resetPasswordKey;
            $_SESSION["userID"] = $userID;

            $subject = 'Изменение пароля';
            $message = 'Перейдите по ссылке для изменения пароля: ' . site_url("/reset-password");
            $success = $mailManager -> send(email: $userEmail, subject: $subject, message: $message);
            
            if($success) {
                $success_key_update = update_user_meta($userID, "resetPasswordKey", $resetPasswordKey);
                if($success_key_update !== false) {
                    $data = [
                        "mail_successfully_sent" => true
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