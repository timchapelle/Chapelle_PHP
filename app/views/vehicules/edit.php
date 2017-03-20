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

