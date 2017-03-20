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