<?php

namespace Model\Domain;
use Model\Base\Domain;
use Model\Domain\Form;

/**
 * A question of a form
 */
class Question extends Domain {

    const TYPE = [
        'radio'    => true,
        'checkbox' => true,
        'input'    => false,
        'textarea' => false,
    ];

    protected $properties = [
        'form_id'   => 0,
        'type'      => '',
        'mandatory' => false,
        'phrase'    => '',
        'position'  => 0,
    ];

    public function hasChoices() {
        return self::TYPE[$this->properties['type']] ?? null;
    }

    public function isRequired() {
        return $this->properties['mandatory'] !== '0' and boolval($this->properties['mandatory']);
    }

    protected function __setFormId($formId) {
        $form = $this->di->create(Form::class);
        $form->fromId($formId);
        $this->validate->exists($form, 'Le formulaire n\'existe pas');
    }

    protected function __setType($type) {
        $this->validate->notNull(self::TYPE[$type] ?? null, 'Le type de question est incorrect');
    }

    protected function __setMandatory($mandatory) {
        $this->validate->isScalar($mandatory);
    }

    protected function __setPhrase($phrase) {
        $this->validate->isString($phrase, 'La question est incorrecte')
                       ->notEmpty($phrase, 'La question ne peut pas être vide')
                       ->max($phrase, 255, 'La question est trop longue');
    }

    protected function __setPosition($position) {
        $this->validate->isInt($position);
    }

    public function getChoices() {
        $choice = $this->di->create(Choice::class);
        return $choice->find(function ($search) {
            $search->where('question_id', $this->getId());
        });
    }

    public function getAnswers() {
        $answer = $this->di->create(Answer::class);
        return $answer->find(function ($search) {
            $search->where('question_id', $this->getId());
        });
    }

    public function hasChoice($compare) {
        $choices = $this->getChoices();
        foreach ($choices as $choice) {
            if ($choice->equals($compare)) {
                return true;
            }
        }
        return false;
    }

    protected function onDelete() {
        foreach ($this->getChoices() as $choice) $choice->delete();
        foreach ($this->getAnswers() as $answer) $answer->delete();
    }

}
