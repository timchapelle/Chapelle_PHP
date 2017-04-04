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
                <th class="<?= $sort == 'id' ? 'text-primary' : '' ?>">
                    <a href="index.php?p=reparations.index&sort=id&order=<?= $nextOrder ?>">
                        Id
                        <?= $sort == 'id' ? '<i class="fa fa-sort-numeric-' . $order . '"></i>' : '' ?>
                    </a></th>
                <th class="<?= $sort == 'intervention' ? 'text-primary' : '' ?>">
                    <a href="index.php?p=reparations.index&sort=intervention&order=<?= $nextOrder ?>">
                        Intervention
                        <?= $sort == 'intervention' ? '<i class="fa fa-sort-alpha-' . $order . '"></i>' : '' ?>
                    </a></th>
                <th class="<?= $sort == 'description' ? 'text-primary' : '' ?>">
                    <a href="index.php?p=reparations.index&sort=description&order=<?= $nextOrder ?>">
                        Description
                        <?= $sort == 'description' ? '<i class="fa fa-sort-alpha-' . $order . '"></i>' : '' ?>
                    </a></th>
                <th class="<?= $sort == 'date' ? 'text-primary' : '' ?>">
                    <a href="index.php?p=reparations.index&sort=date&order=<?= $nextOrder ?>">
                        Date
                        <?= $sort == 'date' ? '<i class="fa fa-sort-numeric-' . $order . '"></i>' : '' ?>
                    </a></th>
                <th class="<?= $sort == 'vehicule_FK' ? 'text-primary' : '' ?>">
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
                    <td  class="<?= is_object($reparation->vehicule) ? '' : 'danger' ?>">
                        <?=
                        (is_object($reparation->vehicule) ?
                                $reparation->vehicule->marque . " " . $reparation->vehicule->modele :
                                "-")
                        ?>
                    </td>

                    <td><div class="btn-group" role="group">
                            <a class="btn btn-sm btn-default" data-toggle="tooltip"
                               title="Détails"
                               href="index.php?p=reparations.show&id=<?= $reparation->id ?>">
                                <i class="fa fa-eye fa-fw"></i></a>
                            <a class="btn btn-sm btn-warning" data-toggle="tooltip"
                               title="Fiche du véhicule"
                               href="index.php?p=vehicules.show&id=<?= $reparation->vehicule_FK ?>">
                                <i class="fa fa-car fa-fw"></i>
                            </a>
                            <?php if (isset($_SESSION["login"])) : ?>
                                <a class="btn btn-sm btn-success" data-toggle="tooltip"
                                   title="Modifier"
                                   href="index.php?p=reparations.edit&id=<?= $reparation->id ?>">
                                    <i class="fa fa-edit fa-fw"></i></a>
                                <button class="btn btn-sm btn-danger" data-toggle="modal"
                                        data-target="#modalDeleteReparation<?= $reparation->id ?>">
                                    <i class="fa fa-trash-o fa-fw" data-toggle="tooltip"
                                       title="Supprimer"></i></button>
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
                <a href="index.php?p=reparations.index&page=<?= $page - 1 ?>&sort=<?= $sort ?>&order=<?= $order ?>" 
                   aria-label="Précédent">
                    <span aria-hidden="true" class="fa fa-caret-left"></span>
                </a>
            <li>
            <?php endif; ?>
            <?php for ($i = 0; $i < $nbReparations / 10; $i++) : ?>
            <li class="<?= $page == $i + 1 ? 'active' : '' ?>">
                <a href="index.php?p=reparations.index&page=<?= $i + 1 ?>&sort=<?= $sort ?>&order=<?= $order ?>">
                    <?= $i + 1 ?>
                </a>
            </li>
        <?php endfor; ?>
        <?php if ($page < ($nbReparations / 10)) : ?>
            <li>
                <a href="index.php?p=reparations.index&page=<?= $page + 1 ?>&sort=<?= $sort ?>&order=<?= $order ?>" 
                   aria-label="Précédent">
                    <span aria-hidden="true" class="fa fa-caret-right"><!--&raquo;--></span>
                </a>
            </li>
        <?php endif; ?>
    </ul>
</div>
<?php include(ROOT . '/app/views/reparations/modals/modalDeleteReparation.php'); ?>