<div ng-show="loading" class="center-block text-center">
    <garage-loader></garage-loader> <!-- Directive crée dans app.js -->
</div>
<div ng-show="!loading" ng-cloak>
    <div class="col-sm-4 col-sm-offset-4" ng-controller="LoginCtrl">

        <div class="alert alert-info">
            Veuillez vous connecter pour accéder à la gestion du garage
        </div>

        <form id="loginForm" role="form" name="loginForm" 
              ng-submit="validerLogin()" novalidate autocomplete="off">

            <div class="form-group" ng-class="loginForm.login.$error.required &&
                                    loginForm.login.$dirty ? 'has-error' : 'has-success'">
                <label for="login">Login</label>
                <input class="form-control" type="text" id="login" name="login" ng-model="login" required>
                <span class="help-block" 
                      ng-show="loginForm.login.$dirty && loginForm.login.$invalid" ng-cloak>
                    Veuillez rentrer un login</span>
            </div>
            <span class="help-block alert" 
                  ng-class="success ? 'alert-success' : 'alert-danger'" ng-show="message" ng-cloak>
                {{ message}}
            </span>
            <div class="form-group" ng-class="loginForm.mdp.$error.required &&
                                    loginForm.mdp.$dirty ? 'has-error' : 'has-success'">
                <label for="mdp">Mot de passe</label>
                <input class="form-control" type="password" id="mdp" name="mdp" 
                       ng-model="mdp" required>
                <span class="help-block text-danger" ng-show="loginForm.mdp.$dirty &&
                                        loginForm.mdp.$invalid" ng-cloak>Veuillez rentrer un mdp</span>
            </div>
            <div class="checkbox">
                <label for="remember">
                    <input type="checkbox" name="remember" id="remember" ng-model="remember">
                    Se souvenir de moi
                </label>
            </div>
            <div class="form-group">
                <input class="form-control btn btn-primary" type="submit" 
                       ng-disabled="loginForm.$invalid" value="Connexion">
            </div>

        </form>
    </div>
</div>