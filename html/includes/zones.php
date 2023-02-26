<?php
//var_dump($_GET);
//echo ('<h1>Зоны</h1>');

$ppDevAddr = 0;
$portID = 0;
$result = $db->query("Select addr, portID from ppDev where ppDev.id=$_GET[dev_id]");
while ($row = $result->fetchArray(SQLITE3_ASSOC)) 
{
	$ppDevAddr = $row['addr'];
	$portID = $row['portID']; 
}
echo ("<h2>Зоны Порт $portID Адрес $ppDevAddr</h2>");


if (empty($_GET['do']))
{
	sendCommand ($portID, ETYPE_ZONE."-0-".$ppDevAddr."-0-".ACT_READ_STATE);
}
else
{
	sendCommand ($portID, $_GET['do']);	
}
$result = $db->query("
SELECT t1.id,t1.ppDevID,num,t1.type,t1.hpState,t1.lpState,t1.counter,t1.zd,t1.hpt,t1.hpd, eDesc.desc lpd FROM
(
SELECT ppZone.id,ppDevID,num,ppZoneTypeID type,hpState,lpState,counter,ppZone.desc zd, eDesc.eTypeID hpt,eDesc.desc hpd  
from ppZone
LEFT JOIN  eDesc on ppZone.hpState = eDesc.id 
where ppZone.ppDevID = $_GET[dev_id]
) t1
LEFT JOIN eDesc on t1.lpState = eDesc.id");

	while ($row = $result->fetchArray(SQLITE3_ASSOC)) 
	{
		
		switch ($row['hpState'])
		{
			case 24: //Взятие входа на охрану
				$color="bg_green";
			break;
			
			case 3: //Тревога проникновения
			case 37: //Пожар
			case 40: //Пожар 2
			case 44: //Внимание!
			case 58: //Тихая тревога
				$color="bg_red";
			break;
			
			case 2: //Авария сети 220В
			case 4: //Помеха
			case 17: //Неудачное взятие
			case 41: //Неиспр.  оборудования
			case 45: //Обрыв  входа
			case 46: //Обрыв ДПЛС
			case 82: //Неисправность термометра
			case 90: //Неисправность канала связи
			case 121: //Обрыв выхода
			case 122: //КЗ выхода
			case 126: //Потеря  связи  с  выходом
			case 135: //Ошибка при авт. тестир-нии
			case 149: //Взлом  корпуса  прибора
			case 155: //Отказ ИУ
			case 165: //Ошибка параметров входа
			case 187: //Потеря связи со входом
			case 189: //Потеря связи по ДПЛС1
			case 190: //Потеря связи по ДПЛС2
			case 192: //Откл. вых. напряжения
			case 194: //Перегрузка ист. пит.
			case 195: //Перегрузка ист. пит. устр.
			case 196: //Неиспр. зарядного уст-ва
			case 198: //Неиспр. источника питания
			case 202: //Неисправность батареи
			case 204: //Требуется обслуживание
			case 205: //Ошибка теста АКБ
			case 211: //Батарея разряжена
			case 212: //Разряд резервной батареи
			case 214: //Короткое замыкание входа
			case 215: //Короткое замыкание ДПЛС
			case 217: //Отключение ветви RS-485
			case 222: //Повышение напряжения ДПЛС
			case 250: //Потеряна связь сприбором
				$color="bg_yellow";
			break;
			
			default:
				$color="bg_gray";	
			break;
		}
		
		echo "<div class=\"view_zone $color\">
			<p class = \"ent_name\">$row[num] - $row[zd]</p>
			<p class = \"hps_name\">$row[hpd]</p>";
		if ($row['lpd']) echo "<p class = \"lps_name\">$row[lpd]</p>";
		else echo "<br>";
		if ($row['type'] == 1)
		{
			echo ("
				<a href=\"./index.php?show=zones&dev_id=$_GET[dev_id]&do=".ETYPE_ZONE."-".$row['id']."-".$ppDevAddr."-".$row['num']."-".ACT_ARM."\">Взять</a> 
				<a href=\"./index.php?show=zones&dev_id=$_GET[dev_id]&do=".ETYPE_ZONE."-".$row['id']."-".$ppDevAddr."-".$row['num']."-".ACT_DISARM."\">Снять</a>
			");
		}
			
		echo("<a href=\"./index.php?show=zones&dev_id=$_GET[dev_id]&do=".ETYPE_ZONE."-".$row['id']."-".$ppDevAddr."-".$row['num']."-".ACT_READ_STATE."\">Перезапрос</a>
			</div>\n");
			
	}

?>
