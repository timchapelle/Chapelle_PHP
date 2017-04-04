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

<?php include(ROOT . '/app/views/vehicules/search.php'); ?>

<div class="spacer"></div>
<!-- Véhicules -->
<div class="row">
    <div class="container">
        <div class="col-sm-10">
            <!-- Boutons -->

            <?php if (isset($_SESSION["login"])) : ?>
                <div class="btn-group">
                    <a class="btn btn-sm btn-default" href="index.php?p=vehicules.edit" style="margin-left:15px"> 
                        <i class="fa fa-plus fa-fw"></i> Ajouter un véhicule
                    </a>

                </div>
            <?php endif; ?>
            <!-- Boutons affichage -->
            <div id="view-group" class="btn-group pull-right">
                <button class="btn btn-sm btn-default" id="show-list">
                    <i class="fa fa-list-ul fa-fw"></i>
                </button>
                <button class="btn btn-sm btn-primary" id="show-panels">
                    <i class="fa fa-columns fa-fw"></i>
                </button>
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
        <?php include(ROOT . '/app/views/vehicules/panels.php'); ?>

        <!-- Liste -->
        <?php include(ROOT . '/app/views/vehicules/list.php'); ?>

        <!-- Alertes suppression --> 
        <?php include(ROOT . '/app/views/vehicules/modals/modalDeleteVehicule.php'); ?>
    <?php endif; ?>
</div>