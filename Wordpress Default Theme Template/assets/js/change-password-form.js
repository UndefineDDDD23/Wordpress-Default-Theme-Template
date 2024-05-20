jQuery(document).ready(function($) { 
    $('.change-password-form').on('submit', function(e) {
        e.preventDefault();
        let newPasswordElement = $(".change-password-form__new-password");
        let newPasswordConfirmationElement = $(".change-password-form__new-password-confirmation");
        let oldPasswordElement = $(".change-password-form__old-password");

        let newPasswordValue = newPasswordElement.val();
        let newPasswordConfirmationValue = newPasswordConfirmationElement.val();
        let oldPasswordValue = oldPasswordElement.val();
        
        GeneralUserTextNotification.removeLatestNotifications();

        let dataIsCorrect = true;
        if(!Validator.isValidPassword(oldPasswordValue)) {
            ErrorUserTextNotification.notify(oldPasswordElement[0], ErrorUserTextNotification.passwordFormatErrorMessage);
            dataIsCorrect = false;
        }

        if(!Validator.isValidPassword(newPasswordValue)) {
            ErrorUserTextNotification.notify(newPasswordElement[0], ErrorUserTextNotification.passwordFormatErrorMessage);
            dataIsCorrect = false;
        }

        if(newPasswordValue == oldPasswordValue) {
            ErrorUserTextNotification.notify(newPasswordElement[0], ErrorUserTextNotification.newPasswordIncorrectErrorMessage);
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
                url: myajax.ajax_url,
                data: {
                    action: 'user_change_password',
                    user_new_password: newPasswordValue,
                    user_old_password: oldPasswordValue,
                },
                success: function(response) {
                    try {
                        if(response === null || typeof(response) !== "object") {
                            ErrorUserTextNotification.notify(newPasswordElement[0], ErrorUserTextNotification.serverErrorMessage);
                            return false;
                        }
                        if(typeof response?.data?.redirect_url !== "undefined") {
                            window.location.href = response.data.redirect_url;
                            return true;
                        }
                        if(!response?.success && typeof(response?.data?.server_error) !== "undefined") {
                            ErrorUserTextNotification.notify(newPasswordElement[0], ErrorUserTextNotification.serverErrorMessage);
                        }
                        if(!response?.success && typeof(response?.data?.old_password_incorrect) !== "undefined") {
                            ErrorUserTextNotification.notify(oldPasswordElement[0], ErrorUserTextNotification.oldPasswordIncorrectErrorMessage);
                        }
                        if(response?.success && typeof(response?.data?.password_changed) !== "undefined") {
                            if(response.data.password_changed) {
                                SuccessUserTextNotification.notify(oldPasswordElement[0], SuccessUserTextNotification.passwordSuccessfullyChangedMessage);
                            }
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