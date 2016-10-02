<div class="input-group">

	<span class="input-group-addon">顆數</span><select class="form-control" id="max-set" name="size">

<?php

		for ($i = 2; $i <= 5; $i++)

		{

			if ($i == $_GET["max"])

				echo "<option selected>";

			else

				echo "<option>";

			echo $i . "</option>";

		}

?>

	</select>

</div>