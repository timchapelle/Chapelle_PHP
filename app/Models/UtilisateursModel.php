<?php

namespace App\Models;

use Core\Db\Db;
use Core\Models\Model;

/**
 * Modèle des utilisateurs
 *
 * @author Tim <tim@tchapelle.be>
 */
class UtilisateursModel extends Model {

    /**
     * Récupérer un utilisateur avec son login et son mot de passe (pour vérifier
     * les identifiants)
     * 
     * @param string $login Login 
     * @param string $password   Mot de passe
     * @return bool
     */
    public function findByLoginPassword($login, $password) {
        return $user = $this->requete("SELECT * FROM " . $this->table . " WHERE login = ? AND password = ?", [$login, $password], true);
    }
    /**
     * Récupérer un utilisateur avec son login
     * @param string $login Login de l'utilisateur
     * @example '../Controller/UtilisateursController.php' Ligne 105
     * @return type
     */
    public function findByLogin($login) {
        return $this->requete("SELECT * FROM " . $this->table . " WHERE login = ?", [$login], true);
    }
}
