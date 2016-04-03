<?php

namespace Model\Domain;
use Model\Base\Domain;

/**
 * A possible choice of a question
 */
class Choice extends Domain {

    protected $properties = [
        'question_id' => 0,
        'content'     => '',
    ];

    protected function __setQuestionId($questionId) {
        $question = $this->di->create(Question::class);
        $question->fromId($questionId);
        $this->validate->exists($question, 'La question n\'existe pas');
    }

    protected function __setContent($content) {
        $this->validate->isString($content);
    }

    public function getAnswers() {
        $answer = $this->di->create(Answer::class);
        return $answer->find(function ($search) {
            $search->where('choice_id', $this->getId());
        });
    }

    public function getPercentage() {
        $answer  = $this->di->create(Answer::class);
        $current = $this->database->select()->from($answer->getTable())
                        ->where('question_id', $this->getQuestionId())
                        ->andWhere('choice_id', $this->getId())
                        ->count();
        $other   = $this->database->select()->from($answer->getTable())
                        ->where('question_id', $this->getQuestionId())
                        ->andWhere('choice_id', '<>', $this->getId())
                        ->andWhere('choice_id', '<>', '0')
                        ->count();
        if ($other + $current > 0)
            return $current / ($other + $current) * 100;
        else
            return 0;
    }

    protected function onDelete() {
        foreach ($this->getAnswers() as $answer) $answer->delete();
    }

}
