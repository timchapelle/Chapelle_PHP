<?php

namespace App\Entites;

use Core\Entites\Entite;

/**
 * Entité 'véhicules'
 *
 * @author Tim <tim at tchapelle dot be>
 */
class VehiculesEntite extends Entite {
    
    /**
     * Constructeur de véhicule en passant les données du formulaire en paramètre.
     * Ceci au cas où les données entrées par l'utilisateur sont incorrectes.
     * @param array $post Les paramètres envoyés avec la méthode 'POST'
     */
    public function __construct($post = null) {
        if (!is_null($post)) {
            foreach ($post as $key => $val) {
                $this->$key = $val;
            }
            $this->id = 0;
        }
    }
    /**
     * Récupération de la plaque, formatée.
     * @return string
     */
    public function getPlaque() {
        return "1-" . $this->plaque1 . "-" . $this->plaque2;
    }
    /**
     * Récupération du n° de chassis, formaté.
     * @return string
     */
    public function getNumero_chassis() {
        return $this->numero_chassis1 . "-" . $this->numero_chassis2 . "-" . $this->numero_chassis3 . "-" . $this->numero_chassis4;
    }
    
    public function getProperties() {
        return ['id','marque','modele','plaque','numero_chassis','type'];
    }
}
