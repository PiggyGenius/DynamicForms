function createForm() {
    
    document.getElementById('trash').style.height = "20%";
    document.getElementById('formlist_container').style.height = "72%";
    document.getElementById('btn_create').innerHTML = "Annuler";
    document.getElementById('btn_create').setAttribute('onClick', 'closeCreateForm();');
    
    var title_txt = document.createElement('p');
    title_txt.innerHTML = "Titre : ";
    var title = document.createElement('input');
    title.type="text";
    title.id="title";
    title.name="title";
    var description = document.createElement('textarea');
    description.id = "description";
    description.name = "description";
    description.rows = "4";
    description.cols = "50";
    description.placeholder = "Decrivez votre formulaire ici...";
    var create = document.createElement('button');
    create.type = "button";
    create.innerHTML = "Valider";
    create.setAttribute('onClick', 'doCreateForm();');
    document.getElementById('btn_create').setAttribute('onClick', 'closeCreateForm();');
    
    document.getElementById('trash').appendChild(title_txt);
    document.getElementById('trash').appendChild(title);
    document.getElementById('trash').appendChild(document.createElement('br'));
    document.getElementById('trash').appendChild(description);
    document.getElementById('trash').appendChild(document.createElement('br'));
    document.getElementById('trash').appendChild(create);

}

function closeCreateForm() {
    document.getElementById('trash').style.height = "0px";
    document.getElementById('formlist_container').style.height = "92%";
    document.getElementById('trash').innerHTML = "";
    document.getElementById('btn_create').innerHTML = "Nouveau formulaire...";
    document.getElementById('btn_create').setAttribute('onClick', 'createForm();');

}

function doCreateForm() {
    if(document.getElementById('title').value == '') {
        alert('Donnez au moins un titre pour votre nouveau formulaire !');
        document.getElementById('title').focus();
        return false;
    }
    document.getElementById('request').value = "Create";
    document.getElementById('form').submit();
}

function php_request(type, key) {
    switch(type) {
    case 'Activate':
    alert("Votre formulaire va devenir actif !\n"
            + "Attention !! Des qu'une personne repondra a votre formulaire, vous ne pourrez plus le modifier !");
        break;
    case 'Deactivate': 
        alert("Plus personne ne peut repondre a votre formulaire.\n"
            + "Vous pouvez le reactiver quand bon vous semble.\n");
        break;
    case 'Delete': 
        if(!confirm("Etes-vous certain(e) de vouloir supprimer ce formulaire ?\n"
            + "Il sera perdu a jamais dans /dev/null."))
            return false;
        break;
    }
    document.getElementById('request').value = type;
    document.getElementById('key').value = key;
    document.getElementById('form').submit();
}

