<?php

namespace Model\Domain;
use Model\Base\Domain;
use Model\Domain\Question;

/**
 * A form
 *
 * @todo Merge link getters
 */
class Form extends Domain {

    protected $properties = [
        'title'   => '',
        'desc'    => '',
        'email'   => '',
        'updated' => 0,
    ];

    protected function __setTitle($title) {
        $this->validate->isString($title, 'Le titre est incorrect')
                       ->notEmpty($title, 'Le titre ne peux pas être vide')
                       ->max($title, 255, 'Le titre est trop long');
    }

    protected function __setDesc($desc) {
        $this->validate->isString($desc, 'La description est incorrecte');
    }

    protected function __setEmail($email) {
        $this->validate->isEmail($email, 'L\'adresse email incorrecte')
                       ->max($email, 255, 'L\'adresse email est trop longue');
    }

    protected function __setUpdated($updated) {
        $this->validate->isInt($updated);
    }

    public function getLink() {
        if ($this->exists())
            return 'http://' . $_SERVER['HTTP_HOST'] . HTTP_ROOT . 'view/id/' . $this->getId() . '/';
        else
            return 'http://' . $_SERVER['HTTP_HOST'] . HTTP_ROOT . 'create/';
    }

    public function getEditLink() {
        if ($this->exists())
            return 'http://' . $_SERVER['HTTP_HOST'] . HTTP_ROOT . 'edit/id/' . $this->getId() . '/' . $this->getHash() . '/';
        else
            return 'http://' . $_SERVER['HTTP_HOST'] . HTTP_ROOT . 'create/';        
    }

    public function getStatsLink() {
        if ($this->exists())
            return 'http://' . $_SERVER['HTTP_HOST'] . HTTP_ROOT . 'stats/id/' . $this->getId() . '/' . $this->getHash() . '/';
        else
            return 'http://' . $_SERVER['HTTP_HOST'] . HTTP_ROOT . 'stats/';        
    }

    public function getQuestions() {
        $question = $this->di->create(Question::class);
        return $question->find(function ($search) {
            $search->where('form_id', $this->getId())->orderBy('position');
        });
    }

    public function getFills() {
        $fill = $this->di->create(Fill::class);
        return $fill->find(function ($search) {
            $search->where('form_id', $this->getId());
        });
    }

    public function checkHash($hash) {
        return $this->crypt->equals($hash, $this->getHash());
    }

    public function getHash() {
        return hash('sha256', ']3@?' . $this->getId() . implode('~€$0x', $this->properties));
    }

    protected function onDelete() {
        foreach ($this->getQuestions() as $question) $question->delete();
        foreach ($this->getFills() as $fill) $fill->delete();
    }

}
