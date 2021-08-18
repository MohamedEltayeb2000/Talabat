var time;
window.onresize = Adapt;
//on resizing window
function Adapt(){
	if(window.innerWidth < 576){
		var recent_chat = document.getElementsByClassName("recent-chat-container");
		for (var i = recent_chat.length - 1; i >= 0; i--) {
			recent_chat[i].style.background = "#fff";
			recent_chat[i].style.color = "#000";
		}
		document.getElementsByClassName("back-btn")[0].style.setProperty("display", "inline-block", "important");
		document.getElementsByClassName("right-side")[0].style.display = "none";
		document.getElementsByClassName("recent-chats")[0].style.display = "block";
	}
	else{
		document.getElementsByClassName("back-btn")[0].style.setProperty("display", "none", "important");
		document.getElementsByClassName("right-side")[0].style.display = "none";
		document.getElementsByClassName("recent-chats")[0].style.display = "block";
	}
}

//on load check for window resoultion for xs
function onLoad(){
	if(window.innerWidth < 576){
		document.getElementsByClassName("back-btn")[0].style.setProperty("display", "inline-block", "important");
		document.getElementsByClassName("right-side")[0].style.display = "none";
		document.getElementsByClassName("recent-chats")[0].style.display = "block";
	}
}