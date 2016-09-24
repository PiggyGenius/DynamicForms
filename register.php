<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="register.css" type="text/css" />
	<script type="text/javascript" src="register_check.js"></script>
    <meta charset="latin" />
    <title>Inscription</title>
  </head>
  <body><!-- action="" -->
    <form name="inscription" method="post" onsubmit="return check('all');">
	  <table>
	  <tr><td>Adresse email :</td><td><input type="email" id="email" name="email" oninput="check('email');"></td><td id="email_msg"></td></tr>
	  <tr><td>Login :</td><td><input type="text" id="login" name="login" oninput="check('login');"></td><td id="login_msg"></td></tr>
      <tr><td>Mot de passe :</td><td><input type="password" id="passwd" name="passwd" oninput="check('passwd');"></td><td id="passwd_msg"></td></tr>
      <tr><td>Confirmer le mot de passe :</td><td><input type="password" id="passwd2" name="passwd2" oninput="check('passwd2');"></td><td id="passwd2_msg"></td></tr>
      <tr><td>Nom :</td><td><input type="text" id="lastname" name="lastname" oninput="check('lastname');"></td><td id="lastname_msg"></td></tr>
      <tr><td>Prénom :</td><td><input type="text" id="firstname" name="firstname" oninput="check('firstname');"></td><td id="firstname_msg"></td></tr>
      <tr><td>Année de Naissance :</td><td><input type="date" name="birthdate" id="birthdate"></td></tr>
      <tr><td></td><td><input type="submit" value="Inscription"></td></tr>
	  </table>
    </form>
    <?php

if(isset($_POST['login'])) {
        require('db_connect.php');
        $link = mysqli_connect($db_host, $db_login, $db_passwd, $db_base);
        if(!$link)
            die("<p>Incapable de se connecter !</p>");
        foreach($_POST as $key => $val) {
          $_POST[$key] = mysqli_real_escape_string($link, $_POST[$key]);
        } 
        extract($_POST);
        $canregister = true;
        $result = mysqli_query($link, "SELECT login FROM `USER` WHERE login='".$login."';");
        if(mysqli_num_rows($result) > 0) {
            $canregister = false;
            echo "<script type=\"text/javascript\">";
            echo "document.getElementById('login_msg').innerHTML = \"<span style='color:red;'>Ce login est déjà utilisé.</span>\"";
            echo "</script>";
        }
        mysqli_free_result($result);
        $result = mysqli_query($link, "SELECT login FROM `USER` WHERE mail='".$email."';");
        if(mysqli_num_rows($result) >= 3) {
            $canregister = false;
            echo "<script type=\"text/javascript\">";
            echo "document.getElementById('email_msg').innerHTML = \"<span style='color:red;'>Cet email a déjà été utilisé 3 fois.</span>\"";
            echo "</script>";
         
        }
        mysqli_free_result($result);
        if($canregister) {
          date_default_timezone_set("Europe/Paris");
          $result = mysqli_query($link, "INSERT INTO `USER`(`login`, `mail`, `pwdHash`, `privileges`, `lastName`, `firstName`, `birthDate`, `lastLogin`) VALUES ('".$login."','".$email."','".sha1($passwd)."','User','".$lastname."','".$firstname."',". ($birthdate=='' ? 'NULL' : "'".$birthdate."'") .",'".date("Y-m-d")."');");
          mysqli_free_result($result);
          setcookie('session', serialize(mysqli_fetch_assoc(mysqli_query($link, "SELECT login, mail, privileges, lastname, firstname, lastlogin FROM `USER` WHERE login='".$login."' AND pwdhash = '".sha1($passwd)."';"))));
          mysqli_close($link);
          header('Location:myforms.php');
          exit();
        }
        mysqli_close($link);
      }
    ?>
 
  </body>
  <?php
      if(isset($canregister))
      if(!$canregister) {
        echo "<script type=\"text/javascript\">";
        foreach(array('login' => $login, 'email' => $email, 'passwd' => $passwd, 'passwd2' => $passwd2, 'lastname' => $lastname, 'firstname' => $firstname) as $key => $val)
            echo "document.getElementById('".$key."').value = '".$val."';";
        echo "</script>";
      }
  ?>
</html>
