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

