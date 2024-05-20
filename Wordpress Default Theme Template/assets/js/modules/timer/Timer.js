class Timer {
    static startButtonTimer(duration, submitButton) {
        submitButton.disabled = true;
        const interval = setInterval(function () {
            submitButton.value = "Кнопка станет доступна через:" + duration + "с";
            duration--;
            if (duration < 0) {
                clearInterval(interval);
                submitButton.value = "Отправить";
                submitButton.disabled = false;
            }
        }, 1000);
    }
}