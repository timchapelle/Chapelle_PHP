<?php $app = App::getInstance(); ?>
<!DOCTYPE html>
<!--
ISFCE - 2017 - Projet de développement web - C. Lemaigre

Gestion d'un garage

CHAPELLE Timothée

-->
<html lang="fr" ng-app="garage"> <!-- Initialisation de l'application AngularJS -->
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="robots" content="noindex">
        <title><?= $app->title ?></title>
        <!-- Favicon -->
        <link rel="icon" type="img/ico" href="favicon.ico">
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
        <!--DatePicker-->
        <link href="http://code.jquery.com/ui/1.12.1/themes/dark-hive/jquery-ui.css" rel="stylesheet" type="text/css" >
        <!-- CSS concaténés et minifiés -->
         
        <!-- Twitter Bootstrap -->
        <link href="../assets/css/bootstrap_paper.css" rel="stylesheet" type="text/css"/>
        <link href="../assets/css/simple-sidebar.css" rel="stylesheet" type="text/css"/>
        <!-- Font-Awesome -->
        <link href="../bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
        <link href="../bower_components/font-awesome-animation/dist/font-awesome-animation.min.css" rel="stylesheet" type="text/css"/>
        <!--jQueryFileTree-->
        <link href="../bower_components/jqueryfiletree/dist/jQueryFileTree.min.css" rel="stylesheet" type="text/css"/>
 <!-- Chapelle -->
        <link href="../assets/css/chapelle.css" rel="stylesheet" type="text/css"/>

    </head>

    <body>
        <div id="wrapper">
            <?php include ROOT . '/app/views/trame/sidebar.php' ?>
            <div id="page-content-wrapper" class="container" ng-controller="GarageCtrl">
                <a href="#menu-toggle" class="btn btn-default menu-toggle-btn hidden-xs"
                   ng-show="$parent.showHamburger"  ng-cloak
                   ng-click="$parent.showHamburger = !$parent.showHamburger">
                    <i class="fa fa-bars fa-fw"></i> Menu
                </a>
                <a href="#menu-toggle" class="btn btn-xs btn-default menu-toggle-btn visible-xs">
                    <i class="fa fa-bars fa-fw"></i> Menu
                </a>
                <?= $content ?>
            </div>
            <?php include ROOT . '/app/views/trame/footer.php' ?>
        </div>
        <!-- SCRIPTS -->
        <!-- jQuery + TBS-->
        <script src="../bower_components/jquery/dist/jquery.min.js" type="text/javascript"></script>
        <script src="../bower_components/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="../bower_components/jqueryfiletree/dist/jQueryFileTree.min.js" type="text/javascript"></script>
<!-- AngularJS -->
        <script src="../bower_components/angular/angular.min.js" type="text/javascript"></script>
        <script src="../bower_components/angular-toArrayFilter/toArrayFilter.js" type="text/javascript"></script>
        
        <!-- Angular Cookies -->
        <script src="../bower_components/angular-cookies/angular-cookies.js" type="text/javascript"></script>
         <!--jQuery UI DatePicker-->
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>   
        <script src="../assets/js/datepicker-fr.js"></script>
        <!-- Chapelle - Angular -->
        <script src="../assets/js/app.js" type="text/javascript"></script>
        <script src="../assets/js/controllers.js" type="text/javascript"></script>
        <script src="../assets/js/services.js" type="text/javascript"></script>

        <!-- Chapelle - jQuery -->
        <script type="text/javascript" src="../assets/js/chapelle_jquery.js"></script>
        <!-- Version concaténée, uglifiée, minifiée des 3 scripts AngularJS + script jQuery -->
        <!--<script src="../dist/js/bigJS.min.js"></script>-->

</html>
