<?php
        require 'config.php';
        // On démarre une session
        session_start();
        $message1 = '';      // Message à afficher à l'utilisateur
        $message2 = '';      // Message à afficher à l'utilisateur
        $message3 = '';      // Message à afficher à l'utilisateur


       
        
        
        
        if (isset($_POST["login"]) && isset($_POST["password"]) ){
        $login=$_POST["login"];
        $password=$_POST["password"];
        }
        /*****************************************
         *  Vérification du formulaire de connexion
        *****************************************/
        // Si le tableau $_POST existe alors le formulaire a été envoyé
        if(!empty($_POST))
        {   
           
               
            // Le login est-il rempli ?
            if(empty($_POST['login']))
            {
            $message1 = 'Veuillez indiquer votre login svp !';
            }
            // Le mot de passe est-il rempli ?
            if(empty($_POST['password']))
            {
            $message2 = 'Veuillez indiquer votre mot de passe svp !';
            }
            // Si les champs ne sont pas vides, on n'est connecté
            if(!empty($_POST['login']) && !empty($_POST['password'])) {
            $connexion = $conn;
            $login =$_POST['login'];
            $password =  $_POST['password'];
            // On fait une requete SQL pour insérer l'ensemble des informations de la table utilisateurs
            // On vérifie si le login et le mot de passe correspondent à un utilisateur dans la base de données
            // On utilise password_verify pour vérifier le mot de passe haché
            $command = "SELECT * FROM utilisateurs WHERE login='$login' ";
            $result = mysqli_query($connexion, $command);
            $user = mysqli_fetch_assoc($result);

            if ($user && password_verify($password, $user['password'])) {
                $message3 = 'Bienvenue ' . $login . ' ! Tu es connecté!';
                $_SESSION['login'] = $login;
                // On remplit $donnee SEULEMENT si la connexion est réussie
                $donnee = $user;
                $_SESSION['id'] = $donnee['id']; // Stocke l'ID de l'utilisateur dans la session
            } elseif(!isset($_POST['modifier'])) {
                $message3 = "Erreur : login ou mot de passe incorrect.";
                // $donnee reste vide, donc le formulaire reste vide
                $donnee = null;
            }
            }
           
            
            
        }
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page profil</title>
    <link rel="stylesheet" href="./assets/css/style.css" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@100..900&display=swap" rel="stylesheet">


    <!-- Load an icon library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">




</head>

<body>

    <header>


        <ul>
            <li>
                <a href="./index.php" class="#">
                    <div class="icon">
                        <i class="fa fa-home"></i>
                        <i class="fa fa-home"></i>
                    </div>
                    <div class="name">Home</div>
                </a>
            </li>

            <?php if (!isset($_SESSION['login'])): ?>
            <li>

                <a href="./connexion.php" class="#">
                    <div class="icon">
                        <i class="fa fa-user-circle-o"></i>
                        <i class="fa fa-user-circle-o"></i>
                    </div>
                    <div class="name">Se connecter</div>
                </a>

            </li>
            <li>
                <a href="./inscription.php" class="#">
                    <div class="icon">
                        <i class="fa fa-user-plus"></i>
                        <i class="fa fa-user-plus"></i>
                    </div>
                    <div class="name">Inscription</div>
                </a>
            </li>

            <?php else: ?>

            <li>
                <a href="./profil.php" class="active">
                    <div class="icon">
                        <i class="fa fa-user"></i>
                        <i class="fa fa-user"></i>
                    </div>
                    <div class="name">Profil</div>
                </a>
            </li>

            <li>
                <a href="./commentaire.php" class="#">
                    <div class="icon">
                        <i class="fa fa-edit"></i>
                        <i class="fa fa-edit"></i>
                    </div>
                    <div class="name">Commentaires</div>
                </a>
            </li>
            <li>
                <a href="./livreor.php" class="#">
                    <div class="icon">
                        <i class="fa fa-book"></i>
                        <i class="fa fa-book"></i>
                    </div>
                    <div class="name">Livre d'or</div>
                </a>
            </li>
            <li>
                <a href="./deconnexion.php" class="#">
                    <div class="icon">
                        <i class="fa fa-sign-out"></i>
                        <i class="fa fa-sign-out"></i>
                    </div>
                    <div class="name">Deconnexion</div>
                </a>
            </li>
            <?php endif; ?>
        </ul>




    </header>

    <main>
        <?php if(!empty($message1)) : ?>
        <p><?php echo $message1; ?></p>
        <?php endif; ?>
        <?php if(!empty($message2)) : ?>
        <p><?php echo $message2; ?></p>
        <?php endif; ?>
        <?php if(!empty($message3)) : ?>
        <p><?php echo $message3; ?></p>
        <?php endif; ?>
        <?php if($message1 || $message2|| $message3 === "Erreur : login ou mot de passe incorrect.") : ?>
        
        <button class="button-profil" onclick="window.location.href='connexion.php'">Retour à la connexion</button>
        <?php endif; ?>
        <?php
        
        
        //on établit la connexion avec la base de donnée livreor
        $connexion = $conn;
        if (!isset($donnee) && isset($_SESSION['login'])) {
        $login = $_SESSION['login'];
        $command = "SELECT * FROM utilisateurs WHERE login='$login'";
        $result = mysqli_query($connexion, $command);
        $donnee = mysqli_fetch_assoc($result);
        
}
        
        
        

?>




        <?php
            
        if (isset($_POST['modifier']) && isset($donnee)) {
        $id = $donnee['id'];
        $login = $_POST["login"];
        $password = $_POST["password"];

        // Échapper les entrées utilisateur pour la sécurité
        $login = mysqli_real_escape_string($connexion, $login);
        $password = mysqli_real_escape_string($connexion, $password);

        if (!empty($password)) {
            // Hashage du nouveau mot de passe
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $update = "UPDATE utilisateurs SET login='$login', password='$password_hash' WHERE id='$id'";
        } else {
            // Pas de changement de mot de passe
            $update = "UPDATE utilisateurs SET login='$login' WHERE id='$id'";
        }

        if (mysqli_query($connexion, $update)) {
            $message3 = "Votre profil mis à jour avec succès !";
            echo "<p>$message3</p>";

            // Recharger les données pour afficher les nouvelles valeurs
            $query = "SELECT * FROM utilisateurs WHERE id='$id'";
            $result = mysqli_query($connexion, $query);
            $donnee = mysqli_fetch_assoc($result);
			$_SESSION['login'] = $donnee['login'];
            $_SESSION['password'] = $donnee['password'];
        } else {
            $message3 = "Erreur lors de la mise à jour du profil.";
            echo "<p>$message3</p>";
        }
    }

?>


        <?php if (isset($_SESSION['login'])): ?>
        <div class=container_form>
            <div>
                <h2>Information du compte</h2>
                <br>
                <form id=form action=" profil.php" method="post">

                    <label><b>Login:</b></label><br>
                    <input type="text" name="login" value="<?php if (isset($donnee)){ echo htmlspecialchars($donnee['login']); } ?>"><br>
                    <label><b>Password:</b></label><br>
                    <input type="text" name="password" value=""><br>
                    <div class=btn>
                        <input class="bouton_submit" type="submit" name="modifier" value="Modifier">
                    </div>
                    
                </form>
            </div>

        </div>
        <?php endif; ?>
    
    
    




    </main>

    <footer>

        <div class="container_footer">

            <div>
                <h3>Services</h3>
                <nav>
                    <a href="#">Web design</a>
                    <a href="#">Development</a>
                    <a href="#">Hosting</a>
                </nav>
            </div>
            <div>
                <h3>About</h3>
                <nav>
                    <a href="#">Company</a>
                    <a href="#">Team</a>
                    <a href="#">Careers</a>
                </nav>
            </div>

            <div>
                <h3>Company </h3>
                <nav>
                    <a href="#">Awards</a>
                    <a href="#">Method</a>
                    <a href="#">Contact</a>
                </nav>
            </div>
        </div>

        <hr class=hr_milieu>

        <p> ©All Rights Reserved.</p>
        </div>


    </footer>


</body>

</html>
