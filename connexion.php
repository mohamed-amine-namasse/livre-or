<?php
      require 'config.php';
      
        
        $message = '';      // Message à afficher à l'utilisateur
        
        /*****************************************
         *  Vérification du formulaire connexion
        *****************************************/
        // Si le tableau $_POST existe alors le formulaire a été envoyé
        if(!empty($_POST))
        {
            
            // Le formulaire a été envoyé vide
            if(empty($_POST['login'])||empty($_POST['password'])||empty($_POST['confirm_password']))
            {
            $message = 'Des champs vides dans le formulaire ont été envoyés ! Veuillez entrer des champs valides';
            }
            if($_POST["password"]!=$_POST["confirm_password"]) {
            $message = 'les deux mots de passe ne sont pas identiques! Veuillez réessayer';}
            
        }


?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de connexion</title>
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
                <a href="./connexion.php" class="active">
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
            <li>
                <a href="./livreor.php" class="#">
                    <div class="icon">
                        <i class="fa fa-book"></i>
                        <i class="fa fa-book"></i>
                    </div>
                    <div class="name">Livre d'or</div>
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
        <?php if(!empty($message)) : ?>
        <p><?php echo $message; ?></p>
        <?php endif; ?>

        <?php
        
        
        //on établit la connexion avec la base de donnée moduleconnexion
        $connexion = $conn;
        
        //on fait une requete SQL pour insérer l'ensemble des informations de la table utilisateurs
        if (isset($_POST["login"]) && isset($_POST["password"])&&isset($_POST["confirm_password"]) ){
        $login=$_POST["login"];
        $password=$_POST["password"];
        $confirm_password=$_POST["confirm_password"];
        }
        //Si les champs ne sont pas vides, on insère les données dans la base de données
        if(!empty($login) && !empty($password) &&
        !empty($_POST["confirm_password"]) && ($_POST["password"]==$_POST["confirm_password"])) {
            // Vérifie si le login existe déjà
            $check = "SELECT * FROM utilisateurs WHERE login='$login'";
            $check_result = mysqli_query($connexion, $check);
            if(mysqli_num_rows($check_result) > 0) {
                $message = "Ce login existe déjà, veuillez en choisir un autre.";
                echo "<p>$message</p>";
            } else {
                // Hash du mot de passe
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $command = "INSERT INTO utilisateurs (id,login,password) VALUES (0,'$login','$password_hash')";
                $result = mysqli_query($connexion, $command);
                $message = 'Parfait, ton utilisateur a bien été créé!';
                echo "<p>$message</p>";
            }
}
            
       

       
        ?>


        <div class=container_form>
            <div>
                <h2>Connexion</h2>
                <br>
                <form id=form action="profil.php" method="post">
                    <label><b>Login:</b></label><br>
                    <input type="text" name="login"><br>
                    <label><b>Password:</b></label><br>
                    <input type="text" name="password">
                    <div class=btn>
                        <input class=bouton_submit type="submit" value="Envoyer">
                    </div>
                </form>
            </div>
        </div>




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