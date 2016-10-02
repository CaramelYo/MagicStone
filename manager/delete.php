<?php
	require_once("../include.inc.php");
	
	if (!IsLoggedIn()) {
		Header("Location: .");
		exit(0);
	}
	
	if (strlen($_GET["id"])) {
		$result = $database->query("SELECT * FROM questions WHERE id=" . $_GET["id"] . " AND account='" . $_SESSION["account"] . "'");
		if ($result->num_rows) {
			$database->query("DELETE FROM questions WHERE id=" . $_GET["id"] . " AND account='" . $_SESSION["account"] . "'");
			$database->query("DELETE FROM rank WHERE dict=" . $_GET["id"]);
			
			Header("Location: .");
		} else {
			echo <<< END
				<link href="../css/bootstrap.min.css" rel="stylesheet">
				<link href="../css/simple-sidebar.css" rel="stylesheet">
				<link href="../css/bootstrap-dialog.min.css" rel="stylesheet">
				<link href="../css/style.css" rel="stylesheet">

				<script type="text/javascript" src="../js/jquery.js"></script>
				<script type="text/javascript" src="../js/bootstrap.min.js"></script>
				<script type="text/javascript" src="../js/bootstrap-dialog.min.js"></script>
				<script type="text/javascript" src="js/script.js"></script>
				<body>
					<script>
						ShowMessage("錯誤", "指定辭庫不存在！");
					</script>
				</body>
END;
		}
	}
	
?>