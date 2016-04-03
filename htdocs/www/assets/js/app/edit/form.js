
(function () {

    /*
     * --------------------------------
     *            Elements
     * --------------------------------
     *
     */
    var $questions     = $('.questions'),
        $addQuestion   = $('.add-question'),
        $questionTypes = $addQuestion.find('.question-types'),
        $questionType  = $questionTypes.find('.question-type'),
        $questionCount = $('.question-count'),
        $templates     = $('.templates');

    var templates = {
        radio:    $templates.find('.template-radio'),
        checkbox: $templates.find('.template-checkbox'),
        input:    $templates.find('.template-input'),
        textarea: $templates.find('.template-textarea'),
    };


    /*
     * --------------------------------
     *            Variables
     * --------------------------------
     *
     */
    var questionCount = ~~$questionCount.val();


    /*
     * --------------------------------
     *          Click actions
     * --------------------------------
     *
     */
    $(document).on('click', function (e) {
        $questionTypes.hide();
    });

    $addQuestion.on('click', function (e) {
        $questionTypes.toggle();
        e.preventDefault();
        e.stopPropagation();
    });

    $questionType.on('click', function (e) {
        $questionTypes.hide();
        e.preventDefault();
        e.stopPropagation();
        var $template = templates[ $(this).data('type') ];
        if ($template) {
            $questions.append($template.html().replace(/{COUNT}/g, ++questionCount));
            $questionCount.val(questionCount);
            $questions.sortable('reload');
        }
    });


    /*
     * --------------------------------
     *           Sortable
     * --------------------------------
     *
     */
    $questions.sortable({
        handle: '.handle'
    }).bind('sortupdate', function (e) {
        updateQuestionCount();
    });


    /*
     * --------------------------------
     *          Manage choices
     * --------------------------------
     *
     */
    var deleteChoice = function ($choice, prev) {
        var $choices = $choice.closest('.choices'),
            $children = $choices.children('.choice');
        if ($children.length <= 1 || $children.last().is($choice)) {
            var $append = createChoice($choice);
            $choice.remove();
            $choices.append($append);
            $append.find('.input-choice').focus();
        } else {
            if (prev) $choice.prev().find('.input-choice').focus();
            else $choice.next().find('.input-choice').focus();
            $choice.remove();
        }
    };

    var createChoice = function ($choice) {
        return $choice.clone()
                      .find('.input-choice').val('').end()
                      .find('input[name^=questions]').attr('name', function (i, name) {
                          return name.replace(/\[content\].*$/, '[content][]');
                      }).end();
    };

    var addChoice = function ($choice) {
        var $choices = $choice.closest('.choices');
        if ($choices.children('.choice').last().find('.input-choice').val().length) {
            $choices.append(createChoice($choice));
        }
    };

    var editQuestion = function (element) {
        $(element).closest('.question').find('.input-edited').val(1);
    };

    $questions.on('keyup', '.input-choice', function (e) {
        var $input = $(this);
        window.setTimeout(function () {
            if ($input.val().length) {
                addChoice($input.closest('.choice'));
            }
        }, 1);
    });

    $questions.on('input', '.input-choice', function (e) {
        editQuestion(this);
    });

    $questions.on('keydown', '.input-choice', function (e) {
        if (e.which === 13 || e.which === 40) {
            e.preventDefault();
            $(this).closest('.choice').next().find('.input-choice').focus();
        } else if (e.which === 38) {
            e.preventDefault();
            $(this).closest('.choice').prev().find('.input-choice').focus();
        } else if (e.which === 8 && $(this).val().length === 0) {
            deleteChoice($(this).closest('.choice'), true);
            e.preventDefault();
        }
    });

    $questions.on('click', '.delete-choice', function (e) {
        editQuestion(this);
        deleteChoice($(this).closest('.choice'));
        e.preventDefault();
    });


    /*
     * --------------------------------
     *         Question count
     * --------------------------------
     *
     */
    var updateQuestionCount = function () {
        $questionList = $questions.children('.question');
        questionCount = $questionList.length;
        $questionCount.val(questionCount);
        $questionList.each(function (i, e) {
            $(e).find('[name^=questions]').each(function () {
                $(this).attr('name', $(this).attr('name').replace(/^questions\[\d+\]/, 'questions[' + (i+1) + ']'));
            });
            $(e).find('.number').text('#' + (i+1));
        });
        console.log(questionCount, $questionCount);
    };


    /*
     * --------------------------------
     *          Manage options
     * --------------------------------
     *
     */
    $questions.on('click', '.mandatory', function (e) {
        var $question = $(this).closest('.question'),
            $mandatoryInput = $question.find('.mandatory-input'),
            $mandatoryText = $question.find('.mandatory-text');
        if (~~$mandatoryInput.val()) {
            $mandatoryInput.val(0);
            $mandatoryText.text('Obligatoire: NON');
        } else {
            $mandatoryInput.val(1);
            $mandatoryText.text('Obligatoire: OUI');
        }
        e.preventDefault();
    });

    $questions.on('click', '.delete-question', function (e) {
        $(this).closest('.question').remove();
        e.preventDefault();
        updateQuestionCount();
    });


    /*
     * --------------------------------
     *         Form validation
     * --------------------------------
     *
     */
    var form = {
        title: $('.input-special-title'),
        // desc: $('.input-special-desc'),
        email: $('.input-special-email'),
    };
    $('.form-send').on('submit', function (e) {
        var inputFilled = true;
        $.each(form, function (k, v) {
            if (!$(this).val().length) {
                $(this).focus();
                e.preventDefault();
                inputFilled = false;
                return false;
            }
        });
        if (inputFilled) {
            $('.questions .question').each(function () {
                var $question = $(this);
                if ($question.find('.input-special-question').val().length === 0) {
                    $question.find('.input-special-question').focus();
                    e.preventDefault();
                    inputFilled = false;
                    return false;
                }
                if ($question.hasClass('question-radio') || $question.hasClass('question-checkbox')) {
                    var choiceFilled = false;
                    $question.find('.input-choice').each(function () {
                        if ($(this).val().length > 0) {
                            choiceFilled = true;
                            return false;
                        } else if (choiceFilled === false) {
                            choiceFilled = $(this);
                        }
                    });
                    console.log(choiceFilled);
                    if (choiceFilled !== true) {
                        if (choiceFilled) choiceFilled.focus();
                        e.preventDefault();
                        inputFilled = false;
                        return false;
                    }
                }
            });
        }
        if (inputFilled) {
            if (!$questions.children('.question').length) {
                App.alert.error('Il n\'y a aucune question..', 3);
                e.preventDefault();
                return;
            }
        }
    });

})();
