'use strict';

var changeLanguage = function(language, callback) {

    var posting = $.post('/language', {
        language: language
    }, "json");

    posting.done(function(data) {
        if (typeof callback == 'function') {
            callback();
        }
        location.reload();
    }).fail(function() {
        //
    }).always(function(e) {
        //
    });
}

module.exports = changeLanguage;