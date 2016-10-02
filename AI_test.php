<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Rock</title>

<script type="text/javascript" language="javascript" charset="utf-8">
var timer;
var t = { time: 0 };	//to be passed by reference

function a(){
	var oXHR=new XMLHttpRequest();  //IE5相容性問題未解決
	var current = document.getElementById("current").value;
	var m = document.getElementById("m").value;
	var n = document.getElementById("n").value;
	var	mode = document.getElementById("mode").value;
	var level = document.getElementById("level").value;
	var turn = 0;
	document.cookie = "number"+"="+current;
	clearTimeout(timer);
	t.time = -1;
	print_time();
	b(current,m,n,mode,level,turn);
}

function b(current,m,n,mode,level,turn){
	var oXHR=new XMLHttpRequest();  //IE5相容性問題未解決
	para= "current="+current+"&m="+m+"&n="+n+"&mode="+mode+"&level="+level+"&turn="+turn+"&t="+t.time;   // encodeURIComponent(para); URL編碼 要傳給AI的參數
	
	oXHR.open("POST","http://140.116.245.148/WebCourse/students/f74024070/project/AI.php",true);
	oXHR.setRequestHeader("cache-control","no-cache"); 
　　oXHR.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
　　oXHR.setRequestHeader("contentType","text/html;charset=uft-8");

//	oXHR.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
//	oXHR.setRequestHeader("Content-length", para.length);
//	oXHR.setRequestHeader("Connection", "close");

	//!mode????
	if( mode == 0 )		//智者
	{
		oXHR.onreadystatechange= function()
		{
			if(oXHR.readyState==4 &&(oXHR.status==200||oXHR.status==304))
			{
				var p=document.getElementById("present");
				var str = oXHR.responseText; 	//用regular expresion 來抓 AI.php 傳回來的 剩下的石頭數
				var res = str.match(/\d+/g);
				var res1 = str.match(/You/g);
				if(!res1)
				{
					p.innerHTML=oXHR.responseText+"<div id=but ></div>";		//印出來自 AI.php 的資訊
					for(var j=1;j<=m;++j)		//印出玩家可選的拿取數
					{    
						var b =  document.getElementById("but");
						var butt = document.createElement("INPUT");
						butt.setAttribute("type","button");
						butt.setAttribute("value",j);
						butt.setAttribute("onclick","b("+res[1]+"-"+j+","+m+","+n+","+mode+","+level+","+turn+")");
						b.appendChild(butt);
					}
				}
			else
			{
				clearTimeout(timer);
				p.innerHTML=oXHR.responseText;
			}
		}
	}
	}
	else		//雙人
	{
		oXHR.onreadystatechange= function()
	{
		if(oXHR.readyState==4 &&(oXHR.status==200||oXHR.status==304))
		{
			var p=document.getElementById("present");
			var str = oXHR.responseText; 	//用regular expresion 來抓 AI.php 傳回來的 剩下的石頭數
			var res = str.match(/\d+/g);
			turn = turn?0:1;
			var res1 = str.match(/Winner/g);
			if(!res1)
			{
				p.innerHTML=oXHR.responseText+"<div id=but ></div>";		//印出來自 AI.php 的資訊
				for(var j=1;j<=m;++j)		//印出玩家可選的拿取數
				{    
					var b =  document.getElementById("but");
					var butt = document.createElement("INPUT");
					butt.setAttribute("type","button");
					butt.setAttribute("value",j);
					butt.setAttribute("onclick","b("+res[0]+"-"+j+","+m+","+n+","+mode+","+level+","+turn+")");
					b.appendChild(butt);
				}
			}
			else
			{
				clearTimeout(timer);
				p.innerHTML=oXHR.responseText;
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
	var oXHR=new XMLHttpRequest();  //IE5相容性問題未解決
	
	oXHR.open("POST","http://140.116.245.148/WebCourse/students/f74024070/project/AI_rank.php",true);
	oXHR.setRequestHeader("cache-control","no-cache"); 
　　oXHR.setRequestHeader("Content-Type","application/x-www-form-urlencoded"); 
　　oXHR.setRequestHeader("contentType","text/html;charset=uft-8");

	oXHR.onreadystatechange= function()
	{
		if(oXHR.readyState==4 &&(oXHR.status==200||oXHR.status==304))
		{
			var p=document.getElementById("present");
			p.innerHTML=oXHR.responseText;		//印出來自 AI_rank.php 的資訊
		}
	}
	oXHR.send();
}

</script>
</head>

<body>

<h2>
<?php
	echo "你好， $_COOKIE[name] <br>";
?>
</h2>
<h2>撿石</h2>

<form>
   石頭總數: <input type="text" id="current" name="current"><br>
   最大拿取數: <input type="text" id="m" name="m"><br>
   堆數:
	<select id="n" name="n">
		<option value="1">一堆</option><br>
	</select><br>
	對戰模式:
	<select id="mode" name="mode">
		<option value="0">挑戰智者</option>
		<option value="1">雙人對戰</option><br>
	</select><br>
	難度:
	<select id="level" name="level">
		<option value="1">easy</option>
		<option value="2">normal</option>
		<option value="3">hard</option>
		<option value="4">hell</option><br>
	</select><br>
   <input type="reset" value="clear">
   <input type="button" value="challenge!!" onclick="a()">
   <input type="button" value="rank!!" onclick="rank()">
   <a href="http://140.116.245.148/WebCourse/students/f74024070/project/AI_logout.php">Logout</a>
   <input type="hidden" name="amount" value="1">
</form>

<div id="time"></div>
<div id="present"></div>

</body>

</html>
