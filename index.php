<?php
	require_once("include.inc.php");
?>

<html>
    <head>
		<meta charset="utf-8">
		<title>Magic Stone</title>

		<link href="css1/bootstrap.min.css" rel="stylesheet">
		<link href="css1/simple-sidebar.css" rel="stylesheet">
		<link href="css1/bootstrap-dialog.min.css" rel="stylesheet">
		<link href="css1/style.css" rel="stylesheet">

		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/bootstrap.min.js"></script>
		<script type="text/javascript" src="js/bootstrap-dialog.min.js"></script>
	</head>
    <body style="text-align:center;" onload="start();">
		<div id="wrapper">
			<div id="sidebar-wrapper">
				<ul class="sidebar-nav">
					<li class="navbar"></li>
					<li><a href="." style="height: 65px; font-size: 18px; line-height: 65px; color: white;">Magic Stone</a></li>
					<li><a href="#" onclick="SetupRock();">選擇石頭</a></li>
					<li><a href="#" onclick="SetupPlayerName();">玩家名稱設定</a></li>
					<li><a href="#" onclick="SetupSize();">設定大小</a></li>										<li><a href="#" onclick="SetupMax();">最大拿取數</a></li>
					<li><a href="#" id="mode" onclick="ChangeMode();">AI模式</a></li>										<li><a href="#" id="mode" onclick="SetupLevel();">難度</a></li>					<li><a href="#" onclick="rank();">排行榜</a></li>										<li><a href="#" onclick="start();">開始遊戲</a></li>
					<li><a href="http://140.116.245.148/WebCourse/students/f74024070/project1/AI_logout.php">登出</a></li>
				</ul>
			</div>
		</div>
		<div id="page-content-wrapper">
			<!-- 導航欄 -->
			<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
				<div class="container-fluid">
					<div class="navbar-header">
						<a href="#menu-toggle" class="navbar-brand" id="menu-toggle"><span class="glyphicon glyphicon-list"></span></a>
					</div>
					<div class="collapse navbar-collapse">
						<div class="nav navbar-nav pull-right">
							<span class="navbar-text" id="cur-mode" style="color: yellow;"></span>
							<span class="navbar-text" id="timeout" style="color: yellow;"></span>
						</div>
					</div>
			</nav>
			<!-- 內容 -->
			<div class="container top-buffer">
				<div class="row">
				<canvas></canvas>				<br><br><br><br><br>				<canvas></canvas>				<div id="content_photo"></div>				<br><br><br><br><br><br><br><br><br><br><br><br>				<div id="present"></div>				
				</div>
			</div>
		</div>
		<script src="js/script.js"></script>		<!--工具欄收放-->
		<script>
			$("#menu-toggle").click(function(e) {
				e.preventDefault();
				$("#wrapper").toggleClass("toggled");
			});
		</script>
    </body>
</html>