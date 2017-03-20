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
