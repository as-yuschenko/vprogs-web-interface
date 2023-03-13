<?php

$ppDevID = $_GET['id'];

/*DEV*/
echo'<h3>С2000-ПП</h3>';
include ('ppdev_edit.php');

/*HAND ADD*/
echo'<div style = "display:flex;">';
include ('zone_add_form.php');
include ('part_add_form.php');
include ('relay_add_form.php');
include ('user_add_form.php');
echo'</div>';

/*ZONES*/

echo'
<br>
<p><b>ZONES</b></p>';

include ('zone_edit.php');



/*PARTS*/
echo'
<br>
<p><b>PARTS</b></p>';
include ('part_edit.php');


/*RELAYS*/
echo'
<br>
<p><b>RELAYS</b></p>';
include ('relay_edit.php');



/*USERS*/
echo'
<br>
<p><b>USERS</b></p>';
include ('user_edit.php');



/*ORION PARTS*/
echo'
<br>
<h3>ORION PARTS</h3>
<table border=1>
<tr>
	<th class="">Номер</th>
	<th class="">Описание</th>

</tr>
';

	$result = $db->query('Select id,num,desc from orPart where ppDevID ='.$ppDevID);


	while ($row = $result->fetchArray(SQLITE3_ASSOC)) 
	{
		//printr ($row);
	
		echo ("<tr>\n");
		echo("<td>".$row['num']."</td>");	
		//echo('<td><input type="text" name="p'.$row['id'].'" value="'.$row['desc'].'"></td>');
		echo('<td>'.$row['desc'].'</td>');
		echo ("</tr>");
		
	}

echo '
</table>
<br>
';


/*ORION USERS*/
echo'
<br>
<h3>ORION USERS</h3>
<table border=1>
<tr>
	<th class="">Номер</td>
	<th class="">Пароль</td>
	<th class="">Описание</td>

</tr>
';

	$result = $db->query('Select id,num,pwd,desc from orUser where ppDevID ='.$ppDevID);


	while ($row = $result->fetchArray(SQLITE3_ASSOC)) 
	{
		//printr ($row);
	
		echo ("<tr>\n");
		echo("<td>".$row['num']."</td>");	
		echo("<td>".$row['pwd']."</td>");	
		echo('<td>'.$row['desc'].'</td>');
		//echo('<td><input type="text" name="u'.$row['id'].'" value="'.$row['desc'].'"></td>');
		echo ("</tr>");
		
	}

echo '
</table>
<br>
';

/*REMOVE ppDev*/
echo'
<hr>
<p align = "center">
<form action = "settings/ppdev_rm.php" method="post">
<input type="hidden" name="ppDevID" value="'.$ppDevID.'">
<input type="submit" value="Удалить устройство">
</form>
</p>
'
;
?>
