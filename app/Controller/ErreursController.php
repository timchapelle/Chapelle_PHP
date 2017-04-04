<?php

namespace App\Controller;

/**
 * Contrôleur pour la gestion des erreurs
 * @author tim
 */
class ErreursController extends AppController {
    
    /**
     * Accès interdit
     */
    public function erreur403() {
        $this->render('erreurs/403');
    }
    /**
     * URL incorrecte
     */
    public function erreur404() {
        $this->render('erreurs/404');
    }
}
