<?php

namespace Core\Models;

use Core\Db\Db;

/**
 * Modèle 'parent' de l'application. 
 * Gère les opérations avec la base de données : Ajout, Recherche, Mise à jour,
 * Suppression (CRUD en anglais).
 *
 * @author Tim <tim at tchapelle dot be>
 */
class Model {

    /**
     * La table sur laquelle exécuter la requête
     * @var string
     */
    protected $table;

    /**
     * La base de données
     * @var Db
     */
    public $db;

    /**
     * Extraction du nom de la table à partir du nom de la classe.
     * Si la table n'est pas encore définie, on récupère la dernière partie
     * du nom de la classe, namespace compris (ex : App\Models\VehiculesModel --> VehiculesModel).
     * Ensuite, on remplace 'Model' par '', et on met le tout en minuscule --> vehicules.
     * La table sur laquelle sera exécutée la requête sera donc la table 'vehicules'.
     * 
     * @param Db $db
     */
    public function __construct(Db $db) {

        $this->db = $db;

        if (is_null($this->table)) {
            $parts = explode('\\', get_class($this));
            $className = end($parts);
            $this->table = strtolower(str_replace('Model', '', $className));
        }
    }

    /**
     * Requête vers la db.
     * S'il y a des paramètres, on effectue une requête préparée, sinon une simple.
     * On retourne un objet de la classe ...Entite correspondant à la classe ...Model 
     * ayant effectué la requête. (ex : VehiculesModel --> VehiculesEntite);
     * 
     * @param type $sql La requête à effectuer
     * @param type $params Les éventuels paramètres
     * @param type $one Résultat unique ?
     * @return Entite
     */
    public function requete($sql, $params = null, $one = false) {
        if ($params) {
            return $this->db->prepare(
                            $sql, $params, str_replace('Model', 'Entite', get_class($this)), $one);
        } else {
            return $this->db->requete(
                            $sql, str_replace('Model', 'Entite', get_class($this)), $one);
        }
    }

    /**
     * Mise à jour d'un enregistrement
     * @param int $id       Id de l'enregistrement
     * @param array $champs Champs à modifier
     */
    public function update($id, $champs) {
        // Tableau des champs
        $sql_parts = [];
        // Tableau des valeurs
        $attributs = [];
        foreach ($champs as $cle => $val) {
            $sql_parts[] = "$cle = ?";
            $attributs [] = $val;
        }
        $attributs[] = $id;
        // Concaténation des bouts de requete avec une virgule entre chaque champ
        $sql_part = implode(',', $sql_parts);
        return $this->requete(""
                        . "UPDATE {$this->table} SET $sql_part WHERE id = ?", $attributs, true);
    }

    /**
     * Création d'un enregistrement
     * 
     * @param type $champs Les champs à remplir
     * @return bool
     */
    public function create($champs) {
        // Tableau des champs
        $sql_parts = [];
        // Tableau des valeurs
        $attributs = [];
        foreach ($champs as $cle => $val) {
            $sql_parts[] = "$cle = ?";
            $attributs [] = $val;
        }
        // Concaténation des bouts de requete avec une virgule entre chaque champ
        $sql_part = implode(',', $sql_parts);

        return $this->requete(""
                        . "INSERT INTO {$this->table} SET $sql_part", $attributs, true);
    }

    /**
     * Suppression d'un enregistrement
     * @param int $id
     * @return bool
     */
    public function delete($id) {
        return $this->requete("DELETE FROM {$this->table} WHERE id = ?", [$id]);
    }

    /**
     * Retourne un élément dans la table correspondant à la classe appelée
     * 
     * @param int $id L'id de l'enregistrement à récupérer
     * @return Entite
     */
    public function find($id) {
        return $this->requete(""
                        . "SELECT * FROM "
                        . $this->table . "
                   WHERE id = ? "
                        , [$id], get_called_class(), true);
    }

    /**
     * Récupérer tous les enregistrements d'une table.
     * @return ObjectCollection
     */
    public function all() {
        return $this->requete("SELECT * FROM " . $this->table);
    }
    
    /**
     * Récupérer l'id du dernier enregistrement sauvegardé dans la db
     * @return int
     */
    public function lastId() {
        return $this->db->lastInsertId();
    }

}
