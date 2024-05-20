<form name="reset-password-form" id="reset-password-form" method="post">
    <input type="text" name="user_id" id="reset-password-form__user-id" value="<?= sanitize_text_field($_SESSION["userID"]); ?>" hidden>
    <input type="text" name="user_new_password" id="reset-password-form__new-password" placeholder="Новый пароль:">
    <input type="text" name="user_new_password_confirmation" id="reset-password-form__new-password-confirmation" placeholder="Подтвердите новый пароль:">
    <input type="submit" name="submit" id="reset-password-form__submit" class="button button-primary submit-button" value="Изменить пароль" />
</form>