<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="myforms.css" type="text/css" />
    <script charset="utf-8" type="text/javascript" src="myforms.js"></script>
    <meta charset="latin" />
    <title>Vos formulaires</title>
  </head>
  <body>
<?php

      if(!isset($_COOKIE['session'])) {
          header('Location:login.php');
          exit();
      } else {
          $session = unserialize($_COOKIE['session']);
          if(isset($_POST['request'])) {
                require('db_connect.php');
                $link = mysqli_connect($db_host, $db_login, $db_passwd, $db_base);
                if(!$link)
                    die('<span style="color:red;">Incapable de se connecter !</span>');
                  
                switch($_POST['request']) {
                case "Create":
                    do {
                        $random_key = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);
                        $result = mysqli_query($link, "SELECT formKey FROM `FORM` WHERE formKey='".$random_key."';");
                    } while(mysqli_num_rows($result) >= 1);
                    date_default_timezone_set("Europe/Paris");
                    mysqli_query($link, "INSERT INTO `FORM`(`login`, `formKey`, `title`, `description`, `creationDate`, `activity`) VALUES ('".$session['login']."', '".$random_key."', '".mysqli_real_escape_string($link, $_POST['title'])."', '".mysqli_real_escape_string($link, $_POST['description'])."', '".date("Y-m-d")."', '0');");
                  break;
                case "Edit":
                    header('Location:app.php?formKey='.$_POST['key']);
                    mysqli_close($link);
                    exit();
                    break;
                case "Results":
                    header('Location:results.php?formKey='.$_POST['key']);
                    mysqli_close($link);
                    exit();
                    break;

                case "Activate":
                case "Deactivate": 
                    mysqli_query($link, "UPDATE `FORM` SET activity='".($_POST['request']=="Activate" ? '1' : '0')."' WHERE formKey='".$_POST['key']."';");
                    break;
                case "Delete": 
                    mysqli_query($link, "DELETE FROM `CONTENT` WHERE formKey = '".$_POST['key']."';");
                    mysqli_query($link, "DELETE FROM `ANSWERDATE` WHERE formKey = '".$_POST['key']."';");
                    mysqli_query($link, "DELETE FROM `ANSWERLIST` WHERE formKey = '".$_POST['key']."';");
                    mysqli_query($link, "DELETE FROM `ANSWERTEXT` WHERE formKey = '".$_POST['key']."';");
                    mysqli_query($link, "DELETE FROM `ELEMENT` WHERE formKey = '".$_POST['key']."';");
                    mysqli_query($link, "DELETE FROM `FORM` WHERE formKey = '".$_POST['key']."';");
                    break;
                }


                mysqli_close($link);
                /* Evite de doubler les requêtes. */
                foreach($_POST as $key => $val)
                    unset($_POST[$key]);
                header('Location:myforms.php');
                exit();
            }
      }
?>
<!-- action="" -->
    <form id="form" method="post">
      <input type="hidden" id="request" name="request">
      <input type="hidden" id="key" name="key">
      <table id="topbar">
        <tr>
          <td>
            <h1>Vos formulaires</h1>
          </td>
          <td style='text-align:center;'>
            <button id="btn_create" type="button" onclick="createForm();">Nouveau formulaire...</button>
          </td>
          <td id="sayhello">
            <?php echo "Bienvenue, ".$session['firstname']." ".$session['lastname']; ?>
            <a href='login.php?logmeout'><button type='button'>Se déconnecter</button></a>
          </td>
        </tr>
      </table>
    <div id="trash"></div>
    <p id="notice"></p>
    <script type="text/javascript">
    </script>
    <div id="formlist_container">
    <table id="formlist">
   <?php
      if(isset($session)) {
          require('db_connect.php');
          $link = mysqli_connect($db_host, $db_login, $db_passwd, $db_base);
          if(!$link)
              die('<span style="color:red;">Incapable de se connecter !</span>');
          $result = mysqli_query($link, "SELECT title, description, formKey, creationDate, activity FROM `FORM` WHERE login = '".$session['login']."' ORDER BY creationDate DESC;");
          if($result) {
              if(mysqli_num_rows($result) == 0) {
                echo "<tr><td><span style='color:gray;'><i>Vous n'avez pas de formulaires.</i></span></td></tr>";
              }
              else {
                  echo "<tr><th>Actions</th><th>Titre</th><th>Clé</th><th>Description</th><th>Date de création</th></tr>";
                  while($enr=mysqli_fetch_assoc($result)) {
                  echo "<tr>";
                  echo "<td style='width:1px; white-space:nowrap;'>";
                  if(!(mysqli_num_rows(mysqli_query($link, "SELECT * FROM `ANSWERTEXT` WHERE formKey='".$enr['formKey']."' LIMIT 1;")) >= 1 || mysqli_num_rows(mysqli_query($link, "SELECT * FROM `ANSWERLIST` WHERE formKey='".$enr['formKey']."' LIMIT 1;")) >= 1 || mysqli_num_rows(mysqli_query($link, "SELECT * FROM `ANSWERDATE` WHERE formKey='".$enr['formKey']."' LIMIT 1;")) >= 1))
                      echo "<button type='button' onclick=\"php_request('Edit', '".$enr['formKey']."');\">Modifier</button>";
                  else
                      echo "<button type='button' onclick=\"php_request('Results', '".$enr['formKey']."');\">Resultats</button>";
                  if($enr['activity']==0)
                      echo "<button type='button' onclick=\"php_request('Activate', '".$enr['formKey']."');\">Activer</button>";
                  else {
                      echo "<button type='button' onclick=\"php_request('Deactivate', '".$enr['formKey']."');\">Périmer</button>";   
                      echo "<button type='button' onclick=\"var foo = document.location.href; foo = foo.substring(0, foo.lastIndexOf('/')) + '/form.php?formKey='; document.getElementById('notice').innerHTML = '<br><br>Pour que des personnes repondent a votre formulaire, envoyez-leur le lien suivant : <br><a href='+foo+'".$enr['formKey']."'+'>' +foo+'".$enr['formKey']."'+'</a><br><br>';\">Obtenir le lien</button>";   
                  }
                  echo "<button type='button' onclick=\"php_request('Delete', '".$enr['formKey']."');\">Supprimer</button>";
                  echo "</td>";
                  echo "<th style='width:1px; white-space:nowrap;'>".$enr['title']."</th>";

                  if($enr['activity']==1)
                      echo "<td style='width:1px; white-space:nowrap;'>".$enr['formKey']."</td>";
                  else
                      echo "<td style='width:1px; white-space:nowrap;'></td>";

                  echo "<td>".$enr['description']."</td>";
                  echo "<td style='width:1px; white-space:nowrap;'>".$enr['creationDate']."</td>";

                  echo "</tr>";
              }}
              mysqli_free_result($result);
          }

          mysqli_close($link);
      }
?>
    </table>
    </div>
  </body>
</html>
