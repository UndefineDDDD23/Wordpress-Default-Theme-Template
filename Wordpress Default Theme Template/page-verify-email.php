<?php

session_start();

// Проверяем, есть ли параметры ключа и пользователя в URL
if (isset($_SESSION['emailVerificationKey']) && isset($_SESSION['userID'])) {
    $verification_key = sanitize_text_field($_SESSION['emailVerificationKey']);
    $userID = $_SESSION['userID'];
    
    $verified = get_user_meta( $userID, "verified", true);
    if($verified === true) {
        wp_redirect(home_url());
    }

    $verification_result = get_user_meta( $userID, "emailVerificationKey", true);
    if ($verification_result === $verification_key) {
        delete_user_meta( $userID, "email_verification_key" );
        delete_user_meta( $userID, "last_activation_sent" );
        update_user_meta( $userID, "verified", true );
        wp_redirect(home_url());
    } 
    else {
        echo '<p>Ошибка при подтверждении почты. Пожалуйста, проверьте ссылку на подтверждение ещё раз!!!!</p>';
    }
} 
else {
    echo '<p>Ошибка при подтверждении почты. Пожалуйста, проверьте ссылку на подтверждение ещё раз.</p>';
}
?>