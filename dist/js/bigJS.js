/* 
 * Initialisation de l'app Angular JS
 */
'use strict';
/**
 * Module principal de l'application.
 * Injection de 2 dépendances : ngCookies (pour la gestion des cookies) et
 * angular-toArrayFilter, pour une gestion plus facile des fonctions de tri
 * @module garage
 * @author Tim C. <tim@tchapelle.be> 
 */
angular.module('garage', ['ngCookies', 'angular-toArrayFilter'])
        /**
         * Directive pour passer au champ suivant lorsqu'on a rentré le nombre
         * de caractère correspondant à la longueur maximale définie grâce
         * à l'attribut maxlength.
         * @ngdoc directives
         * @name moveNext
         * @memberof garage
         * @desc Passer au champ de n° de chassis suivant
         * @restrict A 
         * @example <input type="number" name="numero_chassis1" id="numero_chassis1" move-next>
         */
        .directive("moveNext", function () {
            return {
                restrict: "A",
                link: function ($scope, element) {
                    element.on("input", function (e) {
                        if (element.val().length == element.attr("maxlength")) {
                            console.log('Longueur maximale atteinte');
                            /**
                             * @memberof moveNext
                             * @var {int} id Correspond au dernier chiffre de 
                             * l'id de l'input
                             */
                            var id = element.attr('id').slice(-1);
                            /**
                             * @memberof moveNext
                             * @var {int} vehiculeId L'id du véhicule correspondant
                             */
                            var vehiculeId = element.attr('data-vehiculeId');
                            
                            console.log(id, vehiculeId);
                            /**
                             * Concaténation pour récupérer l'id unique du champ
                             * de formulaire suivant
                             */
                            id = '#vehicule_' + parseInt(vehiculeId, 10) + '_numero_chassis' + (parseInt(id, 10) + 1);
                            console.log(id);
                            /**
                             * @memberof moveNext
                             * @var {DOMelement} nextElement Le champ suivant
                             */
                            var nextElement = angular.element(id);
                            console.log(nextElement);
                            if (nextElement.length) {
                                nextElement[0].focus();
                            } else {
                                console.log('Dernier champ');
                            }
                        } else {
                            console.log('Longueur maximale pas encore atteinte');
                        }
                    });
                }
            };
        })
        /**
         * @ngdoc directives
         * @memberof garage
         * @name garageLoader
         * @restrict E
         * @example <garageLoader></garageLoader>
         * @desc 
         *      Affichage d'un message de chargement lors de requêtes asynchrones
         */
        .directive("garageLoader", function () {
            return {
                restrict: "E",
                replace: true,
                template: "<h4><i class='fa fa-wrench faa-wrench animated'></i>   <i class='fa fa-car faa-bounce animated'></i><br>Chargement en cours...</h4>"
            };
        })
        /**
         * Configuration pour intercepter les requêtes HTTP
         */
        .config(['$httpProvider', function ($httpProvider) {
                $httpProvider.interceptors.push('LoadingInterceptor');
            }]);
;


$(function () {

    // Initialisation des tooltip Twitter Bootstrap
    $('[data-toggle="tooltip"]').tooltip();

    // Toggle du bouton menu
    $(".menu-toggle-btn").click(function (e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });

    // Initialisation de l'arbre des dossiers
    $('#file_tree').fileTree({
        root: '/Chapelle_PHP/',
        script: '../php/jqueryFileTree.php',
        expandSpeed: 200,
        collapseSpeed: 200,
        multiFolder: false
    }, function (file) {

        // 'file' est un string contenant le chemin absolu vers le fichier,
        // je vais donc lui retirer 'Chapelle_PHP' pour améliorer la lisibilité.
        var regExp = /Chapelle_PHP\//g;
        var result = file.replace(regExp, "");

        alert('Veuillez vous rendre dans la documentation ou sur GitHub (voir footer) pour avoir accès au code de \n ' + result);
        // Mise en évidence du bouton de documentation
        $('#docLink').css({
            'color': 'red',
            'font-size': '20px',
            'transition': '0.3s'
        }).addClass('faa-horizontal animated');

        $('#github-icon').addClass('faa-shake animated');
        $('#github-icon').attr('style', 'color:red');
    });

    $('#github-icon').hover(function () {
        $(this).removeClass('faa-shake animated');
        $(this).attr('style', '')
    })

    // Animation sur le bouton documentation
    $('#docLink').on({
        'mouseover': function () {
            $(this).removeClass('faa-horizontal animated');
        },
        'click': function () {
            $(this).css({
                'color': '#999',
                'font-size': 'inherit',
                'transition': '0s'
            });
        }
    });

    // Animation de la bannière de la homepage
    $('#homeBanner').on({
        'mouseenter': function () {
            $('#wrenchIcon, #carIcon').addClass('animated');
        },
        'mouseleave': function () {
            $('#wrenchIcon, #carIcon').removeClass('animated');
        }
    });

    // Affichage/masquage de la description des réparations (fiche véhicule)
    $('.show-reparation-desc').click(function (e) {
        e.preventDefault();
        e.stopPropagation();
        var id = $(this).attr('id').split('-')[2];
        $('#description-' + id).toggleClass('hide');
        $('#icon-' + id).toggleClass('fa-eye fa-eye-slash');
        var title = $(this).attr('data-original-title');
        var newTitle = (title === "Afficher la description") ? "Masquer la description" : "Afficher la description";
        $(this).attr('data-original-title', newTitle);
    });

    // Affichage/masquage des infos (fiche véhicule)
    var toggleInfo = true;
    $('.show-infos').click(function () {

        if (toggleInfo) {
            $('#infos-ul').slideToggle('fast',
                    function () {
                        $('#infos').fadeOut(10);
                    });
        } else {
            $('#infos').fadeIn(10,
                    function () {
                        $('#infos-ul').slideToggle('slow');
                    });
        }
        toggleInfo = !toggleInfo;
        $(this).find('i').toggleClass('fa-eye-slash fa-eye');
    });

    // Affichage/masquage des infos (fiche véhicule)
    var toggleRep = true;
    $('.show-reparations').click(function () {
        // Vérification de l'élément à 'slider' : soit l'ul des 
        // réparations, soit l'alerte comme quoi il n'y en a pas

        var rep = $('#reparations-ul')[0];

        if (rep) {
            if (toggleRep) {
                $('#reparations-ul').slideToggle('slow',
                        function () {
                            $('#reparations').fadeOut(10);
                        });
            } else {
                $('#reparations').fadeIn(10,
                        function () {
                            $('#reparations-ul').slideToggle('slow');
                        });
            }
        } else {
            if (toggleRep) {
                $('#alert').slideToggle('slow',
                        function () {
                            $('#reparations').fadeOut(10);
                        });
            } else {
                $('#reparations').fadeIn(10,
                        function () {
                            $('#alert').slideToggle('slow');
                        });
            }
        }
        // Inversion
        toggleRep = !toggleRep;
        // Inversion du sens de l'icône (bas <-> haut)
        $(this).find('i').toggleClass('fa-eye-slash fa-eye');
    });

    /**
     * Initialisation du datepicker (pour entrer facilement les dates sur Firefox)
     */
    var date = $('#date').val();
    $('.datepicker').datepicker({
        dateFormat: "yy-mm-dd",
        defaultDate: date
    });

    $('.datepicker').datepicker("option", $.datepicker.regional["fr"]);
});

$('#show-list').click(function (e) {
    e.preventDefault();
    var vp = $('#vehicules-panels');
    if (!vp.hasClass('hide')) {
        vp.fadeOut();
    }
    $(this).removeClass('btn-default').addClass('btn-primary');
    $('#show-panels').removeClass('btn-primary').addClass('btn-default');
    $('#vehicules-list').fadeIn();
});

$('#show-panels').click(function (e) {
    e.preventDefault();
    var vl = $('#vehicules-list');
    vl.fadeOut();
    $('#show-list').removeClass('btn-primary').addClass('btn-default');
    $(this).removeClass('btn-default').addClass('btn-primary');
    $('#vehicules-panels').fadeIn();
});


$('#csv-btn').click(function (e) {
    e.preventDefault();
    $('#alert-csv').toggleClass('hide');

});

$('#csv-btn-2').click(function () {
    $('#csv-input').click();
});

var files;

$('#csv-input').change(function (e) {
    files = e.target.files;
    var file = e.target.files[0];
    console.log(file);
    var response = file.name + " (" + file.size / 1000 + "kb) : " + (file.type === "text/csv" ? "Fichier valide" : "Veuillez choisir un fichier .csv");
    $('#response').fadeIn();
    $('#response-txt').text(response);

    if (file.type !== "text/csv") {
        $('#submit').prop('disabled', 'true');
        $('#response').removeClass('alert-info').removeClass('alert-success').addClass('alert-danger');
    } else {
        $('#response').removeClass('alert-danger').removeClass('alert-info').addClass('alert-success');
        $('#submit').prop('disabled', '').removeClass('btn-success').addClass('btn-default');
    }
});
$('#csv-form').submit(function (e) {

    e.stopPropagation();
    e.preventDefault();

    var fd = new FormData();

    $.each(files, function (key, value) {
        fd.append(key, value);
    });
    console.log('fd');
    console.log(fd);
    $.post({
        url: 'index.php?p=vehicules.importCSV',
        data: fd,
        cache: false,
        dataType: 'json',
        processData: false, // Don't process the files
        contentType: false, // Set content type to false as jQuery will tell the server its a query string request
        success: function (data)
        {
            console.log('réussite de la requete ajax');
            console.log(data);
            var totalErr = data.errorRows != "" ? parseInt(data.errorRows, 10) : 0;
            var totalIns = data.insertedRows != "" ? parseInt(data.insertedRows, 10) - 1 : 0;
            var total = totalErr + totalIns;
            var rowNum = data.errorRowNumbers != "" ? " lignes (" + data.errorRowNumbers + ")" : "";
            var responseTxt = "Total des lignes : " + total
                    + "<br> Lignes insérées : " + totalIns
                    + "<br> Erreurs : " + totalErr + rowNum;
            $('#response-txt').html(responseTxt);
            var link = $('<a></a>');
            link.attr('href', 'index.php?p=vehicules.indexPHP')
                    .attr('class', 'btn btn-sm btn-primary')
                    .append('<i class="fa fa-refresh fa-fw"></i> Rafraichir');
            $('#submit-csv').hide().after(link);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            // Handle errors here
            console.log(jqXHR, textStatus, errorThrown);
            // STOP LOADING SPINNER
        }
    });

});


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



/* French initialisation for the jQuery UI date picker plugin. */
/* Written by Keith Wood (kbwood{at}iinet.com.au),
			  Stéphane Nahmani (sholby@sholby.net),
			  Stéphane Raimbault <stephane.raimbault@gmail.com> 
   Edited by Tim Chapelle <tim@tchapelle.be>
 */
( function( factory ) {
	if ( typeof define === "function" && define.amd ) {

		// AMD. Register as an anonymous module.
		define( [ "../widgets/datepicker" ], factory );
	} else {

		// Browser globals
		factory( jQuery.datepicker );
	}
}( function( datepicker ) {

datepicker.regional.fr = {
	closeText: "Fermer",
	prevText: "Précédent",
	nextText: "Suivant",
	currentText: "Aujourd'hui",
	monthNames: [ "Janvier", "Février", "Mars", "Avril", "Mai", "Juin",
		"Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre" ],
	monthNamesShort: [ "Janv.", "Févr.", "Mars", "Avr.", "Mai", "Juin",
		"Juil.", "Août", "Sept.", "Oct.", "Nov.", "Déc." ],
	dayNames: [ "Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi" ],
	dayNamesShort: [ "Dim.", "Lun.", "Mar.", "Mer.", "Jeu.", "Ven.", "Sam." ],
	dayNamesMin: [ "D","L","M","M","J","V","S" ],
	weekHeader: "Sem.",
	dateFormat: "yy-mm-dd",
	firstDay: 1,
	isRTL: false,
	showMonthAfterYear: false,
	yearSuffix: "" };
datepicker.setDefaults( datepicker.regional.fr );

return datepicker.regional.fr;

} ) );
/** 
 * SERVICES 
 * Les services vont permettre aux contrôleurs de récupérer des données depuis
 * le serveur, via des requêtes http vers les pages php du back-end.
 */

'use strict';

angular.module('garage')
        /**
         * @ngdoc services
         * @memberof garage
         * @name VehiculesService
         * @requires $http
         * @desc 
         *      Service de gestion des véhicules
         */
        .service('VehiculesService', ['$http', function ($http) {
                /**
                 * @ngdoc method
                 * @name getVehicules
                 * @memberof garage.service:VehiculesService
                 * @description 
                 * Récupération des véhicules dans la db via le contrôleur PHP
                 * @returns {Array} Liste de véhicules
                 */
                this.getVehicules = function () {
                    return $http.get('index.php?p=vehicules.getVehicules')
                            .then(function (response) {
                                console.log(response.data);
                                return response.data;
                            }, function (error) {
                                console.log(error);
                                return error;
                            });

                };
                /**
                 * @ngdoc method
                 * @memberOf VehiculesService
                 * @name addVehicule
                 * @param {Vehicule} form Les données du nouveau véhicule entrées par l'utilisateur
                 * @desc Création d'un véhicule dans la db via le contrôleur PHP
                 */
                this.addVehicule = function (form) {
                    $http({
                        method: 'POST',
                        url: 'index.php?p=vehicules.addVehicule',
                        data: $.param({
                            marque: form.marque,
                            plaque: form.plaque,
                            type: form.type,
                            numero_chassis1: form.numero_chassis1,
                            numero_chassis2: form.numero_chassis2,
                            numero_chassis3: form.numero_chassis3,
                            numero_chassis4: form.numero_chassis4,
                            numero_chassis5: form.numero_chassis5,
                            plaque1: form.plaque1,
                            plaque2: form.plaque2,
                            plaque3: form.plaque3,
                            modele: form.modele,
                            autreType: form.autreType,
                            mode: "angular"
                        }),
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                    }).then(function (response) {
                        console.log('succes VehiculesService.addVehicule | Réponse :');
                        console.log(response);
                        /**
                         * Le contrôleur renvoie l'id du dernier enregistrement créé,
                         * ce qui va permettre de créer le nouvel élément dans le DOM
                         * pour afficher le véhicule.
                         */
                        form.id = response.data;
                    }, function (error) {
                        console.log('erreur VehiculesService.addVehicule');
                        console.log(error);
                        form.id = 0;
                    });
                };
                /**
                 * @ngdoc method
                 * @memberOf VehiculesService
                 * @name updateVehicule
                 * @desc Mise à jour d'un véhicule
                 * @param {Vehicule} form Les données du véhicule à mettre à jour entrées par l'utilisateur
                 */
                this.updateVehicule = function (form) {
                    console.log(form);
                    $http({
                        method: 'POST',
                        url: 'index.php?p=vehicules.updateVehicule',
                        data: $.param({
                            marque: form.marque,
                            plaque: form.plaque,
                            type: form.type,
                            numero_chassis: form.numero_chassis,
                            modele: form.modele,
                            id: form.id
                        }),
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                    }).then(function (response) {
                        console.log('succes VehiculesService.updateVehicule | Réponse :');
                        console.log(response);
                    }, function (error) {
                        console.log('erreur VehiculesService.updateVehicule');
                        console.log(error);
                        form.id = 0;
                    });
                };
                /**
                 * @ngdoc method
                 * @memberof VehiculesService
                 * @name deleteVehicule
                 * @param {int} id Id du véhicule à supprimer 
                 * @desc Supression d'un véhicule
                 */
                this.deleteVehicule = function (id) {
                    $http({
                        method: 'POST',
                        url: 'index.php?p=vehicules.deleteVehicule',
                        data: $.param({
                            id: id
                        }),
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                    }).then(function (response) {
                        console.log('succes VehiculesService.deleteVehicule | Réponse :');
                        console.log(response);
                    }, function (error) {
                        console.log('erreur VehiculesService.deleteVehicule');
                        console.log(error);
                    });
                };
                /**
                 * @ngdoc method
                 * @memberof VehiculesService
                 * @name getOptions
                 * @desc Récupération des options à afficher dans le select
                 * pour le type de véhicule
                 * @return {Array}
                 */
                this.getOptions = function () {
                    return $http({
                        method: 'GET',
                        url: 'index.php?p=vehicules.getTypeOptions'
                    }).then(function (response) {
                        console.log(response);
                        return response.data;
                    });
                };
            }])
        /**
         * @ngdoc services
         * @memberof garage
         * @name ReparationsService
         * @requires $http
         * @desc 
         *      Service de gestion des réparations
         */
        .service('ReparationsService', ['$http', function ($http) {
                /**
                 * @ngdoc method
                 * @memberof ReparationsService
                 * @name addReparation
                 * @param {object} reparation
                 * @desc Ajout d'une réparation
                 */
                this.addReparation = function (reparation) {
                    $http({
                        method: 'POST',
                        url: 'index.php?p=reparations.addReparation',
                        data: $.param({
                            vehicule_FK: reparation.vehicule_FK,
                            intervention: reparation.intervention,
                            description: reparation.description,
                            date: reparation.date
                        }),
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                    }).then(function (response) {
                        console.log('succes ReparationsService.addReparation | Réponse :');
                        console.log(response);
                        reparation.id = response.data;
                    }, function (error) {
                        console.log('erreur ReparationsService.addReparation');
                        console.log(error);
                        reparation.id = 0;
                    });
                };
                /**
                 * @ngdoc method
                 * @memberof ReparationsService
                 * @name update
                 * @param {formData} form Informations envoyées par l'utilisateur
                 * @return {unresolved}
                 * @desc Mise à jour d'une réparation
                 */
                this.update = function (form) {

                    return $http({
                        method: 'POST',
                        url: 'index.php?p=reparations.update',
                        data: $.param({
                            intervention: form.intervention,
                            description: form.description,
                            date: form.date,
                            id: form.id,
                            vehicule_FK: form.vehicule_FK,
                            mode: form.mode
                        }),
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                    });
                };
                /**
                 * @ngdoc method
                 * @memberof ReparationsService
                 * @name delete
                 * @param {int} id Id de la réparation à supprimer
                 * @return {unresolved}
                 * @desc Suppression d'une réparation
                 */
                this.delete = function (id) {
                    return $http({
                        method: 'GET',
                        url: 'index.php?p=reparations.delete&id=' + id,
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
                    });
                };
            }])
        /**
         * @ngdoc services
         * @memberof garage
         * @desc 
         * Service d'interception des requêtes XHR (HTTP)
         * @requires $q
         * @requires $rootScope
         * @requires $log
         * @return {servicesL#200.servicesAnonym$15}
         */
        .service('LoadingInterceptor', ['$q', '$rootScope', '$log',
            function ($q, $rootScope, $log) {
                'use strict';

                var xhrCreations = 0;
                var xhrResolutions = 0;

                function isLoading() {
                    return xhrResolutions < xhrCreations;
                }

                function updateStatus() {
                    $rootScope.loading = isLoading();
                }

                return {
                    request: function (config) {
                        xhrCreations++;
                        updateStatus();
                        return config;
                    },
                    requestError: function (rejection) {
                        xhrResolutions++;
                        updateStatus();
                        $log.error('Request error:', rejection);
                        return $q.reject(rejection);
                    },
                    response: function (response) {
                        xhrResolutions++;
                        updateStatus();
                        return response;
                    },
                    responseError: function (rejection) {
                        xhrResolutions++;
                        updateStatus();
                        $log.error('Response error:', rejection);
                        return $q.reject(rejection);
                    }
                };
            }]);
;

