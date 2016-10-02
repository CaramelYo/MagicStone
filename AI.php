<?php
	
header("Content-Type:text/html;charset=utf-8");

//$_REQUEST[current] 目前石數 $_REQUEST[m] 最大拿數 $_REQUEST[n] 堆數 $_REQUEST[mode] 單/雙人 $_REQUES[level] 難度 $_REQUEST[turn] 換誰， 0=>藍方 1=>紅方 $_REQUEST[t]	時間

switch($_REQUEST['mode'])
{
	case 1:	//智者
	{
		if( $_REQUEST['current'] == NULL )	// to test if data is set
		{
			echo "快輸入資料吧……<br>老朽等到心臟都快停了啊!!<br>";
			break;
		}
		
		if( $_REQUEST['current'] <= 0 )		//ending
		{
			echo "完成時間: $_REQUEST[t] s<br>";
			echo "你比我還聰明!!<br>You are the winner!!<br>";
			echo "……話畢智者大笑三聲後，就嚥下最後一口氣<br>";
			echo "你是殺人兇手!!<br>";
			
			// to update
			include_once "./AI_mysql_config.php";

			//中文output
			mysql_query("SET NAMES 'utf8'");
			mysql_query("SET CHARACTER_SET_CLIENT='utf8'");
			mysql_query("SET CHARACTER_SET_RESULTS='utf8'");
			
			$result1= mysql_query("SELECT * FROM `rank` WHERE level='$_REQUEST[level]' AND number='$_COOKIE[number]' AND max='$_REQUEST[m]'", $link);
			$rows=mysql_fetch_array($result1,MYSQL_ASSOC);
			
			if( $rows )
			{
				//there is old grade
				if( $rows['time'] <= $_REQUEST['t'] )
					echo "你還太嫩了……再接再厲<br>";
				else
				{
					//to update the better grade
					mysql_query("UPDATE `rank` SET `time` = '$_REQUEST[t]', `account` = '$_COOKIE[account]' WHERE `rank`.`level` = '$_REQUEST[level]' AND `rank`.`number` = '$_COOKIE[number]' AND `rank`.`max` = '$_REQUEST[m]' AND `rank`.`time` = '$rows[time]'",$link);
				}
			}
			else
			{
				//new grade
				mysql_query("INSERT INTO `rank` (`account`, `level`, `number`, `max`, `time`) VALUES ('$_COOKIE[account]', '$_REQUEST[level]', '$_COOKIE[number]', '$_REQUEST[m]', '$_REQUEST[t]')",$link);
			}
		}
		else		//AI get stone
		{
			$temp = $_REQUEST['current'] % ( $_REQUEST['m'] + 1 );
			$temp1 = rand(1,4);
			if($temp && ( 0.25*$_REQUEST['level'] >= 0.25*$temp1) )
			{
				//good choice
				$_REQUEST['current'] -= $temp;
				echo "從老人的天靈蓋上噴出了一道聖光!!<br>";
				echo "智者拿走了 $temp 顆石頭<br>目前剩餘石頭數: $_REQUEST[current]<br>";
				
				if( $_REQUEST['current'] <= 0 )
				{
					echo "浪費時間: $_REQUEST[t] s<br>";
					echo "你是個智障!!<br>You are the looooooser!!<br>";
				}
			}
			else
			{
				$temp = rand(1,$_REQUEST['m']);
				$_REQUEST['current'] -= $temp;
				echo "智者拿走了 $temp 顆石頭<br>目前剩餘石頭數: $_REQUEST[current]<br>";
				if( $_REQUEST['current']<= 0 )
				{
					echo "浪費時間: $_REQUEST[t] s<br>";
					echo "你是個智障!!<br>You are the looooooser!!<br>";
				}
			}
		}
		break;
	}
	case 0:	//雙人
	{
		if( $_REQUEST['current'] == NULL)	// to test if data is set
		{
			echo "快輸入資料吧……<br>老朽等到心臟都快停了啊!!<br>";
			break;
		}
		
		if( $_REQUEST['current'] <= 0 )
		{
			if( !$_REQUEST['turn'] )
				echo "Winner is……紅色角落…魔鬼筋肉人!!<br>";
			else
				echo "Winner is……藍色角落…魔鬼筋肉人!!<br>";
		}
		else
		{
			if( $_REQUEST['turn'] )
			{
				echo "目前剩餘石頭數: $_REQUEST[current]<br>";
				echo "紅色角落，請取石!!<br>";
			}
			else
			{
				echo "目前剩餘石頭數: $_REQUEST[current]<br>";
				echo "藍色角落，請取石!!<br>";
			}
		}
		break;
	}
	default:
	{
		echo "mode error<br>";
		break;
	}
}
?>