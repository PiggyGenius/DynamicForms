<!DOCTYPE html>
<html>
  <head> 
    <link rel="stylesheet" href="app.css" type="text/css" />
    <script charset="utf-8" type="text/javascript" src="form.js"></script>
<?php

        require('db_connect.php');
        $link = mysqli_connect($db_host, $db_login, $db_passwd, $db_base);
        if(isset($_GET['formKey'])) { 
            $formKey=mysqli_real_escape_string($link,$_GET['formKey']);
            $result = mysqli_query($link, "SELECT activity FROM `FORM` WHERE formKey = '".$formKey."';");
            if(mysqli_num_rows($result) == 0)
                die("Ce formulaire n'existe pas.");
            $enr = mysqli_fetch_array($result);
            if($enr[0] == 0)
                die("Ce formulaire n'est plus actif.");


            require('form_to_array.php');
              
      	    echo '<script charset="utf-8" type="text/javascript">';
            echo 'elements = '.json_encode($elements).';'; 
            echo "key = '".$formKey."';";
            echo '</script>';
 
        } if(isset($_POST['formKey'])) {
            $formKey = mysqli_real_escape_string($link, $_POST['formKey']);
            for($i=0;$i<count($_POST)-1;$i++){
                $last_answertext = mysqli_fetch_array(mysqli_query($link, "SELECT MAX(uselessId) FROM ANSWERTEXT WHERE formKey='".$formKey."' AND indexElement='".$i."';"));
                $last_answerdate = mysqli_fetch_array(mysqli_query($link, "SELECT MAX(uselessId) FROM ANSWERDATE WHERE formKey='".$formKey."' AND indexElement='".$i."';"));
                $last_answerlist = mysqli_fetch_array(mysqli_query($link, "SELECT MAX(uselessId) FROM ANSWERLIST WHERE formKey='".$formKey."' AND indexElement='".$i."';"));
        		if(isset($_POST['TEXTFIELD'.$i]))
    	    		mysqli_query($link, "INSERT INTO ANSWERTEXT() VALUES('".$formKey."','".$i."','".mysqli_real_escape_string($link, $_POST['TEXTFIELD'.$i])."', ".($last_answertext[0]+1).");");
        		else if(isset($_POST['TEXTAREA'.$i]))
        			mysqli_query($link, "INSERT INTO ANSWERTEXT() VALUES('".$formKey."','".$i."','".mysqli_real_escape_string($link, $_POST['TEXTAREA'.$i])."', ".($last_answertext[0]+1).");");
    	    	else if(isset($_POST['DATE'.$i]))
    		    	mysqli_query($link, "INSERT INTO ANSWERDATE() VALUES('".$formKey."','".$i."','".mysqli_real_escape_string($link, $_POST['DATE'.$i])."', ".($last_answerdate[0]+1).");");
        		else if(isset($_POST['LIST'.$i]))
                    mysqli_query($link, "INSERT INTO ANSWERLIST() VALUES('".$formKey."','".$i."','".mysqli_real_escape_string($link, $_POST['LIST'.$i])."', ".($last_answerlist[0]+1).");");
                else if(isset($_POST['RADIOBUTTON'.$i]))
                    mysqli_query($link, "INSERT INTO ANSWERLIST() VALUES('".$formKey."','".$i."','".mysqli_real_escape_string($link, $_POST['RADIOBUTTON'.$i])."', ".($last_answerlist[0]+1).");");
                else if(isset($_POST['CHECKBOX'.$i])){
                     for($j=0;$j<count($_POST['CHECKBOX'.$i]);$j++) {
                         mysqli_query($link, "INSERT INTO ANSWERLIST() VALUES('".$formKey."','".$i."','".mysqli_real_escape_string($link, $_POST['CHECKBOX'.$i][$j])."', ".($last_answerlist[0]+1).");");
                         $last_answerlist[0]++;
                     }
                }
                else continue;
            }
            header("Location:thankyou.html"); /* A commenter absolument lors du debuggage */
            exit(); /* idem */
        }
        mysqli_close($link);

    ?>

	<meta  charset="utf-8" />
    <title>Répondre au formulaire</title>
  </head>
  <body onload='updatePreview();'>
    <form id="form_container" method="post">
    </form>
  </body>
</html>
