jQuery(document).ready(function($) { 
    // Отправка данных пользователя на сервер без обновления страницы
    $('#reset-password-form').on('submit', function(e) {
        e.preventDefault();
        let newPasswordElement = $("#reset-password-form__new-password");
        let newPasswordConfirmationElement = $("#reset-password-form__new-password-confirmation");
        let userIdElement = $("#reset-password-form__user-id");

        let newPasswordValue = newPasswordElement.val();
        let newPasswordConfirmationValue = newPasswordConfirmationElement.val();
        let userIdValue = userIdElement.val();
        
        GeneralUserTextNotification.removeLatestNotifications(); // Удаление старых ошибок

        let dataIsCorrect = true; // Переменная которая означает коректность данных
        if(!Validator.isValidPassword(newPasswordValue)) {
            ErrorUserTextNotification.notify(newPasswordElement[0], ErrorUserTextNotification.incorrectPasswordErrorMessage);
            dataIsCorrect = false;
        }

        if(newPasswordValue != newPasswordConfirmationValue) {
            ErrorUserTextNotification.notify(newPasswordConfirmationElement[0], ErrorUserTextNotification.invalidPasswordConfirmationErrorMessage);
            dataIsCorrect = false;
        }

        if(dataIsCorrect) {                              
            let submitButton = $(e.currentTarget).find('.submit-button')[0];
            Timer.startButtonTimer(5, submitButton);  
            $.ajax({
                type: 'POST',
                url: myajax.ajax_url, // Ссылка на обработчик AJAX запросов
                data: {
                    action: 'user_reset_password', // Хук для обработки запроса на сервере
                    user_new_password: newPasswordValue,
                    user_id: userIdValue,
                },
                success: function(response) {
                    try {
                        if(response === null || typeof response !== "object") {
                            ErrorUserTextNotification.notify(newPasswordElement[0], ErrorUserTextNotification.serverErrorMessage);
                            return false;
                        }
                        if(typeof response?.data?.redirect_url !== "undefined") {
                            window.location.href = response.data.redirect_url;
                            return true;
                        }
                        if(!response?.success && typeof response?.data?.server_error !== "undefined") {
                            ErrorUserTextNotification.notify(newPasswordElement[0], ErrorUserTextNotification.serverErrorMessage);
                        }
                    }
                    catch(error) {
                        ErrorUserTextNotification.notify(newPasswordElement[0], ErrorUserTextNotification.serverErrorMessage);
                        return false;
                    }                    
                },
                error: function(error) {
                    ErrorUserTextNotification.notify(newPasswordElement[0], ErrorUserTextNotification.serverErrorMessage);
                }
            });
        }        
        return false;
    });
});