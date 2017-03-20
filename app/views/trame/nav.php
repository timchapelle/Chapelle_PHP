<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">Garage</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="active"><a href="index.php"><i class="fa fa-home fa-fw"></i> Home<span class="sr-only">(current)</span></a></li>
                <li><a href="index.php?p=vehicules.index"><i class="fa fa-car fa-fw"></i> Véhicules</a></li>
                <li><a href="index.php?p=reparations.index"><i class="fa fa-cogs fa-fw"></i> Réparations</a></li>

            </ul>
            <ul class = "nav navbar-nav navbar-right">

                <?php if (!isset($_SESSION["login"])) : ?>
                    <li><a href = "index.php?p=utilisateurs.login"><i class = "fa fa-user fa-fw"></i> Login</a></li>
                <?php else : ?>
                    <li><a href="index.php?p=utilisateurs.logout"><i class="fa fa-lock fa-fw"></i> Déconnexion</a></li>
                    <?php endif; ?>
            </ul>


        </div>
    </div>
</nav>