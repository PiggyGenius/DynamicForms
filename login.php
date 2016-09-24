<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="login.css" type="text/css" />
    <meta charset="latin" />
    <title>Connexion</title>
<?php
      if(isset($_COOKIE['session'])) {
          if(isset($_GET['logmeout'])) {
            unset($_COOKIE['session']);   
            setcookie('session', null, time()-3600);
        } else {
            header('Location:myforms.php');
            exit();
        }
      }
    ?>
  </head>
  <body onload="document.getElementById('login').focus();">
    <noscript>
	  Vous �tes une bien brave personne pour avoir d�sactiv� Javascript.
	  J'ai l'immense honneur de vous attribuer la m�daille du Warrior de l'internet.
	  <img src="javascript_disabled.gif" alt="Quel courage">
	</noscript>
   <div id="presentation">
    </div><!-- @Comment� pour pas qu'il y aie le caract�re 'espace' entre les deux
 --><div id="loginbar">
      <div><!-- Sert � aligner les champs de texte au milieu du ruban ! --></div>
        <form name="log_in" method="post">
          Login :<br><input type="text" id="login" name="login"><br>
          Mot de passe :<br><input type="password" name="passwd"><br>
          <input type="checkbox" name="rememberme" value="true">Se souvenir de moi<br>
          <input type="submit" value="Connexion">
          <?php
            extract($_POST);
            if(isset($login)) {
              require('db_connect.php');
              $link = mysqli_connect($db_host, $db_login, $db_passwd, $db_base);
              if(!$link)
                  die("<p>Incapable de se connecter !</p>");
              $login = mysqli_real_escape_string($link, $login);
              $passwd= mysqli_real_escape_string($link, $passwd);
              $result = mysqli_query($link, "SELECT login, mail, privileges, lastname, firstname, lastlogin FROM `USER` WHERE login='".$login."' AND pwdhash = '".sha1($passwd)."';");
              if(!$result)
                  echo 'Il y a une erreur SQL.';
              if(mysqli_num_rows($result) == 1) {
                  date_default_timezone_set("Europe/Paris");
                  mysqli_query($link, "UPDATE `USER` SET `lastLogin`='".date("Y-m-d")."' WHERE `login`='".$login."';");
                  if(isset($rememberme))
                      setcookie('session', serialize(mysqli_fetch_assoc($result)), time()+60*60*24*365*2); /* 2 ans lol */
                  else 
                      setcookie('session', serialize(mysqli_fetch_assoc($result))); 
                  mysqli_free_result($result);
                  mysqli_close($link);
                  header('Location:myforms.php');
                  exit();
              }
              else
                echo "<br><span style=\"color:red;\">Login ou mot de passe incorrects.</span>";
              mysqli_free_result($result);
              mysqli_close($link);
            }
        ?>
        </form>
        Vous n'avez pas de compte ? <a href="register.php">Inscrivez-vous.</a><br><br>
        <div style="color:red;">
          Ce site admet que vous...<br><ul style="list-style-type:none;"> 
          <li>...Acceptez les cookies;</li>
          <li>...Utilisez un navigateur moderne comme Firefox ou Google Chrome;</li>
          <li>...N'�tes pas sur un petit �cran.</li>
      </ul></div>
    </div>
  </body>
</html>
