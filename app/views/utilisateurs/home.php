<?php /* Page d'accueil */ global $md; ?>
<div class="well" id="homeBanner">
    <h2 class="text-center text-uppercase"><span style="color:#f5f5f5">Garage</span> <i id="wrenchIcon" class="fa fa-wrench faa-wrench"></i> | <i id="carIcon" class="fa fa-car faa-bounce"></i> Chapelle</h2>
    <?php if (isset($_SESSION["login"])) : ?>
        <h4 class="text-center">Bienvenue, mécano <?= $_SESSION["login"] ?></h4>
    <?php endif; ?> 
</div>
<div class="container">
    <div class="row">
        <div class="col-sm-offset-2 col-sm-6">
            <div class="alert alert-warning alert-dismissible fade in" ng-if="!cookie" ng-cloak>
                <p>Ce site utilise des <abbr title fake-title="Données stockées sur l'ordinateur client">cookies</abbr> afin d'améliorer votre expérience.<br>
                    Cliquez sur l'icône correspondant à votre navigateur/système d'exploitation mobile afin
                    de découvrir comment désactiver les cookies.<br>
                    <a href="https://support.mozilla.org/t5/Cookies-and-cache/Activer-et-d%C3%A9sactiver-les-cookies-que-les-sites-Internet/ta-p/11860"
                       class="social-link" target="_blank">
                        <i class="fa fa-firefox fa-fw"></i>
                    </a>
                    <a href="http://support.google.com/chrome/bin/answer.py?hl=fr&hlrm=en&answer=95647" 
                       class="social-link" target="_blank">
                        <i class="fa fa-chrome fa-fw"></i>
                    </a>
                    <a href="http://windows.microsoft.com/fr-FR/windows-vista/Block-or-allow-cookies" 
                       class="social-link" target="_blank">
                        <i class="fa fa-internet-explorer fa-fw"></i>
                    </a>
                    <a href="http://help.opera.com/Windows/10.20/fr/cookies.html" 
                       class="social-link" target="_blank">
                        <i class="fa fa-opera fa-fw"></i>
                    </a>
                    <a href="https://support.apple.com/kb/ph17191?locale=fr_FR&viewlocale=fr_FR" 
                       class="social-link" target="_blank">
                        <i class="fa fa-safari fa-fw"></i>
                    </a>
                    <a href="http://www.wikihow.com/Disable-Cookies#Android_Devices" 
                       class="social-link" target="_blank">
                        <i class="fa fa-android fa-fw" target="_blank"></i>
                    </a>
                    <a href="https://support.apple.com/fr-fr/HT201265" 
                       class="social-link" target="_blank">
                        <i class="fa fa-apple fa-fw"></i>
                    </a>
                    <a href="https://support.microsoft.com/fr-fr/help/17442/windows-internet-explorer-delete-manage-cookies" 
                       class="social-link" target="_blank">
                        <i class="fa fa-windows fa-fw"></i>
                    </a>
                </p>

                <button type="button" class="btn btn-primary pull-right" style="margin-top: -30px;" data-dismiss="alert" aria-label="Fermer" ng-click="setAlertCookie()"
                        data-toggle="tooltip" title="En cliquant sur ce bouton, vous activerez un cookie qui empêchera l'affichage de cette alerte dans le futur">
                    <span aria-hidden="true">Ok !</span>
                </button>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a data-target="#structure" aria-controls="structure" role="tab" data-toggle="tab">Structure</a></li>
                <li role="presentation"><a data-target="#fonctionnalites" aria-controls="fonctionnalites" role="tab" data-toggle="tab">Fonctionnalités</a></li>
                <li role="presentation"><a data-target="#outils" aria-controls="outils" role="tab" data-toggle="tab">Outils</a></li>
                <li role="presentation"><a data-target="#stats" aria-controls="stats" role="tab" data-toggle="tab">Stats</a></li>
                <!--<li role="presentation"><a data-target="#versions" aria-controls="messages" role="tab" data-toggle="tab">Versions</a></li>-->
            </ul>
            <div class="tab-content">
                <section id="structure" class="tab-pane active" role="tabpanel">
                    <div id="file_tree"></div>
                </section>
                <section id="fonctionnalites" class="tab-pane" role="tabpanel">
                    <?= $md->text($utilities) ?>
                </section>
                <section id="outils" class="tab-pane" role="tabpanel">
                    <?= $md->text($tools) ?>
                </section>
<!--                <section id="versions" class="tab-pane" role="tabpanel">
                    <?php //echo $md->text($versions) ?>
                </section>-->
                <section id="stats" class="tab-pane" role="tabpanel">
                    <div class="col-sm-9">
                        <ul class="list-group">
                            <li class="list-group-item">Scripts PHP 
                                <span class="label label-success pull-right">
                                    <?= $php = count(file('../dist/php/bigPHP.php')) . " lignes \n" ?>
                                </span>
                            </li>
                            <li class="list-group-item">Vues (PHP, HTML)
                                <span class="label label-success pull-right">
                                    <?= $views = count(file('../dist/php/bigViews.php')) . " lignes \n" ?>
                                </span>
                            </li>
                            <li class="list-group-item">Scripts JS :  
                                <span class="label label-warning pull-right">
                                    <?= $js = count(file('../dist/js/bigJS.js')) . " lignes \n" ?>
                                </span>
                            </li>
                            <li class="list-group-item">Feuilles de style CSS 
                                <span class="label label-danger pull-right">
                                    <?= $css = count(file('../dist/css/chapelle.css')) . " lignes \n" ?>
                                </span>
                            </li>
                            <li class="list-group-item list-group-item-info">Total : <?= $total = ($php + $views + $js + $css) . " lignes" ?></li>
                        </ul>
                        <div class="progress">
                            <div class="progress-bar progress-bar-success" role="progressbar" style="width:<?= (($php + $views) / $total) * 100 ?>%">
                                PHP
                            </div>
                            <div class="progress-bar progress-bar-warning" role="progressbar" style="width:<?= ($js / $total) * 100 ?>%">
                                JS
                            </div>
                            <div class="progress-bar progress-bar-danger" role="progressbar" style="width:<?= ($css / $total) * 100 ?>%">
                                CSS
                            </div>
                        </div>

                    </div   >
                </section>
            </div>
        </div>
        <div class="col-sm-4">
            <?= $md->text($structure) ?>
        </div>
    </div>
</div>

