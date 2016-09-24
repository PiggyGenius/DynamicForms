<?php
    if(isset($_GET['formKey'])) {
	    $formKey=mysqli_real_escape_string($link, $_GET['formKey']);
    	$form = mysqli_query($link, "SELECT indexElement,label,help,type FROM ELEMENT WHERE formKey=\"".$formKey."\" ORDER BY(indexElement)");
        $elements = array();
    	while($result=mysqli_fetch_assoc($form)){
	    	$elements[$result['indexElement']][0]=$result['label'];
		    $elements[$result['indexElement']][1]=$result['help'];
        
            /* Pour la concordance avec le javascript */

            switch($result['type']) {
            case 'DATE': $elements[$result['indexElement']][2] = "DateInput"; break;
            case 'TEXTAREA': $elements[$result['indexElement']][2] = "TextArea"; break;
            case 'TEXTFIELD': $elements[$result['indexElement']][2] = "TextField"; break; 
            case 'LIST': $elements[$result['indexElement']][2] = "List"; break; 
            case 'CHECKBOX': $elements[$result['indexElement']][2] = "CheckBoxGroup"; break; 
            case 'RADIOBUTTON': $elements[$result['indexElement']][2] = "RadioButtonGroup"; break;
            }
            /*    */

	    	if($result['type']=="LIST"||$result['type']=="CHECKBOX"||$result['type']=="RADIOBUTTON"){
		    	$form_list = mysqli_query($link, "SELECT value FROM `CONTENT` WHERE indexElement='".$result['indexElement']."' AND formKey='".$formKey."' ORDER BY(indexContent);");			
			    for($elements_list=array(), $i=0;$result_list=mysqli_fetch_assoc($form_list);$i++){
				    $elements_list[$i]=$result_list['value'];
    			}
	    		$elements[$result['indexElement']][3]=$elements_list;
		    }
        }
    }
?>
