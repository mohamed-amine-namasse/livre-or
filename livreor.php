  <?php
        require 'config.php';
        session_start();
        $message = '';      // Message à afficher à l'utilisateur
        /*****************************************
         *  Vérification du formulaire de commantaire
        *****************************************/
        // Si le tableau $_POST existe alors le formulaire a été envoyé
        if(!empty($_POST))
        {   
           
               
            // Le login est-il rempli ?
            if(empty($_POST['comment']))
            {
            $message = "Veuillez indiquer votre commentaire svp avant de l'envoyer !";
            }
                
            
        }

?>


  <!DOCTYPE html>
  <html lang="fr">

  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Livre d'or</title>
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
              <li>
                  <a href="./livreor.php" class="active">
                      <div class="icon">
                          <i class="fa fa-book"></i>
                          <i class="fa fa-book"></i>
                      </div>
                      <div class="name">Livre d'or</div>
                  </a>
              </li>

              <?php else: ?>

              <li>
                  <a href="./profil.php" class="#">
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
                  <a href="./livreor.php" class="active">
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

          <?php if(!empty($message)) : ?>
          <p><?php echo $message; ?></p>
          <?php endif; ?>
          <?php
        //on établit la connexion avec la base de donnée moduleconnexion
        $connexion = $conn;
        
        
        //on fait une requete SQL pour insérer l'ensemble des informations du commentaire dans la table commentaires
        if (isset($_POST["comment"]) ){
        $comment = mysqli_real_escape_string($connexion,$_POST["comment"]);
        //$comment=$_POST["comment"];
        date_default_timezone_set('Europe/Paris');
        $date = date('Y-m-d H:i:s'); // Format de date et heure
        $id_utilisateur= $_SESSION['id'];
        }
        //Si les champs ne sont pas vides, on insère les données dans la base de données
           if(!empty($comment)){
            // On insère le commentaire dans la base de données
            $command = "INSERT INTO commentaires  (id,commentaire,id_utilisateur,date) VALUES (0,'$comment','$id_utilisateur','$date')";
            $result = mysqli_query($connexion, $command);
            $message = 'Parfait, ton commentaire a bien été créé!';
            echo "<p>$message</p>";
            // Redirection pour éviter la duplication
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit(); // Arrête le script après redirection
        }
    



        ?>
          <div class=container_form2>



              <?php    
              // On récupère la page actuelle
              if (isset($_GET['page']) && !empty($_GET['page']) ) {
                  $currentPage = (int)$_GET['page'];}
              else{
                  $currentPage = 1;
              }
                
              // On compte le nombre total des commentaires 
              $nbcommentaires = mysqli_query($connexion, "SELECT COUNT(*) AS total FROM commentaires");
              $nbcommentaires = mysqli_fetch_assoc($nbcommentaires)['total'];
              
             // On définit le nombre de commentaires par page
             $ParPage = 4;
            // On calcule le nombre total de pages
            $pages = ceil($nbcommentaires / $ParPage);
            //calcul du premier article de la page
            $premier= $currentPage * $ParPage-$ParPage;

            // Récupérer tous les commentaires, du plus récent au plus ancien
            $command = "SELECT commentaires.date AS 'Posté le' ,utilisateurs.login AS 'Par utilisateur',commentaires.commentaire AS 'Commentaires'
            FROM commentaires
            JOIN utilisateurs ON commentaires.id_utilisateur = utilisateurs.id
            ORDER BY commentaires.date DESC LIMIT $premier,$ParPage";
            $result = mysqli_query($connexion, $command);
            ?>
              <h2>Livre d'or</h2>
              <div style="overflow-x:auto;">
                  <table>
                      <tr>
                          <?php
                      //on recupère le header de notre table
                      $fields = mysqli_fetch_fields($result);
                      foreach ($fields as $field) {
                      echo"<th>".htmlspecialchars($field->name)."</th>";}
                      ?>
                      </tr>
                      <?php    //on recupère le body de notre table 
                    while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    foreach ($fields as $field) {
                    $fieldName = $field->name;
                    echo "<td>" . htmlspecialchars($row[$fieldName]) . "</td>";
                    }
                    echo"</tr>";
                    }
                
                    ?>

                  </table>
              </div>
          </div>
          <nav id=pag>
              <ul class="pagination">
                <?php if ($currentPage > 1): ?>
                  <li class="page-item">
                      <a class="" href="?page=<?=$currentPage-1?>">Précédente</a>
                  </li>
                <?php endif; ?>

                  <?php for($page = 1; $page <= $pages; $page++): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?=$page?>"><?=$page?></a>
                    </li>
                  <?php endfor; ?>
                  <?php if ($currentPage < $pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?page=<?=$currentPage+1?>">Suivante</a>
                    </li>
                  <?php endif; ?>
              </ul>
          </nav>
          <?php if (!isset($_SESSION['login'])): ?>
          <button class="button" onclick="window.location.href='connexion.php'">Connecter vous pour ajouter un
              commentaire</button>
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