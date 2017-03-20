<ol class="breadcrumb">
    <li><a href="index.php"><i class="fa fa-home fa-fw"></i></a></li>
    <li class="active">Véhicules (version AngularJS)</li>
</ol>

<div ng-controller="VehiculesController">
    <div ng-show="loading" class="center-block text-center">
        <garage-loader></garage-loader> <!-- Directive crée dans app.js -->
    </div>
    <div ng-show="!loading" ng-cloak>
        <div class="alert alert-success" ng-show="showSuccessMessage">
            {{successMessage}}</div>
        <div ng-if="vehicules.length == 0">Pas encore de véhicules</div>
        <div class="row well well-vehicules" ng-show="vehicules.length > 0">
            <div class="col-sm-12 col-md-6">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-search fa-fw"></i></span>
                    <input class="form-control" type="text" ng-model="searchVehicules" 
                           placeholder="Rechercher (type, marque, modèle, ...)">
                </div>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-filter fa-fw"></i></span>
                    <select class="form-control" id="orderType" name="orderType" 
                            ng-model="searchVehicules">
                        <option value="">Tout type</option>
                        <option ng-repeat="option in options" ng-value="option.type">
                            {{option.type}}&nbsp;({{option.nb}})</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-12 col-md-6">
                <div class="form-group order-group">
                    <i class="fa fa-sort fa-fw"></i>
                    <input type="radio" class="radio radio-inline" ng-model="orderVehicules" 
                           name="orderVehicules" value="marque" id="orderMarque">
                    <label for="orderMarque">Marque</label>
                    <input type="radio" class="radio radio-inline" ng-model="orderVehicules" 
                           name="orderVehicules" value="modele" id="orderModele">
                    <label for="orderModele">Modèle</label>
                    <input type="radio" class="radio radio-inline" ng-model="orderVehicules" 
                           name="orderVehicules" value="plaque" id="orderPlaque">
                    <label for="orderPlaque">Plaque</label>
                    <input type="radio" class="radio radio-inline" ng-model="orderVehicules" 
                           name="orderVehicules" value="type" id="sortType">
                    <label for="sortType">Type</label>
                </div>
                <div class="form-group order-group">
                    <i class="fa fa-fw" ng-class="descIcon ? 'fa-sort-alpha-desc' : 'fa-sort-alpha-asc'"></i>
                    <input type="checkbox" ng-model="reverse" name="reverse" id="reverse" ng-click="descIcon = !descIcon"> 
                    <label for="reverse">Inverser</label>
                </div>
            </div>
        </div>
        <div class="spacer"></div>
        <button class='btn btn-default' id='addVehiculeBtn' ng-if="logged"
                data-toggle='modal' data-target='#modalAddVehicule'> 
            <i class='fa fa-plus fa-fw'></i> Ajouter un véhicule
        </button>
        <div class="spacer"></div>
        <div class='col-xs-12 col-sm-6 col-md-6 col-lg-4' 
             ng-repeat="vehicule in vehicules| toArray:false | orderBy: orderVehicules:reverse | filter: searchVehicules">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ vehicule.marque + ' ' + vehicule.modele}}
                        <div class="dropdown pull-right" ng-show="logged">
                            <button class="btn btn-xs btn-default dropdown-toggle" 
                                    type="button" data-toggle="dropdown">
                                <i class="fa fa-cog fa-fw"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="#" data-toggle="modal" 
                                       data-target="#modalAddReparation{{vehicule.id}}">
                                        <i class="fa fa-wrench fa-fw"></i> 
                                        Ajouter une réparation </a></li>
                                <li><a href="#" data-toggle='modal' 
                                       data-target='#modalEditVehicule{{vehicule.id}}'>
                                        <i class="fa fa-edit fa-fw"></i> 
                                        Modifier</a></li>
                                <li><a href="#" ng-click="remove(vehicule)">
                                        <i class="fa fa-trash-o fa-fw"></i> 
                                        Supprimer</a></li>
                            </ul>
                        </div>
                        <span class='label label-info label-vehicule pull-right'>{{ vehicule.type}}</span>
                    </h3>
                </div>
                <div class="panel-body">
                    <ul id="tabs_{{vehicule.id}}" class="nav nav-tabs" data-tabs="tabs">
                        <li role="presentation" class="active">
                            <a data-target="#infos{{vehicule.id}}" data-toggle="tab">
                                Infos</a>
                        </li>
                        <li><a data-target="#reparations{{vehicule.id}}" data-toggle="tab">
                                Réparations ({{vehicule.reparations.length|| 0}})</a>
                        </li>
                    </ul>

                    <div id="my-tab-content-{{vehicule.id}}" class="tab-content">
                        <!--INFOS-->
                        <div class="tab-pane active" id="infos{{vehicule.id}}">
                            <ul class="list-group">
                                <li class="list-group-item">
                                    N° de chassis : {{ vehicule.numero_chassis}}</li>
                                <li class="list-group-item">
                                    N° de plaque : {{ vehicule.plaque}}</li>
                            </ul>
                        </div>
                        <!--REPARATIONS-->
                        <div class="tab-pane" id="reparations{{vehicule.id}}">
                            <!--Pas encore de réparations-->
                            <div ng-hide="vehicule.reparations.length > 0">
                                <p class="text-center" style="padding-top:10px">
                                    Pas encore de réparations pour ce véhicule</p>

                                <button class="btn btn-primary center-block"  ng-show="logged"
                                        data-toggle="modal" data-target="#modalAddReparation{{vehicule.id}}">
                                    <i class="fa fa-wrench fa-fw"></i> Ajouter une réparation
                                </button>
                            </div> 
                            <!--1 ou plusieurs réparations-->   
                            <div ng-if="vehicule.reparations.length > 0">
                                <ul class="list-group">
                                    <li class="list-group-item" 
                                        ng-repeat="reparation in vehicule.reparations">

                                        <a href="#" data-toggle="modal"
                                           data-target="#modalShowReparation{{reparation.id}}">
                                            {{ reparation.intervention | limitTo: 30}}
                                            {{ reparation.intervention.length > 30 ? '...' : ''}}
                                        </a>
                                        <span class="pull-right badge">
                                            {{ reparation.date | date: 'dd-MM-yyyy' }}
                                        </span>
                                        <div ng-include="'../app/views/reparations/modals/modalShowReparation.html'"></div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div ng-include="'../app/views/vehicules/modals/modalEditVehicule.html'"></div>
            <div ng-include="'../app/views/vehicules/modals/modalAddReparation.html'"
        </div>
    </div>
    <!-- Modal ajout d'un véhicule -->
</div>
</div>
<div ng-include="'../app/views/vehicules/modals/modalAddVehicule.html'"></div>
</div>