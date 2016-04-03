<?php

namespace View;
use Base\View;

/**
 * Stats view
 */
class Stats extends View {

    public function id() {
        $this->set('title', 'Voir les réponses');
        $this->render([
            'parts/head/boxed',
            'parts/navbar/stats',
            'element/boxed/start',
            'page/stats/id',
            'parts/footer/boxed'
        ]);
    }

    public function link() {
        $this->set('title', 'Voir les réponses');
        $this->render([
            'parts/head/boxed',
            'parts/navbar/default',
            'element/boxed/start',
            'page/stats/link',
            'parts/footer/boxed'
        ]);
    }

}
