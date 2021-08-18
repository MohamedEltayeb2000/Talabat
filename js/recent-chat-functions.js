document.getElementsByTagName("body")[0].onload = recentChats;



function recentChats(){

	//function found in chats-xs-handler.js
	onLoad();

	//disable right side until user choose user
	document.getElementsByClassName("right-side")[0].style.display = "none";

	//talking with PHP file(recent-messages.php) using XMLHttpRequest(xhr)"Ajax"
	var xhr = new XMLHttpRequest();

	xhr.open("POST", "backend/recent-messages.php", true);

	xhr.onload = function(){
		if(xhr.responseText != ""){
			var data = xhr.responseText;
			localStorage.setItem("data", data);
			makeRecentChatList(data);
		}
	}
	xhr.send();
}

//function that make the recent chat list
function makeRecentChatList(data){
	var chat_field = document.getElementsByClassName("recent-chat-field")[0];
	var recent_chat;
	data = JSON.parse(data);

	for (var j = 0; j < Object.keys(data["chats"]).length; j++) {
		 if(data["type"][j] == "user"){
		 	recent_chat = "<div class=\"recent-chat-container\" id=\""+ j +"\"><div class=\"media border-top border-bottom\"><a class=\"media-left\" href=\"\"><img src=\"img/1.jpg\" alt=\"profile-image\"></a><div class=\"media-body\"><h4 class=\"media-heading\">"+data["users"][j]+"</div></h4><p>Welcome</p></div></div>";
		 }
		 else{
		 	recent_chat = "<div class=\"recent-chat-container\" id=\""+ j +"\"><div class=\"media border-top border-bottom\"><a class=\"media-left\" href=\"chef.php?id="+ data["chats"][j] +"\"><img src=\""+ data["photo"][j] +"\" alt=\"profile-image\"></a><div class=\"media-body\"><h4 class=\"media-heading\">"+data["users"][j]+"</div></h4><p>Welcome</p></div></div>";
		 }
		 chat_field.insertAdjacentHTML('beforeend', recent_chat);
	}
	recentChatList();
}


//function assing click function to each recent chat object
function recentChatList(){
	//assign recentChats function to onload in body
	var data = JSON.parse(localStorage.getItem("data"));
	var len = Object.keys(data["chats"]).length;
	var recent_chats_list = document.getElementsByClassName("recent-chat-container");
	for (var i = 0; i < len; i++) {
		recent_chats_list[i].addEventListener("click", onRecentChatClick);
	}
}

