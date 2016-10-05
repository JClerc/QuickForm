<?php

namespace View;

/**
 * View form
 */
class View extends \Base\View {

    public function id() {
        $this->set('title', $this->get('form')->getTitle());
        $this->asset->addJS('app/view/form.js');
        $this->render([
            'parts/head/boxed',
            'parts/navbar/view',
            'element/boxed/start',
            'page/view/index',
            'parts/footer/boxed'
        ]);
    }

    public function success() {
        $this->set('title', 'Réponse enregistrée !');
        $this->render([
            'parts/head/boxed',
            'parts/navbar/view',
            'element/boxed/start',
            'page/view/success',
            'parts/footer/boxed'
        ]);
    }

}
