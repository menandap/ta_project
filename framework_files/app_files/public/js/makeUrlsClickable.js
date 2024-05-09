// makeUrlsClickable.js
function makeUrlsClickable() {
    var textareas = document.querySelectorAll('.clickable-textarea');
    textareas.forEach(function(textarea) {
        var text = textarea.value;
        var replacedText = text.replace(/(\b(?:https?|ftp):\/\/\S+)/gi, function(url) {
            return '<a href="' + url + '" target="_blank">' + url + '</a>';
        });
        textarea.innerHTML = replacedText;
    });
}