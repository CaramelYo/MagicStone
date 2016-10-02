<?php
	include_once "./AI_mysql_config.php";

	//中文output
	mysql_query("SET NAMES 'utf8'");
	mysql_query("SET CHARACTER_SET_CLIENT='utf8'");
	mysql_query("SET CHARACTER_SET_RESULTS='utf8'");
	
	error_reporting(0);
	
	$result1= mysql_query("SELECT * FROM `rank`", $link);
	$i = 0;
	
	while( 1 )
	{
		$rows=mysql_fetch_array($result1,MYSQL_ASSOC);
		
		echo "偉名: $rows[account] 難度: ";
		switch($rows['level'])
		{
			case 1:
			{
				echo "easy 石頭數: $rows[number] 最大拿取: $rows[max] 時間: $rows[time]<br>";
				break;
			}
			case 2:
			{
				echo "normal 石頭數: $rows[number] 最大拿取: $rows[max] 時間: $rows[time]<br>";
				break;
			}
			case 3:
			{
				echo "hard 石頭數: $rows[number] 最大拿取: $rows[max] 時間: $rows[time]<br>";
				break;
			}		
			case 4:
			{
				echo "hell 石頭數: $rows[number] 最大拿取: $rows[max] 時間: $rows[time]<br>";
				break;
			}
			default :
				break;
		}
		++$i;
		if ( !mysql_data_seek($result1,$i) )
			break;
	}
?>