<?php

namespace Core\Entites;

/**
 * La classe Entite permet de créer des méthodes pour tous les objets récupérés
 * depuis la db.
 *
 * @author Tim <tim@tchapelle.be>
 */
class Entite {

    /**
     * Méthode "magique" : permet d'appeler des getters en les faisant passer pour des attributs
     * @example '../../app/Entites/ReparationsEntite.php' description: $reparation->dateFormatee exécutera $reparation->getDateFormatee()
     * @param string $key L'attribut à récupérer
     * @return type
     */
    public function __get($key) {
        $method = 'get' . ucfirst($key);
        $this->$key = $this->$method();
        return $this->$key;
    }
    
    public function toArray() {
        
        $result = [];
        
        foreach($this->getProperties() as $property) {
            $result[$property] = $this->$property;
        }
        
        return $result;
    }
    
    public function getProperties(){}
}
