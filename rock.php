<div class="input-group">

	<span class="input-group-addon">Style</span><select class="form-control" id="rock-set" name="size">

<?php
		$array = array("egg", "cookie", "rice");
		for ($i = 0 ; $i <= 2; $i++){

			if ($array[$i] == $_GET["rock"])
				echo "<option selected>";

			else
				echo "<option>";

			echo $array[$i]  . "</option>";

		}

?>

	</select>

</div>