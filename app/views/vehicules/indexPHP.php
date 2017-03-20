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
        <?php include(ROOT . '/app/views/vehicules/panels.php'); ?>

        <!-- Liste -->
        <?php include(ROOT . '/app/views/vehicules/list.php'); ?>
        
        <!-- Alertes suppression --> 
        <?php include(ROOT . '/app/views/vehicules/modals/modalDeleteReparation.php'); ?>
    <?php endif; ?>
</div>