<?php 
require_once __DIR__ . "/User.php";
require_once __DIR__ . "/UserPassword.php";
require_once __DIR__ . "/Logger.php";

class MailManager {
    public function __construct() {
    }

    /**
     * Saves the result of the mail sending operation and sets mail last sent time.
     *
     * @param bool $result - The result of the mail sending operation.
     * @param int $timeSeconds - The cookie lifetime for the session.
     * @return void
    */
    private function saveMailSentResult(bool $result, int $timeSeconds) {
        session_start([
            'cookie_lifetime' => $timeSeconds,
        ]);
        $_SESSION["mail_success_sent"] = $result; 
        if($result === true) {
            $_SESSION["mail_last_time_sent"] = time();
        }
    }

    /**
     * Sending a message by email.
     *
     * @param string $email - Email to which the message is send.
     * @param string $subject - Subject for the message.
     * @param string $message - Message to be sent by email.
     * @return bool
    */
    public function send(string $email, string $subject,string $message) {
        try {
            session_start();
            $messageDelay = 5;
            if(isset($_SESSION["mail_last_time_sent"]) && time() - $_SESSION["mail_last_time_sent"] <= $messageDelay) {
                return false; // If the message delay has expired return false.
            }
            $success = wp_mail( $email, $subject, $message );     
            $this -> saveMailSentResult(result: $success, timeSeconds: 86400);
            return $success;
        } 
        catch (\Throwable $th) {     
            Logger::log($exception);
            return false;
        }        
    }
}

?>