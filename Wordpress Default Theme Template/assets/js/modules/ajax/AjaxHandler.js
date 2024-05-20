class AjaxHandler {
    static sendAjaxRequest({ajax_url, type, data, success_callback, error_callback}) {
        let xhr = new XMLHttpRequest();
        xhr.open(type, ajax_url, true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.onload = function() {
            if (xhr.status === 200) {
                success_callback(JSON.parse(xhr.responseText));
            }
        };
        xhr.onerror = function() {
            error_callback(xhr.statusText);
        };
        xhr.send(JSON.stringify(data));
    }
}