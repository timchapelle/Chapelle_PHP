<?php

namespace App\Models;

use Core\Models\Model;

/**
 * Modèle des réparations
 *
 * @author Tim <tim@tchcapelle.be>
 */
class ReparationsModel extends Model {

    /**
     * Récupération de tous les enregistrements, par ordre décroissant de création.
     * 
     * @return ReparationsEntite
     */
    public function all() {
        return $this->requete("SELECT * FROM " . $this->table . " ORDER BY date DESC");
    }
    /**
     * Récupération des réparations liées à un véhicule.
     * @param int $id Id du véhicule correspondant
     * @return array
     */
    public function findByVehiculeId($id) {
        return $this->requete("SELECT * FROM " . $this->table . " WHERE vehicule_FK = ? ORDER BY date DESC", [$id]);
    }
    /**
     * Récupération de toutes les réparations, triées ou filtrées par l'utilisateur.
     * 
     * @param string $sort Le critère de tri
     * @param string $order Ascendant/Descendant
     * @param int $limit Limite
     * @param int $offset Offset
     * @return array
     */
    public function allOrderedBy($sort, $order, $limit, $offset) {
        return $this->requete("SELECT * FROM " . $this->table . " ORDER BY " . $sort . " " . $order . " LIMIT $limit " . " OFFSET $offset");
    }

}
