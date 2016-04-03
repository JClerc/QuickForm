<?php

namespace View;
use Base\View;

/**
 * Index view
 */
class Index extends View {

    public function index() {
        $this->set('title', 'Créez et partagez rapidement des formulaires, sondages ou enquêtes !');
        $this->render([
            'parts/head/default',
            'parts/navbar/home',
            'page/home/index',
            'parts/footer/default'
        ]);
    }

}
