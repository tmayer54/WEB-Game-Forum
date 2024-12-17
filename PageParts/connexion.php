<!-- Form utilisé pour se connecter au blog. Action réalisé dans login.php -->
<div id="popup-login" class="popup">
		<button id="close-login-btn" class="close-popup" onclick='hidePopup("popup-login")'>&times;</button>
        <div class="form">
            <h2>Formulaire de connexion</h2>

                <form id="form-connexion" method="POST" action="login.php">
                    <div class="form-element">
                        <label for="pseudo">Pseudo : 
                            <input id="input-connexion-pseudo" class="input-connexion" name="login" type="text" required />
                        </label>
                    </div>
                    <div class="form-element">
                        <label for="mdp">Mot de passe : 
                            <input id="input-connexion-mdp" class="input-connexion" name="mdp" type="password" required />
                        </label>
                    </div>
                    <div class="form-element">
                        <input type="submit" class="input-connexion" value="Connexion" />
                    </div>
                </form>
                <div><ul id="retour-connexion"></ul></div>
                <a id="show-register-link" href="#" onclick='showPopup("popup-register")'>Pas de compte?</a>
        </div>
</div>