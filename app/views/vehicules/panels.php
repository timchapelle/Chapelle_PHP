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
                                    <li><a href="#" data-toggle="modal" data-target="#modalDeleteVehicule_<?=$vehicule->id?>">
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