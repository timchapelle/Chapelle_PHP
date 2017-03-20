<?php

namespace Core;

/**
 * Description of Config
 *
 * @author Tim <tim at tchapelle dot be>
 */
class Config {

    private $settings = [];
    private static $_instance;

    public function __construct($file) {
        $this->settings = require($file);
    }

    public static function getInstance($file) {
        if (is_null(self::$_instance)) {
            self::$_instance = new Config($file);
        }
        return self::$_instance;
    }

    public function get($cle) {
        if (!isset($this->settings[$cle])) {
            return null;
        } else {
            return $this->settings[$cle];
        }
    }

}
