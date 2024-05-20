jQuery(document).ready(function($) {
    // Отправка данных пользователя на сервер без обновления страницы
    $('#registration-form').on('submit', function(e) {
        e.preventDefault();
        let loginElement = $("#registration-form__login");
        let emailElement = $("#registration-form__email");
        let passwordElement = $("#registration-form__password");

        let loginValue = loginElement.val();
        let emailValue = emailElement.val();
        let passwordValue = passwordElement.val();

        GeneralUserTextNotification.removeLatestNotifications(); // Удаление старых ошибок
        
        let dataIsCorrect = true; // Переменная которая означает коректность данных
        if( !Validator.isValidEmail(emailValue) ) {
            ErrorUserTextNotification.notify(emailElement[0], ErrorUserTextNotification.emailFormatErrorMessage);
            dataIsCorrect = false;
        }

        if( !Validator.isValidLogin(loginValue) ) {
            ErrorUserTextNotification.notify(loginElement[0], ErrorUserTextNotification.loginFormatErrorMessage);
            dataIsCorrect = false;
        }

        if( !Validator.isValidPassword(passwordValue) ) {
            ErrorUserTextNotification.notify(passwordElement[0], ErrorUserTextNotification.passwordFormatErrorMessage);
            dataIsCorrect = false;
        }
        
        if(dataIsCorrect) {   
            let submitButton = $(e.currentTarget).find('.submit-button')[0];
            Timer.startButtonTimer(5, submitButton);  
            // Отправка AJAX-запроса на сервер для генерации и отправки кода
            $.ajax({
                type: 'POST',
                url: myajax.ajax_url, // Ссылка на обработчик AJAX запросов
                data: {
                    action: 'registration', // Хук для обработки запроса на сервере
                    user_login: loginValue,
                    user_email: emailValue,
                    user_password: passwordValue
                },
                success: function(response) {
                    try {
                        if(response === null || typeof response !== "object") {
                            ErrorUserTextNotification.notify(loginElement[0], ErrorUserTextNotification.serverErrorMessage);
                            return false;
                        }
                        if(typeof response?.data?.redirect_url !== "undefined") {
                            window.location.href = response.data.redirect_url;
                            return false;
                        }
                        if(!response?.success && typeof response?.data?.server_error !== "undefined") {
                            ErrorUserTextNotification.notify(loginElement[0], ErrorUserTextNotification.serverErrorMessage);
                        }
                        if(typeof response?.data?.mail_successfully_sent !== "undefined") {                            
                            if(response.data.mail_successfully_sent) {
                                SuccessUserTextNotification.notify(loginElement[0], SuccessUserTextNotification.emailSuccessfullySentMessage);
                            }
                            else {
                                ErrorUserTextNotification.notify(loginElement[0], ErrorUserTextNotification.emailNotSuccessfullySentErrorMessage);
                            }
                        }
                        if(!response?.success && typeof response?.data?.login_error !== "undefined") {
                            ErrorUserTextNotification.notify(loginElement[0], ErrorUserTextNotification.usernameExistErrorMessage);
                        }
                        if(!response?.success && typeof response?.data?.email_error !== "undefined") {
                            ErrorUserTextNotification.notify(emailElement[0], ErrorUserTextNotification.emailExistErrorMessage);
                        }
                    }
                    catch(error) {
                        ErrorUserTextNotification.notify(loginElement[0], ErrorUserTextNotification.serverErrorMessage);
                        return false;
                    }
                },
                error: function(error) {
                    if(typeof error?.data?.redirect_url !== "undefined") {
                        window.location.href = response.data.redirect_url;
                        return true;
                    }
                    ErrorUserTextNotification.notify(loginElement[0], ErrorUserTextNotification.serverErrorMessage);
                    return false;
                }
            });
        }        
        return false;
    });


    $('#resend-verification-key-form, #send-verification-key-form').on('submit', function(e) {
        e.preventDefault();
        let notificationElement = $(e.currentTarget).find(".notification");

        GeneralUserTextNotification.removeLatestNotifications(); // Удаление старых ошибок

        let submitButton = $(e.currentTarget).find('.submit-button')[0];
        Timer.startButtonTimer(5, submitButton);
        $.ajax({
            type: 'POST',
            url: myajax.ajax_url, // WordPress обеспечивает этот URL для обработки AJAX-запросов
            data: {
                action: 'send_verification_key', // Хук для обработки запроса на сервере
            },
            success: function(response) {
                try {
                    if(response === null || typeof response !== "object") {
                        ErrorUserTextNotification.notify(notificationElement[0], ErrorUserTextNotification.serverErrorMessage);
                        return false;
                    }

                    if(typeof response?.data?.redirect_url !== "undefined") {
                        window.location.href = response.data.redirect_url;
                        return true;
                    }

                    if(!response?.success && typeof response?.data?.server_error !== "undefined") {
                        ErrorUserTextNotification.notify(notificationElement[0], ErrorUserTextNotification.serverErrorMessage);
                    }
                    
                    if(!response?.success && typeof response?.data?.already_verified !== "undefined") {
                        if(response.data.already_verified) {
                            ErrorUserTextNotification.notify(notificationElement[0], ErrorUserTextNotification.userAlreadyVerifiedErrorMessage);
                        }
                    }

                    if(typeof response?.data?.mail_successfully_sent !== "undefined") {                      
                        if(response.data.mail_successfully_sent) {
                            SuccessUserTextNotification.notify(notificationElement[0], SuccessUserTextNotification.emailSuccessfullySentMessage);
                        }
                        else {
                            ErrorUserTextNotification.notify(notificationElement[0], ErrorUserTextNotification.emailNotSuccessfullySentErrorMessage);
                        }
                    }
                }
                catch(error) {
                    ErrorUserTextNotification.notify(notificationElement[0], ErrorUserTextNotification.serverErrorMessage);
                    return false;
                }
            },
            error: function(error) {
                ErrorUserTextNotification.notify(notificationElement[0], ErrorUserTextNotification.serverErrorMessage);
                return false;
            }
        });
        return true;
        
    });
});