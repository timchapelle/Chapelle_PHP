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
