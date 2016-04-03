<?php

namespace Model\Domain;
use Model\Base\Domain;

/**
 * A filled form
 */
class Fill extends Domain {

    protected $properties = [
        'form_id' => 0,
    ];

    protected function __setFormId($formId) {
        $form = $this->di->create(Form::class);
        $form->fromId($formId);
        $this->validate->exists($form);
    }

    public function getAnswers() {
        $answer = $this->di->create(Answer::class);
        return $answer->find(function ($search) {
            $search->where('fill_id', $this->getId());
        });
    }

    protected function onDelete() {
        foreach ($this->getAnswers() as $answer) $answer->delete();
    }

}
