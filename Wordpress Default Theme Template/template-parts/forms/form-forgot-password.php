<form name="forgot-password-form" id="forgot-password-form" method="post">    
    <input type="text" name="user_email" id="forgot-password-form__email" value="<?= isset($_GET["user_email"])?sanitize_text_field($_GET["user_email"]):"" ?>" placeholder="Email:">
    <input type="submit" name="submit" id="resend-button" class="button button-primary submit-button" value="Отправить запрос на изменение пароля"/>    
    <a href="<?= home_url().'/login' ?>">Войти</a>
</form>