<?php

namespace View;
use Base\View;

/**
 * Create view
 */
class Create extends View {

    public function index() {
        $this->set('title', 'Créer un formulaire');
        $this->asset->addJS('vendor/jquery.sortable.min.js');
        $this->asset->addJS('app/edit/form.js');
        $this->render([
            'parts/head/boxed',
            'parts/navbar/default',
            'element/boxed/start',
            'page/create/index',
            'parts/footer/boxed'
        ]);
    }

    public function success() {
        $this->set('title', 'Formulaire créé !');
        $this->render([
            'parts/head/boxed',
            'parts/navbar/default',
            'element/boxed/start',
            'page/create/success',
            'parts/footer/boxed'
        ]);
    }

}
