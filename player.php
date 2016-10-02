<div class="input-group">
	<span class="input-group-addon">玩家一名稱</span><input class="form-control" id="player1-set" type="text" value="<?php echo $_GET["player1"]; ?>">
</div><br>
<div class="input-group">
	<span class="input-group-addon">玩家二名稱</span><input class="form-control" id="player2-set" type="text" value="<?php echo $_GET["player2"]; ?>" <?php if ($_GET["ai"]) echo "disabled"; ?>>
</div>