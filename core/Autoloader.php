<?php

namespace Core;

/**
 * Chargement automatique des classes de la partie "Core" (réutilisable pour une autre app)
 * @author Tim <tim at tchapelle dot be>
 */
class Autoloader {

    /**
     * Appel récursif de la fonction autoload()
     */
    static function register() {
        spl_autoload_register([__CLASS__, 'autoload']);
    }
    
    /**
     * Chargement automatique d'une  classe
     * @param string $nomClasse
     */
    static function autoload($nomClasse) {
        // Si la classe est en dehors du namespace de l'app, on ne la loade pas auto
        if (strpos($nomClasse, __NAMESPACE__ . '\\') === 0) {
            $nomClasse = str_replace(__NAMESPACE__ . '\\', '', $nomClasse);
            $nomClasse = str_replace('\\', '/', $nomClasse);
            require __DIR__ . '/' . $nomClasse . '.php';
        }
    }

}
