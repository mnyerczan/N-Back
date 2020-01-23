function Delete_log(logid){

	var xmlhttp = new XMLHttpRequest();
	var params = new FormData();
	params.append('logid', logid);

	xmlhttp.open("POST", 'forum/delete_log.php', true);
	xmlhttp.send(params);

	xmlhttp.onload = function () {
		console.log(this.responseText);
	};

	document.getElementById("forum_logs").innerHTML = msg;
}

function Step_next_page(add){

	/*
	*	lineáris léptetésért felelős eljárás
	*
	*/


	if(theme == "black"){
		if(add == "forward"){

			document.getElementById("page_font_"+(parseInt(offset)+1)).style.backgroundColor = "white";
			document.getElementById("page_font_"+(parseInt(offset)+1)).style.color = "black";
			document.getElementById("page_font_"+offset).style.backgroundColor = "black";
			document.getElementById("page_font_"+offset).style.color = "white";
			offset = parseInt(offset) +1;
		}
		else{
			document.getElementById("page_font_"+offset).style.backgroundColor = "black";
			document.getElementById("page_font_"+offset).style.color = "white";
			document.getElementById("page_font_"+(parseInt(offset) -1)).style.backgroundColor = "white";
			document.getElementById("page_font_"+(parseInt(offset) -1)).style.color = "black";
			offset = parseInt(offset) -1;
		}
	}
	else{
		if(add == "forward"){
			document.getElementById("page_font_"+(parseInt(offset)+1)).style.backgroundColor = "black";
			document.getElementById("page_font_"+(parseInt(offset)+1)).style.color = "white";
			document.getElementById("page_font_"+parseInt(offset)).style.backgroundColor = "white";
			document.getElementById("page_font_"+parseInt(offset)).style.color = "black";
			offset = parseInt(offset) +1;
		}
		else{
			document.getElementById("page_font_"+offset).style.backgroundColor = "white";
			document.getElementById("page_font_"+offset).style.color = "black";
			document.getElementById("page_font_"+(parseInt(offset) -1)).style.backgroundColor = "black";
			document.getElementById("page_font_"+(parseInt(offset) -1)).style.color = "white";
			offset = parseInt(offset) -1;
		}
	}
	$.ajax({

		type: "GET",
		url : "forum/logs.php?uid=uid&rid="+ rid +"&main_theme="+main_theme +"&get_logs=1&limit="+ limit +"&privilege=" +privilege +"&user_priv="+ user_priv +"&offset="+offset,
		async: false,
		success:function(text){
			msg = text.trim();
		}
	});

	console.log("forum/logs.php?uid=uid&rid="+ rid +"&main_theme="+main_theme +"&get_logs=1&limit="+ limit +"&privilege=" +privilege +"&user_priv="+ user_priv +"&offset=" + offset);
	document.getElementById("forum_logs").innerHTML = msg;
}



function Change_page(current_offset){

/*
	* tetszöleges offset meghívásáért felelős eljárás
	*
	*/


	$.ajax({

		type: "GET",
		url : "forum/logs.php?uid=uid&rid="+ rid +"&main_theme="+main_theme +"&get_logs=1&limit="+ limit +"&privilege=" +privilege +"&user_priv="+ user_priv +"&offset="+current_offset,
		async: false,
		success:function(text){
			msg = text.trim();
		}
	});

	if(offset != current_offset){

		if(theme == "black"){
			document.getElementById("page_font_"+current_offset).style.backgroundColor = "white";
			document.getElementById("page_font_"+current_offset).style.color = "black";
			document.getElementById("page_font_"+offset).style.backgroundColor = "black";
			document.getElementById("page_font_"+offset).style.color = "white";
		}
		else{
			document.getElementById("page_font_"+current_offset).style.backgroundColor = "black";
			document.getElementById("page_font_"+current_offset).style.color = "white";
			document.getElementById("page_font_"+offset).style.backgroundColor = "white";
			document.getElementById("page_font_"+offset).style.color = "black";
		}
	}
	console.log("page_font_"+current_offset+'|'+document.getElementById("page_font_"+current_offset).style.backgroundColor);
	console.log("page_font_"+offset+'|'+document.getElementById("page_font_"+current_offset).style.backgroundColor);

	document.getElementById("forum_logs").innerHTML = msg;

	offset = current_offset;
}
