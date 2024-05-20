class Validator {
    static isValidEmail(email) {
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        return emailRegex.test(email);
    }

    static isValidLogin(login) {
        const loginRegex = /^[a-zA-Z0-9_]{4,20}$/;
        return loginRegex.test(login) || this.isValidEmail(login);
    }

    static isValidPassword(password) {
        const passwordRegex = /^.{8,}$/;
        return passwordRegex.test(password);
    }

    static isUrl(str) {
        const urlRegex = /^(https?|http):\/\/[^\s/$.?#].[^\s]*$/i;
        return urlRegex.test(str);
    }
}