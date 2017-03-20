<?php
 
 namespace App\Controller;
 
  use Core\Controller\Controller;
  use App;
  
  /**
   * Contrôleur principal de l'application.
  * Ici est déterminé le template utilisé pour le site, ainsi que le répertoire
  * par défaut contenant les vues.
  *
  * @author Tim <tim@tchapelle.be>
  */
 class AppController extends Controller {
 
     /**
      * Constructeur : détermination du répertoire par défaut des vues et du template par défaut
      */
     public function __construct() {
         parent::__construct();
         $this->viewPath = ROOT . '/app/views/';
         $this->template = 'default';
     }
 
     /**
      * Charger un modèle (i.e. obtenir un accès à une table)
      * @param string $modele Le nom du modèle à charger (par exemple: "Vehicules" pour le modèle 'VehiculesModel'
      */
     protected function loadModel($modele) {
         $this->$modele = App::getInstance()->getModel($modele);
     }
 
 }
 