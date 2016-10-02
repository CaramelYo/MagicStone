<div class="input-group">
	<span class="input-group-addon">顆數</span><select class="form-control" id="col-set" name="size">
<?php
		for ($i = 5; $i <= 20; $i++)
		{
			if ($i == $_GET["col"])
				echo "<option selected>";
			else
				echo "<option>";
			echo $i . "</option>";
		}
?>
	</select>
</div><br>