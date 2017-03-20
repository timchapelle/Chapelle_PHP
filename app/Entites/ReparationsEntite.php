<?php

namespace App\Entites;

use Core\Entites\Entite;

/**
 * Entité 'Réparations'
 *
 * @author Tim <tim at tchapelle dot be>
 */
class ReparationsEntite extends Entite {
    /**
     * Formatage de la date au format 'belge' : 31/12/2017
     * @return string
     */
    public function getDateFormatee() {
        return date('d/m/Y', strtotime($this->date));
    }
    
    public function getProperties() {
        return ['id','intervention','description','vehicule_FK','date'];
    }
}
