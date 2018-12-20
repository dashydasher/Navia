function validation(){
	if (validateName() && validateSurname() &&	validateUsername() && validatePassword()) {
		return true;
	} else {
		return false;
	}
}

$("#submit-button").click(function(e) {
	if (validation()) {
		swal("Registracija", "Podaci su poslani", "uspjeh");
	} else {
		e.preventDefault();
	}
});

function validateName(){
	var name = $("#name").val();
	var legalChars = "^[a-zA-Z -]+$";
	if (name == "") {
        error = "Niste unijeli ime!\n";
        sweetAlert("Oops...",error, "error");
        //alert(error);
        return false;
    } else if (!name.match(legalChars)) {
        error = "Ime sadrži ilegalne znakove!\n";
        sweetAlert("Oops...",error, "error");
		//alert(error);
		return false;
    }
    return true;

}

function validateSurname(){
	var surname = $("#surname").val();
	var legalChars = "^[a-zA-Z -]+$";
	if (surname == "") {
        error = "Niste unijeli prezime!\n";
        sweetAlert("Oops...",error, "error");
        //alert(error);
        return false;
    } else if (!surname.match(legalChars)) {
        error = "Prezime sadrži ilegalne znakove!\n";
        sweetAlert("Oops...",error, "error");
		//alert(error);
		return false;
    }
    return true;

}

function validateUsername(){
	var legalChars = "^[a-zA-Z0-9_]+$"; // allow letters, numbers, and underscores
	var username = $("#username").val();

	if (username == "") {
        error = "Niste unijeli korisničko ime!\n";
        sweetAlert("Oops...",error, "error");
        //alert(error);
        return false;
    } else if ((username.length < 5) || (username.length > 15)) {
        error = "Korisničko ime je krive duljine! Skrati ga ili produži - mora imati 5-15 znakova.\n";
        sweetAlert("Oops...",error, "error");
		//alert(error);
		return false;
	} else if (!username.match(legalChars)) {
        error = "Korisničko ime sadrži ilegalne znakove!\n";
        sweetAlert("Oops...",error, "error");
		//alert(error);
		return false;
    }
    return true;
}

function validatePassword(){
	var password = $("#password").val();
	if (password == "") {
        error = "Niste unijeli lozinku!\n";
        sweetAlert("Oops...",error, "error");
        //alert(error);
        return false;
    } else if ((password.length < 6)) {
        error = "Lozinka je prekratka, treba imati minimalno 6 znakova! \n";
        sweetAlert("Oops...",error, "error");
		//alert(error);
		return false;
    }
    return true;
}
