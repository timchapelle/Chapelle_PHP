<div id="modalShowReparation{{reparation.id}}" class="modal fade" role="dialog" ng-controller="EditReparationController">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                                <button class="close" data-dismiss="modal">&times;</button>

                <h4 class="modal-title">Réparation #{{reparation.id}}</h4>
            </div>
            <div class="alert alert-success" ng-show="showUpdateMessage">
                <i class="fa fa-info-circle fa-fw"></i>  Réparation mise à jour avec succès !
            </div>
            <div class="alert alert-warning" ng-show="showErrorMessage">
                <i class="fa fa-info-circle fa-fw"></i>  Erreur lors de la mise à jour de la réparation : {{error}} !
            </div>
            <form name="editReparationForm" ng-submit="updateReparation()" novalidate>

                <div class="modal-body">
                    <dl ng-hide="editMode">
                        <dt>Intervention</dt>
                        <dd>{{ reparation.intervention}}</dd>
                        <dt>Description</dt>
                        <dd>{{ reparation.description}}</dd>
                        <dt>Date</dt>
                        <dd>{{ reparation.date | date: 'dd-MM-yyyy' }}</dd>
                    </dl>
                    <div  ng-show="editMode">
                        <div class="form-group" ng-class="editReparationForm.intervention.$valid ? 'has-success' : 'has-error'">
                            <label for="Intervention" class="control-label">Intervention</label>
                            <input type="text" class="form-control" ng-model="reparation.intervention"
                                   name="intervention" id="intervention" required>
                        </div> 
                        <div class="form-group" ng-class="editReparationForm.description.$valid ? 'has-success' : 'has-error'">
                            <label for="description" class="control-label">Description</label>
                            <textarea class="form-control" name="description" id="description"
                                      ng-model="reparation.description" required></textarea>
                        </div>
                        <div class="form-group" ng-class="editReparationForm.date.$valid ? 'has-success' : 'has-error'">
                            <label for="date" class="control-label">Date</label>
                            <input type="text" class="form-control" name="date" id="date" datetime="yyyy-MM-dd"
                                   ng-model="reparation.date" ng-pattern="datePattern" required>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-warning"
                            ng-hide="editMode" ng-click="cleanAlerts()">
                        <i class="fa fa-close fa-fw"></i> Fermer
                    </button>

                    <button type="button" class="btn btn-warning"
                            ng-show="editMode" ng-click="cancelEdit()">
                        <i class="fa fa-close fa-fw"></i> Annuler
                    </button>

                    <button type="button" class="btn btn-primary"  
                            ng-show="logged && !editMode" ng-click="editMode = !editMode">
                        <i class="fa fa-edit fa-fw"></i> Modifier
                    </button>

                    <button type="button" href="#" class="btn btn-danger" ng-click="removeReparation()"
                            ng-show="logged && !editMode" data-dismiss="modal">
                        <i class="fa fa-trash-o fa-fw"></i> Supprimer
                    </button>

                    <button class="btn btn-success" type="submit" 
                            ng-show="editMode" ng-disabled="editReparationForm.$invalid">
                        <i class="fa fa-check fa-fw"></i> Sauvegarder
                    </button>
                </div>
                <input type="hidden" name="mode" value="angular">
            </form>
        </div>
    </div>
</div>

<div id="modalAddReparation{{vehicule.id}}" class="modal fade" role="dialog" ng-controller="ReparationsController">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Ajouter une réparation sur le véhicule {{vehicule.marque + ' ' + vehicule.modele}}</h4>
            </div>
            <form name="addReparationForm{{vehicule.id}}" id="addReparationForm{{vehicule.id}}" 
                  novalidate ng-submit="addReparation()">
                <div class="modal-body">
                    <div class="form-group" ng-class="addReparationForm{{vehicule.id}}.intervention.$dirty ? (addReparationForm{{vehicule.id}}.intervention.$valid ? 'has-success':'has-error') : ''">
                        <label for="intervention" class="control-label">Intervention</label>
                        <input type="text" class="form-control" 
                               name="intervention" ng-model="newReparation.intervention" required>
                        <span class="help-block" ng-show="addReparationForm{{vehicule.id}}.intervention.$dirty
                                    && addReparationForm{{vehicule.id}}.intervention.$invalid">
                            Le nom de l'intervention est obligatoire</span>
                    </div>
                    <div class="form-group" ng-class="addReparationForm{{vehicule.id}}.description.$dirty ? (addReparationForm{{vehicule.id}}.description.$valid ? 'has-success':'has-error') : ''">
                        <label for="description" class="control-label">Description</label>
                        <textarea class="form-control" name="description" 
                                  ng-model="newReparation.description" required>
                        </textarea>
                        <span class="help-block" ng-show="addReparationForm{{vehicule.id}}.description.$dirty
                                    && addReparationForm{{vehicule.id}}.description.$invalid">
                            La description est obligatoire</span>
                    </div>
                    <div class="form-group" ng-class="addReparationForm{{vehicule.id}}.date.$dirty ? (addReparationForm{{vehicule.id}}.date.$valid ? 'has-success':'has-error') : ''">
                        <label for="date" class="control-label">Date de l'intervention</label>
                        <input type="date" class="form-control" name="date" datetime="yyyy-MM-dd" ng-model="newReparation.date" required>
                        <span class="help-block" ng-show="addReparationForm{{vehicule.id}}.date.$dirty
                                    && addReparationForm{{vehicule.id}}.date.$invalid">
                            Veuillez entrer une date correcte (2017-02-01 || 01/01/2017)</span>
                    </div>
                </div>
                <input type="hidden" name="vehicule_FK" ng-model="newReparation.vehicule_FK" value="vehicule.id">
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" 
                            ng-click="resetForm(vehicule.id)">
                        <i class="fa fa-close fa-fw"></i> Annuler</button>
                    <button type="submit" class="btn btn-success"  
                           ng-disabled="addReparationForm{{vehicule.id}}.$invalid">
                        <i class="fa fa-check fa-fw"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<div id="modalAddVehicule" class="modal fade" role="dialog" ng-controller="AddVehiculeController">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Ajouter un véhicule</h4>
            </div>
            <form name="vehiculeForm" id="vehiculeForm" novalidate ng-submit="validateVehicule()">
                <div class="modal-body">
                    <div class="form-group" ng-class="vehiculeForm.marque.$dirty ? (vehiculeForm.marque.$valid ? 'has-success' : 'has-error') : ''">
                        <label for="marque" class="control-label">Marque</label>
                        <input type="text" class="form-control" id="marque" 
                               name="marque" ng-model="userVehiculeForm.marque" required>
                        <span class="help-block"
                              ng-show="vehiculeForm.marque.$dirty && vehiculeForm.marque.$invalid">
                            Veuillez renseigner la marque du véhicule
                        </span>
                    </div>
                    <div class="form-group" ng-class="vehiculeForm.modele.$dirty ? (vehiculeForm.modele.$valid ? 'has-success' : 'has-error') : ''">
                        <label for="modele" class="control-label">Modèle</label>
                        <input type="text" class="form-control" id="modele" 
                               name="modele" ng-model="userVehiculeForm.modele" required>
                    </div>
                    <div class="form-group numero_chassis" ng-class="(vehiculeForm.plaque2.$dirty && vehiculeForm.plaque3.$dirty) ? ((vehiculeForm.plaque2.$valid && vehiculeForm.plaque3.$valid) ? 'has-success' : 'has-error') : ''">
                        <label for="plaque1" class="control-label">Plaque</label>
                        <select class='form-control select-plaque' name='plaque1' ng-model='userVehiculeForm.plaque1'>
                            <option value='0'>0</option>
                            <option value='1'>1</option>
                        </select>
                        <input type="text" class="form-control text-uppercase" id="plaque2" placeholder="ABC"
                               name="plaque2" ng-model="userVehiculeForm.plaque2" 
                               ng-pattern="plaquePattern1" required>
                        <input type="text" class="form-control text-uppercase" id="plaque3" placeholder="123"
                               name="plaque3" ng-model="userVehiculeForm.plaque3" 
                               ng-pattern="plaquePattern2" required>
                        <span class="help-block" ng-show="(vehiculeForm.plaque2.$dirty && vehiculeForm.plaque3.dirty)&& (vehiculeForm.plaque2.$invalid || vehiculeForm.plaque3.$invalid)">
                            Veuillez respecter le format "[0-1]-[A-Z]{3}-[0-9]{3}"</span>
                    </div>
                    <div class="form-group numero_chassis">
                        <label class="control-label">N° de chassis</label><br>
                        <div ng-class="vehiculeForm.numero_chassis1.$dirty ? (vehiculeForm.numero_chassis1.$valid ? 'has-success' : 'has-error') : ''">
                            <input type="text" class="form-inline text-center" id="vehicule_0_numero_chassis1" placeholder="12345" data-vehiculeId="0"
                                   name="numero_chassis1" ng-model="userVehiculeForm.numero_chassis1" ng-minlength="5" ng-maxlength="5" maxlength="5" move-next required >
                        </div>
                        <strong>-</strong>
                        <div ng-class="vehiculeForm.numero_chassis2.$dirty ? (vehiculeForm.numero_chassis2.$valid ? 'has-success' : 'has-error') : ''">
                            <input type="text" class="form-inline text-center" id="vehicule_0_numero_chassis2" placeholder="12345" data-vehiculeId="0"
                                   name="numero_chassis2" ng-model="userVehiculeForm.numero_chassis2" ng-minlength="5" ng-maxlength="5" maxlength="5" move-next required>
                        </div>
                        <strong>-</strong>
                        <div ng-class="vehiculeForm.numero_chassis3.$dirty ? (vehiculeForm.numero_chassis3.$valid ? 'has-success' : 'has-error') : ''">
                            <input type="text" class="form-inline text-center" id="vehicule_0_numero_chassis3" placeholder="12345" data-vehiculeId="0"
                                   name="numero_chassis3" ng-model="userVehiculeForm.numero_chassis3" ng-minlength="5" ng-maxlength="5" maxlength="5" move-next required>
                        </div>
                        <strong>-</strong>
                        <div ng-class="vehiculeForm.numero_chassis4.$dirty ? (vehiculeForm.numero_chassis4.$valid ? 'has-success' : 'has-error') : ''">
                            <input type="text" class="form-inline text-center" id="vehicule_0_numero_chassis4" placeholder="123456" data-vehiculeId="0"
                                   name="numero_chassis4" ng-model="userVehiculeForm.numero_chassis4" ng-minlength="6" ng-maxlength="6" maxlength="6" required>
                        </div>
                        <div class="has-error">
                            <span class="help-block" 
                                  ng-show="(vehiculeForm.numero_chassis1.$dirty &&
                                                          vehiculeForm.numero_chassis2.$dirty &&
                                                          vehiculeForm.numero_chassis3.$dirty &&
                                                          vehiculeForm.numero_chassis4.$dirty)
                                                          &&
                                                          (vehiculeForm.numero_chassis1.$invalid ||
                                                                  vehiculeForm.numero_chassis2.$invalid ||
                                                                  vehiculeForm.numero_chassis3.$invalid ||
                                                                  vehiculeForm.numero_chassis4.$invalid )">

                                N° de chassis incorrect (modèle : 12345-12345-12345-123456)
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="type" class="control-label">Type</label>
                        <select class="form-control" id="type" name="type" 
                                ng-model="userVehiculeForm.type" required
                                >
                            <option value="">Veuillez choisir une option</option>
                            <option ng-repeat="option in options" value="{{option.type}}">{{option.type}}</option>
                            <option value="autre">Autre ...</option>
                        </select>
                        <div class="input-group" ng-show="userVehiculeForm.type === 'autre'">
                            <span class="input-group-addon"><i class="fa fa-question-circle-o fa-fw"></i></span>
                            <input class="form-control" type="text" name="autreType" id="autreType" 
                                   placeholder="Veuillez préciser votre choix"
                                   ng-model="userVehiculeForm.autreType">
                            </div>
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"
                            ng-click="resetForm()">Annuler</button>
                    <input type="submit" class="btn btn-success"  value="Ajouter" 
                           ng-disabled="vehiculeForm.$invalid || 
                                       (userVehiculeForm.type === 'autre' && userVehiculeForm.autreType === '') ||
                                       (userVehiculeForm.type === 'autre' && !userVehiculeForm.autreType)" >
                </div>
                <input type="hidden" name="mode" value="angular">
            </form>
        </div>

    </div>
</div>
<div id="modalEditVehicule{{vehicule.id}}" class="modal fade" role="dialog" ng-controller="EditVehiculeController">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Modifier {{ vehicule.marque + ' ' + vehicule.modele}}</h4>
            </div>
            <form name="editVehiculeForm" id="editVehiculeForm{{vehicule.id}}"
                     novalidate ng-submit="updateVehicule()">
                <div class="modal-body">
                    <div class="form-group" 
                         ng-class="editVehiculeForm.marque.$valid ? 'has-success' : 'has-error'">
                        <label for="marque">Marque</label>
                        <input type="text" class="form-control" id="marque" 
                               name="marque" ng-model="$parent.vehicule.marque" required>
                        <span class="help-block text-danger"
                              ng-show="editVehiculeForm.marque.$dirty && editVehiculeForm.marque.$invalid">
                            Veuillez introduire la marque du véhicule
                        </span>
                    </div>
                    <div class="form-group"
                         ng-class="editVehiculeForm.modele.$valid ?
                                                 'has-success' :
                                                 'has-error'">
                        <label for="modele">Modèle</label>
                        <input type="text" class="form-control" id="modele" 
                               name="modele" ng-model="vehicule.modele" required>
                        <span class="help-block"
                              ng-show="editVehiculeForm.modele.$dirty && editVehiculeForm.modele.$invalid">
                            Veuillez introduire la marque du véhicule
                        </span>
                    </div>
                    <div class="form-group plaque"
                         ng-class="(editVehiculeForm.plaque2.$invalid ||
                                                 editVehiculeForm.plaque3.$invalid) ?
                                                 'has-error' :
                                                 'has-success'">
                        <label for="plaque1">Plaque</label>
                        <select class="form-control select-plaque" name="plaque1" id="plaque1" ng-init="vehicule.plaque1 = vehicule.plaque.slice(0, 1)" ng-model="vehicule.plaque1">
                            <option ng-repeat="number in [0, 1]">{{number}}</option>
                        </select>
                        <b>-</b>
                        <input type="text" class="form-control text-uppercase" id="plaque2" ng-init="vehicule.plaque2 = vehicule.plaque.slice(2, 5)"
                               name="plaque2" ng-model="vehicule.plaque2" ng-pattern="plaquePattern2" required>
                        <b>-</b>
                        <input type="text" class="form-control" id="plaque3" ng-init="vehicule.plaque3 = vehicule.plaque.slice(6, 9)"
                               name="plaque3" ng-model="vehicule.plaque3" ng-pattern="plaquePattern3" required>
                        <span class="help-block"
                              ng-show="editVehiculeForm.plaque2.$invalid || editVehiculeForm.plaque3.$invalid">
                            Veuillez respecter le format suivant : 1-ABC-123
                        </span>
                    </div>
                    <div class="form-group numero_chassis">
                        <label for="numero_chassis">N° de chassis</label>
                        <div ng-class="editVehiculeForm.numero_chassis1.$valid ? 'has-success' : 'has-error'">
                            <input type="text" class="form-control" id="vehicule_{{vehicule.id}}_numero_chassis1" 
                                   name="numero_chassis1" ng-model="vehicule.numero_chassis1" data-vehiculeId="{{vehicule.id}}"
                                   maxlength="5" ng-minlength="5" ng-maxlength="5" move-next required>
                        </div>
                        <b>-</b>
                        <div ng-class="editVehiculeForm.numero_chassis2.$valid ? 'has-success' : 'has-error'">
                            <input type="text" class="form-control" id="vehicule_{{vehicule.id}}_numero_chassis2" 
                                   name="numero_chassis2" ng-model="vehicule.numero_chassis2"  data-vehiculeId="{{vehicule.id}}"
                                   maxlength="5" ng-minlength="5" ng-maxlength="5" move-next required>
                        </div>
                        <b>-</b>
                        <div ng-class="editVehiculeForm.numero_chassis3.$valid ? 'has-success' : 'has-error'">
                            <input type="text" class="form-control" id="vehicule_{{vehicule.id}}_numero_chassis3" 
                                   name="numero_chassis3" ng-model="vehicule.numero_chassis3" data-vehiculeId="{{vehicule.id}}"
                                   maxlength="5" ng-minlength="5" ng-maxlength="5" move-next required>
                        </div>
                        <b>-</b>
                        <div ng-class="editVehiculeForm.numero_chassis4.$valid ? 'has-success' : 'has-error'">
                            <input type="text" class="form-control" id="vehicule_{{vehicule.id}}_" 
                                   name="numero_chassis4" ng-model="vehicule.numero_chassis4" data-vehiculeId="{{vehicule.id}}"
                                   maxlength="6" ng-minlength="6" ng-maxlength="6" required>
                        </div>
                    </div>
                    <div class="form-group" ng-class="editVehiculeForm.type.$valid ?
                                            'has-success' : 'has-error'">
                        <label for="type">Type</label>
                        <select class="form-control" id="type" name="type" 
                                ng-model="vehicule.type">
                            <option value="">Veuillez choisir une option</option>
                            <option ng-repeat="option in options" value="{{option.type}}" ng-selected="'vehicule.type == option.type'">
                                {{option.type}}    
                            </option>
                        </select>
                    </div>
                </div>
                <input type="hidden" name="id" ng-model="vehicule.id">
                <input type="hidden" name="mode" value='angular'>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"
                            ng-click="cancelEdit()">
                        <i class="fa fa-close fa-fw"></i> Annuler</button>
                    <button type="submit" class="btn btn-success" ng-disabled="editVehiculeForm.$invalid">
                        <i class="fa fa-check fa-fw"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

<div class="alert alert-danger">
    <h4><i class="fa fa-lock"></i> Erreur 403</h4>
    Accès non autorisé
</div>

<img src="../assets/img/authorized.JPG" class="img-responsive center-block"
     alt="Oups">

<div class="alert alert-danger">
    <h4><i class="fa fa-question-circle"></i> Erreur 404</h4>
    La page demandée n'a pu être trouvée sur le serveur
</div>
<img src="../assets/img/404.jpg" class="img-responsive center-block"
     alt="Oups">

<?php
/**
 * Vue d'édition/ajout d'une réparation
 */
?>
<!-- Breadcrumbs -->

<ol class="breadcrumb">
    <li><a href="index.php"><i class="fa fa-home fa-fw"></i></a></li>
    <li><a href='index.php?p=vehicules.indexPHP'>Véhicules</a></li>
    <li>
        <?php if (!empty($reparation->vehicule)) : ?>
            <a href='index.php?p=vehicules.show&id=<?= $reparation->vehicule_FK ?>'>
                <?= $reparation->vehicule->marque . " " . $reparation->vehicule->modele ?></a>
        <?php else: ?>
            Véhicule inconnu
        <?php endif; ?>
    </li>
    <li class="<?= $reparation->id > 0 ? '' : 'active' ?>">
        <?=
        $reparation->id > 0 ?
                '<a href="index.php?p=reparations.show&id=' . $reparation->id . '">'
                . $reparation->intervention . '</a>' : "Ajouter une réparation"
        ?>
    </li>
    <?php if ($reparation->id > 0) : ?>
        <li class="active">Modifier</li>
    <?php endif; ?>
</ol>

<!-- Title -->

<h4><?= $title ?></h4>

<!-- Body -->

<div class="col-sm-6">
    <form class='form-vertical' id='editReparationForm' action='<?= $action ?>' method='POST'>
        <div class="form-group <?= isset($errors) ? (isset($errors['intervention']) ? 'has-error' : 'has-success') : '' ?>">
            <label for='intervention' class='control-label'>Intervention</label>
            <input class='form-control' type='text' id='intervention' name='intervention' 
                   value="<?= $reparation->intervention ?>">
            <span class="help-block">
                <?= isset($errors["intervention"]) ? $errors["intervention"] : "" ?>
            </span>
        </div>
        <div class="form-group <?= isset($errors) ? (isset($errors['description']) ?
                                'has-error' : 'has-success') : ''
                ?>">
            <label for='description' class='control-label'>Description</label>
            <textarea class='form-control'  id='description' name='description'><?= $reparation->description ?></textarea>
            <span class="help-block">
                <?= isset($errors["description"]) ? $errors["description"] : "" ?>
            </span>
        </div>
        <div class="form-group <?=
             isset($errors) ? (isset($errors['vehicule_FK']) ?
                             'has-error' : 'has-success') : ''
             ?>">
            <label for="vehicule_FK" class="control-label">Vehicule_FK</label>
            <select class="form-control" name="vehicule_FK" <?= isset($new) && $new ? "disabled" : "" ?>>
                <option value="<?= empty($reparation->vehicule) ? $reparation->vehicule_FK : '' ?>"></option>
                        <?php foreach ($vehicules as $v) : ?>
                    <option value="<?= $v->id ?>" 
                                <?= $v->id == $reparation->vehicule_FK ? 'selected = "selected"' : '' ?>>
                                <?=
                                $v->marque . " " . $v->modele .
                                " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;( " . $v->plaque . ")"
                                ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <span class="help-block">
                <?= isset($errors["vehicule_FK"]) ? $errors["vehicule_FK"] : "" ?>
            </span>
        </div>
        <div class="form-group <?= isset($errors) ? (isset($errors['date']) ? 'has-error' : 'has-success') : '' ?>">
            <label for="date" class="control-label">Date</label>
            <input class="form-control datepicker" type="text" name="date" id="dateRep" 
                   value="<?= $reparation->date ?>" placeholder="<?= $reparation->date ?>" >
            <span class="help-block">
                <?= isset($errors["date"]) ? $errors["date"] : "" ?>
            </span>
        </div>
        <div class="form-group">
            <?php if (!isset($new)) : ?>
            <a href="index.php?p=reparations.show&id=<?= $reparation->id ?>" 
               class="btn btn-default">
                <i class="fa fa-close fa-fw"></i> Annuler</a>
               <?php else: ?>
            <a href="index.php?p=vehicules.show&id=<?= $reparation->vehicule_FK ?>"
               class="btn btn-danger"> 
                <i class="fa fa-close fa-fw"></i> Annuler
            </a>
            <?php endif; ?>
            <button type="submit" class="btn btn-success">
                <i class="fa fa-save fa-fw"></i> Sauvegarder
            </button>
        </div>
        <input type="hidden" name="id" value="<?= $reparation->id ?>">
        <input type="hidden" name="mode" value="php">
        <input type="hidden" name="originalVehiculeFK"  
               value="<?= $reparation->vehicule_FK ?>">
    </form>
</div>
<ol class="breadcrumb">
    <li><a href="index.php"><i class="fa fa-home fa-fw"></i></a></li>
    <li class="active">Réparations</li>
</ol>

<?php if (isset($msg)) : ?>
    <div class="alert alert-info"><?= $msg ?></div>
<?php endif; ?>
    
<div class="table-responsive">
    <table class="table table-striped">
    <thead>
        <tr>
            <th class="<?= $sort == 'id' ? 'text-primary':''?>">
                <a href="index.php?p=reparations.index&sort=id&order=<?= $nextOrder ?>">
                    Id
                    <?= $sort == 'id' ? '<i class="fa fa-sort-numeric-' . $order . '"></i>' : '' ?>
                </a></th>
            <th class="<?= $sort == 'intervention' ? 'text-primary':''?>">
                <a href="index.php?p=reparations.index&sort=intervention&order=<?= $nextOrder ?>">
                    Intervention
                    <?= $sort == 'intervention' ? '<i class="fa fa-sort-alpha-' . $order . '"></i>' : '' ?>
                </a></th>
            <th class="<?= $sort == 'description' ? 'text-primary':''?>">
                <a href="index.php?p=reparations.index&sort=description&order=<?= $nextOrder ?>">
                    Description
                    <?= $sort == 'description' ? '<i class="fa fa-sort-alpha-' . $order . '"></i>' : '' ?>
                </a></th>
            <th class="<?= $sort == 'date' ? 'text-primary':''?>">
                <a href="index.php?p=reparations.index&sort=date&order=<?= $nextOrder ?>">
                    Date
                    <?= $sort == 'date' ? '<i class="fa fa-sort-numeric-' . $order . '"></i>' : '' ?>
                </a></th>
            <th class="<?= $sort == 'vehicule_FK' ? 'text-primary':''?>">
                <a href="index.php?p=reparations.index&sort=vehicule_FK&order=<?= $nextOrder ?>">
                    Véhicule_FK
                    <?= $sort == 'vehicule_FK' ? '<i class="fa fa-sort-numeric-' . $order . '"></i>' : '' ?>
                </a></th>
            <th>Nom</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($reparations as $reparation) : ?>
            <tr>
                <td><?= $reparation->id ?></td>
                <td><?= $reparation->intervention ?></td>
                <td><?= nl2br($reparation->description) ?></td>
                <td><?= $reparation->dateFormatee ?></td>
                <td class="text-center"><?= $reparation->vehicule_FK ?></td>
                <td  class="<?= is_object($reparation->vehicule) ? '':'danger'?>">
                <?=
                    (is_object($reparation->vehicule) ?
                            $reparation->vehicule->marque . " " . $reparation->vehicule->modele :
                            "-")
                    ?>
                </td>

                <td><div class="btn-group" role="group">
                        <a class="btn btn-sm btn-default" 
                           href="index.php?p=reparations.show&id=<?= $reparation->id ?>">
                            <i class="fa fa-eye fa-fw"></i></a>
                        <?php if (isset($_SESSION["login"])) : ?>
                            <a class="btn btn-sm btn-default" 
                               href="index.php?p=reparations.edit&id=<?= $reparation->id ?>">
                                <i class="fa fa-edit fa-fw"></i></a>
                            <a class="btn btn-sm btn-danger" 
                               href="index.php?p=reparations.delete&id=<?= $reparation->id ?>">
                                <i class="fa fa-trash-o fa-fw"></i></a>
                        </div>
                    <?php endif; ?>
                </td>

            </tr>
        <?php endforeach; ?>

    </tbody>
</table>
</div>
<div class="col-sm-offset-4">
    <ul class="pagination">
        <?php if ($page > 1): ?>
            <li>
                <a href="index.php?p=reparations.index&page=<?= $page - 1 ?>&sort=<?=$sort?>&order=<?=$order?>" 
                   aria-label="Précédent">
                    <span aria-hidden="true" class="fa fa-caret-left"></span>
                </a>
            <li>
            <?php endif; ?>
            <?php for ($i = 0; $i < $nbReparations / 10; $i++) : ?>
            <li class="<?= $page == $i + 1 ? 'active' : '' ?>">
                <a href="index.php?p=reparations.index&page=<?= $i + 1 ?>&sort=<?=$sort?>&order=<?=$order?>">
                    <?= $i + 1 ?>
                </a>
            </li>
        <?php endfor; ?>
        <?php if ($page < ($nbReparations / 10)) : ?>
            <li>
                <a href="index.php?p=reparations.index&page=<?= $page + 1 ?>&sort=<?=$sort?>&order=<?=$order?>" 
                   aria-label="Précédent">
                    <span aria-hidden="true" class="fa fa-caret-right"><!--&raquo;--></span>
                </a>
            </li>
        <?php endif; ?>
    </ul>
</div>
<?php
//Affichage d'une réparation unique
?>
<ol class="breadcrumb">
    <li><a href="index.php"><i class="fa fa-home fa-fw"></i></a></li>
    <li><a href="index.php?p=reparations.index">Réparations</a></li>
    <li class="active"><?= $reparation->intervention ?></li>
</ol>

<h4>Réparation # <?= $reparation->id ?></h4>
<div class="well">
    <dl>
        <dt>Intervention</dt>
        <dd><?= $reparation->intervention ?></dd>

        <dt>Description</dt>
        <dd><?= $reparation->description ?></dd>

        <dt>Date</dt>
        <dd><?= date('d/m/Y', strtotime($reparation->date)) ?></dd>

        <dt>Vehicule_FK</dt>
        <dd><a href="index.php?p=vehicules.show&id=<?= $reparation->vehicule_FK ?>">
                <?= $reparation->vehicule_FK ?></a></dd>
    </dl>
</div>
<?php if (isset($_SESSION["login"])) : ?>
    <a href="index.php?p=reparations.edit&id=<?= $reparation->id ?>"
       class="btn btn-primary">
        <i class="fa fa-edit fa-fw"></i> Modifier
    </a>
    <a href="#" class="btn btn-danger" data-toggle="modal" data-target="#modalRepDelete">
        <i class="fa fa-trash-o fa-fw"></i> Supprimer
    </a>
<?php endif; ?>
<div class="modal fade" id="modalRepDelete" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Confirmer la suppression</h4>
            </div>
            <div class="modal-body">
                Etes-vous sûr(e) de vouloir supprimer cette réparation ?
            </div>
            <div class="modal-footer">
                <a href="#" data-dismiss="modal" class="btn btn-default">Annuler </a>
                <a href="index.php?p=reparations.delete&id=<?= $reparation->id ?>"
                   class="btn btn-danger">
                    <i class="fa fa-trash-o fa-fw"></i> Oui !
                </a>
            </div>
        </div>
    </div>
</div>

<?php $app = App::getInstance(); ?>
<!DOCTYPE html>
<!--
ISFCE - 2017 - Projet de développement web - C. Lemaigre

Gestion d'un garage

CHAPELLE Timothée

-->
<html lang="fr" ng-app="garage"> <!-- Initialisation de l'application AngularJS -->
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="robots" content="noindex">
        <title><?= $app->title ?></title>
        <!-- Favicon -->
        <link rel="icon" type="img/ico" href="favicon.ico">
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
        <!--DatePicker-->
        <link href="http://code.jquery.com/ui/1.12.1/themes/dark-hive/jquery-ui.css" rel="stylesheet" type="text/css" >
        <!-- CSS concaténés et minifiés -->
         
        <!-- Twitter Bootstrap -->
        <link href="../assets/css/bootstrap_sandstone.css" rel="stylesheet" type="text/css"/>
        <link href="../assets/css/simple-sidebar.css" rel="stylesheet" type="text/css"/>
        <!-- Font-Awesome -->
        <link href="../bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
        <link href="../bower_components/font-awesome-animation/dist/font-awesome-animation.min.css" rel="stylesheet" type="text/css"/>
        <!--jQueryFileTree-->
        <link href="../bower_components/jqueryfiletree/dist/jQueryFileTree.min.css" rel="stylesheet" type="text/css"/>
 <!-- Chapelle -->
        <link href="../assets/css/chapelle.css" rel="stylesheet" type="text/css"/>

    </head>

    <body>
        <div id="wrapper">
            <?php include ROOT . '/app/Views/trame/sidebar.php' ?>
            <div id="page-content-wrapper" class="container" ng-controller="GarageCtrl">
                <a href="#menu-toggle" class="btn btn-default menu-toggle-btn hidden-xs"
                   ng-show="$parent.showHamburger"  ng-cloak
                   ng-click="$parent.showHamburger = !$parent.showHamburger">
                    <i class="fa fa-bars fa-fw"></i> Menu
                </a>
                <a href="#menu-toggle" class="btn btn-xs btn-default menu-toggle-btn visible-xs">
                    <i class="fa fa-bars fa-fw"></i> Menu
                </a>
                <?= $content ?>
            </div>
            <?php include ROOT . '/app/Views/trame/footer.php' ?>
        </div>
        <!-- SCRIPTS -->
        <!-- jQuery + TBS-->
        <script src="../bower_components/jquery/dist/jquery.min.js" type="text/javascript"></script>
        <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="../bower_components/jqueryfiletree/dist/jQueryFileTree.min.js" type="text/javascript"></script>
<!-- AngularJS -->
        <script src="../bower_components/angular/angular.min.js" type="text/javascript"></script>
        <script src="../bower_components/angular-toArrayFilter/toArrayFilter.js" type="text/javascript"></script>
        
        <!-- Angular Cookies -->
        <script src="../bower_components/angular-cookies/angular-cookies.js" type="text/javascript"></script>
         <!--jQuery UI DatePicker-->
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>   
        <script src="../assets/js/datepicker-fr.js"></script>
        <!-- Chapelle - Angular -->
        <script src="../assets/js/app.js" type="text/javascript"></script>
        <script src="../assets/js/controllers.js" type="text/javascript"></script>
        <script src="../assets/js/services.js" type="text/javascript"></script>

        <!-- Chapelle - jQuery -->
        <script type="text/javascript" src="../assets/js/chapelle_jquery.js"></script>
        <!-- Version concaténée, uglifiée, minifiée des 3 scripts AngularJS + script jQuery -->
        <!--<script src="../dist/js/bigJS.min.js"></script>-->

</html>

<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-sm-10">
                <span class="text-muted pull-left"> GARAGE </span>
                <span class="text-muted pull-right"> by T. CHAPELLE   
                    <a  href="https://github.com/timchapelle/Chapelle_PHP">
                        <i data-toggle="tooltip" title="Voir le projet sur Github" id="github-icon"
                           class="fa fa-github fa-2x"></i>
                    </a>
                </span>
            </div>
        </div>
    </div>
</footer>
<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">Garage</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="active"><a href="index.php"><i class="fa fa-home fa-fw"></i> Home<span class="sr-only">(current)</span></a></li>
                <li><a href="index.php?p=vehicules.index"><i class="fa fa-car fa-fw"></i> Véhicules</a></li>
                <li><a href="index.php?p=reparations.index"><i class="fa fa-cogs fa-fw"></i> Réparations</a></li>

            </ul>
            <ul class = "nav navbar-nav navbar-right">

                <?php if (!isset($_SESSION["login"])) : ?>
                    <li><a href = "index.php?p=utilisateurs.login"><i class = "fa fa-user fa-fw"></i> Login</a></li>
                <?php else : ?>
                    <li><a href="index.php?p=utilisateurs.logout"><i class="fa fa-lock fa-fw"></i> Déconnexion</a></li>
                    <?php endif; ?>
            </ul>


        </div>
    </div>
</nav>
<div id="sidebar-wrapper">
    <ul class="sidebar-nav">
        <li class="sidebar-brand">
            <a href="index.php">
                Garage
                <span class="close menu-toggle-btn" style="color:red" ng-click="showHamburger = !showHamburger" >&times;</span>
            </a>

        </li>
        <li ng-controller="SVNavController">
            <form name="searchVehiculeNav">
                <div class="input-group search-vehicule-nav">

                    <input type="text" class="form-control" name="search"
                           placeholder="Rechercher" ng-model="searchTerm">
                    <span class="input-group-btn">
                    </span>
                </div>
                <ul class="media-list search-list-group" style="background:transparent">
                    <li class="media search-list-group-item" 
                        ng-repeat="vehicule in vehicules| toArray:false | filter: searchTerm" 
                        ng-show="searchTerm.length >= 2" ng-cloak>
                        <a href="index.php?p=vehicules.show&id={{vehicule.id}}">
                            <div class="media-left">
                                <img class="media-object" src="https://images.vexels.com/media/users/3/128998/isolated/lists/15630dcae2578399bfabe65c4290ed8a-vintage-car-flat-icon.png" height="35" alt="...">
                            </div>
                            <div class="media-body">
                                <h6 class="media-heading">{{ (vehicule.marque + ' ' + vehicule.modele) | limitTo: 15}}</h6>
                                <p>{{ vehicule.plaque}}</p>
                            </div>
                        </a>

                    </li>
                    <li class="media search-list-group-item" ng-show="searchTerm !== '' && searchTerm.length < 2" ng-cloak>
                        <p>
                            2 caractères minimum
                        </p>
                    </li>
                </ul>
            </form>
        </li>
        <li>
            <a href="#" data-toggle="collapse" data-target="#vehiculeMenu">Véhicules
                <i class="fa fa-caret-down pull-right sidebar-caret"></i>
            </a>
        </li>
        <li id="vehiculeMenu" class="collapse">
            <a href="index.php?p=vehicules.index">
                <img src="../assets/img/angular_icon.png" style="height:30px" alt="Angular Logo">Version Angular JS</a>
            <a href="index.php?p=vehicules.indexPHP">
                <img src="../assets/img/PHP_icon.png" alt="PHP Logo">Version PHP</a>

        </li>
        <li>
            <a href="index.php?p=reparations.index">Réparations</a>
        </li>
        <li><a href="#" data-toggle="collapse" data-target="#docMenu" id="docLink">
                Documentation 
                <i class="fa fa-caret-down pull-right sidebar-caret"></i></a>
        </li>
        <li class="collapse" id="docMenu">
            <a href="../docs/php" target="_blank">PHP</a>
            <a href="../docs/js" target="_blank">Javascript</a>
        </li>

        <?php if (!isset($_SESSION["login"])) : ?>
            <li><a href = "index.php?p=utilisateurs.login"><i class = "fa fa-user fa-fw" 
                                                              style="margin-left:-20px;margin-right:20px"></i> 
                    Login</a></li>
        <?php else : ?>
            <li><a href="index.php?p=utilisateurs.logout"><i class="fa fa-lock fa-fw" 
                                                             style="margin-left:-20px;margin-right:20px"></i> 
                    Déconnexion (<?= $_SESSION["login"] ?>)</a></li>
        <?php endif; ?>
    </ul>
</div>
<?php /* Page d'accueil */ global $md; ?>
<div class="well" id="homeBanner">
    <h2 class="text-center text-uppercase"><span style="color:#f5f5f5">Garage</span> <i id="wrenchIcon" class="fa fa-wrench faa-wrench"></i> | <i id="carIcon" class="fa fa-car faa-bounce"></i> Chapelle</h2>
    <?php if (isset($_SESSION["login"])) : ?>
        <h4 class="text-center">Bienvenue, mécano <?= $_SESSION["login"] ?></h4>
    <?php endif; ?> 
</div>
<div class="container">
    <div class="row">
        <div class="col-sm-offset-2 col-sm-6">
            <div class="alert alert-warning alert-dismissible fade in" ng-if="!cookie" ng-cloak>
                <p>Ce site utilise des <abbr title fake-title="Données stockées sur l'ordinateur client">cookies</abbr> afin d'améliorer votre expérience.<br>
                    Cliquez sur l'icône correspondant à votre navigateur/système d'exploitation mobile afin
                    de découvrir comment désactiver les cookies.<br>
                    <a href="https://support.mozilla.org/t5/Cookies-and-cache/Activer-et-d%C3%A9sactiver-les-cookies-que-les-sites-Internet/ta-p/11860"
                       class="social-link" target="_blank">
                        <i class="fa fa-firefox fa-fw"></i>
                    </a>
                    <a href="http://support.google.com/chrome/bin/answer.py?hl=fr&hlrm=en&answer=95647" 
                       class="social-link" target="_blank">
                        <i class="fa fa-chrome fa-fw"></i>
                    </a>
                    <a href="http://windows.microsoft.com/fr-FR/windows-vista/Block-or-allow-cookies" 
                       class="social-link" target="_blank">
                        <i class="fa fa-internet-explorer fa-fw"></i>
                    </a>
                    <a href="http://help.opera.com/Windows/10.20/fr/cookies.html" 
                       class="social-link" target="_blank">
                        <i class="fa fa-opera fa-fw"></i>
                    </a>
                    <a href="https://support.apple.com/kb/ph17191?locale=fr_FR&viewlocale=fr_FR" 
                       class="social-link" target="_blank">
                        <i class="fa fa-safari fa-fw"></i>
                    </a>
                    <a href="http://www.wikihow.com/Disable-Cookies#Android_Devices" 
                       class="social-link" target="_blank">
                        <i class="fa fa-android fa-fw" target="_blank"></i>
                    </a>
                    <a href="https://support.apple.com/fr-fr/HT201265" 
                       class="social-link" target="_blank">
                        <i class="fa fa-apple fa-fw"></i>
                    </a>
                    <a href="https://support.microsoft.com/fr-fr/help/17442/windows-internet-explorer-delete-manage-cookies" 
                       class="social-link" target="_blank">
                        <i class="fa fa-windows fa-fw"></i>
                    </a>
                </p>

                <button type="button" class="btn btn-primary pull-right" style="margin-top: -30px;" data-dismiss="alert" aria-label="Fermer" ng-click="setAlertCookie()"
                        data-toggle="tooltip" title="En cliquant sur ce bouton, vous activerez un cookie qui empêchera l'affichage de cette alerte dans le futur">
                    <span aria-hidden="true">Ok !</span>
                </button>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a data-target="#structure" aria-controls="structure" role="tab" data-toggle="tab">Structure</a></li>
                <li role="presentation"><a data-target="#fonctionnalites" aria-controls="fonctionnalites" role="tab" data-toggle="tab">Fonctionnalités</a></li>
                <li role="presentation"><a data-target="#outils" aria-controls="outils" role="tab" data-toggle="tab">Outils</a></li>
                <li role="presentation"><a data-target="#stats" aria-controls="stats" role="tab" data-toggle="tab">Stats</a></li>
                <li role="presentation"><a data-target="#versions" aria-controls="messages" role="tab" data-toggle="tab">Versions</a></li>
            </ul>
            <div class="tab-content">
                <section id="structure" class="tab-pane active" role="tabpanel">
                    <div id="file_tree"></div>
                </section>
                <section id="fonctionnalites" class="tab-pane" role="tabpanel">
                    <?= $md->text($utilities) ?>
                </section>
                <section id="outils" class="tab-pane" role="tabpanel">
                    <?= $md->text($tools) ?>
                </section>
                <section id="versions" class="tab-pane" role="tabpanel">
                    <?= $md->text($versions) ?>
                </section>
                <section id="stats" class="tab-pane" role="tabpanel">
                    <div class="col-sm-9">
                        <ul class="list-group">
                            <li class="list-group-item">Scripts PHP 
                                <span class="label label-success pull-right">
                                    <?= $php = count(file('../dist/php/bigPHP.php')) . " lignes \n" ?>
                                </span>
                            </li>
                            <li class="list-group-item">Vues (PHP, HTML)
                                <span class="label label-success pull-right">
                                    <?= $views = count(file('../dist/php/bigViews.php')) . " lignes \n" ?>
                                </span>
                            </li>
                            <li class="list-group-item">Scripts JS :  
                                <span class="label label-warning pull-right">
                                    <?= $js = count(file('../dist/js/bigJS.js')) . " lignes \n" ?>
                                </span>
                            </li>
                            <li class="list-group-item">Feuilles de style CSS 
                                <span class="label label-danger pull-right">
                                    <?= $css = count(file('../dist/css/chapelle.css')) . " lignes \n" ?>
                                </span>
                            </li>
                            <li class="list-group-item list-group-item-info">Total : <?= $total = ($php + $views + $js + $css) . " lignes" ?></li>
                        </ul>
                        <div class="progress">
                            <div class="progress-bar progress-bar-success" role="progressbar" style="width:<?= (($php + $views) / $total) * 100 ?>%">
                                PHP
                            </div>
                            <div class="progress-bar progress-bar-warning" role="progressbar" style="width:<?= ($js / $total) * 100 ?>%">
                                JS
                            </div>
                            <div class="progress-bar progress-bar-danger" role="progressbar" style="width:<?= ($css / $total) * 100 ?>%">
                                CSS
                            </div>
                        </div>

                    </div   >
                </section>
            </div>
        </div>
        <div class="col-sm-4">
            <?= $md->text($structure) ?>
        </div>
    </div>
</div>


<div ng-show="loading" class="center-block text-center">
    <garage-loader></garage-loader> <!-- Directive crée dans app.js -->
</div>
<div ng-show="!loading" ng-cloak>
    <div class="col-sm-4 col-sm-offset-4" ng-controller="LoginCtrl">

        <div class="alert alert-info">
            Veuillez vous connecter pour accéder à la gestion du garage
        </div>

        <form id="loginForm" role="form" name="loginForm" 
              ng-submit="validerLogin()" novalidate autocomplete="off">

            <div class="form-group" ng-class="loginForm.login.$error.required &&
                                    loginForm.login.$dirty ? 'has-error' : 'has-success'">
                <label for="login">Login</label>
                <input class="form-control" type="text" id="login" name="login" ng-model="login" required>
                <span class="help-block" 
                      ng-show="loginForm.login.$dirty && loginForm.login.$invalid" ng-cloak>
                    Veuillez rentrer un login</span>
            </div>
            <span class="help-block alert" 
                  ng-class="success ? 'alert-success' : 'alert-danger'" ng-show="message" ng-cloak>
                {{ message}}
            </span>
            <div class="form-group" ng-class="loginForm.mdp.$error.required &&
                                    loginForm.mdp.$dirty ? 'has-error' : 'has-success'">
                <label for="mdp">Mot de passe</label>
                <input class="form-control" type="password" id="mdp" name="mdp" 
                       ng-model="mdp" required>
                <span class="help-block text-danger" ng-show="loginForm.mdp.$dirty &&
                                        loginForm.mdp.$invalid" ng-cloak>Veuillez rentrer un mdp</span>
            </div>
            <div class="checkbox">
                <label for="remember">
                    <input type="checkbox" name="remember" id="remember" ng-model="remember">
                    Se souvenir de moi
                </label>
            </div>
            <div class="form-group">
                <input class="form-control btn btn-primary" type="submit" 
                       ng-disabled="loginForm.$invalid" value="Connexion">
            </div>

        </form>
    </div>
</div>
<?php
/*
 * Ajouter / Modifier un véhicule
 */
?>
<ol class="breadcrumb">
    <li><a href="index.php"><i class="fa fa-home fa-fw"></i></a></li>
    <li><a href="index.php?p=vehicules.indexPHP">Véhicules</a></li>
    <?php if (isset($vehicule)) : ?>
        <li class="active"><?= $vehicule->marque . " " . $vehicule->modele ?></li>
    <?php else : ?>
        <li class="active">Ajouter un véhicule</li>
    <?php endif; ?>
</ol>

<!--Le titre est différent selon qu'on ajoute ou qu'on modifie un véhicule-->
<h4><?= $title ?></h4>
<?php if (isset($erreurs) && !empty($erreurs)) : ?>
    <div class="alert alert-danger">
        Des erreurs ont été repérées : <br>
        <ul>
            <?php foreach ($erreurs as $erreur) : ?>
                <li><?= $erreur ?></li>
            <?php endforeach; ?>

        </ul>

    </div>
<?php endif; ?>
<?php if (isset($message) && $message !== "") : ?>
    <div class="alert alert-success">
        <p><?= $message ?></p>
    </div>
<?php endif; ?>
<form class='form-horizontal' action='<?= $action ?>' method='POST'>

    <?php if (isset($vehicule)): ?>
        <input type="hidden" id="id" name="id" value="<?= $vehicule->id ?>">
        <div class="form-group">
            <label class="col-sm-2 control-label">Id</label>
            <div class="col-sm-10">
                <p class="form-control-static"><strong><?= $vehicule->id ?></strong></p>
            </div>
        </div>
    <?php endif ?>
    <div class="form-group">
        <label for="marque" class="col-sm-2 control-label">Marque</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="marque" name="marque" placeholder="Marque"
                   value="<?= isset($vehicule) ? $vehicule->marque : "" ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="modele" class="col-sm-2 control-label">Modèle</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="modele" name="modele" placeholder="Modèle"
                   value="<?= isset($vehicule) ? $vehicule->modele : "" ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="plaque" class="col-sm-2 control-label">Plaque</label>
        <div class="col-sm-10 numero_chassis">
            <select class='form-control select-plaque' name='plaque1' id='plaque1'>
                <option value='0' <?= (isset($vehicule) && substr($vehicule->plaque, 0, 1) == "0") ? ' selected' : '' ?>>0</option>
                <option value='1' <?= (isset($vehicule) && substr($vehicule->plaque, 0, 1) == "1") ? ' selected' : '' ?>>1</option>
            </select>
            <input type="text" class="text-center" id="plaque2" name="plaque2" placeholder="ABC"
                   value="<?= isset($vehicule) ? substr($vehicule->plaque, 2, 3) : "" ?>">
            <span class="form-control-static"><strong> - </strong></span>
            <input type="text" class="text-center" id="plaque3"  name="plaque3" placeholder="123"
                   value="<?= isset($vehicule) ? substr($vehicule->plaque, 6, 3) : "" ?>">

        </div>
    </div>
    <div class="form-group">
        <label for="numero_chassis" class="col-sm-2 control-label">Numéro de chassis</label>
        <div class="col-sm-10 numero_chassis">
            <input type="text" class="text-center" id="numero_chassis1" name="numero_chassis1" placeholder="12345"
                   value='<?= isset($vehicule) ? substr($vehicule->numero_chassis, 0, 5) : "" ?>'>
            <span class='form-control-static'>-</span>
            <input type="text" class="text-center" id="numero_chassis2" name="numero_chassis2" placeholder="12345"
                   value='<?= isset($vehicule) ? substr($vehicule->numero_chassis, 6, 5) : "" ?>'>
            <span class='form-control-static'>-</span>
            <input type="text" class="text-center" id="numero_chassis3" name="numero_chassis3" placeholder="12345"
                   value='<?= isset($vehicule) ? substr($vehicule->numero_chassis, 12, 5) : "" ?>'>
            <span class='form-control-static'>-</span>
            <input type="text" class="text-center"  id="numero_chassis4" name="numero_chassis4" placeholder="123456"
                   value='<?= isset($vehicule) ? substr($vehicule->numero_chassis, 18, 6) : "" ?>'>
        </div>
    </div>

    <div class="form-group">
        <label for="type" class="col-xs-2 control-label">Type</label>
        <div class="col-xs-4">
            <select class="form-control" id="type" name="type">
                <option value="">Choisissez un type</option>
                <?php foreach ($options as $option) : ?>
                    <option value="<?= $option->type ?>" <?= (isset($vehicule) && $vehicule->type === $option->type) ? ' selected' : '' ?>><?= $option->type ?></option>
                <?php endforeach; ?>
                <option value="">Autre</option>
            </select>
        </div> 
        <div class="col-xs-6">
            <input type="text" id="autreType" name="autreType" class="form-control" placeholder="A remplir uniquement si 'Autre' choisi">
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-offset-2 col-xs-10">
            <?php if (!isset($vehicule)): ?>
                <input type="reset" class="btn btn-danger" value="Remettre à zéro">
            <?php endif; ?>
            <input type="submit" class="btn btn-success" value="<?= isset($vehicule) ? 'Enregistrer' : 'Ajouter' ?>">
        </div>
    </div>
    <input type="hidden" name="mode" value="php">
</form>


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
                                        <div ng-include="'../app/Views/reparations/modals/modalShowReparation.html'"></div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div ng-include="'../app/Views/vehicules/modals/modalEditVehicule.html'"></div>
            <div ng-include="'../app/Views/vehicules/modals/modalAddReparation.html'"
        </div>
    </div>
    <!-- Modal ajout d'un véhicule -->
</div>
</div>
<div ng-include="'../app/Views/vehicules/modals/modalAddVehicule.html'"></div>
</div>
<?php
/*
 * Index des véhicules, version PHP
 */
?>
<!-- Navigation -->
<ol class="breadcrumb">
    <li><a href="index.php"><i class="fa fa-home fa-fw"></i></a></li>
    <li class="active">Véhicules (version PHP)</li>
</ol>
<!-- Recherche / Filtres -->

<?php include(ROOT . '/app/Views/vehicules/search.php'); ?>

<div class="spacer"></div>
<!-- Véhicules -->
<div class="row">
    <div class="container">
        <div class="col-sm-10">
            <!-- Boutons -->
            <div class="btn-group">
                <?php if (isset($_SESSION["login"])) : ?>
                    <a class="btn btn-sm btn-default" href="index.php?p=vehicules.edit" style="margin-left:15px"> 
                        <i class="fa fa-plus fa-fw"></i> Ajouter un véhicule
                    </a>
                <?php endif; ?>
                <a class="btn btn-sm btn-default" href="index.php?p=vehicules.exportAllAsPDF">
                    <i class="fa fa-file-pdf-o fa-fw"></i> Export PDF
                </a>
                <a class="btn btn-sm btn-default" href="index.php?p=vehicules.exportAsCSV">
                    <i class="fa fa-file-excel-o fa-fw"></i> Export CSV
                </a>
                <?php if (isset($_SESSION["login"])) : ?>
                    <a class="btn btn-sm btn-default" href="#" id="csv-btn">
                        <i class="fa fa-upload fa-fw"></i>  Import CSV
                    </a>
                <?php endif; ?>
            </div>
            <!-- Boutons affichage -->
            <div id="view-group" class="btn-group pull-right">
                <button class="btn btn-sm btn-default" id="show-list">
                    <i class="fa fa-list-ul fa-fw"></i>
                </button>
                <button class="btn btn-sm btn-primary" id="show-panels">
                    <i class="fa fa-columns fa-fw"></i>
                </button>
            </div>
            <!-- Alerte CSV -->
            <div id="alert-csv" class="hide">
                <div class='spacer'></div>
                <div class="alert alert-info">
                    <div class="well" id="response" style="display:none;margin-top:10px">
                        <form id="csv-form">
                            <span id="response-txt"></span>
                            <input id="csv-input" name="csv" type="file" class="hide">
                            <button type="submit" id="submit-csv" class="btn btn-sm btn-success pull-right">
                                <i class="fa fa-paper-plane-o fa-fw"></i> Envoyer
                            </button>
                        </form>
                    </div>
                    <dl>
                        <button class="btn btn-sm btn-default" id="csv-btn-2"> Choisir un fichier</button>
                        <dt>Format à respecter : </dt>
                        <dd>
                            L'ordre des champs est le suivant : ID, Marque, Modèle, Plaque, Numéro de chassis, Type <br>
                            Il ne doit pas y avoir d'en-têtes aux colonnes.
                            <table class="table table-bordered">
                                <tr>
                                    <th>ID</th>
                                    <td>0 pour un nouveau véhicule ou l'id du véhicule pour un update</td>
                                </tr>
                                <tr><th>Marque</th><td>Chaîne de caractères</td></tr>
                                <tr><th>Modèle</th><td>Chaîne de caractères</td></tr>
                                <tr><th>Plaque</th><td>Exemples : 1-ABC-123, 0-DEF-456, ...</td></tr>
                                <tr><th>N° de chassis</th><td>Exemples : 1-ABC-123, 0-DEF-456, ...</td></tr>
                            </table>
                        </dd>
                    </dl>
                    0, "VW", "Golf", "1-ABC-123","12345-12345-12345-123456","Voiture" <br>
                    0, "Renault", "Megane", "1-DEF-456","54321-98765-32154-654321","Tracteur"
                </div>
            </div>
        </div>
    </div>
    <div class="spacer"></div>
    <!-- Pas encore de véhicules -->
    <?php if (empty($vehicules)) : ?>
        <div class="alert alert-info">
            <p><i class="fa fa-exclamation-circle fa-fw"></i>
                Aucun véhicule ne correspond à la recherche 
                '<em><b><?= $searchParams["filter"] ?></b></em>' <br>
                Veuillez tenter une autre recherche
                <?php if (isset($_SESSION["login"])) : ?>
                    ou <a href="index.php?p=vehicules.edit">créer le véhicule manquant</a>
                <?php endif; ?>
                .
            </p>
        </div>
    <?php else : ?>
        <?php if (!empty($_POST["filter"])) : ?>
            <div class="alert alert-info">Résultat de la recherche : 
                <?= count($vehicules) ?> véhicules</div>
        <?php endif; ?>

        <?php if (!empty($message)) : ?>
            <div class="alert alert-success"><?= $message ?></div>
        <?php endif; ?>
        <!-- Panels -->
        <?php include(ROOT . '/app/Views/vehicules/panels.php'); ?>

        <!-- Liste -->
        <?php include(ROOT . '/app/Views/vehicules/list.php'); ?>
    <?php endif; ?>
</div>
<div id="vehicules-list" style="display:none">
    <div class="col-sm-12">
        <table class="table  table-striped table-responsive text-center table-vehicules-list">
            <th>Id</th>
            <th>Marque</th>
            <th>Modèle</th>
            <th>Plaque</th>
            <th>Numéro de chassis</th>
            <th>Type</th>
            <th>Nombre de réparations</th>
            <th>Actions</th>
            <?php foreach ($vehicules as $vehicule): ?>
                <tr>
                    <td>
                        <a href="index.php?p=vehicules.show&id=<?= $vehicule->id ?>">
                            <?= $vehicule->id ?>
                        </a>
                    </td>
                    <td><?= $vehicule->marque ?></td>
                    <td><?= $vehicule->modele ?></td>
                    <td class="text-center"><?= $vehicule->plaque ?></td>
                    <td><?= $vehicule->numero_chassis ?></td>
                    <td><?= $vehicule->type ?></td>
                    <td><?= count($vehicule->reparations) ?></td>
                    <td>
                        <div class="btn-group">
                            <a href="index.php?p=vehicules.show&id=<?= $vehicule->id ?>" 
                               class="btn btn-xs btn-default"
                               data-toggle="tooltip" title="Voir la fiche du véhicule">
                                <i class="fa fa-info fa-fw"></i>
                            </a>
                            <?php if (isset($_SESSION["login"])) : ?>
                                <a href="index.php?p=reparations.edit&vehicule_FK=<?= $vehicule->id ?>" 
                                   class="btn btn-xs btn-warning"
                                   data-toggle="tooltip" title="Ajouter une réparation">
                                    <i class="fa fa-wrench fa-fw"></i>
                                </a>
                                <a href="index.php?p=vehicules.edit&id=<?= $vehicule->id ?>"
                                   class="btn btn-xs btn-success"
                                   data-toggle="tooltip" title="Modifier le véhicule">
                                    <i class="fa fa-edit fa-fw"></i>
                                </a>
                                <a href="index.php?p=vehicules.delete&id=<?= $vehicule->id ?>"
                                   class="btn btn-xs btn-danger"
                                   data-toggle="tooltip" title="Supprimer le véhicule">
                                    <i class="fa fa-trash-o fa-fw"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</div>
<div id="vehicules-panels">
    <?php foreach ($vehicules as $vehicule) : ?>
        <div class='col-xs-12 col-sm-6 col-md-4'>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><?= $vehicule->marque . ' ' . $vehicule->modele ?>

                        <div class="dropdown pull-right">
                            <button class="btn btn-xs btn-default dropdown-toggle" 
                                    type="button" data-toggle="dropdown">
                                <i class="fa fa-cog fa-fw"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="index.php?p=vehicules.show&id=<?= $vehicule->id ?>">
                                        <i class="fa fa-info fa-fw"></i> Voir la fiche du véhicule</a></li>
                                <?php if (isset($_SESSION["login"])) : ?>
                                    <li><a href="index.php?p=reparations.edit&vehicule_FK=<?= $vehicule->id ?>">
                                            <i class="fa fa-wrench fa-fw"></i> Ajouter une réparation</a></li>
                                    <li><a href="index.php?p=vehicules.edit&id=<?= $vehicule->id ?>">
                                            <i class="fa fa-edit fa-fw"></i> Modifier</a></li>
                                    <li><a href="index.php?p=vehicules.delete&id=<?= $vehicule->id ?>">
                                            <i class="fa fa-trash-o fa-fw"></i> Supprimer</a></li>
                                <?php endif; ?>
                            </ul>
                        </div>

                        <span class='label label-info label-vehicule pull-right'><?= $vehicule->type ?></span>
                    </h3>
                </div>
                <div class="panel-body">
                    <ul id="tabs_<?= $vehicule->id ?>" class="nav nav-tabs" data-tabs="tabs">
                        <li role="presentation" class="active"><a data-target="#infos<?= $vehicule->id ?>" data-toggle="tab">Infos</a></li>
                        <li><a data-target="#reparations<?= $vehicule->id ?>" data-toggle="tab">Réparations (<?= count($vehicule->reparations) > 0 ? count($vehicule->reparations) : 0 ?>)</a></li>
                    </ul>
                    <div id="my-tab-content-<?= $vehicule->id ?>" class="tab-content">
                        <div class="tab-pane active" id="infos<?= $vehicule->id ?>">
                            <ul>
                                <li>N° de chassis : <?= $vehicule->numero_chassis ?></li>
                                <li>N° de plaque : <?= $vehicule->plaque ?> </li>
                            </ul>
                        </div>
                        <div class="tab-pane" id="reparations<?= $vehicule->id ?>">
                            <?php if (count($vehicule->reparations) == 0) : ?>
                                <div>
                                    <p>Pas encore de réparations pour ce véhicule</p>
                                    <!--ng-show = "$rootScope.logged" -->
                                    <a class = "btn btn-primary"
                                       href="index.php?p=reparations.edit&vehicule_FK=<?= $vehicule->id ?>">
                                        <i class = "fa fa-wrench fa-fw"></i> Ajouter une réparation
                                    </a>
                                </div>
                            <?php else: ?>
                                <div>
                                    <ul class = "list-group">
                                        <?php foreach ($vehicule->reparations as $reparation) : ?>
                                            <li class = "list-group-item">
                                                <a href="index.php?p=reparations.show&id=<?= $reparation->id ?>">
                                                    <?= $reparation->intervention ?></a>
                                                <span class = "pull-right badge"><?= $reparation->date ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<div class="row well well-vehicules">
    <form id="filterVehiculeForm" action="index.php?p=vehicules.indexPHP" method="POST">
        <div class="col-sm-4">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-search fa-fw"></i></span>
                <input class="form-control" id="filter" name="filter" 
                       type="text" placeholder="Rechercher (type, marque, modèle, ...)">
            </div>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-filter fa-fw"></i></span>
                <select class="form-control" id="orderType" name="filterType">
                    <option value="">Tout type</option>
                    <?php foreach ($options as $option) : ?>
                        <option value="<?= $option->type ?>" 
                                <?= $searchParams["filterType"] == $option->type ? 'selected' : '' ?>>
                            <?= $option->type ?>&nbsp;(<?= $option->nb ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="col-sm-8">
            <div class="form-group order-group">
                <i class="fa fa-sort fa-fw"></i>
                <input type="radio" class="radio radio-inline" name="orderBy" 
                       value="marque" id="orderMarque" 
                       <?= $searchParams["orderBy"] == "marque" ? 'checked' : '' ?>>
                <label for="orderMarque">Marque</label>
                <input type="radio" class="radio radio-inline" name="orderBy" 
                       value="modele" id="orderModele"
                       <?= $searchParams["orderBy"] == "modele" ? 'checked' : '' ?>>
                <label for="orderModele">Modèle</label>
                <input type="radio" class="radio radio-inline" name="orderBy" 
                       value="plaque" id="orderPlaque" 
                       <?= $searchParams["orderBy"] == "plaque" ? 'checked' : '' ?>>
                <label for="orderPlaque">Plaque</label>
                <input type="radio" class="radio radio-inline" name="orderBy" 
                       value="type"
                       <?= $searchParams["orderBy"] == "type" ? 'checked' : '' ?>>
                <label for="orderPlaque">Type</label>
                <br>
                <i class="fa fa-<?=
                $searchParams["sortOrderBy"] == "asc" ||
                $searchParams["sortOrderBy"] == "" ?
                        'sort-alpha-asc' : 'sort-alpha-desc'
                ?> fa-fw"></i> 
                <select style="width:125px" id="sortOrder" name="sortOrderBy">
                    <option value="asc" <?= $searchParams["sortOrderBy"] == 'asc' ? 'selected' : '' ?>>
                        Ascendant</option>
                    <option value="desc" <?= $searchParams["sortOrderBy"] == 'desc' ? 'selected' : '' ?>> 
                        Descendant</option>
                </select>
            </div>
        </div>
        <div id="applyFilters">
            <input type="hidden" name="action" value="filter">
            <button type="submit" class="btn btn-success btn-black">
                <i class="fa fa-refresh fa-fw"></i> Appliquer les filtres</button>
        </div>
    </form>
</div>
<?php
/*
 * Fiche d'un véhicule
 */
?>
<ol class="breadcrumb">
    <li><a href="index.php"><i class="fa fa-home fa-fw"></i></a></li>
    <li><a href="index.php?p=vehicules.indexPHP">Véhicules (PHP)</a></li>
    <li class="active"><?= $vehicule->marque . " " . $vehicule->modele ?></li>
</ol>

<h1> <?= $vehicule->marque . " " . $vehicule->modele ?>
    <span class="small">Véhicule #<?= $vehicule->id ?></span>
</h1>

<div class="row">
    <div class="col-sm-10 col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Informations
                    <div class='btn-group pull-right'>
                        <a class="btn btn-xs btn-default show-infos" data-slide="infos" href="#">
                            <i class="fa fa-eye-slash"></i>
                        </a>
                    </div>
                </h3>
            </div>
            <div class="panel-body" id="infos">
                <ul id="infos-ul">
                    <li>
                        <strong>Plaque: </strong> <?= $vehicule->plaque ?>
                    </li>
                    <li>
                        <strong>Numéro de chassis: </strong> <?= $vehicule->numero_chassis ?>
                    </li>
                    <li>
                        <strong>Type: </strong> <?= $vehicule->type ?>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-sm-10 col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Réparations (<?= count($vehicule->reparations) ?>)
                    <?php if(count($vehicule->reparations) > 0) : ?>
                    <div class="dropdown pull-right">
                        <a class="btn btn-xs btn-default" 
                           data-toggle="dropdown">
                            <i class="fa fa-download fa-fw"
                               data-toggle='tooltip'
                               title='Exporter les réparations'></i>
                        </a>
                        <ul class='dropdown-menu'>
                            <li>
                                <a href="index.php?p=vehicules.exportAsPDF&id=<?= $vehicule->id ?>">
                                    <i class="fa fa-file-pdf-o fa-fw"></i> Export PDF
                                </a>
                            </li>
                            <li>
                                <a href="index.php?p=vehicules.exportReparationsAsCSV&id=<?= $vehicule->id ?>">
                                    <i class="fa fa-file-excel-o fa-fw"></i> Export CSV
                                </a>
                            </li>
                        </ul>
                    </div>
                    <?php endif; ?>
                    <div class="btn-group pull-right btn-rep">
                        <a class="btn btn-xs btn-default show-reparations" data-slide="reparations" href="#"
                           data-toggle="tooltip" title="Masquer les réparations">
                            <i class="fa fa-eye-slash"></i>
                        </a>

                        <?php if (isset($_SESSION["login"])) : ?>
                            <a class="btn btn-xs btn-default" data-toggle="tooltip" title="Ajouter une réparation"
                               href="index.php?p=reparations.edit&vehicule_FK=<?= $vehicule->id ?>">
                                <i class="fa fa-plus-circle fa-fw"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </h3>
            </div>
            <div class="panel-body" id="reparations">
                <?php if (!empty($vehicule->reparations)) : ?>
                    <ul class="list-group" id="reparations-ul">
                        <?php foreach ($vehicule->reparations as $reparation) : ?> 
                            <li class="list-group-item">
                                <?= $reparation->intervention ?>
                                <span class="label label-primary pull-right" style="margin-top: 5px;">
                                    <?= date("d/m/Y", strtotime($reparation->date)) ?>       
                                </span>
                                <?php if (isset($_SESSION["login"])) : ?>
                                    <a href="index.php?p=reparations.edit&id=<?= $reparation->id ?>" 
                                       id="edit-reparation-<?= $reparation->id ?>"
                                       data-toggle="tooltip" title="Modifier la réparation"
                                       class="pull-right">
                                        <i class="fa fa-edit fa-fw"></i>
                                    </a>
                                <?php endif; ?>
                                <a href="#" id="show-reparation-<?= $reparation->id ?>" 
                                   class="show-reparation-desc pull-right"
                                   data-toggle="tooltip" title="Afficher la description">
                                    <i id="icon-<?= $reparation->id ?>" 
                                       class="fa fa-eye fa-fw" style="margin-right: 5px;"></i>
                                </a>
                                <p id="description-<?= $reparation->id ?>" class="hide">
                                    <?= $reparation->description ?>
                                </p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <div class="alert alert-warning" id="alert">
                        Pas encore de réparations pour ce véhicule

                    </div>
                    <?php if (isset($_SESSION["login"])) : ?>
                        <a class="btn btn-primary" href="index.php?p=reparations.edit&vehicule_FK=<?= $vehicule->id ?>">
                            <i class='fa fa-plus fa-fw'></i> Ajouter une réparation
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

