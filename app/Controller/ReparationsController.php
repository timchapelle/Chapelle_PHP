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

            if ($vU && $_POST["mode"] !== "angular") { // version PHP
                $this->index("Réparation mise à jour avec succès");
            } else if ($vU) {
                echo "succes"; // version Angular
            }
        } else {
            $this->edit($_POST["originalVehiculeFK"], $errors);
        }
    }

    /**
     * Suppression d'une réparation 
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
     * Affichage d'une réparation
     */
    public function show() {

        $id = $this->validateInt($_GET["id"]);
        if ($id > 0) {
            $reparation = $this->Reparations->find($id);
        }
        if (isset($r) && $r) {
            $this->app->setTitle($r->intervention);
            $this->render('reparations/show', ["reparation" => $reparation]);
        } else {
            $this->notFound();
        }
    }

}
