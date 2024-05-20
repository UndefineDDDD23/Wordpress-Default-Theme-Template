

jQuery(document).ready(function($) { 
    // Отправка данных пользователя на сервер без обновления страницы
    $('#login-form').on('submit', function(e) {
        e.preventDefault();
        let loginElement = $("#login-form__login");
        let passwordElement = $("#login-form__password");

        let loginValue = loginElement.val();
        let passwordValue = passwordElement.val();
        let rememberMeValue = $("#login-form__remember-me")[0].checked;
        
        GeneralUserTextNotification.removeLatestNotifications(); // Удаление старых ошибок

        let dataIsCorrect = true;
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
                    action: "user_login", // Хук для обработки запроса на сервере
                    user_login: loginValue,
                    user_password: passwordValue,
                    user_remember_me: rememberMeValue,
                },
                success: function(response) {
                    try {
                        if(response === null || typeof response !== "object") {
                            ErrorUserTextNotification.notify(loginElement[0], ErrorUserTextNotification.serverErrorMessage);
                            return false;
                        }
                        if(typeof response?.data?.redirect_url !== "undefined") {
                            window.location.href = response.data.redirect_url;
                            return true;
                        }
                        if(!response?.success && typeof response?.data?.server_error !== "undefined") {
                            ErrorUserTextNotification.notify(loginElement[0], ErrorUserTextNotification.serverErrorMessage);
                        }

                        if(!response?.success && typeof response?.data?.incorrect_password !== "undefined") {
                            ErrorUserTextNotification.notify(passwordElement[0], ErrorUserTextNotification.incorrectPasswordErrorMessage);
                        }
                        if(!response?.success && typeof response?.data?.invalid_username !== "undefined") {
                            ErrorUserTextNotification.notify(loginElement[0], ErrorUserTextNotification.invalidUsernameErrorMessage);
                        }
                        
                    }
                    catch(error) {
                        ErrorUserTextNotification.notify(loginElement[0], ErrorUserTextNotification.serverErrorMessage);
                        return false;
                    }                    
                },
                error: function(error) {
                    ErrorUserTextNotification.notify(loginElement[0], ErrorUserTextNotification.serverErrorMessage);
                }
            });
        }        
        return false;
    });
});