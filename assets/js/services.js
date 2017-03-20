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

