

function validation(){

	if (validateName() && validateSurname() &&	validateUsername() && validatePassword()){
		swal("Bravo!", "Uspješno ste registrirani","uspjeh");
	}

}



function validateName(){
	var name = document.getElementById('name').value;
	var illegalChars = "^[a-zA-Z ]+$";
	if (name == "") {

        error = "Niste unijeli ime!\n";
        sweetAlert("Oops...",error, "error");
        //alert(error);

        return false;


    } else if (illegalChars.test(name)) {

        error = "Ime sadrži ilegalne znakove!\n";
        sweetAlert("Oops...",error, "error");
		//alert(error);
		return false;

    }

    return true;

}

function validateSurname(){
	var surname = document.getElementById('surname').value;
	var illegalChars = "^[a-zA-Z ]+$";
	if (surname == "") {

        error = "Niste unijeli prezime!\n";
        sweetAlert("Oops...",error, "error");
        //alert(error);
        return false;


    } else if (illegalChars.test(surname)) {

        error = "Prezime sadrži ilegalne znakove!\n";
        sweetAlert("Oops...",error, "error");
		//alert(error);
		return false;

    }

    return true;

}

function validateUsername(){
	var illegalChars = /\W/; // allow letters, numbers, and underscores
	var username = document.getElementById('username').value;

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

    } else if (illegalChars.test(username)) {

        error = "Korisničko ime sadrži ilegalne znakove!\n";
        sweetAlert("Oops...",error, "error");
		//alert(error);
		return false;

    }

    return true;
}

function validatePassword(){
	var password = document.getElementById('password').value;

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
