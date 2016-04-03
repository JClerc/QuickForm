<?php

namespace View;
use Base\View;

/**
 * Error pages
 */
class Error extends View {

    public function form() {
        $this->set('title', 'Formulaire inconnu');
        $this->render([
            'parts/head/boxed',
            'parts/navbar/default',
            'element/boxed/start',
            'page/error/form',
            'parts/footer/boxed'
        ]);
    }

    public function index() {
        $this->set('title', 'Erreur');
        $this->render([
            'parts/head/boxed',
            'parts/navbar/default',
            'element/boxed/start',
            'page/error/index',
            'parts/footer/boxed'
        ]);
    }

}
