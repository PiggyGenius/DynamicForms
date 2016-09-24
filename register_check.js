function check(type) {
    var res = true;
    switch(type) {
	case 'all':
	case "email":
	    if(document.getElementById('email').value==undefined 
		   || ! new RegExp('^[a-z0-9]+([_|\.|-]{1}[a-z0-9]+)*@[a-z0-9]+([_|\.|-]{1}[a-z0-9]+)*[\.]{1}[a-z]{2,6}$', 'i').test(document.getElementById('email').value))
	        {document.getElementById('email_msg').innerHTML = "<span style='color:red;'>Syntaxe incorrecte.</span>"; res = false;}
        else
            document.getElementById('email_msg').innerHTML = '';
		if(type!='all')break;
	case "login":
	    if(document.getElementById('login').value=='') {
		    document.getElementById('login_msg').innerHTML = "<span style='color:red;'>Ne peut être vide.</span>";
			res = false;
	    } else
            document.getElementById('login_msg').innerHTML = '';
	    if(type!='all')break;
	case "passwd":
	    var min = 6;
	    if(document.getElementById('passwd').value.length < min) {
		    document.getElementById('passwd_msg').innerHTML = "<span style='color:red;'>Ne peut faire moins de "+min+" caractères.</span>";
			res = false;
	    } else
           document.getElementById('passwd_msg').innerHTML = "";
	    if(type!='all')break;
	case "passwd2":
	    if(document.getElementById('passwd2').value != document.getElementById('passwd').value) {
		    document.getElementById('passwd2_msg').innerHTML = "<span style='color:red;'>Les deux mots de passe ne correspondent pas.</span>";
			res = false;
	    } else document.getElementById('passwd2_msg').innerHTML = '';
	    if(type!='all')break;
	case "lastname":
	    if(document.getElementById('lastname').value == '') {
		    document.getElementById('lastname_msg').innerHTML = "<span style='color:red;'>Ne peut être vide.</span>";
			res = false;
	    } else
            document.getElementById('lastname_msg').innerHTML = '';
		if(type!='all')break;
	case "firstname":
	    if(document.getElementById('firstname').value == '') {
		    document.getElementById('firstname_msg').innerHTML = "<span style='color:red;'>Ne peut être vide.</span>";
			res = false;
	    } else
            document.getElementById('firstname_msg').innerHTML = '';
	    if(type!='all')break;
	}
	return res;
}
/*
      <tr><td>Login :</td><td><input type="text" name="login" onblur="check('login');"></td></tr>
      <tr><td>Mot de passe :</td><td><input type="password" name="passwd" onblur="check('passwd');></td></tr>
      <tr><td>Confirmer le mot de passe :</td><td><input type="password" name="passwd2" onblur="check('passwd2');></td></tr>
      <tr><td>Nom :</td><td><input type="text" name="lastname" onblur="check('lastname');></td></tr>
      <tr><td>Prénom :</td><td><input type="text" name="firstname" onblur="check('firstname');></td></tr>
	  */
