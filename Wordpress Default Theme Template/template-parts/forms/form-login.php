<form name="login-form" id="login-form" method="post">
    <input type="text" name="user_login" id="login-form__login" placeholder="Имя пользователя:" />
    <input type="user_password" name="pwd" id="login-form__password" placeholder="Пароль:" />
    <input type="submit" name="submit" id="login-form__submit" class="button button-primary submit-button" value="Войти" />
    <p>Remember me <input name="remember-me" type="checkbox" id="login-form__remember-me" value="forever"></p>
    <a href="<?= home_url().'/forgot-password' ?>">Forgot password</a>
</form>