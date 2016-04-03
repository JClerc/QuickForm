
var App = (function (App) {

    /*
     * --------------------------------
     *           Create App
     * --------------------------------
     *
     */
    var $alert = $('#alert');
    App.alert = {
        success: function (message, timeout) { this.__set('success', message, timeout); },
        info:    function (message, timeout) { this.__set('info',    message, timeout); },
        warn:    function (message, timeout) { this.__set('warning', message, timeout); },
        error:   function (message, timeout) { this.__set('danger',  message, timeout); },
        clear:   function () { $alert.empty(); },
        __set:   function (className, message, timeout) {
            $alert.empty().append($('<div>').addClass('alert alert-' + className).text(message));
            window.clearTimeout(this.__timeout);
            if (timeout) {
                var self = this;
                this.__timeout = window.setTimeout(function () {
                    self.clear();
                }, timeout * 1000);
            }
        },
        __timeout: null,
    };


    /*
     * --------------------------------
     *           Let's begin
     * --------------------------------
     *
     */
    $('[data-toggle="tooltip"]').tooltip();


    /*
     * --------------------------------
     *           Return App
     * --------------------------------
     *
     */
    return App;

})({});
