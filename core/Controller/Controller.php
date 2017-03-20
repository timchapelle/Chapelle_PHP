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
