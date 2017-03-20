<?php

namespace App\Models;

use Core\Db\Db;
use Core\Models\Model;

/**
 * Description of VehiclesModel
 *
 * @author Tim <tim at tchapelle dot be>
 */
class VehiculesModel extends Model {
    public function __construct(Db $db) {
        parent::__construct($db);
    }
    /**
     * Substitution de la fonction all()
     * @return VehiculesEntite
     */
    public function all() {return $this->requete("SELECT * FROM " . $this->table . " ORDER BY marque");}
    /**
     * Récupération des différents types de véhicules, afin de créer les select.
     * @return array
     */
    public function getTypes() {
        
       return $this->requete("SELECT DISTINCT type FROM " . $this->table . " ORDER BY type");
        
    }
    
    public function getTypeCount($type) {
        $nbType = $this->requete("SELECT count(*) as nb FROM " . $this->table . " WHERE type = ?", [$type], true);
        return $nbType->nb;
    }
    /**
     * Récupération des véhicules, filtrés/triés par l'utilisateur.
     * @param array $params Contient les renseignements suivants : filtre, ordre, ascendant/descendant
     * @return type
     */
    public function allSorted($params) {
        $filter = $params["filter"];
        $sql = "SELECT * FROM vehicules "
                . " WHERE (type LIKE '%" . $filter
                . "%' OR marque LIKE '%" . $filter
                . "%' OR plaque LIKE '%" . $filter
                . "%' OR numero_chassis LIKE '%" . $filter
                . "%' OR modele LIKE '%" . $filter . "%') AND (type LIKE '%" . $params["filterType"] . "%')"
                . " ORDER BY " . $params["orderBy"] . " " . $params["sortOrderBy"];

        return $this->requete($sql);
    }
    
    public function delete($id) {
        return $this->db->getPDO()->exec("DELETE FROM vehicules WHERE id = " . $id);
    }
}
