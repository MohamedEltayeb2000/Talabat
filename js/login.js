function checkURL(){
	let url = document.URL;
	if(url.indexOf("UserNotFound") > -1){
		alert("Oops account not found..\nplease check your credential and try again");
	}
	else if(url.indexOf("PasswordNotFound") > -1){
		alert("Oops we ran into a problem..\npassword field can't be empty");
	}
	else if(url.indexOf("EmailNotFound") > -1){
		alert("Oops we ran into a problem..\nemail field can't be empty");
	}
}
checkURL();