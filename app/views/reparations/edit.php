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