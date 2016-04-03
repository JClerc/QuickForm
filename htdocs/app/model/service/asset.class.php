<?php

namespace Model\Service;
use Model\Base\Service;

/**
 * Asset service
 */
class Asset extends Service {

    const ASSETS_CSS = HTTP_ROOT . 'assets/css/';
    const ASSETS_JS  = HTTP_ROOT . 'assets/js/';

    const FILE_CSS = PHP_ROOT . 'www/assets/css/';
    const FILE_JS  = PHP_ROOT . 'www/assets/js/';

    private $beforeCSS = '';
    private $beforeJS  = '';

    private $afterCSS = '';
    private $afterJS  = '';

    public function addCSS($css, $beforeCurrent = false) {
        if (substr($css, -4) === '.css') {
            $this->addRawCSS('<link href="' . e(self::ASSETS_CSS . $css) . '" rel="stylesheet">', $beforeCurrent);
        } else {
            $this->addRawCSS('<style>' . $css . '</style>', $beforeCurrent);
        }
    }

    public function addRawCSS($css, $beforeCurrent = false) {
        if ($beforeCurrent) $this->beforeCSS .= $css . PHP_EOL;
        else $this->afterCSS .= $css . PHP_EOL;
    }

    public function beforeCSS() {
        return $this->beforeCSS;
    }

    public function afterCSS() {
        return $this->afterCSS;
    }

    public function getCurrentCSS() {
        $resource = $this->request->getResource();
        $command  = $this->request->getCommand();
        $return = '';
        if (is_file(self::FILE_CSS . 'app/' . $resource . '/' . $command . '/style.css'))
            $return = '<link href="' . e(self::ASSETS_CSS . 'app/' . $resource . '/' . $command . '/style.css') . '" rel="stylesheet">' . PHP_EOL;
        else if (is_file(self::FILE_CSS . 'app/' . $resource . '/style.css'))
            $return = '<link href="' . e(self::ASSETS_CSS . 'app/' . $resource . '/style.css') . '" rel="stylesheet">' . PHP_EOL;
        return $return;
    }

    public function getAllCSS() {
        return $this->beforeCSS() . $this->getCurrentCSS() . $this->afterCSS();
    }

    public function addJS($js, $beforeCurrent = false) {
        if (substr($js, -3) === '.js') {
            $this->addRawJS('<script src="' . e(self::ASSETS_JS . $js) . '"></script>', $beforeCurrent);
        } else {
            $this->addRawJS(
                // This comment is for removing error of jshint
                '<script>' . $js . /*'; // */ '</script>',
                $beforeCurrent
            );
        }
    }

    public function addRawJS($js, $beforeCurrent = false) {
        if ($beforeCurrent) $this->beforeJS .= $js . PHP_EOL;
        else $this->afterJS .= $js . PHP_EOL;
    }

    public function beforeJS() {
        return $this->beforeJS;
    }

    public function afterJS() {
        return $this->afterJS;
    }

    public function getJS() {
        return $this->js;
    }

    public function getCurrentJS() {
        $resource = $this->request->getResource();
        $command  = $this->request->getCommand();
        $return = '';
        if (is_file(self::FILE_JS . 'app/' . $resource . '/' . $command . '/script.js'))
            $return = '<script src="' . e(self::ASSETS_JS . 'app/' . $resource . '/' . $command . '/script.js') . '"></script>' . PHP_EOL;
        else if (is_file(self::FILE_JS . 'app/' . $resource . '/script.js'))
            $return = '<script src="' . e(self::ASSETS_JS . 'app/' . $resource . '/script.js') . '"></script>' . PHP_EOL;
        return $return;
    }

    public function getAllJS() {
        return $this->beforeJS() . $this->getCurrentJS() . $this->afterJS();
    }

}
