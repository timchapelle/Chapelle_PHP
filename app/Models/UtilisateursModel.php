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
     * Retrouver un utilisateur avec son login et son mot de passe.
     * 
     * @param string $login Login 
     * @param string $password   Mot de passe
     * @return bool
     */
    public function findByLoginPassword($login, $password) {
        return $user = $this->requete("SELECT * FROM " . $this->table . " WHERE login = ? AND password = ?", [$login, $password], true);
    }
    
    public function findByLogin($login) {
        return $this->requete("SELECT * FROM " . $this->table . " WHERE login = ?", [$login], true);
    }
}
