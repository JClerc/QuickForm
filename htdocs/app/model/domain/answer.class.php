<?php

namespace Model\Domain;
use Model\Base\Domain;

/**
 * An answer to question
 */
class Answer extends Domain {

    protected $properties = [
        'fill_id'     => 0,
        'question_id' => 0,
        'content'     => '',
        'choice_id'   => 0,
    ];

    protected function __setFillId($fillId) {
        $fill = $this->di->create(Fill::class);
        $fill->fromId($fillId);
        $this->validate->exists($fill);
    }

    protected function __setQuestionId($questionId) {
        $question = $this->di->create(Question::class);
        $question->fromId($questionId);
        $this->validate->exists($question);
    }

    protected function __setContent($content) {}

    protected function __setChoiceId($choiceId) {}

}
