class SuccessUserTextNotification extends GeneralUserTextNotification {
    static emailSuccessfullySentMessage = "Было отправлено сообщение на почту.";
    static passwordSuccessfullyChangedMessage = "Пароль был изменён.";
    
    #notificationElementClass = "registration-form__success";
    #notificationElementId = "";
    #notificationElementTagName = "label";
}