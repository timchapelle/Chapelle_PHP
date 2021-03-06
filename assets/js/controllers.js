/* 
 * Contrôleurs Angular JS
 */

'use strict';

angular.module('garage')
        /**
         * @ngdoc controllers
         * @name GarageCtrl
         * @memberof garage
         * @requires $scope
         * @requires $rootScope
         * @requires $cookies
         * @requires $http
         * @description 
         *      Contrôleur principal de l'application
         * @scope
         */
        .controller('GarageCtrl', ['$scope', '$rootScope', '$cookies', '$http',
            function ($scope, $rootScope, $cookies, $http) {
                /**
                 * @memberOf GarageCtrl
                 * @type {boolean}
                 * @desc Récupération du statut de connexion via le back-end PHP 
                 * (méthode isLogged() de la classe UtilisateursController)
                 */
                $rootScope.logged = $http.get('index.php?p=utilisateurs.isLogged')
                        .then(function (response) {
                            console.log('reponse isLogged');
                            console.log(response.data);
                            $rootScope.logged = response.data != "" ? true : false;
                        });
                /**
                 * @memberOf GarageCtrl
                 * @type {string}
                 * @desc Récupération du cookie pour l'affichage de l'alerte sur les cookies
                 */
                $scope.cookie = $cookies.get('alert');
                /**
                 * @memberof GarageCtrl
                 * @desc Définition d'un cookie pour masquer l'alerte sur les cookies
                 * @returns {undefined}
                 */
                $scope.setAlertCookie = function () {
                    /**
                     * Date d'expiration du cookie
                     */
                    var expireDate = new Date(); // aujourd'hui
                    expireDate.setDate(expireDate.getDate() + 365); // + 365 jours
                    // Mise en place du cookie
                    $cookies.put('alert', 'true', {expires: expireDate});
                };
            }])
        /**
         * @scope
         * @class garage.LoginCtrl
         * @ngdoc controllers
         * @name LoginCtrl
         * @memberof garage
         * @param {object} $scope
         * @param {object} $rootScope
         * @param {object} $cookies
         * @param {object} $http
         * @desc 
         *      Contrôleur de connexion.
         */
        .controller('LoginCtrl', ['$scope', '$rootScope', '$cookies', '$http',
            function ($scope, $rootScope, $cookies, $http) {
                /**
                 * @memberof LoginCtrl
                 * @var {string} loginCookie Le cookie de connexion, récupéré
                 * sur l'ordinateur du client
                 */
                var loginCookie = $cookies.get('garageLogin');
                /**
                 * @memberof LoginCtrl
                 * @property remember 
                 * @type boolean
                 * @desc Choix de l'utilisateur (se souvenir de moi)
                 */
                $scope.remember = false;

                if (loginCookie) {

                    $scope.remember = true;
                    $scope.login = $cookies.get('garageLogin');
                    /**
                     * Requête ajax : récupération du password de l'utilisateur
                     * afin d'assigner cette valeur au champ de formulaire
                     * correspondant au mot de passe 
                     */
                    $http({
                        method: 'POST',
                        url: 'index.php?p=utilisateurs.getPassword',
                        data: $.param({
                            login: $scope.login
                        }),
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                    }).then(function (response) {
                        console.log('Réponse mot de passe', response);
                        $scope.mdp = response.data;
                    });

                }
                /**
                 * Méthode de validation du login.
                 * @memberof LoginCtrl
                 * @methodof LoginCtrl
                 * @return {void}
                 */
                $scope.validerLogin = function () {
                    $http({
                        method: 'POST',
                        url: 'index.php?p=utilisateurs.validateLogin',
                        data: $.param({
                            login: $scope.login,
                            mdp: $scope.mdp,
                            remember: $scope.remember
                        }),
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                    }).then(function (response) {
                        $scope.message = response.data.message;
                        if (response.data.status) {
                            // Maintien du statut de connexion dans le scope principal
                            // afin de le garder accessible à travers toute l'application
                            $rootScope.logged = true;
                            $scope.success = true;
                            // Redirection vers la page d'accueil
                            window.location.replace("index.php")
                        }
                    }, function (error) {
                        $scope.message = error.status;
                    });
                };
            }])
        /**
         * @memberOf garage
         * @ngdoc controllers
         * @class garage.VehiculesController
         * @name VehiculesController
         * @requires $scope
         * @requires VehiculesService
         * @requires ReparationsService
         * @desc
         *      Contrôleur principal des véhicules
         * @scope
         */
        .controller('VehiculesController', ['$scope', '$rootScope', 'VehiculesService', 'ReparationsService',
            function ($scope, $rootScope, VehiculesService, ReparationsService) {


                /**
                 * @memberof VehiculesController
                 * @type {Vehicule}
                 * @desc Objet servant à accueillir le vehicule à éditer
                 */
                $scope.vehiculeToEdit = {};
                /**
                 * @memberof VehiculesController
                 * @type {boolean}
                 * @desc Afficher ou non le message de succès
                 */
                $scope.showSuccessMessage = false;
                /**
                 * @memberof VehiculesController
                 * @type {string}
                 * @desc Le message de succès
                 */
                $scope.successMessage = "";
                $scope.orderVehicules = 'marque';
                $scope.reverse = false;
                /**
                 * @memberof VehiculesController
                 * @type {Array}
                 * @desc Table contenant tous les véhicules
                 */
                $scope.vehicules = VehiculesService.getVehicules()
                        .then(function (response) {
                            $scope.vehicules = response;
                        });
                /**
                 * @memberof VehiculesController
                 * @type {array}
                 * @desc Récupération des options (types de véhicules)
                 */
                $scope.options = VehiculesService.getOptions()
                        .then(function (response) {
                            $scope.options = response;
                        });
                /**
                 * @memberof VehiculesController
                 * @property userVehiculeForm 
                 * @type {formData}
                 * @desc Objet représentant le nouveau véhicule introduit par l'utilisateur
                 */
                $scope.userVehiculeForm = {
                    marque: "", plaque: "", numero_chassis: "", modele: "", type: "", id: ""
                };
                /**
                 * @ngdoc method
                 * @memberof VehiculesController
                 * @desc Inverser l'ordre de tri
                 * @return {undefined}
                 */
                $scope.switchOrder = function () {
                    if ($scope.sortOrder === "desc") {
                        $scope.orderVehicules = '-' + $scope.orderVehicules;
                    }
                };

                /**
                 * @ngdoc method
                 * @memberof VehiculesController
                 * @desc Modification d'un véhicule
                 * @param {Vehicule} vehicule Le véhicule à éditer
                 * @return {undefined}
                 */
                $scope.edit = function (vehicule) {
                    $scope.vehiculeToEdit = vehicule;
                };
                /**
                 * @ngdoc method
                 * @memberof VehiculesController
                 * @desc Suppression d'un véhicule
                 * @param {vehicule} vehicule Le véhicule à supprimer
                 */
                $scope.remove = function (vehicule) {
                    console.log(vehicule.id);

                    if (confirm('*** Voulez-vous vraiment supprimer ce véhicule ? ***')) {

                        VehiculesService.deleteVehicule(vehicule.id);

                        $scope.vehicules.splice($scope.vehicules.indexOf(vehicule), 1);

                        $scope.showSuccessMessage = true;
                        $scope.successMessage = "Véhicule " + vehicule.marque
                                + " " + vehicule.modele + " supprimé avec succès !";
                    }
                };
                /**
                 * @ngdoc events
                 * @name #reparationDeleted
                 * @memberof garage
                 * @eventOf VehiculesController
                 * @eventType on
                 * @desc Exemple d'utilisation d'événement : on émet un évenement dans un contrôleur
                 * différent (ici EditReparationController, à l'aide de la méthode
                 * $emit()), et on exécute une action dans un autre contrôleur (celui-ci), en
                 * interceptant l'évènement à l'aide de la méthode $on();
                 */
                $scope.$on('reparationDeleted', function (event, params) {

                    var index = params[0];
                    var vehiculeId = params[1];
                    var reparationId = params[2];

                    console.log('index de la rep : ', index);
                    console.log('index à supprimer : ', vehiculeId);
                    console.log('vehicules : ', $scope.vehicules);
                    console.log('vehicule contenant la rep : ', $scope.vehicules[vehiculeId]);
                    console.log(angular.element('#modalShowReparation' + reparationId));

                    $scope.vehicules[vehiculeId].reparations.splice(index, 1);
                    $scope.showSuccessMessage = true;
                    $scope.successMessage = "Réparation #" + reparationId + " supprimée avec succès !";

                });

            }])
        /**
         * @ngdoc controllers
         * @class garage.AddVehiculeController
         * @memberof garage
         * @name AddVehiculeController
         * @requires $scope
         * @requires VehiculesService
         * @desc 
         *      Contrôleur pour la gestion de l'ajout d'un véhicule
         * @scope
         */
        .controller('AddVehiculeController', ['$scope', 'VehiculesService',
            function ($scope, VehiculesService) {
                /**
                 * @memberof AddVehiculeController
                 * @property {regex} plaquePattern 
                 * @desc Expression régulière pour vérifier le format de la plaque
                 */
                $scope.plaquePattern = /^[01]-[a-zA-Z]{3}-[0-9]{3}$/;
                /**
                 * @memberof AddVehiculeController
                 * @property {regex} plaquePattern1 
                 * @desc Expression régulière pour vérifier
                 * la première partie de la plaque
                 */
                $scope.plaquePattern1 = /^[a-zA-Z]{3}$/;
                $scope.plaquePattern2 = /^[0-9]{3}$/;
                /**
                 * @ngdoc method
                 * @memberof AddVehiculeController
                 * @desc
                 *      Méthode de validation du nouveau véhicule, exécutée au submit du formulaire
                 */
                $scope.validateVehicule = function () {

                    if ($scope.vehiculeForm.$dirty) {
                        // Concaténation des parties de numéro de chassis
                        $scope.userVehiculeForm.numero_chassis = $scope.userVehiculeForm.numero_chassis1
                                + '-' + $scope.userVehiculeForm.numero_chassis2
                                + '-' + $scope.userVehiculeForm.numero_chassis3
                                + '-' + $scope.userVehiculeForm.numero_chassis4;

                        // Plaque 
                        $scope.userVehiculeForm.plaque = $scope.userVehiculeForm.plaque1
                                + '-' + $scope.userVehiculeForm.plaque2.toUpperCase()
                                + '-' + $scope.userVehiculeForm.plaque3;

                        // Si le type est autre, on récupère la valeur du type entré par l'utilisateur
                        if ($scope.userVehiculeForm.type === "autre") {
                            $scope.userVehiculeForm.type = $scope.userVehiculeForm.autreType;
                        }
                        // Ajout du véhicule dans la db via le service
                        VehiculesService.addVehicule($scope.userVehiculeForm);
                        // Ajout du véhicule dans le scope 
                        $scope.$parent.vehicules.push($scope.userVehiculeForm);
                        // Remize à zéro du formulaire
                        $scope.resetForm();
                        // Masquage du modal
                        angular.element('#modalAddVehicule').modal('hide');
                        // Affichage du message de succès
                        $scope.$parent.$parent.showSuccessMessage = true;
                        $scope.$parent.$parent.successMessage = "Véhicule ajouté avec succès";
                    }
                };
                /**
                 * @ngdoc method
                 * @memberof EditVehiculeController
                 * @desc Méthode de remise à zéro du formulaire
                 * @example <button ng-click="resetForm()"></button>
                 */
                $scope.resetForm = function () {
                    // Remise à zéro du formulaire
                    $scope.vehiculeForm.$setPristine();
                    // Suppression des classes succès/erreur
                    angular.element('div.form-group').removeClass('has-success has-error');
                    // Remise à zéro de l'objet contenant le véhicule entré par l'utilisateur
                    $scope.userVehiculeForm = {marque: "", plaque: "", chassis: "", modele: "", type: ""};

                };
            }])
        /**
         * @ngdoc controllers
         * @memberof garage
         * @name EditVehiculeController
         * @requires $scope
         * @requires VehiculesService
         * @desc Contrôleur de mise à jour d'un véhicule
         * Injection de 2 dépendances : $scope et le service des Vehicules
         */
        .controller('EditVehiculeController', ['$scope', 'VehiculesService',
            function ($scope, VehiculesService) {

                /**
                 * Création des regex pour la plaque
                 */
                $scope.plaquePattern2 = /^[a-zA-Z]{3}$/;
                $scope.plaquePattern3 = /^[0-9]{3}$/;

                /**
                 * Remplissage des numéros manquants par des '*' en cas de données 
                 * tronquées dans la db
                 */
                $scope.vehicule.numero_chassis1 = ($scope.vehicule.numero_chassis + "*****").slice(0, 5);
                $scope.vehicule.numero_chassis2 = ($scope.vehicule.numero_chassis + "*****").slice(6, 11);
                $scope.vehicule.numero_chassis3 = ($scope.vehicule.numero_chassis + "*****").slice(12, 17);
                $scope.vehicule.numero_chassis4 = ($scope.vehicule.numero_chassis + "******").slice(18, 24);

                /**
                 * @ngdoc method
                 * @memberof EditVehiculeController
                 * @desc Méthode de mise à jour d'un véhicule
                 */
                $scope.updateVehicule = function () {

                    var vehiculeToEdit = {
                        id: $scope.vehicule.id,
                        marque: $scope.vehicule.marque,
                        modele: $scope.vehicule.modele,
                        plaque: $scope.vehicule.plaque1 + '-' + $scope.vehicule.plaque2 + '-' + $scope.vehicule.plaque3,
                        numero_chassis: $scope.vehicule.numero_chassis1 + '-' + $scope.vehicule.numero_chassis2 + '-' + $scope.vehicule.numero_chassis3 + '-' + $scope.vehicule.numero_chassis4,
                        type: $scope.vehicule.type
                    };

                    VehiculesService.updateVehicule(vehiculeToEdit);
                    $scope.vehicule.plaque = $scope.vehicule.plaque1 + '-' + $scope.vehicule.plaque2 + '-' + $scope.vehicule.plaque3;
                    $scope.vehicule.numero_chassis = $scope.vehicule.numero_chassis1 + '-' + $scope.vehicule.numero_chassis2 + '-' + $scope.vehicule.numero_chassis3 + '-' + $scope.vehicule.numero_chassis4;
                    angular.element('#modalEditVehicule' + $scope.vehicule.id).modal('hide');

                    $scope.$parent.$parent.$parent.showSuccessMessage = true;
                    $scope.$parent.$parent.$parent.successMessage = "Véhicule "
                            + $scope.vehicule.marque + " " + $scope.vehicule.modele + " mis à jour avec succès";
                };
                /**
                 * @ngdoc method
                 * @memberof EditVehiculeController
                 * @desc Création d'un véhicule de backup en vue de l'annulation de la modification
                 */
                $scope.backupVehicule = {
                    id: $scope.vehicule.id,
                    marque: $scope.vehicule.marque,
                    modele: $scope.vehicule.modele,
                    plaque: $scope.vehicule.plaque,
                    numero_chassis: $scope.vehicule.numero_chassis1 + '-' + $scope.vehicule.numero_chassis2 + '-' + $scope.vehicule.numero_chassis3 + '-' + $scope.vehicule.numero_chassis4,
                    type: $scope.vehicule.type
                };
                /**
                 * @ngdoc method
                 * @memberof EditVehiculeController
                 * @desc
                 *      Annulation de la modification
                 */
                $scope.cancelEdit = function () {

                    console.log('edit annulé');
                    console.log('vehicule de backup', $scope.backupVehicule);
                    console.log('vehicule modifié', $scope.vehicule);

                    $scope.vehicule.marque = $scope.backupVehicule.marque;
                    $scope.vehicule.modele = $scope.backupVehicule.modele;
                    $scope.vehicule.plaque = $scope.backupVehicule.plaque;
                    $scope.vehicule.numero_chassis = $scope.backupVehicule.numero_chassis;
                    $scope.vehicule.type = $scope.backupVehicule.type;
                };

            }])
        /**
         * @ngdoc controllers
         * @memberof garage
         * @name ReparationsController
         * @requires $scope
         * @requires ReparationsService
         * @desc Contrôleur principal des réparations
         * Injection de 2 dépendances : $scope et le service de Reparations
         */
        .controller('ReparationsController', ['$scope', 'ReparationsService',
            function ($scope, ReparationsService) {
                /**
                 * @memberof ReparationsController
                 * @var {Reparation} newReparation Objet reprenant la nouvelle réparation entrée par l'utilisateur
                 */
                $scope.newReparation = {intervention: "", description: "", date: "", vehicule_FK: ""};

                /**
                 * @ngdoc method
                 * @memberof ReparationsController
                 * @desc 
                 *      Méthode d'ajout d'une réparation
                 */
                $scope.addReparation = function () {

                    var date = new Date($scope.newReparation.date).toISOString();
                    var annee = date.slice(0, 4);
                    var mois = date.slice(5, 7);
                    var jour = parseInt(date.slice(8, 10)) + 1;
                    var jour = ("0" + jour).slice(-2);
                    var dateGoodFormat = annee + '-' + mois + '-' + jour;

                    $scope.newReparation.vehicule_FK = $scope.vehicule.id;
                    $scope.newReparation.date = dateGoodFormat;

                    ReparationsService.addReparation($scope.newReparation);
                    // Création d'un tableau de réparations si non existant (nouveau véhicule)
                    if (!$scope.vehicule.reparations) {
                        $scope.vehicule.reparations = [];
                    }
                    // Sinon, erreur ici : impossible de pusher si les reps ne sont pas définies
                    $scope.vehicule.reparations.push($scope.newReparation);
                    angular.element('#modalAddReparation' + $scope.vehicule.id).modal('hide');
                    // Remise à zéro du formulaire
                    $scope.resetForm();
                    // Message de succès
                    $scope.$parent.$parent.$parent.showSuccessMessage = true;
                    $scope.$parent.$parent.$parent.successMessage = "Réparation ajoutée avec succès !";
                };
                /**
                 * @ngdoc method
                 * @memberof ReparationsController
                 * @desc
                 *      Méthode de remise à zéro du formulaires
                 */
                $scope.resetForm = function () {

                    var formId = "addReparationForm" + $scope.vehicule.id;

                    angular.element('#' + formId + ' div.form-group').removeClass('has-success has-error');

                    $scope.newReparation = {intervention: "", description: "", date: "", vehicule_FK: ""};

                    $scope[formId].$setPristine();

                    console.log('Formulaire #' + formId + " remis à zéro");
                };

            }])
        /**
         * @ngdoc controllers
         * @memberof garage
         * @name EditReparationController
         * @requires $scope
         * @requires ReparationsService
         * @desc 
         *      Contrôleur pour la modification d'une réparation
         * @return {undefined}
         */
        .controller('EditReparationController', ['$scope', 'ReparationsService', function ($scope, Reparations) {
                /**
                 * @memberof EditReparationController
                 * @var {regex} datePattern Regex d'une date
                 */
                $scope.datePattern = /^[0-9]{4}-[0-9]{2}\-[0-9]{2}$/;
                /**
                 * Création d'un objet de backup
                 * @var {reparation} backup Backup de la réparation
                 */
                $scope.backup = {id: 0, intervention: "", description: "", date: ""};
                $scope.backup.id = $scope.reparation.id;
                $scope.backup.intervention = $scope.reparation.intervention;
                $scope.backup.description = $scope.reparation.description;
                $scope.backup.date = $scope.reparation.date;
                $scope.backup.vehicule_FK = $scope.reparation.vehicule_FK;

                /**
                 * @ngdoc method
                 * @memberof EditReparationController
                 * @desc Annuler la modification
                 */
                $scope.cancelEdit = function () {
                    $scope.editMode = false;
                    $scope.reparation.id = $scope.backup.id;
                    $scope.reparation.intervention = $scope.backup.intervention;
                    $scope.reparation.description = $scope.backup.description;
                    $scope.reparation.date = $scope.backup.date;
                    $scope.reparation.vehicule_FK = $scope.backup.vehicule_FK;
                };
                /**
                 * @ngdoc method
                 * @memberof EditReparationController
                 * @description     
                 *      Sauvegarder la modification
                 */
                $scope.updateReparation = function () {
                    $scope.reparation.mode = "angular";
                    Reparations.update($scope.reparation)
                            .then(function () {
                                $scope.editMode = false;
                                $scope.showUpdateMessage = true;
                            }, function (error) {
                                $scope.error = error.statusText;
                                $scope.showErrorMessage = true;
                            })
                            ;
                };
                /**
                 * @ngdoc method
                 * @memberof EditReparationController
                 * @desc 
                 *      Méthode de suppression d'une réparation
                 */
                $scope.removeReparation = function () {

                    /**
                     * @memberof EditReparationController
                     * @var {int}index  L'index du véhicule, qu'on récupère à l'aide de
                     * indexOf() plutôt que $index (fourni par Angular), à cause
                     * des tris.
                     */
                    var index = $scope.vehicule.reparations.indexOf($scope.reparation);
                    /**
                     * @memberof EditReparationController
                     * @var {int} vehiculeId L'id du véhicule auquel appartient
                     * la réparation à supprimer
                     */
                    var vehiculeId = $scope.vehicules.indexOf($scope.vehicule);

                    console.log(index);
                    console.log(vehiculeId);

                    /**
                     * Demande de confirmation à l'utilisateur
                     */
                    if (confirm('Etes-vous sûr de vouloir supprimer cette réparation ?')) {
                        /**
                         * Appel du service ReparationsService, et de sa méthode delete(),
                         * en passant en paramètre l'id de la réparation à supprimer
                         */
                        Reparations.delete($scope.reparation.id)
                                .then(function () {
                                    // Fermeture du modal (quelques bugs entre bootstrap et angular)
                                    angular.element('#modalShowReparation30').modal('hide');
                                    angular.element('body').removeClass('modal-open');
                                    angular.element('.modal-backdrop').remove();
                                    // Transmission au scope parent de l'info comme quoi le véhicule a été supprimé
                                    $scope.$emit('reparationDeleted', [index, vehiculeId, $scope.reparation.id]);
                                    console.log('réparation supprimée');
                                    // Affichage du message de suppression
                                    $scope.showDeleteMessage = true;
                                }, function (error) {
                                    /**
                                     * En cas d'erreur, récupération du message
                                     * + affichage de celui-ci
                                     */
                                    $scope.error = error.statusText;
                                    $scope.showError = true;
                                    console.log(error);
                                });
                    }

                };

                /**
                 * @ngdoc method
                 * @memberof EditReparationController
                 * @desc
                 *      Masquer toutes les alertes
                 * @return {undefined}
                 */
                $scope.cleanAlerts = function () {
                    $scope.showSuccessMessage = false;
                    $scope.showErrorMessage = false;
                };


            }])
        /**
         * 
         * @ngdoc controllers
         * @memberof garage
         * @name SVNavController
         * @requires $scope
         * @requires VehiculesService
         * @desc Contrôleur pour la recherche de véhicules dans la sidebar
         * Injection de 2 dépendances : $scope et le service des Vehicules
         */
        .controller('SVNavController', ['$scope', 'VehiculesService', function ($scope, Vehicules) {
                /**
                 * @memberof SVNavController
                 * @var {string} searchTerm Le terme à rechercher
                 */
                $scope.searchTerm = '';
                /**
                 * @memberof SVNavController
                 * @var {bool} hideSearchResult Afficher/Cacher résultats
                 */
                $scope.hideSearchResult = false;
                /**
                 * @memberof SVNavController
                 * @var {Array} vehicules L'ensemble des véhicules
                 */
                $scope.vehicules = Vehicules.getVehicules()
                        .then(function (response) {
                            $scope.vehicules = response;
                        });
                /**
                 * @ngdoc method
                 * @memberof SVNavController
                 * @desc
                 *  Quand on clique sur le véhicule, met la plaque dans le champ de recherche
                 * @param {event} e Event
                 * @param {Vehicule} vehicule Le véhicule à rechercher
                 */
                $scope.setSearchTerm = function (e, vehicule) {
                    $scope.searchTerm = vehicule.plaque;
                    $scope.hideSearchResult = true;
                };
            }]);


