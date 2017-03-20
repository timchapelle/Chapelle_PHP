<?php

namespace App;

/**
 * Description of Autoloader
 * Autoloader pour la partie propre à l'application
 * 
 * @author Tim <tim at tchapelle dot be>
 */
class Autoloader {

    static function register() {
        spl_autoload_register([__CLASS__, 'autoload']);
    }

    static function autoload($nomClasse) {
        // Si la classe est en dehors du namespace de l'app, on ne la loade pas auto
        
        if (strpos($nomClasse, __NAMESPACE__ . '\\') === 0 && substr($nomClasse,-3) != 'PDF') {
            $nomClasse = str_replace(__NAMESPACE__ . '\\', '', $nomClasse);
            $nomClasse = str_replace('\\', '/', $nomClasse);
            require __DIR__ . '/' . $nomClasse . '.php';
        }
    }

}
