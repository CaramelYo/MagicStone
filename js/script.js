/* TODO List:

*/

var level = 1;
var turn = 0;

var size = 50;

var radius = size / 2 - 4;

var line_width = 2;

var title_height = 50;

var timeout = 30;	

var col = 12, row = 1;	/*row 堆*/

var max =3;		/*大拿*/

var rock = "cookie";

var background = new Image();
background.src = 'res/egg.png';

var background2 = new Image();
background2.src = 'res/egg2.png';

var level = 1;



var player1 = "Player 1";

var player2 = "Player 2";

var player1_color = "#198cff";

var player2_color = "#c61236";

var color = player1_color;

		

var game_canvas = $("canvas")[0];

var game = game_canvas.getContext("2d");

var anim_canvas = $("canvas")[1];

var anim = anim_canvas.getContext("2d");

var quest_dlg;



var left_arrow = new Image();

var right_arrow = new Image();



var selected;

var ai_weight;



var ended; /* 0 for playing, 1 for player 1, 2 for player 2, 3 for tie*/

var page_status; /* 0 for game, 1 for dic, 2 for player, and etc.*/

var ai_mode = 1; /* 0 for normal mode, non-zero for ai mode*/



var timeout_token;

var drawarrow_token;



var last_hand;

var need_sleep;


var answer = ["Red", "Green", "Black", "White", "Yellow", "Blue"];

var answer_pos;

var right_answer;

var steps_counter = 0;



var dict_list;

var dict_index = 0;


var timer;
var t = { time: 0 };	/*to be passed by reference*/


//******** Game Functions *******//

function Init() {

	/* Initialize grid status */

	selected = Alloc2dArray(row, temp);

	ai_weight = Alloc2dArray(row, temp);

			

	/* Set-up canvas */

	game_canvas.width = temp * size + 10;

	game_canvas.height = row * size + 10 + title_height;

	game_canvas.style.position = "absolute";

	game_canvas.style.left = ((window.innerWidth - game_canvas.width) / 2 > 6) ? (window.innerWidth - game_canvas.width) / 2 : 6;

	game_canvas.style.zIndex = 0;

	
	
	anim_canvas.width = game_canvas.width;

	anim_canvas.height = game_canvas.height;

	anim_canvas.style.position = game_canvas.style.position;

	anim_canvas.style.left = game_canvas.style.left;

	anim_canvas.style.zIndex = 1;
	
	

	game.translate(10 / 2, 10 / 2);

	anim.translate(10 / 2, 10 / 2);

	game.textBaseline = "hanging"; 

			

	/* Load arrow images */

	left_arrow.src = "res/left_arrow.png";

	right_arrow.src = "res/right_arrow.png";

	

	/* Draw something */

	color = player1_color;

	DrawPlayerName();

	DrawBoard(temp, row,col);

	DrawArrow();

	

	/* Display timeout */

	/*clearTimeout(timeout_token);*/

	/*PlayerTimeout(timeout);*/
	/*clearTimeout(timer);*/
	/*PlayerTimeout(t.time);*/



	/* Others */

	page_status = 0;

	steps_counter = 0;

	ended = 0;

	need_sleep = true;

	right_answer = false;

	$("#cur-mode").html((!ai_mode) ? "目前模式：普通" : "目前模式：AI");
	
	

}



function DrawCircle(x, y, color) {

	game.save();

	game.translate(0, title_height); /* Reserved height for title*/

	game.beginPath();

	game.strokeStyle = "rgba(" + parseInt(color.substring(1, 3), 16) + ", " + 

					   parseInt(color.substring(3, 5), 16) + ", " + parseInt(color.substring(5, 7), 16) + ", 0.4)";

	game.fillStyle = game.strokeStyle;

	game.arc((x - 1) * size + size / 2, (y - 1) * size + size / 2, radius, 0, 2 * Math.PI, false);

	game.fill();

	game.closePath();

	game.stroke();

	game.restore();

	

	if (typeof(last_hand) == "object")

		anim.clearRect((last_hand[0] - 1) * size, title_height + (last_hand[1] - 1) * size, size, size);

	last_hand = [x, y];

	

	anim.save();

	anim.translate(0, title_height); /* Reserved height for title*/

	anim.beginPath();

	anim.lineWidth = line_width + 1;

	anim.strokeStyle = color;

	anim.arc((x - 1) * size + size / 2, (y - 1) * size + size / 2, radius, 0, 2 * Math.PI, false);

	anim.closePath();

	anim.stroke();

	anim.restore();

}

		

function DrawLine(x1, y1, x2, y2, color) {

	game.save();

	game.beginPath();

	game.moveTo(x1, y1);

	game.lineTo(x2, y2);

	game.lineWidth = line_width;

	game.strokeStyle = color;

	game.closePath();

	game.stroke();

	game.restore();

}

		

function DrawBoard(width, height,tmp) {

	game.save();

	game.translate(0, title_height); /* Reserved height for title*/

	game.font = "16pt CustomFont";

	

	for (var x = 0; x <= width; x++)

		DrawLine(x * size, 0, x * size, height * size, "black");

	for (var y = 0; y <= height; y++)

		DrawLine(0, y * size, width * size, y * size, "black");



	for (var x = 0; x < width; x++) {

		for (var y = 0; y < height; y++) {
				if(x<tmp){
				game.drawImage(background,x*size,y*size,50,50);
				}
				else{
				game.drawImage(background2,x*size,y*size+25,50,25);
				}
		}

	}

	

	game.restore();

}

		

function DrawPlayerName() {

	var player2_name = (ai_mode) ? "Computer" : player2;

	

	game.save();

	game.font = "18pt CustomFont";

			

	game.fillStyle = player1_color;

	game.fillText(player1, 0, Math.ceil((title_height - MeasureText(player1, false, "CustomFont", 18)[1]) / 2));

			

	game.fillStyle = player2_color;

	game.fillText(player2_name, temp * size - MeasureText(player2_name, false, "CustomFont", 18)[0],

				  Math.ceil((title_height - MeasureText(player2_name, false, "CustomFont", 18)[1]) / 2));

			

	game.restore();

}

		

function DrawArrow(count) {

	var player2_name = (ai_mode) ? "Computer" : player2;

	

	if (typeof(count) == "undefined") {

		count = 1;

		clearTimeout(drawarrow_token);

	}

	

	game.save();

	

	var player1_text_info = MeasureText(player1, false, "CustomFont", 18);

	var player2_text_info = MeasureText(player2_name, false, "CustomFont", 18);

	

	game.clearRect(temp * size - player2_text_info[0] - right_arrow.width - 10 - 10,

						Math.ceil((title_height - player2_text_info[1]) / 2), right_arrow.width + 10, right_arrow.height);

	game.clearRect(player1_text_info[0] + 10, Math.ceil((title_height - player1_text_info[1]) / 2),

				left_arrow.width + 10, left_arrow.height);

				

	if (color == player1_color) {

		game.drawImage(left_arrow, player1_text_info[0] + 10 + (count % 2) * 10, Math.ceil((title_height - player1_text_info[1]) / 2));

	} else {

		game.drawImage(right_arrow, temp * size - player2_text_info[0] - right_arrow.width - 10 - (count % 2) * 10,

						Math.ceil((title_height - player2_text_info[1]) / 2));

	}

	

	drawarrow_token = setTimeout("DrawArrow(" + (count + 1) + ")", 500);	

	game.restore();

}



function ShowMessage(title, message) {

	var type = BootstrapDialog.TYPE_DEFAULT;

	var cssClass = "btn-warning";

	

	if (title == "警告") {

		type = BootstrapDialog.TYPE_WARNING;

		cssClass = "btn-warning";

	} else if (title == "錯誤") {

		type = BootstrapDialog.TYPE_DANGER;

		cssClass = "btn-danger";

	} else if (title == "提示") {

		type = BootstrapDialog.TYPE_INFO;

		cssClass = "btn-info";

	}

		

	BootstrapDialog.show({

		title: title,

		message: message,

		type: type,

		buttons: [{

			label: "確定",

			cssClass: cssClass,

			action: function(dialogRef){

					dialogRef.close();

                }

			}]

		});

}

function Restart(time) {
	if (typeof(time) == "undefined"){
		time = 5;

	}
	/*clearTimeout(timeout_token);*/
	$.get("AI.php?current="+col+"&m="+max+"&n="+row+"&mode="+ai_mode+"&level="+level+"&turn="+turn+"&t="+timeout, function(data, status){Init();} );
	

	if (time != 0) {
		setTimeout("Restart(" + (time - 1) + ")", 50);
	}
}



function PlayerTimeout() {
	var miniutes = parseInt(t.time / 60);
	var seconds = parseInt(t.time % 60);

	if (miniutes.toString().length == 1){
		miniutes = "0" + miniutes;
	}
	
	if (seconds.toString().length == 1){
		seconds = "0" + seconds;
	}

	$("#timeout").html(miniutes + ":" + seconds);
	++t.time;

	/*if (time == 0) {
		color = (color == player1_color) ? player2_color : player1_color;
		DrawArrow();
		if (!ended)
			PlayerTimeout(timeout);
		if (ai_mode) {

		}

	} 
	else {*/
		/*timeout_token = setTimeout("PlayerTimeout(" + (time - 1) + ")", 1000);*/
		timer = setTimeout("PlayerTimeout()", 1000);
}



function EndGame(font_size) {

	var player2_name = (ai_mode) ? "Computer" : player2;

	var string;

	if (ended != 3)

		string = ((ended == 1) ? player1 : player2_name) + " Wins!";

	else

		string = "Tie!";



	BootstrapDialog.show({

		title: "遊戲結束",

		message: "<span style=\"font-size: 50pt;\">" + string + "</span>",

		buttons: [{  

			label: "重新遊戲",

			cssClass: "btn-primary", 

			action: function(dialogRef){ 

				/*Restart();*/

				dialogRef.close();

			}

		}, {  

			label: "確定",

			cssClass: "btn-primary", 

			action: function(dialogRef){ 

				dialogRef.close();

			}

		}]

	});

}





//********* Set up *********//



function SetupMax(){
	$.get("max.php?max=" + max, function(data, status) {

		BootstrapDialog.show({

			title: "最大拿取數",

			message: data,

			buttons: [{

				label: "確定",

				cssClass: "btn-primary",

				action: function(dialogRef){

					if (!isNaN(parseInt($("#max-set").val()))) {

						max = parseInt($("#max-set").val());

						/*Restart();*/

						dialogRef.close();

					} else {

						ShowMessage("警告", "還沒做。");

					}

                }

			}]

		});

	});
	
}

function SetupLevel() {
	$.get("level.php?level=" + level , function(data, status) {
	
	BootstrapDialog.show({

			title: "選擇難度",

			message: data,

			buttons: [{

				label: "確定",

				cssClass: "btn-primary",

				action: function(dialogRef){
					
						if( !($("#level-set").val().localeCompare("easy")))
							level = 1;
						else if(!($("#level-set").val().localeCompare("normal")))
							level = 2;
						else if($("#level-set").val() =="hard")
							level = 3;
						else if($("#level-set").val() =="hell")
							level = 4;
						else
							ShowMessage("警告", "還沒做。");
						Restart();

						dialogRef.close();

                }

			}]

		});
		
	});
}


function SetupPlayerName() {
	var player2_name = (!ai_mode) ? player2 : "Computer";
	$.get("player.php?ai=" + ai_mode + "&player1=" + player1 + "&player2=" + player2, function(data, status) {

		BootstrapDialog.show({

			title: "玩家名稱設定",

			message: data,

			buttons: [{

				label: "確定",

				cssClass: "btn-primary",

				action: function(dialogRef){
						player1 = $("#player1-set").val();
						player2 = $("#player2-set").val();
						/*Restart();*/
						dialogRef.close();
				}
			}]

		});

	});

}



function SetupSize() {

	$.get("size.php?col=" + row + "&row=" + col, function(data, status) {

		BootstrapDialog.show({

			title: "設定大小",

			message: data,

			buttons: [{

				label: "確定",

				cssClass: "btn-primary",

				action: function(dialogRef){

					if (

						!isNaN(parseInt($("#col-set").val())) ) {

						

						col = parseInt($("#col-set").val());

						

						/*Restart();*/

						dialogRef.close();

					} else {

						ShowMessage("警告", "還沒做。");

					}

                }

			}]

		});

	});

}

function SetupRock() {
	$.get("rock.php?rock=" + rock , function(data, status) {
	
	BootstrapDialog.show({

			title: "石頭樣式",

			message: data,

			buttons: [{

				label: "確定",

				cssClass: "btn-primary",

				action: function(dialogRef){
					
						background.src = 'res/'+$("#rock-set").val()+'.png';
						background2.src = 'res/'+$("#rock-set").val()+'2.png';
						Restart();

						dialogRef.close();

                }

			}]

		});
		
	});
}

function SetupTimeout() {

	$.get("timeout.php?t=" + timeout, function(data, status) {

		BootstrapDialog.show({

			title: "時間設定",

			message: data,

			buttons: [{

				label: "確定",

				cssClass: "btn-primary",

				action: function(dialogRef){

					if (!isNaN(parseInt($("#timeout-set").val())) && parseInt($("#timeout-set").val()) >= 10) {

						timeout = parseInt($("#timeout-set").val());

						

						/*Restart();*/

						dialogRef.close();

					} else if (isNaN(parseInt($("#timeout-set").val()))) {

						ShowMessage("警告", "請輸入數字。");

					} else {

						ShowMessage("警告", "輸入的秒數需至少10秒。");

					}

                }

			}]

		});

	});

}



function ShowModifyPage() {
	$.get("modify.php", function(data, status) {

		BootstrapDialog.show({

			title: "修改密碼",

			message: data,

			buttons: [{

				label: "更改密碼",

				cssClass: "btn-primary",

				action: function(dialogRef){

                    $.post("modify.php", $("form").serialize(), function(d, s) {

						if (d == "WRONG_INFO") {

							ShowMessage("錯誤", "舊密碼或新密碼不一致！");

						} else {

							ShowMessage("提示", "密碼更改成功！");

							dialogRef.close();

						}

					});

                }

			}]

		});

	});

}



//******* Shared Functions *******//



function Alloc2dArray(row, col) {
	var result = [];
	for (var i = 0; i <= row; i++) {
		var columns = [];
		for (var j = 0; j <= col; j++)
			columns[j] = 0;
		result[i] = columns;
	}
	return result;
}



function LeftTopPos(obj) {

	var result = [];

			

	result[0] = result[1] = 0;

	if(obj.offsetParent){

		while(obj.offsetParent){

			result[0] += obj.offsetLeft;

			result[1] += obj.offsetTop;

			obj = obj.offsetParent;

		}

	}

			

	return result;

}

function MeasureText(text, bold, font, size){
	var key = text + ":" + bold + ":" + font + ":" + size;
	if (typeof(measure_text_cache) == "object" && measure_text_cache[key]){
		return measure_text_cache[key];
	}
	var div = document.createElement("div");

	div.innerHTML = text;

	div.style.position = "absolute";

	div.style.top = "-100px";

	div.style.left = "-100px";

	div.style.fontFamily = font;

	div.style.fontWeight = (bold) ? "bold" : "normal";

	div.style.fontSize = size + "pt";

	document.body.appendChild(div);



	var size = [div.offsetWidth, div.offsetHeight];



	document.body.removeChild(div);

	

	if (typeof(measure_text_cache) != "object")

		measure_text_cache = [];

	measure_text_cache[key] = size;



	return size;

}


//******* AI Mode Functions *********//



function ChangeMode()
{
	if (!ai_mode) {
		ai_mode = 1;
		$("#mode").html("AI模式");
	}
	else {
		ai_mode = 0;
		$("#mode").html("普通模式");
	}
}
var temp;

function start()
{
	a();	
	Init();
};

function a(){
	var oXHR=new XMLHttpRequest();
	temp = col;
	var current = col;
	var m = max;
	var n = row;
	var	mode = ai_mode;
	
	
	document.cookie = "number"+"="+current;
	clearTimeout(timer);
	t.time = 0;
	PlayerTimeout();
	/*print_time();*/
	b(current,m,n,mode,level,turn);
}

function b(current,m,n,mode,level,turn){
	var oXHR=new XMLHttpRequest();  /*IE5相容性問題未解決*/
	para= "current="+current+"&m="+m+"&n="+n+"&mode="+mode+"&level="+level+"&turn="+turn+"&t="+t.time;   /* encodeURIComponent(para); URL編碼 要傳給AI的參數*/
	
	oXHR.open("POST","http://140.116.245.148/WebCourse/students/f74024070/project1/AI.php",true);
	oXHR.setRequestHeader("cache-control","no-cache"); 
　　oXHR.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
　　oXHR.setRequestHeader("contentType","text/html;charset=uft-8");

	if( mode == 1 )		/*智者*/
	{
		oXHR.onreadystatechange= function()
		{
			if(oXHR.readyState==4 &&(oXHR.status==200||oXHR.status==304))
			{
				var p=document.getElementById("present");
				var str = oXHR.responseText; 	/*用regular expresion 來抓 AI.php 傳回來的 剩下的石頭數*/
				var res = str.match(/\d+/g);
				var res1 = str.match(/You/g);
				if(!res1)
				{
					p.innerHTML=oXHR.responseText+"<div id=but ></div>";		/*印出來自 AI.php 的資訊*/
					for(var j=1;j<=m;++j)		/*印出玩家可選的拿取數*/
					{    
						var b =  document.getElementById("but");
						var butt = document.createElement("INPUT");
						butt.setAttribute("type","button");
						butt.setAttribute("value",j);
						butt.setAttribute("onclick","b("+res[1]+"-"+j+","+m+","+n+","+mode+","+level+","+turn+")");
						b.appendChild(butt);
					}
					col = res[1];
					Init();
				}
			else
			{
				clearTimeout(timer);
					col = 0;
					Init();
				p.innerHTML=oXHR.responseText;
					col = temp;
			}
		}
	}
	}
	else		/*雙人*/
	{
		oXHR.onreadystatechange= function()
	{
		if(oXHR.readyState==4 &&(oXHR.status==200||oXHR.status==304))
		{
			var p=document.getElementById("present");
			var str = oXHR.responseText; 	/*用regular expresion 來抓 AI.php 傳回來的 剩下的石頭數*/
			var res = str.match(/\d+/g);
			turn = turn?0:1;
			var res1 = str.match(/Winner/g);
			if(!res1)
			{
				p.innerHTML=oXHR.responseText+"<div id=but ></div>";		/*印出來自 AI.php 的資訊*/
				for(var j=1;j<=m;++j)		/*印出玩家可選的拿取數*/
				{    
					var b =  document.getElementById("but");
					var butt = document.createElement("INPUT");
					butt.setAttribute("type","button");
					butt.setAttribute("value",j);
					butt.setAttribute("onclick","b("+res[0]+"-"+j+","+m+","+n+","+mode+","+level+","+turn+")");
					b.appendChild(butt);
				}
					col = res[0];
					Init();
			}
			else
			{
				clearTimeout(timer);
					col = 0;
					Init();
				p.innerHTML=oXHR.responseText;
				col = temp;
			}
		}
	}
	}
	oXHR.send(para);
}

function print_time()
{
	++t.time;
	var p=document.getElementById("time");
	p.innerHTML = "time: "+t.time+"s";
	timer = setTimeout("print_time()",1000);
}

function rank()
{
	var oXHR=new XMLHttpRequest();  /*IE5相容性問題未解決*/
	
	oXHR.open("POST","http://140.116.245.148/WebCourse/students/f74024070/project1/AI_rank.php",true);
	oXHR.setRequestHeader("cache-control","no-cache"); 
　　oXHR.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
　　oXHR.setRequestHeader("contentType","text/html;charset=uft-8");

	oXHR.onreadystatechange= function()
	{
		if(oXHR.readyState==4 &&(oXHR.status==200||oXHR.status==304))
			{
					BootstrapDialog.show({title: "排行榜", message: oXHR.responseText});
				//var p = document.getElementById("present");
				//p.innerHTML = oXHR.responseText;
				}
	}
	oXHR.send();
	
}

window.onresize = function() {

	game_canvas.style.left = ((window.innerWidth - game_canvas.width) / 2 > 6) ? (window.innerWidth - game_canvas.width) / 2 : 6;

	anim_canvas.style.left = game_canvas.style.left;

}



left_arrow.onload = DrawArrow;