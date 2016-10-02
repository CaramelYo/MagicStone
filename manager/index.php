<?php
	require_once("../include.inc.php");
?>

<html>
    <head>
		<meta charset="utf-8">
		<title>漢字五子棋</title>

		<link href="../css/bootstrap.min.css" rel="stylesheet">
		<link href="../css/simple-sidebar.css" rel="stylesheet">
		<link href="../css/bootstrap-dialog.min.css" rel="stylesheet">
		<link href="../css/style.css" rel="stylesheet">

		<script type="text/javascript" src="../js/jquery.js"></script>
		<script type="text/javascript" src="../js/bootstrap.min.js"></script>
		<script type="text/javascript" src="../js/bootstrap-dialog.min.js"></script>
	</head>
    <body style="text-align:center;">
		<div id="wrapper">
			<div id="sidebar-wrapper">
				<ul class="sidebar-nav">
					<li class="navbar"></li>
					<li><a href=".." style="height: 65px; font-size: 18px; line-height: 65px; color: white;">漢字五子棋</a></li>
					<li><a href=".">辭庫列表</a><li>
					<li><a href="modify.php">新增辭庫</a><li>
					<li><a href="..">返回遊戲</a><li>
					<li><a href="../logout.php">登出</a><li>
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
			</nav>
			<!-- 內容 -->
			<div class="container top-buffer">
				<div class="row">
					<div class="col-md-4 col-md-offset-4">
						<table class="table table-striped">
							<tr><th>辭庫名稱</th><th>操作</th></tr>
							<?php
								$result = $database->query("SELECT * FROM questions WHERE account='" . $_SESSION["account"] . "'");
								while($row = $result->fetch_array()) {
									echo "<tr><td>" . $row["name"] . "</td>";
									echo "<td><button class=\"btn btn-xs btn-success\" onclick=\"location.href='modify.php?id=" . $row["id"] . "';\">修改</button>&nbsp;&nbsp;";
									echo "<button class=\"btn btn-xs btn-danger\" onclick=\"location.href='delete.php?id=" . $row["id"] . "';\">刪除</button></td></tr>";
								}
							?>
						</table>
					</div>
				</div>
			</div>
		</div>
		
		<script>
			$("#menu-toggle").click(function(e) {
				e.preventDefault();
				$("#wrapper").toggleClass("toggled");
			});
		</script>
    </body>
</html>