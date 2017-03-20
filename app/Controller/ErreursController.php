<?php

namespace App\Controller;

/**
 * Description of ErreursController
 *
 * @author tim
 */
class ErreursController extends AppController {
    
    public function erreur403() {
        $this->render('erreurs/403');
    }
    
    public function erreur404() {
        $this->render('erreurs/404');
    }
}
