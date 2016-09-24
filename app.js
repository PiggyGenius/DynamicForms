/* Note à Ludo :
** Trop souvent, le innerHTML ne suffit pas à bien gérer les éléments. 
** En gros, là où j'utilise des longues méthodes chiantes au lieu du innerHTML, c'est que y'avait un problème que le innerHTML causait. */


var elements = [];
var current_id = 0;
var has_saved = true;

/* Le p'tit code ci-dessous nous permet de poster le tableau elements comme variable post.
** L'élément d'id 'elements' est un <input type="hidden"> que PHP recevra sous le nom "elements".
** On ignorera les autres variables POST. */

/*
window.onload = function() {
    var form = document.getElementById('input');
    form.addEventListener('submit', function(){
        document.getElementById('elements').value = JSON.stringify(elements);
    });
}
*/

function reallyQuit() {
    if(!has_saved) {
        if(confirm("Certains changements n'ont pas été sauvegardés. Voulez-vous vraiment quitter sans sauvegarder ?"))
	        window.open("myforms.php", "_self");
        else 
	        ;
    } else {
		window.open("myforms.php", "_self");
	}
}

function updatePreview() {
	
    document.getElementById('elements').value = JSON.stringify(elements);
 
    var container = document.getElementById('form_container');
	var new_elem;
    var i, j;

    container.innerHTML = elements.length > 0 ? '' : "<span style=\"color:gray; text-align:center;\"><i>Ce formulaire est vide.</i></span>";

    for(i=0 ; i<elements.length ; i++) {
    container.innerHTML += "<div class=\"element\" id=\"elem"+i+"\">"
	+"<button onclick=\"moveElementUp("+i+")\" type=\"button\">Monter</button>"
	+"<button onclick=\"moveElementDown("+i+");\" type=\"button\">Descendre</button>"
	+"<button onclick=\"removeElement("+i+");\" type=\"button\">Supprimer</button>"
	+"<br></div>";
	new_elem = document.getElementById('elem'+i);
	/* Ajout de la Question et de l'aide si il y a. Celui qui a inventé les opérateurs ternaires est un Dieu. */
	new_elem.innerHTML += elements[i][0] + "<br>" + ( elements[i][1]!='' ? ("<span style=\"color:gray;\"><i>" + elements[i][1] + "</i></span><br>") : '');

	switch(elements[i][2]) {

	case "TextField": 
	    new_elem.innerHTML += "<input type=\"text\"><br>";
	    break;
	    
	case "TextArea":
	    new_elem.innerHTML += "<textarea rows=\"4\" cols=\"50\"></textarea><br>";
	    break;

	case "DateInput":  
	    new_elem.innerHTML += "<input type=\"date\"><br>";
	    break;

	case "List":  
	    new_elem.innerHTML += "<select id=\"select_tmp\"></select>";
		var foo;
		/* Mon Dieu c'que c'est moche - je peux pas compter sur le innerHTML pour ajouter les options, wtf */
	    for(j=0 ; j<elements[i][3].length ; j++) {
		    foo = document.createElement('option');
		    foo.text = elements[i][3][j];
		    document.getElementById('select_tmp').add(foo);
	    }
		document.getElementById('select_tmp').id = ''; /* On libère l'id pour une prochaine liste. */
	    break;

	case "CheckBoxGroup": 
	    for(j=0 ; j<elements[i][3].length ; j++)
		    new_elem.innerHTML += "<input type=\"checkbox\">" + elements[i][3][j] + "<br>";
	    break;
	    
	case "RadioButtonGroup": 
	    for(j=0 ; j<elements[i][3].length ; j++)
		    new_elem.innerHTML += ("<input type=\"radio\" name=\"" /* le nom est nécéssaire pour prévenir les autres boutons radio. */
					+ elements[i][0] + "\">" + elements[i][3][j] + "<br>");
	    break;

	}
	
    }
}

function updateElementsArray(type) {
    var options = [];
	elements[elements.length] = [];
    if(type=="List"||type=="CheckBoxGroup"||type=="RadioButtonGroup") {
	    var i, j;
	    for(j=0, i=0 ; i<current_id; i++)
	        if(document.getElementById('option'+i) != null)
		        if(document.getElementById('option'+i).value != '') {
		            options[j] = document.getElementById('option'+i).value;
    		        j++;
        		}   
    	elements[elements.length-1][3] = options; 
    }
    elements[elements.length-1][0] = document.getElementById('label').value;
    elements[elements.length-1][1] = document.getElementById('help').value;
    elements[elements.length-1][2] = type;
}

function insertElement(type) {
    if(document.getElementById('label').value == '') {
	    alert('Veuillez compléter le texte de la question.');
		document.getElementById('label').focus();
		return false;
	}
    for(i=0 ; i<current_id ; i++)
        if(document.getElementById('option'+i) != null)
            if(document.getElementById('option'+i).value == "") {
                alert("Une des options est vide. Veuillez corriger ça ou la supprimer.");
                document.getElementById('option'+i).focus();
                return false;
            }
    updateElementsArray(type);
    updatePreview();
	closeAddElement();
	has_saved = false;
}

function removeElement(index) {
    var i, j;
	for(i=index ; i<(elements.length-1) ; i++)
        elements[i] = elements[i+1];
    elements.splice(i, 1);
    updatePreview();
	has_saved = false;
}

function moveElementUp(index) {
    if(index > 0) {
        var tmp = elements[index-1];
		elements[index-1] = elements[index];
		elements[index] = tmp;
	}
	updatePreview();
    has_saved = false;
}

function moveElementDown(index) {
    if(index < (elements.length-1)) {
        var tmp = elements[index+1];
		elements[index+1] = elements[index];
		elements[index] = tmp;
	}
	updatePreview();
	has_saved = false;
}

function removeOption(id) {
    var foo = document.getElementById('option'+id);
    foo.parentNode.removeChild(foo);
    foo = document.getElementById('btn_rmoption'+id);
    foo.parentNode.removeChild(foo);
    foo = document.getElementById('optionbr'+id);
    foo.parentNode.removeChild(foo);
}

function addOption(id) {
    current_id++;
    if(id > 0) {
	var btn = document.getElementById('btn_add');
	btn.parentNode.removeChild(btn);
    }
	   
	var opt = document.createElement('input');
	opt.type = "text";
	opt.id = "option"+id;
    var rm = document.createElement('button');
    rm.type = "button";
    rm.id =	"btn_rmoption"+id;
	rm.setAttribute("onClick", "removeOption("+id+");");
	rm.innerHTML = 'Retirer ce choix';
	var br = document.createElement('br');
	br.id = "optionbr"+id;
	var add = document.createElement('button');
	add.type = "button";
	add.id = "btn_add";
	add.setAttribute("onClick", "addOption("+(id+1)+");");
	add.innerHTML = "Ajouter un choix";
	
	document.getElementById('trash').appendChild(opt);
	document.getElementById('option'+id).focus();
	if(id > 0) {
	    document.getElementById('trash').appendChild(rm);
		document.getElementById('trash').appendChild(br);
    } else { document.getElementById('trash').appendChild(document.createElement('br')); }
	document.getElementById('trash').appendChild(add);
}

function editList(type) {
    document.getElementById('trash').innerHTML = "";
    if(type=='List' || type=='CheckBoxGroup' || type=='RadioButtonGroup')
	addOption(0);
}

function closeAddElement() {
    current_id = 0;
    document.getElementById('form_container').style.height = "95%";
    document.getElementById('form_tmp').innerHTML = "";
    document.getElementById('form_tmp').style.height = "0%";
    document.getElementById('form_tmp').style.minHeight = "0px";
}

function addElement() {
    document.getElementById('form_container').style.height = "60%";
    document.getElementById('form_tmp').style.height = "35%";
    document.getElementById('form_tmp').style.minHeight = "140px";
    document.getElementById('form_tmp').innerHTML = 
	("<table style=\"text-align:center;\">"
	 +"<tr><td>Question</td><td><input type=\"text\" id=\"label\"></td></tr>"
	 +"<tr><td>Aide</td><td><input type=\"text\" id=\"help\"></td></tr>"

	 /* Note : onclick sur une option ne marche pas sous Chrome. Du coup, il faut utiliser onchange sur le select à la place. C'est moche mais pas d'autre moyen. */

	 +"<tr><td>Type de réponse</td><td><select id=\"type\" onchange=\"editList(this.value);\">"
	 +"<option value=\"TextField\">Champ de texte</option>"
	 +"<option value=\"TextArea\">Zone de texte</option>"
	 +"<option value=\"List\">Liste déroulante</option>"
	 +"<option value=\"CheckBoxGroup\">Cases à cocher</option>"
	 +"<option value=\"RadioButtonGroup\">Choix unique</option>"
	 +"<option value=\"DateInput\">Entrée de date</option>"
	 +"</select></td></tr>"
	 +"</table>"
	 +"<div id=\"trash\"></div>"
	 +"<input type=\"button\" style=\"padding:5px;\" value=\"Annuler\" onclick=\"closeAddElement();\">"
	 +"<input type=\"button\" style=\"padding:5px;\" value=\"Valider\" onclick=\"insertElement(document.getElementById('type').value);\">"
	);
	document.getElementById('label').focus();
}
