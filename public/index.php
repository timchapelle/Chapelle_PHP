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