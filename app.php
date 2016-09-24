<!DOCTYPE html>
<html>
  <head> 
    <link rel="stylesheet" href="app.css" type="text/css" />
    <script charset="utf-8" type="text/javascript" src="app.js"></script>
	<meta http-equiv="Content-Type" content="application/json"; charset="utf-8" />
    <title>Votre formulaire</title>
  </head>
  <body onload='updatePreview();'>
    <form id="input" name="input" method="post">

<?php
      if(!isset($_COOKIE['session'])) {
        header("Location:login.php");
        exit();
      }
      if(!isset($_GET['formKey'])) {
        header("Location:myforms.php");
        exit();
      }
      if(isset($_POST['elements'])) {
          require('db_connect.php');
          $link = mysqli_connect($db_host, $db_login, $db_passwd, $db_base);
          
          $session = unserialize($_COOKIE['session']);
          $elements = json_decode($_POST['elements']);
          $formKey = mysqli_real_escape_string($link, $_GET['formKey']);

          $result = mysqli_query($link, "SELECT formKey FROM `FORM` WHERE formKey='".$formKey."' AND login='".$session['login']."';");
          
          if(mysqli_num_rows($result) == 0) {
            echo "<span style='color:red;'>Vous n'avez pas les droits de modification sur ce formulaire.<br>Assurez-vous que la clé soit correcte.</span>";
          } else {
        
              mysqli_query($link, "DELETE FROM `CONTENT` WHERE formKey = '".$formKey."';");
              mysqli_query($link, "DELETE FROM `ANSWERDATE` WHERE formKey = '".$formKey."';");
              mysqli_query($link, "DELETE FROM `ANSWERLIST` WHERE formKey = '".$formKey."';");
              mysqli_query($link, "DELETE FROM `ANSWERTEXT` WHERE formKey = '".$formKey."';");
              mysqli_query($link, "DELETE FROM `ELEMENT` WHERE formKey = '".$formKey."';");
 
              for($index=0 ; $index<count($elements) ; $index++) {
                  $label = mysqli_real_escape_string($link, $elements[$index][0]);
                  $help  = mysqli_real_escape_string($link, $elements[$index][1]);
                  $type  = mysqli_real_escape_string($link, $elements[$index][2]);
                  if($type=='CheckBoxGroup')
                      $type='CHECKBOX';
                  else if($type=='RadioButtonGroup')
                      $type='RADIOBUTTON';
                  else if($type=='DateInput')
                      $type='DATE';
                  mysqli_query($link, "REPLACE INTO `ELEMENT` (`formKey`, `indexElement`, `label`, `help`, `type`)  VALUES ('".$formKey."', '".$index."', '".$label."', '".$help."', '".$type."');");
                  if($type=='CHECKBOX'||$type=='RADIOBUTTON'||$type=='List') {
                      $opts = $elements[$index][3];
                      for($opt_index=0 ; $opt_index<count($opts) ; $opt_index++) {
                          mysqli_query($link, "REPLACE INTO `CONTENT` (`formKey`, `indexElement`, `indexContent`, `value`) VALUES ('".$formKey."', '".$index."', '".$opt_index."', '".mysqli_real_escape_string($link, $opts[$opt_index])."');");
                      }
                  }
            }
          
          }

          mysqli_close($link);
          /* On redonne le tableau au javascript. Vu qu'il actualise la page il va tout effacer. 
          ** C'est tellement bourrin le PHP et le javascript */
  		  echo '<script charset="utf-8" language="javascript" type="text/javascript">';
          echo 'elements = '.json_encode($elements).';'; 
          echo 'has_saved = true;';
          echo '</script>';

          } else {


              /* On rapatrie le formulaire depuis la BD ! */
          require('db_connect.php');
          $link = mysqli_connect($db_host, $db_login, $db_passwd, $db_base);
          require('form_to_array.php');
          
          mysqli_close($link);

  		  echo '<script charset="utf-8" language="javascript" type="text/javascript">';
          echo 'elements = '.json_encode($elements).';'; 
          echo 'has_saved = true;';
          echo '</script>';
        
      }
    ?>

      <div id="form_toolbar">
	    <button type="button" onclick="reallyQuit();">Terminer</button>
	    <input type="submit" value="Sauvegarder">
	    <button type="button" onclick="addElement();">Ajouter un élément...</button>
        <input type="hidden" id="elements" name="elements">
      </div>
      <div id="form_container"></div>
      <div id="form_tmp"></div>
    </form> 
  </body>
</html>
