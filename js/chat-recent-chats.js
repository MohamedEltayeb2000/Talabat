//on click on recent chat

// on clicking on recent chat object
function onRecentChatClick(event){
	//to precent loading
	event.preventDefault();

	//to show right side(chat field)
	document.getElementsByClassName("right-side")[0].style.display = "block";
	

	var chat_field = document.getElementsByClassName("chat-field")[0];

	//###### to get clikced object
	var target = event.target;
	if(target.tagName == "H4"){
		target = target.parentNode.parentNode.parentNode;
	}
	else if(target.tagName == "DIV" && target.className == "media-body" || target.tagName == "P"){
		target = target.parentNode.parentNode;
	}
	else if(target.tagName == "IMG"){
		target = target.parentNode.parentNode.parentNode;
		console.log(target);
	}
	else{
		target = target.parentNode;
	}

	//document.getElementById("chat_field_image_header");
	var temp_data = JSON.parse(localStorage.getItem("data"));
	if(temp_data["type"][target.id] == "user"){
		document.getElementsByClassName("message-header")[0].innerHTML = "<div class=\"media\"><span class=\"back-btn d-block d-sm-none\"><i class='fa fa-arrow-left'></i></span><a class=\"media-left\" href=\""+ "#" +"\"><img id=\"chat-field_image_header\" src=\""+ "img/1.jpg" +"\" alt=\"profile-image\"></a><div id=\"chat_field_header\"><h4>"+ temp_data["users"][target.id] +"</h4></div></div>";
	}
	else{
		document.getElementsByClassName("message-header")[0].innerHTML = "<div class=\"media\"><span class=\"back-btn d-block d-sm-none\"><i class='fa fa-arrow-left'></i></span><a class=\"media-left\" href=\"chef.php?id="+ temp_data["chats"][target.id] +"\"><img id=\"chat-field_image_header\" src=\""+ temp_data["photo"][target.id] +"\" alt=\"profile-image\"></a><div id=\"chat_field_header\"><h4 >"+ temp_data["users"][target.id] +"</h4></div></div>";
	}
	document.getElementsByTagName("title")[0].innerHTML = temp_data["users"][target.id];


	//change background color and font-color of recent chat
	changeBackAndFontColor(target);

	//save id of clicked chat
	localStorage.setItem("chat_number", target.id);

	//if window is xs
	if(window.innerWidth < 576){
		document.getElementsByClassName("back-btn")[0].style.setProperty("display", "inline-block", "important");
		document.getElementsByClassName("back-btn")[0].addEventListener("click", onBtnClick);
		document.getElementsByClassName("right-side")[0].style.display = "block";
		document.getElementsByClassName("recent-chats")[0].style.display = "none";
	}


	//get old messages after clicking on specific object
	getMessages(parseInt(target.id));
	var msgs = sessionStorage.getItem("old_messages");
	printMessages(msgs, chat_field, temp_data, parseInt(target.id));
}

//function to put messages
function printMessages(msgs, chat_field, temp_data, user){
	if(typeof msgs !== "undefined" && msgs != "nothing"){
		var last_msg, this_day, this_month, this_year, day, month, year;
		var date = new Date();

		this_day = date.getDate();
		this_month = date.getMonth() + 1;
		this_year = date.getFullYear();
		var today = dayOfTheYear(this_day, this_month, this_year);

		day = date.getDate();
		month = date.getMonth() + 1;
		year = date.getFullYear();


		var message = "";
		var message_separator = "";
		var day_of_year;

		chat_field.innerHTML = "";
		msgs = JSON.parse(msgs);
		for (var i = 0; i < msgs["messages"].length; i++) {
			last_msg = msgs["messages"][i];
			msg_day_of_year = dayOfTheYear(last_msg["day"], last_msg["month"], last_msg["year"]);
			day_of_year = dayOfTheYear(day, month, year);

			if(msg_day_of_year[1] == day_of_year[1] && last_msg["year"] == year && i != 0){
			}
			else{
				if(msg_day_of_year[1] == today[1] && last_msg["year"] == this_year){
					message_separator = "<div class=\"date-seprator\"><h4>Today</h4></div>" + "\n";
				}
				else if(msg_day_of_year[1] + 1 == today[1] && last_msg["year"] == this_year){
					message_separator = "<div class=\"date-seprator\"><h4>Yesterday</h4></div>" + "\n";
				}
				else if(last_msg["year"] + 1 == this_year && msg_day_of_year[1] ==  msg_day_of_year[0] && today == 1){
					message_separator = "<div class=\"date-seprator\"><h4>Yesterday</h4></div>" + "\n";
				}
				else{
					message_separator = "<div class=\"date-seprator\"><h4>"+last_msg["day"]+"/"+last_msg["month"]+"/"+last_msg["year"]+"</h4></div>" + "\n";
				}
				chat_field.insertAdjacentHTML("afterbegin", message_separator);
			}

			if(msgs["messages"][i]["user_from"]["id"] == "0" || msgs["messages"][i]["user_from"]["id"] == 0){
				let end = msgs["messages"][i]["message"][0].length;
				let name, quantity, price;
				let sum = 0;
				message = "<div class=\"order-message\"><br>";
				for (var x = 0; x < end ; x++) {
					name = msgs["messages"][i]["message"][0][x]["name"];
					quantity = parseInt(msgs["messages"][i]["message"][0][x]["quantity"]);
					offer = parseInt(msgs["messages"][i]["message"][0][x]["offer"])
					price = parseFloat(msgs["messages"][i]["message"][0][x]["price"]);
					price = Number(Math.round((price - price * (offer/100)) + "e" + 2) + "e-" + 2);
					total = Number(Math.round(price * quantity + "e" + 2) + "e-" + 2); 
					sum =  Number(Math.round(sum + total + "e" + 2) + "e-" + 2);
					message += "<hr><h4>Order: "+ name +"<br>Quantity: "+ quantity +"<br>Price: "+ price + "<br>Total for one meal: "+ total +"</h4><hr>";
				}
				message +="<h4>Total: "+ sum +"</h4><br></div>";
			}
			else if(msgs["messages"][i]["user_from"]["id"] == "-1" || msgs["messages"][i]["user_from"]["id"] == -1 && msgs["messages"][i]["user_from"]["user_id"] == temp_data["id"]){
				message = "<form method=\"POST\" action=\"backend/rate.php?from="+ temp_data["id"] +"&to="+ msgs["messages"][i]["user_to"]["id"] + "&order=" + msgs["messages"][i]["message"] + "\" class=\"review\"><div class=\"rating-message\"><br><h4>Leave a rating for the chef</h4><h4>From 0 to 5</h4><label>Rating: <input type=\"number\" name=\"rate\" step=\"0.1\" min=\"0\" max=\"5\"></label><button type=\"submit\">Submit</button><br><br></div></form>";
			}
			else if(msgs["messages"][i]["user_from"]["id"] == "-2" || msgs["messages"][i]["user_from"]["id"] == -2){
				message = "<div class=\"rating-message\"><br><h4>the rate for the resturant ***"+ msgs["messages"][i]["user_to"]["restname"] +"*** is : "+ msgs["messages"][i]["message"] +"</h4><br></div>";
			}
			else if(msgs["messages"][i]["user_from"]["id"] == temp_data["id"]){
				message = "<div class=\"you\"><div class=\"message-content\"><div class=\"message-text\">"+ msgs["messages"][i]["message"] +"</div><div class=\"message-time\">"+ msgs["messages"][i]["time"] +"</div></div></div>";
			}
			else{
				if(temp_data["type"][user] == "user"){
					message = "<div class=\"other message\"><div class=\"message-content\"><a href=\""+ "" +"\"><img src=\""+ "img/1.jpg" +"\" alt=\"profile-image\"></a><div class=\"message-text\">"+ msgs["messages"][i]["message"] +"</div><div class=\"message-time\">"+ msgs["messages"][i]["time"] +"</div></div></div>";
				}
				else{
					message = "<div class=\"other message\"><div class=\"message-content\"><a href=\"chef.php?id="+ temp_data["chats"][user] +"\"><img src=\""+ temp_data["photo"][user] +"\" alt=\"profile-image\"></a><div class=\"message-text\">"+ msgs["messages"][i]["message"] +"</div><div class=\"message-time\">"+ msgs["messages"][i]["time"] +"</div></div></div>";
				}
				
			}
			
			chat_field.insertAdjacentHTML("afterbegin", message);
			day = msgs["messages"][i]["day"];
			month = msgs["messages"][i]["month"];
			year = msgs["messages"][i]["year"];
		}
		sessionStorage.setItem("last_msg", JSON.stringify(last_msg));
	}
	else{
		chat_field.innerHTML = "";
	}
}

//function day in year
function dayOfTheYear(day, month, year){
	var max = 365;
	var days_of_months = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
	var sum_of_day = 0;

	if(year % 4 == 0){
		if(year % 100 == 0 && year % 400 == 0){
			max = 366;
			days_of_months[1] = 29;
		}
	}
	for (let i = 0; i <= month-1; i++) {
		if(i == month-1){
			sum_of_day += day;
			break;
		}
		sum_of_day += days_of_months[i];
	}
	return [max, sum_of_day];
}

//function to change background color on clicked object in recent chat list
function changeBackAndFontColor(target){
	var recent_chats_list = document.getElementsByClassName("recent-chat-container");
	for(var a = 0; a < recent_chats_list.length; a++){
		if(target == recent_chats_list[a]){
			target.style.background = "#000";
			target.style.color = "#fff";
		}
		else{
			recent_chats_list[a].style.background = "#fff";
			recent_chats_list[a].style.color = "#000";
		}
	}
}


//get messages from file
function getMessages(i){

	var xhr = new XMLHttpRequest();
	var data = localStorage.getItem("data");
	data = JSON.parse(data);

	var otherid = data["chats"][i];
	var myid = data["id"];

	xhr.open("POST", "includes/messages_getter.php", false);
	xhr.setRequestHeader("Content-type","Application/x-www-form-urlencoded");
	let response;
	xhr.onload = function(){
		if(xhr.response != ""){
			response = xhr.responseText;
			sessionStorage.setItem("old_messages", response);
		}
	}
	var parms = "first_user=" + myid + "&second_user=" + otherid;
	xhr.send(parms);
}
