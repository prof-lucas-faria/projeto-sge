<?php


namespace core\sistema;


class Footer {

    private $js = [];

    public function setJS($path) {
        $this->js[] = $path;
    }

    public function getJS() {
        return $this->js;
    }
}
