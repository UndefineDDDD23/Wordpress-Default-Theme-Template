class GeneralUserTextNotification {

    #notificationElementClass = "notification";
    #notificationElementId = "";
    #notificationElementTagName = "p";

    static notify(element, message) {
        const notificationElement = document.createElement(this.notificationElementTagName);
        notificationElement.classList.add(this.notificationElementClass);
        notificationElement.id = this.notificationElementId;
        notificationElement.textContent = message;        
        element.parentNode.insertBefore(notificationElement, element);
    }

    static removeLatestNotifications() {
        const elements = document.querySelectorAll(`.${this.notificationElementClass}`);
        elements.forEach(element => element.remove());
    }
}