<?php

namespace Core\Db;

use PDO;

/**
 * Gestion de la base de données : 
 * Connexion, requête simple et requête préparée.
 *
 * @author Tim <tim at tchapelle dot be>
 */
class Db {

    /**
     * Nom d'hôte de la base de données 
     * @var string 
     */
    private $dbhost;

    /**
     * Nom de la db 
     * @var string
     */
    private $dbname;

    /**
     * Nom d'utilisateur db
     * @var string
     */
    private $dbuser;

    /**
     * Mot de passe de l'utilisateur db
     * @var string
     */
    private $dbpass;

    /**
     * Objet PDO, avec lequel les requêtes seront lancées
     * @var PDO
     */
    private $pdo;

    /**
     * Construction des paramètres de connexion
     * @param string $dbhost
     * @param string $dbname
     * @param string $dbuser
     * @param string $dbpass
     */
    public function __construct($dbhost, $dbname, $dbuser, $dbpass) {
        $this->dbhost = $dbhost;
        $this->dbname = $dbname;
        $this->dbuser = $dbuser;
        $this->dbpass = $dbpass;
    }

    /**
     * Connexion à la base de données et récupération de l'objet PDO qui 
     * permettra d'exécuter les requêtes.
     * Définition de l'UTF-8 comme standard de codage.
     * Activation des avertissements et de l'affichage des erreurs lors de
     * requêtes vers la db.
     * 
     * @return PDO
     */
    public function getPDO() {
        if ($this->pdo === null) {
            $pdo = new PDO("mysql:host=$this->dbhost;dbname=$this->dbname", $this->dbuser, $this->dbpass, [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo = $pdo;
        }
        return $this->pdo;
    }

    /**
     * Requête simple.
     * Retour sous forme d'objet si requête effectuée en dehors d'une classe,
     * sinon on renvoie un objet de la classe correspondante, dont les attributs
     * correspondront aux valeurs récupérées dans la db.
     * 
     * @param string $sql La requête à effectuer
     * @param class $classe La classe de l'objet à retourner
     * @param bool $one La requête doit-elle renvoyer un seul résultat ?
     * 
     * @return object
     */
    public function requete($sql, $classe = null, $one = false) {
        $req = $this->getPDO()->query($sql);
        if (
                strpos($sql, 'INSERT') === 0 ||
                strpos($sql, 'UPDATE') === 0 ||
                strpos($sql, 'DELETE') === 0
        ) {
            return $req;
        }
        if ($classe === null) {
            $req->setFetchMode(PDO::FETCH_OBJ);
        } else {
            $req->setFetchMode(PDO::FETCH_CLASS, $classe);
        }
        if ($one) {
            $data = $req->fetch();
        } else {
            $data = $req->fetchAll();
        }

        return $data;
    }

    /**
     * Requête préparée. Même principe que la requête simple.
     * 
     * @param string $sql
     * @param array $params
     * @param classe|null $classe
     * @param bool $one
     * @return type
     */
    public function prepare($sql, $params, $classe = null, $one = false) {
        $req = $this->getPDO()->prepare($sql);
        $res = $req->execute($params);
        if (
                strpos($sql, 'INSERT') === 0 ||
                strpos($sql, 'UPDATE') === 0 ||
                strpos($sql, 'DELETE') === 0
        ) {
            return $res;
        }
        if ($classe === null) {
            $req->setFetchMode(PDO::FETCH_OBJ);
        } else {
            $req->setFetchMode(PDO::FETCH_CLASS, $classe);
        }
        if ($one) {
            $data = $req->fetch();
        } else {
            $data = $req->fetchAll();
        }
        return $data;
    }

    /**
     * Récupération de l'id du dernier enregistrement effectué dans la db
     * 
     * @return int
     */
    public function lastInsertId() {
        return $this->getPDO()->lastInsertId();
    }

}
