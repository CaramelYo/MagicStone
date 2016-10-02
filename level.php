<div class="input-group">

	<span class="input-group-addon">Difficulty</span><select class="form-control" id="level-set" name="size">

<?php

		$array = array("easy", "normal", "hard","hell");
		for ($i = 0 ; $i <= 3; $i++){

			if ($array[$i] == $_GET["level"])
				echo "<option selected>";

			else
				echo "<option>";

			echo $array[$i]  . "</option>";

		}

?>

	</select>

</div>