class ErrorUserTextNotification extends GeneralUserTextNotification {
    static passwordFormatErrorMessage = "Пароль должен содержать хотя бы одну заглавную букву, одну строчную букву, одну цифру и один специальный символ (не менее 8 символов).";
    static emailFormatErrorMessage = "Некорректный формат почты.";
    static loginFormatErrorMessage = "Логин должен содержать только буквы, цифры и символ подчеркивания (от 4 до 20 символов).";
    static usernameExistErrorMessage = "Пользователь с таким именем уже зарегистрирован.";
    static emailExistErrorMessage = "Пользователь с такой почтой уже зарегистрирован.";
    static serverErrorMessage = "Возникли неожиданные неполадки с сервером!";
    static incorrectPasswordErrorMessage = "Вы ввели не верный пароль!";
    static invalidUsernameErrorMessage = "Вы ввели не верный логин!";
    static invalidPasswordConfirmationErrorMessage = "Пароли не совпадают!";
    static oldPasswordIncorrectErrorMessage = "Неправильно введён прошлый пароль!";
    static newPasswordIncorrectErrorMessage = "Неправильно введён новый пароль!";
    static emailNotSuccessfullySentErrorMessage = "Сообщение не было отправлено на почту.";
    static userAlreadyVerifiedErrorMessage = "Почта уже прошла верификацию.";
    #notificationElementClass = "registration-form__error";
    #notificationElementId = "";
    #notificationElementTagName = "label";
}