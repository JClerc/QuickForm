
(function () {

    /*
     * --------------------------------
     *            Elements
     * --------------------------------
     *
     */
    var $questions = $('.questions'),
        $questionList = $questions.children('.question');


    /*
     * --------------------------------
     *         Form validation
     * --------------------------------
     *
     */
    var questionType = {
        radio: function ($question) {
            return $question.find('input[type="radio"]:checked').length > 0;
        },
        checkbox: function ($question) {
            return $question.find('input[type="checkbox"]:checked').length > 0;
        },
        input: function ($question) {
            return $question.find('input[type="text"]').val().length > 0;
        },
        textarea: function ($question) {
            return $question.find('textarea').val().length > 0;
        }
    };
    $('.form-send').on('submit', function (e) {
        $questionList.each(function () {
            var $question = $(this);
            if ($question.data('required') === true) {
                var type = $question.data('type');
                if (questionType[type]) {
                    if (!questionType[type]($question)) {
                        e.preventDefault();
                        App.alert.error('Vous devez répondre à la question #' + (~~$question.data('index') + 1) + ' "' + $.trim($question.find('.phrase').text()) + '"', 3);

                        var elOffset = $question.offset().top;
                        var elHeight = $question.height();
                        var windowHeight = $(window).height();
                        var offset;

                        if (elHeight < windowHeight) offset = elOffset - ((windowHeight / 2) - (elHeight / 2));
                        else offset = elOffset;

                        $('html, body').animate({scrollTop:offset}, 300);
                        return false;
                    }
                }
            }
        });
    });

})();
