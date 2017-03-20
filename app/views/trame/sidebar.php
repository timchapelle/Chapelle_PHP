<div id="sidebar-wrapper">
    <ul class="sidebar-nav">
        <li class="sidebar-brand">
            <a href="index.php">
                Garage
                <span class="close menu-toggle-btn" style="color:red" ng-click="showHamburger = !showHamburger" >&times;</span>
            </a>

        </li>
        <li ng-controller="SVNavController">
            <form name="searchVehiculeNav">
                <div class="input-group search-vehicule-nav">

                    <input type="text" class="form-control" name="search"
                           placeholder="Rechercher" ng-model="searchTerm">
                    <span class="input-group-btn">
                    </span>
                </div>
                <ul class="media-list search-list-group" style="background:transparent">
                    <li class="media search-list-group-item" 
                        ng-repeat="vehicule in vehicules| toArray:false | filter: searchTerm" 
                        ng-show="searchTerm.length >= 2" ng-cloak>
                        <a href="index.php?p=vehicules.show&id={{vehicule.id}}">
                            <div class="media-left">
                                <img class="media-object" src="https://images.vexels.com/media/users/3/128998/isolated/lists/15630dcae2578399bfabe65c4290ed8a-vintage-car-flat-icon.png" height="35" alt="...">
                            </div>
                            <div class="media-body">
                                <h6 class="media-heading">{{ (vehicule.marque + ' ' + vehicule.modele) | limitTo: 15}}</h6>
                                <p>{{ vehicule.plaque}}</p>
                            </div>
                        </a>

                    </li>
                    <li class="media search-list-group-item" ng-show="searchTerm !== '' && searchTerm.length < 2" ng-cloak>
                        <p>
                            2 caractères minimum
                        </p>
                    </li>
                </ul>
            </form>
        </li>
        <li>
            <a href="#" data-toggle="collapse" data-target="#vehiculeMenu">Véhicules
                <i class="fa fa-caret-down pull-right sidebar-caret"></i>
            </a>
        </li>
        <li id="vehiculeMenu" class="collapse">
            <a href="index.php?p=vehicules.index">
                <img src="../assets/img/angular_icon.png" style="height:30px" alt="Angular Logo">Version Angular JS</a>
            <a href="index.php?p=vehicules.indexPHP">
                <img src="../assets/img/PHP_icon.png" alt="PHP Logo">Version PHP</a>

        </li>
        <li>
            <a href="index.php?p=reparations.index">Réparations</a>
        </li>
        <li><a href="#" data-toggle="collapse" data-target="#docMenu" id="docLink">
                Documentation 
                <i class="fa fa-caret-down pull-right sidebar-caret"></i></a>
        </li>
        <li class="collapse" id="docMenu">
            <a href="../docs/php" target="_blank">PHP</a>
            <a href="../docs/js" target="_blank">Javascript</a>
        </li>

        <?php if (!isset($_SESSION["login"])) : ?>
            <li><a href = "index.php?p=utilisateurs.login"><i class = "fa fa-user fa-fw" 
                                                              style="margin-left:-20px;margin-right:20px"></i> 
                    Login</a></li>
        <?php else : ?>
            <li><a href="index.php?p=utilisateurs.logout"><i class="fa fa-lock fa-fw" 
                                                             style="margin-left:-20px;margin-right:20px"></i> 
                    Déconnexion (<?= $_SESSION["login"] ?>)</a></li>
        <?php endif; ?>
    </ul>
</div>