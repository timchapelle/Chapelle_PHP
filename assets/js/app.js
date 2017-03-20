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

