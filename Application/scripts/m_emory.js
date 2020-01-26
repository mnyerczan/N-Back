/**
 * _$ because jquery $() function
 * @param {*} id 
 */
function _$(id)
{
	return document.getElementById(id);	
}

//-----------------------------------------------------------------------
/**
 * Color Feedback function
 */
function setRed(id)
{
	_$(id).style.color = "white";
	_$(id).style.backgroundColor = "rgb(200, 100, 100)";
}

function setBlue(id)
{
	_$(id).style.color = "rgb(255, 255, 255)";
	_$(id).style.backgroundColor = "rgb(140, 140, 220)";	
}

function setGreen(id)
{
	_$(id).style.color = "rgb(255, 255, 255)";
	_$(id).style.backgroundColor = "rgb(140, 200, 140)";
}

function setOrange(id)
{
	_$(id).style.color = "rgb(255, 255, 255)";
	_$(id).style.backgroundColor = "rgb(148, 88, 15, 0.6)";
}

function setInitial(id)
{
	_$(id).style.color = "rgb(100, 100, 100)";
	_$(id).style.backgroundColor = "inherit";
}

//-----------------------------------------------------------------------
/**
 * Escape from session if user key down esc.
 */
function Escape_event()
{
	setRed("n_back_notice_3");
	clearInterval(interval);
	
	if(confirm("Do you really want to exit?"))
	{		
		window.location= "index.php";
		return;
	}
	
	setInitial("n_back_notice_3");

	Session();	
 }

//-----------------------------------------------------------------------
/**
 * NBACK game function
 */
function Session(){	

	_$('n_back_notice_0').innerHTML= manual + " " + level +'-back ';

	/**
	 * Declare a global interval variable
	 */
	interval = setInterval( NbGame, seconds_js * 1000);
 }

 function NbGame(){

	if(number_of_visiblility -1 < length_of_session)
	{
		// If wasn't agreement under the game 
		if(number_of_visiblility == length_of_session - 1 &&  wrongHit == 0 && correctHit == 0)
		{
			place_index = array_indexes[1];
		}
		else
		{									
			do
			{
				place_index = Math.floor( Math.random() * 10 );
				
			}while( place_index == 4 || place_index >= 9 );
		}
		if(number_of_visiblility  < length_of_session) _$('n_back_table_image_' + place_index).className = 'n_back_visible';


		//------------------------------------------------------------------------------------------------------
		/** 
		* If the level value smaller than visibling of session
		*/
		if(number_of_visiblility > this.level)
		{

			//ha van egyezés, de nem volt reakció
			if((_$('n_back_notice_2').style.backgroundColor != "rgb(0, 170, 0)" && _$('n_back_notice_2').style.backgroundColor != "rgb(140, 200, 140)")
			&&  array_indexes[0] === array_indexes[array_indexes.length - 1] )
			{
				wrongHit = parseInt(wrongHit) +1;
				_$("info_wrong").innerHTML = wrongHit;

				//Jelzésképp kékre vált
				setBlue('n_back_notice_2');
			}


			//Ha volt reakció		
			if(array_indexes)
			{
				//console.clear();
				//console.log(_$('n_back_notice_2').style.backgroundColor);

				switch(_$('n_back_notice_2').style.backgroundColor)
				{
					case "rgb(200, 100, 100)": if(this.array_indexes[0] != this.array_indexes[this.array_indexes.length - 1] )
					{
						wrongHit = parseInt(wrongHit) +1;
						_$("info_wrong").innerHTML = wrongHit;

					}	break;

					case "rgb(140, 200, 140)" : correctHit= parseInt(correctHit) +1;
							_$("info_correct").innerHTML = correctHit;
						break; 
				}

				//console.log("wrong:\t" + wrongHit);
				//console.log("correct:\t" + correctHit);
			}

		}



		//------------------------------------------------------------------------------------------------------

		Array_indexes_handled(place_index);
		setTimeout(function(){setInitial('n_back_notice_2');}, 300);

		setTimeout(function()
		{
			_$('n_back_table_image_' + place_index).className = 'n_back_hidden';

			if(number_of_visiblility  <= length_of_session )
			{
				_$('n_back_notice_1').placeholder= length_of_session - number_of_visiblility + n_back_notice_1_help + ' back';
			}

		}, this.eventLength * 1000);
	}




	//------------------------------------------------------------------------------------------------------
	/**
	 * Alert
	 */
	if(number_of_visiblility >= length_of_session - 10 && number_of_visiblility < length_of_session ) 
	{
		setOrange("n_back_notice_1");
		
		setTimeout(() => {
			setInitial("n_back_notice_1");
		}, 1000);
	}
	




	//------------------------------------------------------------------------------------------------------
	if(parseInt(correctHit) == 0 && parseInt(wrongHit) != 0 )
	{
		_$("info_percent").innerHTML = "0%";				
	}
	else if(parseInt(correctHit) != 0 && (parseInt(wrongHit) + parseInt(correctHit)) > 0)
	{
		_$("info_percent").innerHTML =
		Math.round((parseFloat(correctHit) / (parseFloat(wrongHit) +parseFloat(correctHit))) *100)+'%';
	}

	 n_back_session_sessionLength = (parseInt(n_back_session_sessionLength) +Timer());
	 _$("info_time").innerHTML = Math.round(n_back_session_sessionLength / 1000) + " s";


	number_of_visiblility++;




	//------------------------------------------------------------------------------------------------------
	if((number_of_visiblility -1) == length_of_session || length_of_session == 0)
	{
		clearInterval(interval);
		if(length_of_session != 0){


			if(correctHit < 0) correctHit = 0;
			document.cookie = "correctHit="+correctHit;

			if(wrongHit < 0) wrongHit = 0;
			document.cookie = "wrongHit="+ wrongHit;

			var game_mode = (this.manual == "Position") ? "Off": "Manual";
			document.cookie = "manual="+game_mode;

			if(this.level < 1) this.level = 1;
			document.cookie = "level="+this.level;

			if(this.length_of_session < 25) this.length_of_session = 25;
			document.cookie = "trial="+this.length_of_session;

			if(this.seconds_js < 0.1) this.seconds_js = 0.1;
			document.cookie = "seconds="+this.seconds_js;

			if(n_back_session_sessionLength < this.length_of_session * this.seconds_js)
			{
				n_back_session_sessionLength = this.length_of_session * this.seconds_js;
			}
			document.cookie = "sessionLength="+n_back_session_sessionLength;

			if(this.eventLength < 0.005) this.eventLength = 0.005;
			document.cookie = "eventLength="+this.eventLength;

			document.cookie = "sessionUpload=0";

			 window.location= "index.php?index=2&case=nb";
		}
		else
		{
			window.location= "index.php";
		}
	}
}

/**
 * Key-handler for N-back game 
 */
 function n_back_action()
 {
		 if(number_of_visiblility <= this.level)
		 {
			setBlue("n_back_notice_2");
		 }
		 else
		 {
			 if(this.array_indexes[0] === this.array_indexes[this.array_indexes.length - 1] )
			 {
				 if(js_theme == "black")
				 {
					 setGreen("n_back_notice_2");	
				 }
				 else
				 {
					 //_$("n_back_notice_2").style.color = "rgb(0, 200, 0)";	
					 setGreen("n_back_notice_2");
				 }
			 }
			 else
			 {
				setRed("n_back_notice_2");
			 }
		 }
  }
 
   

/**
 * For Nback game index array
 * @param {*} place_index 
 */
function Array_indexes_handled(place_index)
{
	if(number_of_visiblility > level )
	{
		array_indexes.shift();
		array_indexes.push(place_index);
	}
	else
	{
		array_indexes.push(place_index);
	}
 }




function  Timer()
{
	var current_timestamp = new Date().getTime();
	t.push(current_timestamp - help_timestamp);
	help_timestamp = current_timestamp;
	return t[t.length - 1];
 }




function login_form_message_change(){
	_$('message').innerHTML='';
 }




function Delete(){

	_$('cu_1').placeholder = '';
	_$('cu_1').value = '';
	_$('cu_lbl_1').style.color = "#555";

	_$('cu_3').value = '';
	_$('cu_lbl_3').style.color = "#555";
	_$('cu_3').placeholder = '';

	_$('cu_4').placeholder = '';
	_$('cu_4').value = '';
	_$('cu_lbl_4').style.color = "#555";

	_$('cu_5').placeholder = '';
	_$('cu_5').value = '';
	_$('cu_lbl_5').style.color = "#555";

	_$('cu_6').value = '';
 }




function Validation()
{	
	if(_$('cu_1').value.length <1)
	{
		('cu_lbl_1').style.color = 'rgb(200, 0, 0)';
		_$('cu_1').placeholder = 'Kötelező kitölteni!';
		return false;

	}

	if(_$('cu_3').value.length <1)
	{
		_$('cu_lbl_3').style.color = 'rgb(200, 0, 0)';
		_$('cu_3').placeholder = 'Kötelező kitölteni!';
		return false;
	}

	if (_$('cu_5').value == 0)
	{
		_$('cu_lbl_5').style.color = 'rgb(200, 0, 0)';
		_$('cu_5').placeholder = 'Kötelező kitölteni!';
		return false;

	}

	if(_$('cu_6').value == 0)
	{
		_$('cu_lbl_6').style.color = 'rgb(200, 0, 0)';
		_$('cu_6').placeholder = 'Kötelező kitölteni!';
		return false;

	}
	 
	return true;
 }




function js_set_manual_function(e)
{
		if(manual) 
		{
			_$('manual').value='Off';
			manual=false;
			e.style.color='black';
			_$('level').disabled = false;
		}
		else 
		{
			_$('manual').value='Manual';
			manual=true;
			e.style.color='#1c5272';
			_$('level').disabled = true;
		}
 }


function f$_GetParameter(parName)
{
	var result = null,
		tmp = [];
	var items = location.search.substr(1).split("&");

	for (var index = 0; index < items.length; index++) 
	{
		tmp = items[index].split("=");
		if (tmp[0] === parName) result = decodeURIComponent(tmp[1]);

	}
	return result;
 }




function n_back_options_modify_long_markup()
{
	_$("n_back_long").placeholder = Math.round((parseInt(_$('trial').value) + parseInt(trial_min) + 20) * _$('seconds').value) +' s';
 }




function key_event(e)
{
 	var key = e.witch || e.keyCode;
	
	if(key == 65 && f$_GetParameter('nb_common') == 1)
	{
		n_back_action();

	}
	else
		if(key == 40 || key == 38) {
			switch(document.activeElement.tabIndex ){
				case 2: manual_mode_function(key); 		break;
				case 3: level_function(key); 			break;
				case 4: n_back_actions_function(key); 	break;
				case 5: trial_function(key);			break;
				case 6: eventLength_function(key);		break;
			}
	}
	else
		if(key == 9 )
		{
			if(f$_GetParameter('nb_common') == 3){
				Key_event_on_nback_options();
			}

		}
	if(key == 27)
 		Escape_event();
 }




function trial_function(key){
	if(key == 38 &&  _$("trial").value < (100 - trial_min) && _$("trial").value != 100){
		_$("trial").value = parseInt(_$("trial").value) + 5;
	}
	else if( key == 40 && _$("trial").value >= 5 ){
		_$("trial").value = parseInt(_$("trial").value) - 5;
	}
	n_back_options_modify_long_markup();
 }




function eventLength_function(key){
	if(key == 38 &&  (_$("eventLength").value <  parseFloat(_$("seconds").value)) &&
		(_$("eventLength").value < parseFloat(_$("seconds").value) -0.01)){
 		_$("eventLength").value = Math.round((parseFloat(_$("eventLength").value) + 0.005) * 1000) / 1000;
	}
	else if( key == 40 && parseFloat(_$("eventLength").value) > 0.005 ){
		_$("eventLength").value = Math.round((parseFloat(_$("eventLength").value) - 0.005) * 1000) / 1000;
	}
 }




function n_back_actions_function(key){
	if(key == 40 && _$("seconds").value >  this.seconds_min ){
		_$("seconds").value = Math.round((parseFloat(_$("seconds").value) - 0.1) * 1000) / 1000;
	}
	else
		if(key == 38 && _$("seconds").value < 10 ){
			_$("seconds").value = Math.round((parseFloat(_$("seconds").value) + 0.1) * 1000) / 1000;
		}

		while(parseFloat(_$("eventLength").value) > parseFloat(_$("seconds").value) -0.05){
			_$("eventLength").value = Math.round((parseFloat(_$("eventLength").value) - 0.01) * 1000) / 1000;
		}

		n_back_options_modify_long_markup();
 }




function manual_mode_function(key){

	if(key == 38 && _$("manual").value == 'Off'){
			_$("manual").value = 'Manual';
			_$("level").disabled = false;
			if(js_theme == "black"){
				_$("level").style.color = "#ccc";
			}
			else _$("level").style.color = "#333";
	}
	else
		if(key == 40 && _$("manual").value == 'Manual'){
			_$("manual").value = 'Off';
			_$("level").disabled = true;
			if(js_theme == "black"){
				_$("level").style.color = "#333";
			}
			else _$("level").style.color = "#aaa";

		}
	}




function level_function(key){
	if(_$("level").value > 1 && key == 40){
		_$("level").value = parseInt(_$("level").value) - 1;
		this.trial_min = parseInt(_$("level").value) * 5;

	}
	else
		if(_$("level").value <= 19  && key == 38){
			_$("level").value = parseInt(_$("level").value) + 1;
			if(_$("trial").value < parseInt(_$("level").value) * 5){
				this.trial_min = parseInt(_$("level").value) * 5;
				_$("trial").value = parseInt(_$("level").value) * 5;
			}
		}
 }




function read_url(input, file, theme){
	if(input.files && input.files[0]){
		var reader = new FileReader();
		reader.onload = function(e){
			$(file).attr("src", e.target.result);
		};
		reader.readAsDataURL(input.files[0]);
	}
 }




function Load_menu_datas_to_form(name,id, privilege, ikon,parent){
	$.ajax({

		type: "GET",
		url : "special_characters_handler.php?special_chararcters=1&msg="+name,
		async: false,
		success:function(text){
			name_with_spec_characters = text.trim();
		}
	});
	_$("create_forum_form_text_container_name_input").value = name_with_spec_characters;
	_$("create_forum_form_text_container_privilege_input").value = privilege;
	_$("create_forum_form_text_container_id_input").value = id;
	_$("file_font_to_js").innerHTML = "Change file";

	if(ikon != 'None'){
		_$("forum_img").style.visibility =  "visible";
		_$("forum_img").src = ikon;
		var mtop = _$("forum_img").style.height;
		_$("forum_img").style.marginTop = ((26 - mtop) /2)+"px";
		_$("edit_user_file").value = ikon;
	}
	else{
		_$("forum_img").style.visibility =  "hidden"
		_$("edit_user_file").value = "none";
		_$("forum_img").style.width = "0";
		_$("forum_img").style.height = "0";
	}
 }




function Profile_form_search_by_string(){
	if(_$("profils_select_input").value != null && _$("profils_select_input").value != ''){
 		window.location= "index.php?index=8&choose=3&s="+_$("profils_select_input").value;
	}
 }




function was_enter_down(event){
	var x = event.which || event.keyCode;
	if(x == "13"){
		Profile_form_search_by_string()
	}
 }




function Was_enter_down_p(event){
	var x = event.which || event.keyCode;
	if(x == "13" && Math.round(_$("num_of_items").value) > 0 &&
		!Number.isNaN(Number(_$("num_of_items").value))){

		document.cookie = "plimit="+Math.round(_$("num_of_items").value);
		_$("offset").value = 0;
		_$("Send_id_post").action="index.php?index=8&choose="+choose
  		_$("Send_id_post").submit();
	}
	else if( x == "13" && (Math.round(_$("num_of_items").value) <= 0 ||
		Number.isNaN(Number(_$("num_of_items").value)))){
		_$("num_of_items").style.color = "rgb(200, 0, 0)";
		_$("alert_div").innerHTML = "only positive";
	}
	else {
		_$("num_of_items").style.color = "inherit";
	}

 }




function Was_enter_down_nb(event,selector,rid){
	var x = event.which || event.keyCode;
	if(x == "13"){
		document.cookie = "limit_of_doc_items="+_$("num_of_items").value;
		window.location = "index.php?index=3&nb_common=2";
	}
 }




function Was_enter_down_f(event, rows_count){
		var x = event.which || event.keyCode;
		if(x == "13" && Math.round(_$("num_of_items").value) > 0 &&
			!Number.isNaN(Number(_$("num_of_items").value))){
			document.cookie = "flimit="+Math.round(_$("num_of_items").value);
			_$("num_of_logs_form").action="index.php?index=7";
			_$("num_of_logs_form").submit();
		}
		else if( x == "13" && (Math.round(_$("num_of_items").value) <= 0 ||
			Number.isNaN(Number(_$("num_of_items").value)))){
			_$("num_of_items").style.color = "rgb(200, 0, 0)";
			_$("alert_div").innerHTML = "just bigger than zero";
		}
		else {
			_$("num_of_items").style.color = "inherit";
		}
	}




function confirmDelete(delUrl){
	if (confirm("Are you sure?")){
		document.location = delUrl;
	}
}




function Check_modify_theme_radio(new_theme, uid)
{
	if(is_session){
		$.ajax({

			type: "GET",
			url : "Application/BackModules/Theme/theme_coordinate.php?theme="+new_theme,
			async: false,
			success:function(text){
				msg = text.trim();
			}
		});		
	}
	else{
		document.cookie = "theme=" + new_theme;		
	}

	_$("theme_link").href = "style/style_"+new_theme+".css";

	if(_$("level")) _$("level").color = js_theme;
	theme = js_theme = new_theme;

}
function Hide_info_lbl(){
// Az infó cimke kikapcsolását végző függvény.
	let x = _$("info_lbl_hide_btn");

	if(_$("info_lbl_container").style.visibility != "hidden"){
		_$("info_lbl_container").style.visibility = "hidden";
		x.style.visibility = "visible";
		x.style.writingMode = "vertical-lr";
		x.style.width = "30%";
		x.style.height = "70px";
		x.style.position = "relative";
		x.style.left = "50px";
		x.style.bottom = "25px";
		x.innerHTML = "Info";
		x.style.color = "#009";
		document.cookie = "infoLabel=0";
	}
	else
		if(_$("info_lbl_container").style.visibility == "hidden"){
		_$("info_lbl_container").style.visibility = "visible";
		x.style.writingMode = "horizontal-tb";
		x.style.width = "100%";
		x.style.height = "20px";
		x.style.position = "relative";
		x.style.left = "0px";
		x.style.bottom = "0px";
		x.innerHTML = "Hide";
		x.style.color = "#000";
		document.cookie = "infoLabel=1";
	}
	console.log(_$("info_lbl_container").style.visibility );

}
// function a(){
// 	if(_$("info_lbl_container").style.visibility != "hidden" ||
// 		_$("info_lbl_container").style.visibility != "d")
// 	_$("info_lbl_container").style.visibility = "hidden";
// 	console.log(_$("info_lbl_container").style.visibility);
// }


function Login_validate(){

	var lName 	= _$("loggin_name").value,
		lPass 	= _$("login_form_pass").value,
		x		=_$("login_label"),
		msg 	= "";

	x.style.backgroundImage = "url('img/loader_transparent.gif')";
	x.style.backgroundSize = "20px 20px";

	x.innerHTML = "";
	_$("message").innerHTML = "";

	setTimeout(function(){



	}, 1000);

	$.ajax({

		type: "GET",
		url : "login/validate_login.php?lName=" + lName +"&lPass=" + lPass,
		async: false,
		success:function(text){
			msg = text.trim();
		}
	});


    if(jsLogLevel) {
		console.log(" - [LOGIN] - \nmsg.length:" + msg.length);

		console.log( "\n'" + msg + "' 'TRUE'" );

		console.log("~n" + msg == "TRUE");
	}

	if(msg == "TRUE") return true;

	_$("message").innerHTML = "Invalid password or username!";
	x.innerHTML = "Login";
	x.style.backgroundImage = "none";

	return false;

}

/*
 *  Functions a beállítás gombok kezeléséhez
 *
 */

function ClickOrWheelInNBackOptions(p, e){

	/*
	 *  Ez a függvény dönti el, hogy a ChangePositionValue() és a
	 *  ChangeNuberValue() függvényt klikkelve, vagy görgetve
	 *  hívtuk meg.
	 */

	let result = 0;

	if( p == "up" || p == "down") {
		result = p;
		return result;
	}
	else
		if( e.deltaY == "-3"){
			result = "up"
		}
		else{
			result = "down";
		}

	return result;
}


function ChangeNuberValue(p, e, l, event){

	/*
	 * 	p = érték növelés "up" vagy csökkentés "down"
	 * 	e = átadott input objektum
	 * 	l = várt tizedesjegyek száma
	 *
	 * 	event = onmwheel esemény görgetés esetében
	 */


	let 	max = parseFloat(e.max),
			min = parseFloat(e.min),
		 value = parseFloat(e.value),
		  step = parseFloat(e.step),
		  name = e.name;
			  p = ClickOrWheelInNBackOptions(p, event);

	let q 					= _$("eventLength").value,
		eventLengthValue 	= parseFloat(q);
			secondsValue 	= parseFloat(_$("seconds").value),
					_manual = _$("_manual").value;



	if( name == "level" &&  _manual == "Manual" || name != "level" ){

		if(p == "up" && value < max && ( name != "eventLength" || name == "eventLength" && eventLengthValue < secondsValue - 0.005)){
				e.value = ( value + step ).toFixed(l);
		}
		else{
			if( p == "down" && value > min){

				if(name == "seconds" ){

					if(value - step < eventLengthValue)

						q = (parseFloat(q) - step).toFixed(3);

					if(value - step == eventLengthValue)

						q = (parseFloat(q) - 0.005).toFixed(3);

				}

				e.value = ( value - step ).toFixed(l);
			}
		}
	}


	//Az Events szinttől függő értékének kiírását végzi
	if(name == "level"){

		_$("default_trials_increase").innerHTML = "Events: " + (parseInt( e.value ) * 5 + 20) + " +";

	}



	//A session hosszát számolja
	if(name == "trial" || name == "seconds" || name == "level"){

		let  trial = parseInt(_$("trial").value),
			seconds = parseFloat(_$("seconds").value).toFixed(1),
			  level = parseInt(_$("level").value);

		_$("n_back_long").value = (seconds * (level * 5 +  20 + trial)).toFixed(0) + " s";

	}

}



function ChangePositionValueInNBackOptions(p, event){

	/*
	 * 	p = érték növelés "up" vagy csökkentés "down"
	 * 	e = átadott input objektum
	 *
	 * 	event = onmwheel esemény görgetés esetében
	 */

	p = ClickOrWheelInNBackOptions(p, event);

	let value = _$("_manual").value,
		color = "#999";//(theme == "white") ? "black" : "white";


	if(p == "down" && value == "Manual" ){

			_$("_manual").value = "Off";
			_$("level").style.color = "#777";
	}
	else
		if (p == "up" && value == "Off" ){

			_$("_manual").value = "Manual";
			_$("level").style.color = color;
		}

	console.log(_$("_manual").value);
}


function ChangeColorValueInNBackOptions(p, e, event){

	/*
	 * 	p = érték növelés "up" vagy csökkentés "down"
	 * 	e = átadott input objektum
	 *
	 * 	event = onmwheel esemény görgetés esetében
	 */

	let t = ["blue", "cyan", "green", "grey", "magenta", "red", "white", "yellow"];

		 p = ClickOrWheelInNBackOptions(p, event);



	if( p == "up" && t.indexOf(_$("color").value) < t.length - 1){

		_$("color").value = t[t.indexOf(_$("color").value) + 1];

	}
	else
		if( p == "down" && t.indexOf(_$("color").value) > 0){

		_$("color").value = t[t.indexOf(_$("color").value) - 1];

	}

}


function Forum_step_back(){
	if(offset >= 1){
		console.log("offset:"+offset);
		Step_next_page("backward");
	}
}

function Forum_step_forward(){
	if(rows_count > ((offset+1) * limit)){
		console.log("offset:"+offset);
		Step_next_page("forward");
	}
}
function SeriaLeftTexHandler( seria, div, isnbCommonEqualManuale )
{	
	/*
	 * Széria isszaszámlálás. Ha tegnapi az utolsó session, nem indul el a ciklus.
	 */	
    //ha a széria több ideje van mint 24 óra
	if( parseInt(seria) >= 1) 
	{
		div.innerHTML 		= "("+ seria + ")" ;	
	}
    //ha a széria az utolsó 14 órában van és nem volt játék
	if( parseInt(seria) >= 0)
	{				
		var counter = setInterval(function(e)
		{
			
			var time = CalculateLeftTimeToMidnight() ;
						
			if( time[1] <= 0)
			{												
				document.getElementById('seria_text').remove();
				clearInterval(counter);
			}
			else
			{
				div.innerHTML = "("+ seria + ") <span> Left " + time[0] + "</span>";
				if(jsLogLevel) 
				{
					console.clear();
				}
			}							
			console.log(time);

			if(jsLogLevel)
			{
				console.log(" - SERIA_LEFT_TIME - \n" + time[1]);
			}				
		}, 1000);
	}

}

function CalculateLeftTimeToMidnight()
{

	var now 				= new Date(),				
		hours 				= ( 24 - now.getHours() - 1) > 0 ? '0' + (24 - now.getHours() - 1) : '00',
		mins 				= ( 60 - now.getMinutes() - 1 < 10  ? "0" + (60 - now.getMinutes() - 1) : (60 - now.getMinutes() - 1)),
		seconds				= ( 60 - now.getSeconds() < 10 ? "0" + ( 60 - now.getSeconds() ) : ( 60 - now.getSeconds() ) );			

	hours 	= parseInt(hours);
	mins 	= parseInt(mins);
	seconds = parseInt(seconds);

	return [
		hours  + ":" + mins + ":" + seconds ,  
		hours * 3600 + mins * 60 + seconds
	];

}