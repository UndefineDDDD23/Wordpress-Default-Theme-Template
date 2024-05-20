jQuery(document).ready(function($) { 
    let errorMessages = {        
        "email_format": "Некорректный формат почты.",
        "email_not_exist": "Пользователь с такой почтой не найден.",
        "server": "Возникли неожиданные неполадки с сервером!",
    };
    let errorClass = "forgot-password-form__error";
    let errorId = "";
    let errorTagName = "label";
    // Отправка данных пользователя на сервер без обновления страницы
    $('#forgot-password-form').on('submit', function(e) {
        e.preventDefault();
        let emailElement = $("#forgot-password-form__email");
        let emailValue = emailElement.val();

        GeneralUserTextNotification.removeLatestNotifications(); // Удаление старых ошибок
        
        let dataIsCorrect = true; // Переменная которая означает коректность данных
        if( !Validator.isValidEmail(emailValue) ) {
            ErrorUserTextNotification.notify(emailElement[0], ErrorUserTextNotification.emailFormatErrorMessage);
            dataIsCorrect = false;
        }
        if(dataIsCorrect) {
            // Отправка AJAX-запроса на сервер для генерации и отправки кода                                                   
            let submitButton = $(e.currentTarget).find('.submit-button')[0];
            Timer.startButtonTimer(5, submitButton);  
            
            $.ajax({
                type: 'POST',
                url: myajax.ajax_url, // Ссылка на обработчик AJAX запросов
                data: {
                    action: 'forgot_password', // Хук для обработки запроса на сервере
                    user_email: emailValue,
                },
                success: function(response) {
                    try {
                        if(response === null || typeof response !== "object") {
                            ErrorUserTextNotification.notify(emailElement[0], ErrorUserTextNotification.serverErrorMessage);
                            return false;
                        }
                        if(typeof response?.data?.redirect_url !== "undefined") {
                            window.location.href = response.data.redirect_url;
                            return true;
                        }
                        if(!response?.success && typeof response?.data?.server_error !== "undefined") {
                            ErrorUserTextNotification.notify(emailElement[0], ErrorUserTextNotification.serverErrorMessage);
                        }
                        if(typeof response?.data?.mail_successfully_sent !== "undefined") {                            
                            if(response.data.mail_successfully_sent) {
                                SuccessUserTextNotification.notify(emailElement[0], SuccessUserTextNotification.emailSuccessfullySentMessage);
                            }
                            else {
                                ErrorUserTextNotification.notify(emailElement[0], ErrorUserTextNotification.emailNotSuccessfullySentErrorMessage);
                            }
                        }
                    }
                    catch(error) {
                        ErrorUserTextNotification.notify(emailElement[0], ErrorUserTextNotification.serverErrorMessage);
                        return false;
                    }
                },
                error: function(error) {
                    ErrorUserTextNotification.notify(emailElement[0], ErrorUserTextNotification.serverErrorMessage);
                    return false;
                }
            });
            
        }        
        return false;
    });
});