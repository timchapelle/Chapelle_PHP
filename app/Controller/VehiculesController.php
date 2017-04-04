<?php

namespace App\Controller;

use App\Entites\VehiculesEntite;

/**
 * Contrôleur pour la gestion des véhicules
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
        if ($uV) { // Réussite de l'update
            echo "Véhicule avec id " . $_POST["id"] . " mis à jour";
        } else {
            echo "Echec"; // Echec de l'update
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
        } else {
            return $types;
        }
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
