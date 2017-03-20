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