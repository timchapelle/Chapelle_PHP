<?php

use Core\Autoloader;
use Core\Config;
use Core\Db\Db;
use App\Autoloader as Autoloader2;

/**
 * Classe principale de l'application
 * 
 * @author Tim <tim at tchapelle dot be>
 */
class App {

    private $db_instance;
    private static $_instance;

    /**
     * Nom de la page (s'affiche dans l'onglet du browser)
     * @var string
     */
    public $title = 'Garage';

    /**
     * Lancement de l'application : 
     * 1. Démarrage de session
     * 2. Chargment des autoloaders
     */
    public static function load() {
        session_start();
        require ROOT . '/core/Autoloader.php';
        Autoloader::register();
        require ROOT . '/app/Autoloader.php';
        Autoloader2::register();
    }

    /**
     * Récupération d'une instance unique de l'application (pattern singleton)
     * @return App
     */
    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new App();
        }
        return self::$_instance;
    }

    /**
     * Obtenir l'accès à une table (chargement de la classe ...Model correspondante)
     * @param string $name Le nom de la table
     * @return object Une instance de la classe appelée
     */
    public function getModel($name) {
        $className = '\\App\\Models\\' . ucfirst($name) . 'Model';
        return new $className($this->getDb());
    }

    /**
     * Récupération d'une instance unique de la db.
     * Permet de limiter le nombre de connexions à la db (1 par utilisateur).
     * @return Db
     */
    public function getDb() {
        if (is_null($this->db_instance)) {
            $config = Config::getInstance(ROOT . '/config/config.php');
            $this->db_instance = new Db($config->get('dbhost'), $config->get('dbname'), $config->get('dbuser'), $config->get('dbpass'));
        }
        return $this->db_instance;
    }

    /**
     * Récupération du titre de la page
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Définition du titre de la page
     * @param string $title
     */
    public function setTitle($title) {
        $this->title .= ' | ' . $title;
    }

}
