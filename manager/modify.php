<?php
	require_once("../include.inc.php");
	
	if (!IsLoggedIn()) {
		Header("Location: .");
		exit(0);
	}
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		for ($i = 0; $i < count($_POST["question"]); $i++) {
			if (strlen($_POST["question"][$i]) && strlen($_POST["answer"][$i])) {
				$question = $question . $_POST["question"][$i] . "\n";
				$answer = $answer . $_POST["answer"][$i] . "\n";
			}
		}
		
		if (strlen($_POST["name"]) && strlen($_POST["vignette"]) && strlen($question) && strlen($answer)) {
			$question = substr($question, 0, -1);
			$answer = substr($answer, 0, -1);
			
			if (strlen($_GET["id"]))
				$database->query("UPDATE questions SET `name`='" . $_POST["name"] . "', `question`='" . $question . "', `answer`='" . $answer . "', `vignette`='" . $_POST["vignette"] . "' WHERE id=" . $_GET["id"]);
			else
				$database->query("INSERT INTO questions (`account`, `name`, `question`, `answer`, `vignette`) VALUES ('" . $_SESSION["account"] . "', '" . $_POST["name"] . "', '" . $question . "', '" . $answer . "', '" . $_POST["vignette"] . "')");
			
			Header("Location: .");
		} else {
			$error_msg = "請輸入完整的題庫資料！";
		}
	}
	
	if (strlen($_GET["id"])) {
		$result = $database->query("SELECT * FROM questions WHERE id=" . $_GET["id"] . " AND account='" . $_SESSION["account"] . "'");
		if ($result->num_rows) {
			$questions = $result->fetch_array();
		} else {
			$error_msg = "指定辭庫不存在！";
		}
	}
?>

<html>
    <head>
		<meta charset="utf-8">
		<title>漢字五子棋 - 新增辭庫</title>

		<link href="../css/bootstrap.min.css" rel="stylesheet">
		<link href="../css/simple-sidebar.css" rel="stylesheet">
		<link href="../css/bootstrap-dialog.min.css" rel="stylesheet">
		<link href="../css/style.css" rel="stylesheet">

		<script type="text/javascript" src="../js/jquery.js"></script>
		<script type="text/javascript" src="../js/bootstrap.min.js"></script>
		<script type="text/javascript" src="../js/bootstrap-dialog.min.js"></script>
		<script type="text/javascript" src="js/script.js"></script>
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
						<form class="form" method="post">
							<div class="input-group">
								<span class="input-group-addon">辭庫名稱</span>
								<input class="form-control" name="name" type="text" value="<?php echo $questions["name"]; ?>">
							</div><br>
							<div class="input-group">
								<span class="input-group-addon">題目：請選出『…』的</span>
								<input type="text" class="form-control" name="vignette" placeholder="例如: 正確答案" value="<?php echo $questions["vignette"]; ?>">
							</div><br>
							<table class="table table-striped" id="quest-table">
								<tr><th>題目</th><th>答案</th></tr>
								<?php
									$titles = explode("\n", $questions["question"]);
									$answers = explode("\n", $questions["answer"]);
									
									for ($i = 0; $i < count($titles); $i++)
									{
										echo "<tr><td><input name=\"question[]\" class=\"form-control\" style=\"width: 100%;\" type=\"text\" value=\"" . $titles[$i] . "\"></td>";
										echo "<td><input name=\"answer[]\" class=\"form-control\" style=\"width: 100%;\" type=\"text\" value=\"" . $answers[$i] . "\"></td></tr>";
									}
									
									echo "<tr><td></td><td><span class=\"btn btn-sm btn-success\" onclick=\"AddRow();\">新增欄位</button></span></tr>";
								?>
							</table><br>
							<button class="btn btn-default btn-primary" type="submit">儲存辭庫</button>
						</form>
					</div>
				</div>
			</div>
		</div>
		
		<script>
			$("#menu-toggle").click(function(e) {
				e.preventDefault();
				$("#wrapper").toggleClass("toggled");
			});
			
			function AddRow() {
				$("#quest-table tr:last").before("<tr><td><input name=\"question[]\" class=\"form-control\" style=\"width: 100%;\" type=\"text\"></td><td><input name=\"answer[]\" class=\"form-control\" style=\"width: 100%;\" type=\"text\"></td></tr>");
			}
		</script>
		
		<?php
			if ($error_msg == "請輸入完整的題庫資料！")
				echo "<script>ShowMessage(\"警告\", \"" . $error_msg ."\")</script>";
			if ($error_msg == "指定辭庫不存在！")
				echo "<script>ShowMessage(\"錯誤\", \"" . $error_msg ."\")</script>";
		?>
    </body>
</html>