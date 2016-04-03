<?php

namespace View;
use Base\View;

/**
 * Create view
 */
class Edit extends View {

    public function id() {
        $this->set('title', 'Modifier votre formulaire');
        $this->asset->addJS('vendor/jquery.sortable.min.js');
        $this->asset->addJS('app/edit/form.js');
        $this->render([
            'parts/head/boxed',
            'parts/navbar/edit',
            'element/boxed/start',
            'page/edit/id',
            'parts/footer/boxed'
        ]);
    }

    public function link() {
        $this->set('title', 'Modifier un formulaire');
        $this->render([
            'parts/head/boxed',
            'parts/navbar/default',
            'element/boxed/start',
            'page/edit/link',
            'parts/footer/boxed'
        ]);
    }

    public function success() {
        $this->set('title', 'Formulaire sauvegardé !');
        $this->render([
            'parts/head/boxed',
            'parts/navbar/edit',
            'element/boxed/start',
            'page/edit/success',
            'parts/footer/boxed'
        ]);
    }

}
