<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="app.css" type="text/css" />
    <meta charset="latin" />
    <title>Resultats</title>
  </head>
  <body>
    <div id="form_toolbar"><!--<a href="myforms.php"><button type="button">Retour</button></a>-->
<form method="GET" action="myforms.php">
    <input type="submit" value="Retour"/>
</form>
</div>
    <div id="form_container">
    <?php
      if(!isset($_COOKIE['session'])) {
        header("Location:login.php");
        exit();
      }
      if(!isset($_GET['formKey'])) {
        header("Location:myforms.php");
        exit();
      }
      
      $session = unserialize($_COOKIE['session']);
      require('db_connect.php');
      $link = mysqli_connect($db_host, $db_login, $db_passwd, $db_base);

      /* Vérifier si ce formulaire est à nous */
      $formKey = mysqli_real_escape_string($link, $_GET['formKey']);
      if(mysqli_num_rows(mysqli_query($link, "SELECT formKey FROM `FORM` WHERE formKey='".$formKey."' AND login='".$session['login']."';")) == 0) {
        mysqli_close($link);
        die("<span style='color:red;'>Vous n'avez pas les droits de lecture sur ce formulaire.<br>Veuillez vous assurer que la clef soit correcte.</span>");
      } else {
        $result = mysqli_query($link, "SELECT formKey, indexElement, label, help, type FROM `ELEMENT` WHERE formKey='".$formKey."' ORDER BY indexElement ASC;");
        while($elem = mysqli_fetch_assoc($result)) {
          echo "<br><br>";
          echo "<p style='color:red;'>".$elem['label']."</p>";
          echo "<p style='color:red;'><i>".$elem['help']."</i></p>";
          switch($elem['type']) {
            case 'TEXTAREA':
            case 'TEXTFIELD': 
                $req = mysqli_query($link, "SELECT value FROM `ANSWERTEXT` WHERE formKey='".$formKey."' AND indexElement='".$elem['indexElement']."';");
                if(mysqli_num_rows($req)==0)
                    echo "<span style='color:blue'><i>Il n'y a pas eu de réponse à cette question.</i></span>";
                else while($txt = mysqli_fetch_assoc($req))
                    echo "<p>".$txt['value']."</p>";
                break;
            
            case 'CHECKBOX':
            case "RADIOBUTTON":
            case 'LIST': 
                $req = mysqli_query($link, "SELECT indexContent FROM `CONTENT` WHERE formKey='".$formKey."' AND indexElement='".$elem['indexElement']."' ORDER BY indexContent DESC LIMIT 1;");
                $enr = mysqli_fetch_assoc($req);
                $num_opts = 1 + $enr['indexContent'];
                $totals = array();
                for($opt=0 ; $opt<$num_opts ; $opt++) {
                    $req = mysqli_query($link, "SELECT COUNT(*) FROM `ANSWERLIST` WHERE formKey='".$formKey."' AND indexElement='".$elem['indexElement']."' AND indexAnswer='".$opt."';");
                    $enr = mysqli_fetch_array($req);
                    $totals[$opt] = $enr[0];
                }
                if(array_sum($totals)==0)
                     echo "<span style='color:blue'><i>Il n'y a pas eu de réponse à cette question.</i></span>";
                else for($opt=0 ; $opt<$num_opts ; $opt++) {
                    $opt_total = $totals[$opt];
                    $opt_percentage = $opt_total/array_sum($totals)*100;
                    $req = mysqli_query($link, "SELECT value FROM `CONTENT` WHERE formKey='".$formKey."' AND indexElement='".$elem['indexElement']."' AND indexContent='".$opt."';");
                    $enr = mysqli_fetch_assoc($req);
                    echo $enr['value']." : ".round($opt_percentage, 1)."% (".$opt_total.")<br>";
                }
                break;
            case 'DATE': 
                $req = mysqli_query($link, "SELECT value FROM `ANSWERDATE` WHERE formKey='".$formKey."' AND indexElement='".$elem['indexElement']."';");
                if(mysqli_num_rows($req)==0)
                    echo "<span style='color:blue'><i>Il n'y a pas eu de réponse à cette question.</i></span>";
                else while($txt = mysqli_fetch_assoc($req))
                    echo "<p>".$txt['value']."</p>";
                break;
          }
        }
      }

      mysqli_close($link);
    

?>
    </div>
  </body>
</html>
