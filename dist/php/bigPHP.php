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
 
<?php

namespace App\Controller;

/**
 * Description of ErreursController
 *
 * @author tim
 */
class ErreursController extends AppController {
    
    public function erreur403() {
        $this->render('erreurs/403');
    }
    
    public function erreur404() {
        $this->render('erreurs/404');
    }
}

<?php

namespace App\Controller;

use App\Entites\ReparationsEntite;

/**
 * Contrôleur principal pour les réparations.
 *
 * @author Tim <tim at tchapelle dot be>
 */
class ReparationsController extends AppController {

    /**
     * Constructeur : chargement de la table des Réparations et des Véhicules
     */
    public function __construct() {
        parent::__construct();
        $this->loadModel('Reparations');
        $this->loadModel('Vehicules');
    }

    /*
     * Page d'accueil des réparations.
     * Fonctionnalités : pagination, tri (asc/desc), filtrage
     */

    public function index($msg = null) {
        
        $this->app->setTitle('Réparations');
        $page = 1;

        $nbReparations = count($this->Reparations->all());

        if (isset($_GET["page"])) {
            $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
            $page = $page ? $page : 1;
        }
        $sort = isset($_GET["sort"]) ? $this->sanitize($_GET["sort"]) : "";

        $permitted_sorts = ["id", "date", "vehicule_FK", "intervention", "description"];

        if (!in_array($sort, $permitted_sorts)) {
            $sort = "date";
        }

        $order = isset($_GET["order"]) ? $this->sanitize($_GET["order"]) : "";
        $permitted_orders = ["asc", "desc"];

        if (!in_array($order, $permitted_orders)) {
            $order = "desc";
        }
        // Limite de la requête sql
        $limit = 10;
        // Offset de la requête sql
        $offset = ($page * 10) - 10;
        // Ordre de tri à appliquer lorsque l'utilisateur re-clique sur un titre de colonne
        $nextOrder = $order == 'asc' ? 'desc' : 'asc';
        // Récupérations des réparations, triées selon les paramètres de l'utilisateur
        $reparations = $this->Reparations->allOrderedBy($sort, $order, $limit, $offset);

        if (!empty($reparations)) {

            foreach ($reparations as $reparation) {
                $reparation->vehicule = $this->Vehicules->find($reparation->vehicule_FK);
            }

            $this->render('reparations/index', [
                "reparations" => $reparations,
                "nbReparations" => $nbReparations,
                "page" => $page,
                "nextOrder" => $nextOrder,
                "msg" => $msg,
                "sort" => $sort,
                "order" => $order
            ]);
        } else {
            $this->notFound();
        }
    }

    /**
     * Modification d'une réparation.
     * 1. Vérification de l'id passé en GET
     * 2. Récupération de la réparation concernée
     * 3. Récupération du véhicule associé à la réparation
     * 4. Récupération de tous les véhicules (pour les proposer dans le select, si on veut attribuer la réparation à un autre véhicule
     * 5. Affichage de la page de modification
     * @param int $vehiculeFK
     * @param Array $errors
     * @param boolean $new
     */
    public function edit($vehiculeFK = null, $errors = null, $new = false) {
        if (isset($_SESSION["login"])) {
            // Première situation : le formulaire a déjà été complété et il y a
            // des erreurs
            if (isset($vehiculeFK) && isset($errors) && $new) {

                $rep = new ReparationsEntite();
                $rep->vehicule_FK = $vehiculeFK;
                $rep->id = 0;
                $rep->intervention = $_POST["intervention"];
                $rep->description = $_POST["description"];
                $rep->date = $_POST["date"];
                $rep->vehicule = $this->Vehicules->find($vehiculeFK);
                $title = "Veuillez corriger vos erreurs";
                $this->app->setTitle('Attention!');
                $action = "index.php?p=reparations.addReparation";
                $vehicules = $this->Vehicules->all();
                $this->render('reparations/edit', [
                    "reparation" => $rep,
                    "errors" => $errors,
                    "title" => $title,
                    "action" => $action,
                    "vehicules" => $vehicules
                ]);
            } else {
                // Sinon, on teste les paramètres GET
                $vehicule_FK = filter_input(INPUT_GET, 'vehicule_FK', FILTER_VALIDATE_INT);
                $id = isset($_GET["id"]) ? filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) : (isset($_POST["id"]) ? $_POST["id"] : 0);
                // Récupération de la réparation correspondant à l'id fourni (ou non)
                $rep = $this->Reparations->find($id);
                $errors = isset($errors) ? $errors : [];
                // Si cette réparation existe
                if ($rep) {
                    // Récupération du véhicule pour afficher ses propriétés
                    $rep->vehicule = $this->Vehicules->find($rep->vehicule_FK);
                    $action = "index.php?p=reparations.update";
                    $vehicules = $this->Vehicules->all();
                    $this->render('reparations/edit', [
                        "reparation" => $rep,
                        "vehicules" => $vehicules,
                        "title" => "Modifier la réparation #" . $rep->id,
                        "action" => $action,
                        "errors" => $errors
                    ]);
                } else if ($vehicule_FK) {
                    // Si il s'agit d'une nouvelle réparation

                    $title = "Ajouter une réparation sur le véhicule #" . $vehicule_FK;

                    $rep = new ReparationsEntite();
                    $rep->intervention = "";
                    $rep->description = "";
                    $rep->date = "";
                    $rep->id = 0;
                    $rep->vehicule_FK = $vehicule_FK;
                    $rep->vehicule = $this->Vehicules->find($vehicule_FK);

                    $vehicules = $this->Vehicules->all();

                    $action = "index.php?p=reparations.addReparation";

                    $this->render('reparations/edit', [
                        "reparation" => $rep,
                        "title" => $title,
                        "action" => $action,
                        "vehicules" => $vehicules,
                        "new" => true
                    ]);
                } else { // Sinon, un des id est erroné --> erreur 404
                    $this->notFound();
                }
            }
        } else { // Si l'utilisateur n'est pas loggé, interdiction de l'accès
            $this->forbidden();
        }
    }

    /**
     * Ajout d'une réparation dans la db, et retour de l'id de l'enregistrement
     * créé dans la db.
     */
    public function addReparation() {
        $errors = [];

        $userData["intervention"] = $this->sanitize($_POST["intervention"]);
        $userData["description"] = $this->sanitize($_POST["description"]);
        $userData["date"] = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
        $userData["vehicule_FK"] = filter_input(INPUT_POST, 'vehicule_FK', FILTER_VALIDATE_INT);
        $userData["originalVehiculeFK"] = $_POST["originalVehiculeFK"];

        $errors = $this->validateReparation($userData);
        
        if(isset($errors["vehicule_FK"])) {
            $userData["vehicule_FK"] = $userData["originalVehiculeFK"];
            unset($errors["vehicule_FK"]);
        }
        
        if (empty($errors)) {

            $aR = $this->Reparations->create([
                "intervention" => $userData["intervention"],
                "description" => $userData["description"],
                "date" => $userData["date"],
                "vehicule_FK" => $userData["vehicule_FK"]
            ]);
        } else {
            $this->edit($_POST["originalVehiculeFK"], $errors, true);
        }

        if (isset($aR) && $aR) {
            // PHP
            if (isset($_POST["mode"]) && $_POST["mode"] === "php") {
                //$this->index("Réparation ajoutée avec succès");
                $v = new VehiculesController();
                $v->indexPHP("Réparation ajoutée avec succès");
            } else {
                // AngularJS
                echo $this->Reparations->lastId();
            }
        } else {
            echo "0";
        }
    }

    /**
     * Valider les données rentrées par l'utilisateur pour l'ajout/modification
     * d'une réparation
     * @param Reparation $userData Les données entrées par l'utilisateur
     * @return Array $errors Tableau contenant les éventuelles erreurs
     */
    private function validateReparation($userData) {
        $errors = [];

        if ($userData["intervention"] == "") {
            $errors["intervention"] = "Veuillez renseigner un titre pour l'intervention";
        }

        if ($userData["description"] == "") {
            $errors["description"] = "Veuillez donner une description";
        }

        $datePattern = "/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/";

        if ($userData["date"] == "" | !preg_match($datePattern, $userData["date"])) {
            $errors["date"] = "Format de date invalide. Format souhaité : 2017-12-31.";
        }

        $vehicule = $this->Vehicules->find($userData["vehicule_FK"]);
        if (!$vehicule) {
            $errors["vehicule_FK"] = "Véhicule inexistant";
        }

        return $errors;
    }

    /**
     * Mise à jour d'une réparation
     */
    public function update() {

        $vU = false;

        $userData["intervention"] = $this->sanitize($_POST["intervention"]);
        $userData["description"] = $this->sanitize($_POST["description"]);
        $userData["vehicule_FK"] = filter_input(INPUT_POST, 'vehicule_FK', FILTER_VALIDATE_INT);
        $userData["id"] = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $userData["date"] = $this->sanitize($_POST["date"]);

        $errors = $this->validateReparation($userData);
        /* validation */

        if (empty($errors)) {

            $vU = $this->Reparations->update($userData["id"], [
                "intervention" => $userData["intervention"],
                "description" => $userData["description"],
                "vehicule_FK" => $userData["vehicule_FK"],
                "date" => $userData["date"]
            ]);

            if ($vU && $_POST["mode"] !== "angular") {
                $this->index("Réparation mise à jour avec succès");
            } else if ($vU) {
                echo "succes";
            }
        } else {
            $this->edit($_POST["originalVehiculeFK"], $errors);
        }
    }

    /**
     * Suppression d'une réparation 
     * @todo Vérifier que l'id existe dans la db.
     */
    public function delete() {
        if (isset($_SESSION["login"])) {
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

            if ($id) {

                if ($this->Reparations->delete($id)) {
                    $msg = "Réparation " . $id . " supprimée avec succès !";
                    $this->index($msg);
                } else {
                    $msg = "Erreur lors de la suppression de la réparation";
                    $this->index($msg);
                }
            }
        } else {
            $this->forbidden();
        }
    }

    /**
     * Afficher une réparation
     */
    public function show() {

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if ($id > 0) {
            $r = $this->Reparations->find($id);
        }
        if (isset($r) && $r) {
            $this->app->setTitle($r->intervention);
            $this->render('reparations/show', ["reparation" => $r]);
        } else {
            $this->notFound();
        }
    }

}

<?php

namespace App\Controller;

/**
 * Description of UsersController
 *
 * @author Tim <tim at tchapelle dot be>
 */
class UtilisateursController extends AppController {

    private $secretKey = "MaSuperCleIncassable";

    public function __construct() {
        parent::__construct();
        $this->loadModel('Utilisateurs');
    }

    /**
     * Affichage de la page de connexion
     */
    public function login() {
        
        $this->app->setTitle("Connexion");
        
        $this->render('utilisateurs/login');
    }

    /**
     * Affichage de la page d'accueil
     */
    public function home() {
        
        $this->app->setTitle("Accueil");
        
        $readme = file_get_contents('../README.md');
        $tools = file_get_contents('../docs/tools.md');
        $utilities = file_get_contents('../docs/utilities.md');
        $versions = file_get_contents('../docs/versions.md');
        $structure = file_get_contents('../docs/structure.md');
        
        $this->render('utilisateurs/home', [
            "readme" => $readme,
            "tools" => $tools,
            "utilities" => $utilities,
            "versions" => $versions,
            "structure" => $structure
        ]);
    }

    /**
     * Déconnexion et destruction de la session, puis retour à l'accueil
     */
    public function logout() {
        session_destroy();
        unset($_SESSION);
        $this->home();
    }

    /**
     * Validation du login et du mot de passe.
     * Retour au format JSON (géré avec AngularJS)
     */
    public function validateLogin() {
        $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_SPECIAL_CHARS);
        $mdp = filter_input(INPUT_POST, 'mdp', FILTER_SANITIZE_SPECIAL_CHARS);

        if ($user = $this->Utilisateurs->findByLoginPassword($login, $mdp)) {
            if ($user->password === $mdp) {
                $_SESSION["login"] = $login;
                if (isset($_POST["remember"]) && $_POST["remember"] == "true") {
                    // Date d'expiration des cookies
                    $expireDate = time() + (3600 * 24 * 365);
                    // Création d'un cookie 'garageLogin' contenant le login de l'utilisateur
                    setcookie('garageLogin', $login, $expireDate);
                } else {
                    if (isset($_COOKIE['garageLogin'])) {
                        setcookie('garageLogin', '');
                    }
                }
                $msg = [
                    "status" => true,
                    "message" => "Connexion réussie"
                ];
            } else {
                $msg = [
                    "status" => false,
                    "message" => "Identifiants incorrects, veuillez retenter votre chance"
                ];
            }
        } else {
            $msg = [
                "status" => false,
                "message" => "Identifiants incorrects, veuillez retenter votre chance"
            ];
        }
        echo json_encode($msg);
    }

    /**
     * Petite fonction pour récupérer le password vers angular (fonction remember me)
     */
    public function getPassword() {
        $user = $this->Utilisateurs->findByLogin($_POST["login"]);
        echo $user->password;
    }

    /**
     * Savoir si un utlisateur est loggé (angular)
     */
    public function isLogged() {
        if (isset($_SESSION["login"])) {
            echo json_encode($_SESSION["login"]);
        } else
            echo "";
    }

}

<?php

namespace App\Controller;

use App\Entites\VehiculesEntite;

/**
 * Description of VehiclesController
 *
 * @author Tim <tim at tchapelle dot be>
 */
class VehiculesController extends AppController {

    /**
     * Constructeur. Chargement de 2 modèles : 
     *  - Vehicules
     *  - Reparations
     */
    public function __construct() {
        parent::__construct();
        $this->loadModel('Vehicules');
        $this->loadModel('Reparations');
    }

    /**
     * Affichage de la page principale des véhicules, version AngularJS
     */
    public function index() {
        $this->app->setTitle('v1 AngularJS');
        $this->render('vehicules/indexAngular');
    }

    /**
     * Affichage de la page principale des véhicules, version PHP
     */
    public function indexPHP($message = null) {
        $this->app->setTitle("v2 PHP");
        $message = $message ? $message : "";
        // Récupération des types de véhicules différents depuis la db
        $options = $this->getTypeOptions("php");
        // Gestion des filtres
        if (isset($_POST["action"]) && $_POST["action"] === "filter") {
            // Epuration des input
            $filter = $this->sanitize($_POST["filter"]);
            $filterType = isset($_POST["filterType"]) ? $this->sanitize($_POST["filterType"]) : "";
            $orderBy = isset($_POST["orderBy"]) ? $this->sanitize($_POST["orderBy"]) : "id";
            $sortOrderBy = isset($_POST["sortOrderBy"]) ? $this->sanitize($_POST["sortOrderBy"]) : "asc";
            // Stockage des paramètres de requête dans un tableau
            $params = [
                "filter" => $filter,
                "filterType" => $filterType,
                "orderBy" => $orderBy,
                "sortOrderBy" => $sortOrderBy
            ];
            // Récupération des véhicules filtrés dans la db, en envoyant les paramètres de requête
            $vehicules = $this->Vehicules->allSorted($params);
        } else {
            // Si pas de filtres, on les récupère tous
            $vehicules = $this->Vehicules->all();
        }
        // Récupération des réparations des véhicules, pour les afficher dans les panels
        foreach ($vehicules as $vehicule) {
            $reparations = $this->Reparations->findByVehiculeId($vehicule->id);
            $vehicule->reparations = $reparations ? $reparations : [];
        }
        if (!isset($params)) {
            $params = [
                "filter" => "",
                "filterType" => "",
                "orderBy" => "",
                "sortOrderBy" => ""
            ];
        }
        // Affichage de la page d'index, version "pur PHP"
        $this->render('vehicules/indexPHP', [
            "vehicules" => $vehicules,
            "options" => $options,
            "searchParams" => $params,
            "message" => $message
        ]);
    }

    /**
     * Modification/ajout d'un véhicule (PHP)
     */
    public function edit() {
        if (isset($_SESSION["login"])) {
            $options = $this->Vehicules->getTypes();
            if (isset($_GET["id"])) {
                $id = $this->validateInt($_GET["id"]);
                $vehicule = $this->Vehicules->find($id);
                if ($vehicule) {
                    $title = "Modifier un véhicule";
                    $this->app->setTitle($title);
                    $action = 'index.php?p=vehicules.update';
                    $this->render('vehicules/edit', [
                        "vehicule" => $vehicule,
                        "title" => $title,
                        "action" => $action,
                        "options" => $options
                    ]);
                } else {
                    $this->notFound();
                }
            } else {

                $title = "Ajouter un véhicule";
                $action = 'index.php?p=vehicules.addVehicule';
                $this->app->setTitle($title);
                $this->render('vehicules/edit', [
                    "title" => $title,
                    "action" => $action,
                    "options" => $options
                ]);
            }
        } else {
            $this->forbidden();
        }
    }

    /**
     * Récupération des véhicules (AngularJS)
     */
    public function getVehicules() {
        $vehicules = $this->Vehicules->all();
        foreach ($vehicules as $vehicule) {
            $reparations = $this->Reparations->findByVehiculeId($vehicule->id);
            $vehicule->reparations = $reparations ? $reparations : [];
        }
        header('Content-Type: application/json');
        echo json_encode($vehicules);
    }

    /**
     * Ajout d'un véhicule :
     * Vérification de la plaque, de la marque, du modèle, du numéro de chassis,
     * et du type. Affichage des erreurs et retour au formulaire le cas échéant.
     */
    public function addVehicule() {
        if (isset($_POST) && !empty($_POST)) {

            // Contrôle de la plaque du véhicule

            $userData["plaque1"] = $this->validateInt($_POST["plaque1"]);
            $userData["plaque2"] = strtoupper($this->sanitize($_POST["plaque2"]));
            $userData["plaque3"] = $this->validateInt($_POST["plaque3"]);

            $userData["plaque"] = $userData["plaque1"] . '-' . $userData["plaque2"] . '-' . $userData["plaque3"];

            // Contrôle de la marque du véhicule
            $userData["marque"] = $this->sanitize($_POST["marque"]);

            // Contrôle du modèle du véhicule
            $userData["modele"] = $this->sanitize($_POST["modele"]);
            $userData["numero_chassis"] = $this->getNumeroChassis($_POST);
            // Contrôle du type de véhicule
            $userData["type"] = isset($_POST["type"]) && !empty($_POST["type"]) ?
                    $this->sanitize($_POST["type"]) :
                    $this->sanitize($_POST["autreType"]);

            $errors = $this->validateVehicule($userData);

            // S'il n'y a pas d'erreurs, on crée le véhicule
            if (empty($errors)) {
                $addVehicule = $this->Vehicules->create([
                    "plaque" => $userData["plaque"],
                    "marque" => $userData["marque"],
                    "modele" => $userData["modele"],
                    "numero_chassis" => $userData["numero_chassis"],
                    "type" => $userData["type"]
                ]);
            } else {
                if ($_POST["mode"] !== "angular") {
                    $vehicule = isset($_POST["id"]) && $_POST["id"] != 0 ?
                            $this->Vehicules->find($_POST["id"]) :
                            new VehiculesEntite($_POST);
                    $options = $this->getTypeOptions("php");
                    $this->render('vehicules/edit', [
                        "vehicule" => $vehicule,
                        "erreurs" => $errors,
                        "title" => "Ajouter un véhicule",
                        "action" => "index.php?p=vehicules.addVehicule",
                        "options" => $options
                    ]);
                } else {
                    echo "échec";
                }
            }

            if (isset($addVehicule) && $addVehicule) {
                if ($_POST["mode"] === "php") {
                    $this->indexPHP("Véhicule ajouté avec succès !");
                } else {
                    echo $this->Vehicules->lastId();
                }
            } else {
                echo "échec";
            }
        } else {
            $this->notFound();
        }
    }

    /**
     * Mise à jour d'un véhicule - Version PHP
     */
    public function update() {
        $message = "";
        // Id 
        $id = $this->validateInt($_POST["id"]);
        // Marque
        $userData["marque"] = $this->sanitize($_POST["marque"]);
        // Modèle du véhicule
        $userData["modele"] = $this->sanitize($_POST["modele"]);
        // Numéro de chassis
        $userData["numero_chassis"] = $this->getNumeroChassis($_POST);
        // Plaque
        $userData["plaque1"] = $this->validateInt($_POST["plaque1"]);
        $userData["plaque2"] = strtoupper($this->sanitize($_POST["plaque2"]));
        $userData["plaque3"] = $this->validateInt($_POST["plaque3"]);
        $userData["plaque"] = $userData["plaque1"] . '-' . $userData["plaque2"] . '-' . $userData["plaque3"];

        // Type de véhicule
        $userData["type"] = isset($_POST["type"]) && !empty($_POST["type"]) ?
                $this->sanitize($_POST["type"]) :
                $this->sanitize($_POST["autreType"]);

        // Validation
        $errors = $this->validateVehicule($userData);
        // Si pas d'erreurs, mise à jour du véhicule 

        if (empty($errors)) {
            $updateVehicule = $this->Vehicules->update($id, [
                "plaque" => $userData["plaque"],
                "marque" => $userData["marque"],
                "modele" => $userData["modele"],
                "numero_chassis" => $userData["numero_chassis"],
                "type" => $userData["type"]
            ]);
            if ($updateVehicule) {
                $message = $userData["marque"] . " " . $userData["modele"] . " mis(e) à jour avec succès";
                $vehicule = $this->Vehicules->find($id);
            } else {
                $message = "Echec lors de la mise à jour dans la db";
            }
        } else {
            $_POST["plaque"] = $userData["plaque"];
            $vehicule = new VehiculesEntite($_POST);
            $vehicule->id = $id;
            $vehicule->numero_chassis = substr($_POST["numero_chassis1"] . "*****", 0, 5) . "-"
                    . substr($_POST["numero_chassis2"] . "*****", 0, 5) . "-"
                    . substr($_POST["numero_chassis3"] . "*****", 0, 5) . "-"
                    . substr($_POST["numero_chassis4"] . "*****", 0, 6);
        }

        $options = $this->getTypeOptions("php");
        $this->render('vehicules/edit', [
            "vehicule" => $vehicule,
            "erreurs" => $errors,
            "message" => $message,
            "options" => $options,
            "title" => "Modification de véhicule",
            "action" => "index.php?p=vehicules.update"
        ]);
    }

    /**
     * Validation d'un véhicule
     */
    private function validateVehicule($userData) {

        $errors = [];

        if ($userData["marque"] == "") {
            $errors[] = "Veuillez spécifier la marque du véhicule";
        }

        if ($userData["modele"] == "") {
            $errors[] = "Veuillez spécifier le modèle du véhicule";
        }

        if ($userData["numero_chassis"] === 0) {
            $errors[] = "Mauvais format de n° de chassis. "
                    . "Veuillez rentrer un format de type '12345-12345-12345-123456'";
        }

        if ($userData["plaque1"] != 0 && $userData["plaque1"] != 1) {
            $errors[] = "La plaque doit débuter par 0 ou 1";
        }

        if (!preg_match("/[A-Z]{3}/", $userData["plaque2"])) {
            $errors[] = "La deuxième partie de la plaque doit comporter 3 caractères";
        }
        if (!preg_match("/[0-9]{3}/", $userData["plaque3"])) {
            $errors[] = "La deuxième partie de la plaque doit comporter 3 chiffres";
        }

        if ($userData["type"] == "") {
            $errors[] = "Veuillez spécifier le type de véhicule";
        }

        return $errors;
    }

    /**
     * Mise à jour d'un véhicule : version Angular
     */
    public function updateVehicule() {
        $plaque = $this->sanitize($_POST["plaque"]);
        $marque = $this->sanitize($_POST["marque"]);
        $num_chassis = $this->sanitize($_POST["numero_chassis"]);
        $modele = $this->sanitize($_POST["modele"]);

        $uV = $this->Vehicules->update($_POST["id"], [
            "plaque" => $plaque,
            "marque" => $marque,
            "modele" => $modele,
            "numero_chassis" => $num_chassis,
            "type" => $_POST["type"]
        ]);
        if ($uV) {
            echo "Véhicule avec id " . $_POST["id"] . " mis à jour";
        } else {
            echo "Echec";
        }
    }

    /**
     * Suppression d'un véhicule (PHP)
     */
    public function delete() {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if ($id && isset($_SESSION["login"])) {
            $dV = $this->Vehicules->delete($id);
            if ($dV > 0) {
                $this->indexPHP("Véhicule supprimé avec succès");
            } else {
                $this->notFound();
            }
        } else if (!isset($_SESSION["login"])) {
            $this->forbidden();
        } else {
            $this->notFound();
        }
    }

    /**
     * Suppression d'un véhicule (Angular)
     */
    public function deleteVehicule() {
        $this->Vehicules->delete($_POST["id"]);
    }

    /**
     * Affichage de la fiche d'un véhicule
     */
    public function show() {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if ($id) {
            $vehicule = $this->Vehicules->find($id);
        }
        if ($vehicule) {
            $vehicule->reparations = $this->Reparations->findByVehiculeId($id);
            $this->app->setTitle($vehicule->marque . " " . $vehicule->modele);
            $this->render('vehicules/show', ["vehicule" => $vehicule]);
        } else {
            $this->notFound();
        }
    }

    /**
     * Récupération des différents types de véhicules
     * @return Array|string Retourne le tableau des types si appelé par un contrôleur PHP, sinon retourne un tableau d'objets JSON pour Angular
     */
    public function getTypeOptions($mode = null) {
        $types = $this->Vehicules->getTypes();
        foreach ($types as $type) {
            $type->nb = $this->Vehicules->getTypeCount($type->type);
        }
        if ($mode !== "php") {
            echo json_encode($types);
        } else
            return $types;
    }

    /**
     * Formatage du n° de chassis
     * @param array $post Les données entrées par l'utilisateur
     * @return int|string Retourne 0 si le numéro est incorrect, sinon le n° de chassis formaté
     */
    private function getNumeroChassis($post) {
        $numero_chassis = "";
        for ($i = 1; $i <= 4; $i++) {
            $num_tmp = "numero_chassis" . $i;
            $numero_chassis .= isset($post[$num_tmp]) ?
                    $this->validateInt($post[$num_tmp]) :
                    "";
            $numero_chassis .= "-";
        }
        // Retrait du dernier tiret
        $numero_chassis = substr($numero_chassis, 0, -1);
        // Contrôle du format du numéro de chassis
        if (preg_match("/([0-9]{5}-[0-9]{5}-[0-9]{5}-[0-9]{6})/", $numero_chassis)) {
            return $numero_chassis;
        } else {
            return 0;
        }
    }

}

 /**
     * Exportation de tous les véhicules au format PDF (sous forme de tableau)
     */
    public function exportAllAsPDF() {
        $vehicules = $this->Vehicules->all();

        $pdf = $this->getPDF("Liste des véhicules", "Landscape");
        $html = "<style>th{background-color:#ececec;text-align:center}</style>";
        $html .= "<h1>Liste des véhicules</h1>";
        $html .= '<table border="1" cellpadding="5">';
        $html .= '<tr><th width="50"><b>ID</b></th><th><b>Marque</b></th><th><b>Modèle</b></th><th><b>Plaque</b></th><th width="210"><b>Chassis</b></th><th><b>Type</b></th></tr>';
        foreach ($vehicules as $v) {
            $html .= '<tr><td width="50" align="center">' . $v->id . "</td><td>";
            $html .= $v->marque . "</td><td>";
            $html .= $v->modele . "</td><td>";
            $html .= $v->plaque . '</td><td width="210">';
            $html .= $v->numero_chassis . "</td><td>";
            $html .= $v->type . "</td></tr>";
        }
        $html .= "</table>";

        // Générer le contenu HTML
        $pdf->writeHTML($html, true, false, true, false, '');

        // Remettre le pointeur sur la dernière page
        $pdf->lastPage();
        // Fermeture et affichage du document PDF
        $pdf->Output('Vehicules_Recapitulatif.pdf', 'I');
    }

    private function getPDF($title, $orientation) {

        $pdf = new \TCPDF_TCPDF($orientation, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $creator = isset($_SESSION["login"]) ? $_SESSION["login"] : "Anonyme";

        $pdf->SetCreator($creator);
        $pdf->SetAuthor($creator);
        $pdf->SetTitle($title);
        $printedBy = "Garage Chapelle ";
        $printedBy .= "(Imprimé  par " . $creator . " le " . date('d/m/Y') . " à " . date('H:i') . ")";

        // Données du header
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $title, $printedBy);

        // Définition des polices du header et du footer
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // Police 'monospaced' par défaut
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // Définition des marges
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // Sauts de page automatiques
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // Mise à l'échelle des images
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // Ajout d'une page
        $pdf->AddPage();

        return $pdf;
    }

    /**
     * Exportation d'un véhicule et ses réparations au format PDF
     */
    public function exportAsPDF() {

        $vehicule = $this->Vehicules->find($_GET["id"]);
        if ($vehicule) {
            $vehicule->reparations = $this->Reparations->findByVehiculeId($_GET["id"]);
            // Création d'un nouveau document PDF
            $pdf = $this->getPDF($vehicule->marque . ' ' . $vehicule->modele, 'landscape');

            // Définition du contenu du document
            $html = "<h1>" . $vehicule->marque . ' ' . $vehicule->modele . "</h1>";
            $html .= "<h3>Caractéristiques</h3>";
            $html .= "<ul>";
            $html .= "<li>Plaque : " . $vehicule->plaque . "</li>";
            $html .= "<li>N° de chassis : " . $vehicule->numero_chassis . "</li>";
            $html .= "</ul>";
            $html .= "<h3> Réparation";
            $html .= count($vehicule->reparations) > 0 ? "s" : "";
            $html .= "</h3>";
            // Si le véhicule a des réparations, on les liste
            if (!empty($vehicule->reparations)) {
                $cpt = 1;
                $html .= '<style>th{font-weight:bold;text-align:center;background-color:#ececec}</style>';
                $html .= '<table border="1" cellpadding="5">';
                $html .= '<tr><th width="40">Id</th><th>Intervention</th><th>Description</th><th>Date</th></tr>';
                foreach ($vehicule->reparations as $rep) {
                    $cpt++;
                    
                    $html .= "<tr>";
                    $html .= '<td width="40">'. $rep->id . "</td>";
                    $html .= "<td>". $rep->intervention . "</td>";
                    $html .= "<td>". $rep->description . "</td>";
                    $html .= "<td>". date('d/m/Y', strtotime($rep->date)) . "</td>";
                   
                    $html .= "</tr>";
                }
                $html .= "</table>";
            } else {
                // Sinon, on affiche un message
                $html .= "<h5>Pas encore de réparations pour ce véhicule</h5>";
            }
            /* Ajouter une page
              $pdf->AddPage();
              $html = '<h1>Hey</h1>';
             */
            // Générer le contenu HTML
            $pdf->writeHTML($html, true, false, true, false, '');

            // Remettre le pointeur sur la dernière page
            $pdf->lastPage();
            // Fermeture et affichage du document PDF
            $pdf->Output('Vehicule_' . $vehicule->id . '_Recapitulatif.pdf', 'I');
        } else {
            // Erreur 404 si un mauvais id est passé en paramètre
            $this->notFound();
        }
    }

    /**
     * Exportation des véhicules au format CSV
     */
    public function exportAsCSV() {

        $vehicules = $this->Vehicules->all();
        $file = fopen('test.csv', 'w');
        $output = fopen("php://output", "w");
        foreach ($vehicules as $v) {
            fputcsv($output, $v->toArray());
        }

        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=test.csv");

        fclose($output);
    }

    /**
     * Exportation des réparations d'un véhicule au format CSV
     */
    public function exportReparationsAsCSV() {

        $id = $this->validateInt($_GET["id"]);
        if ($id) {
            $reparations = $this->Reparations->findByVehiculeId($id);
            $output = fopen("php://output", "w");
            foreach ($reparations as $r) {
                fputcsv($output, $r->toArray());
            }
            header("Content-Type: text/csv");
            header("Content-Disposition: attachment; filename=vehicule_" . $id . "_reparations.csv");
            fclose($output);
        } else {
            $this->indexPHP("L'id indiqué n'est pas correct");
        }
    }

    /**
     * Importation d'un fichier CSV contenant des nouveaux véhicules
     */
    public function importCSV() {

        $file = $_FILES[0];

        $uploadDir = ROOT . '/assets/uploads/';
        $id = uniqid();

        if (move_uploaded_file($file['tmp_name'], $uploadDir . $id . ".csv")) {
            $response = [
                "insertedRows" => 0,
                "errorRows" => 0,
                "errorRowNumbers" => ""
            ];

            $row = 0; // ligne actuelle

            if (($handle = fopen($uploadDir . $id . ".csv", "r"))) { // si on arrive à ouvrir le fichier en lecture
                while (($data = fgetcsv($handle, 1000, ","))) { // tant qu'on n'est pas à la fin du fichier
                    $num = count($data); // $num champs à la ligne $row
                    $row++;              // passage à la ligne suivante
                    for ($c = 0; $c < $num; $c++) {
                        // echo $data[$c] . "<br />\n";
                        $v = [];
                        $v["id"] = $data[0];
                        $v["marque"] = $data[1];
                        $v["modele"] = $data[2];
                        $v["plaque"] = $data[3];
                        $v["plaque1"] = $v["plaque"] != "" ? intval(substr($v["plaque"], 0, 1)) : 2;
                        $v["plaque2"] = $v["plaque"] != "" ? substr($v["plaque"], 2, 3) : "123";
                        $v["plaque3"] = $v["plaque"] != "" ? substr($v["plaque"], 6, 3) : "abc";
                        $v["numero_chassis"] = $data[4];
                        $v["type"] = $data[5];
                    }

                    $errors = $this->validateVehicule($v);

                    if (empty($errors)) {
                        $creation = $this->Vehicules->create([
                            "marque" => $v["marque"],
                            "modele" => $v["modele"],
                            "plaque" => $v["plaque"],
                            "numero_chassis" => $v["numero_chassis"],
                            "type" => $v["type"]
                        ]);
                    } else {
                        $response["errorRows"] ++;
                        $response["errorRowNumbers"] .= ($row . " ");
                    }
                    if ($creation) {
                        $response["insertedRows"] ++;
                    }
                }
                fclose($handle); // fermeture du fichier
                unlink($uploadDir . $id . ".csv"); // Destruction du fichier temporaire

                echo json_encode($response);
            }
        } else {
            $response = [
                "status" => "Fichier pas uploadé"
            ];
            echo json_encode($response);
        }
    }

<?php

namespace App\Entites;

use Core\Entites\Entite;

/**
 * Entité 'Réparations'
 *
 * @author Tim <tim at tchapelle dot be>
 */
class ReparationsEntite extends Entite {
    /**
     * Formatage de la date au format 'belge' : 31/12/2017
     * @return string
     */
    public function getDateFormatee() {
        return date('d/m/Y', strtotime($this->date));
    }
    
    public function getProperties() {
        return ['id','intervention','description','vehicule_FK','date'];
    }
}

<?php

namespace App\Entites;

use Core\Entites\Entite;

/**
 * Description of UtilisateursEntite
 *
 * @author Tim <tim at tchapelle dot be>
 */
class UtilisateursEntite extends Entite {
    
}

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

<?php

namespace App;

/**
 * Description of Autoloader
 * Autoloader pour la partie propre à l'application
 * 
 * @author Tim <tim at tchapelle dot be>
 */
class Autoloader {

    static function register() {
        spl_autoload_register([__CLASS__, 'autoload']);
    }

    static function autoload($nomClasse) {
        // Si la classe est en dehors du namespace de l'app, on ne la loade pas auto
        
        if (strpos($nomClasse, __NAMESPACE__ . '\\') === 0 && substr($nomClasse,-3) != 'PDF') {
            $nomClasse = str_replace(__NAMESPACE__ . '\\', '', $nomClasse);
            $nomClasse = str_replace('\\', '/', $nomClasse);
            require __DIR__ . '/' . $nomClasse . '.php';
        }
    }

}

<?php

namespace Core;

/**
 * Chargement automatique des classes de la partie "Core" (réutilisable pour une autre app)
 * @author Tim <tim at tchapelle dot be>
 */
class Autoloader {

    /**
     * Appel récursif de la fonction autoload()
     */
    static function register() {
        spl_autoload_register([__CLASS__, 'autoload']);
    }
    
    /**
     * Chargement automatique d'une  classe
     * @param string $nomClasse
     */
    static function autoload($nomClasse) {
        // Si la classe est en dehors du namespace de l'app, on ne la loade pas auto
        if (strpos($nomClasse, __NAMESPACE__ . '\\') === 0) {
            $nomClasse = str_replace(__NAMESPACE__ . '\\', '', $nomClasse);
            $nomClasse = str_replace('\\', '/', $nomClasse);
            require __DIR__ . '/' . $nomClasse . '.php';
        }
    }

}

<?php

namespace Core;

/**
 * Description of Config
 *
 * @author Tim <tim at tchapelle dot be>
 */
class Config {

    private $settings = [];
    private static $_instance;

    public function __construct($file) {
        $this->settings = require($file);
    }

    public static function getInstance($file) {
        if (is_null(self::$_instance)) {
            self::$_instance = new Config($file);
        }
        return self::$_instance;
    }

    public function get($cle) {
        if (!isset($this->settings[$cle])) {
            return null;
        } else {
            return $this->settings[$cle];
        }
    }

}

<?php

namespace Core\Controller;

/**
 * Contrôleur 'parent' de l'application. Ce contrôleur fait partie du namespace Core,
 * et par conséquent ne contient que des méthodes génériques, pouvant être réutilisées
 * pour d'autres projets. 
 * Les méthodes spécifiques à l'application se trouveront dans la partie App.
 *
 * @author Tim <tim@tchapelle.be>
 */
class Controller {

    /**
     * Répertoire par défaut contenant les vues.
     * @example "./app/Views"
     * @var string 
     */
    protected $viewPath;

    /**
     * Template par défaut
     * @var string 
     */
    protected $template;
    protected $app;

    public function __construct() {
        $this->app = \App::getInstance();
    }

    /**
     * Affichage d'une vue, et "transfert" des variables du contrôleur à la vue
     * @param string $view Le chemin de la vue à afficher
     * @param array $params Les variables à passer du contrôleur à la vue
     */
    public function render($view, $params = []) {
        ob_start();
        // Extraction des variables passées en paramètre
        extract($params);
        // Inclusion de la vue
        require($this->viewPath . $view . '.php');
        // Récupération de la vue pour l'insérer dans le template
        $content = ob_get_clean();
        // Inclusion du template
        require($this->viewPath . 'templates/' . $this->template . '.php');
    }

    /**
     * "Assainir" une variable texte (entrée par l'utilisateur).
     *  Maintien des single/double quotes.
     * @param string $txt
     * @return string
     */
    public function sanitize($txt) {
        /* return trim(htmlspecialchars(strip_tags($txt), ENT_NOQUOTES)); */
        return trim(filter_var($txt, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES));
    }

    public function validateInt($int) {
        return filter_var($int, FILTER_VALIDATE_INT);
    }

    public function forbidden() {
        header('HTTP/1.0 403 Forbidden');
        header('Location:index.php?p=erreurs.erreur403');
    }

    public function notFound() {
        header('HTTP/1.0 404 Not Found');
        header('Location:index.php?p=erreurs.erreur404');
    }

}

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

<?php

/* Index (fait office de front controller)
  Définition d'une constante 'racine' (ROOT) pour faciliter la gestion des URL */

define('ROOT', dirname(__DIR__));

// ROOT = /var/www/html/Chapelle_PHP
require ROOT . '/app/App.php';
App::load();

require_once ROOT . '/assets/Parsedown.php';
require ROOT . '/assets/ParsedownExtra.php';
$md = new ParsedownExtra();

if (isset($_GET["p"])) {
    $p = $_GET["p"]; // à sanitizer : sanitize($_GET["p"])
} else {
    $p = 'utilisateurs.home';  // accueil
}
/**
 *  J' "explose" $p en différentes parties, séparées par un point
 *  Le résultat est retourné sous forme de tableau
 * Ex : $p = garages.index
 *      $p = explode('.', $p)
 *      --> $p[0] = garages
 *      --> $p[1] = index
 */
$p = explode('.', $p);


/**
 * @var string $controller La classe du contrôleur à appeler
 * @var string $action     La méthode du contrôleur à exécuter
 * @var Controller $ctrl   Une instance du contrôleur principal pour rediriger en cas d'erreur
 */
$ctrl = new Core\Controller\Controller;
$controller = '\App\Controller\\' . ucfirst($p[0]) . 'Controller'; // ucfirst : 1ère lettre en majuscule (UpperCase First)
$action = $p[1];
// Tableau reprenant les contrôleurs autorisés
$authorized_controllers = ["vehicules", "reparations", "utilisateurs", "erreurs"];
// Vérification de l'url rentrée.
if (in_array($p[0], $authorized_controllers)) {
// Création d'une nouvelle instance du contrôleur voulu
    $controller = new $controller();
} else {
    // Erreur 404
    $ctrl->notFound();
}
// Si la méthode existe : 
// Exécution de la méthode spécifiée par le contrôleur instancié. 
// Ex : \App\Controller\VehiculesController->index();

if (method_exists($controller, $action)) {
    $controller->$action();
} else {
    // Sinon, erreur 404
    $ctrl->notFound();
}