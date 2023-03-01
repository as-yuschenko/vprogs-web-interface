<?php

var_dump ($_POST);
//var_dump ($_FILE);



if (isset($_FILES['ppDevTXT']))
{
	if  ($_FILES['ppDevTXT']['error'])
	{
		die ('Error!<br>S2000M config file received with errors. Code:'.$_FILES['ppDevTXT']['error']);
	}
}
else 
{
	die ('Error!<br>No information about 2000M config file received');
}
echo '<br>';
var_dump ($_FILES['ppDevTXT']);
$f = fopen($_FILES['ppDevTXT']['tmp_name'], "r");


$contents = fread($f, $_FILES['ppDevTXT']['size']);
$contents = mb_convert_encoding($contents, "UTF-8", "CP1251");
//echo $contents;
echo '<hr>';
echo '<br>';
preg_match_all('/Номер:\s*(\d+)\s*,[^,]+,\s*Пароль:\s*([^\s,]+)\s*,\s*Хозорган:\s*\"([^\"]+)\"/m', $contents, $matches, /*PREG_OFFSET_CAPTURE*/);
///Номер:\s*(\d+)\s*,[^,]+,\s*Пароль:\s*([^\s,]+)\s*,\s*Хозорган:\s*\"([^\"]+)\"/gm
var_dump($matches);

preg_match_all('/Адрес:\s*(\d+)\s*,\s*Тип_прибора:[^,]+,\s*Версия:[^,\n]+\s*(?>,\s*Описание:\s*\"([^\"]+)\")*\s*Состояние прибора с адресом:\s*\d*\s*(?>,\s*Раздел:\s*(\d+))*/m', $contents, $matches, /*PREG_OFFSET_CAPTURE*/);

var_dump($matches);
echo '<br>END';

fclose($f);
exit();




























if (isset($_FILES['ppDevCNU']))
{
	if  ($_FILES['ppDevCNU']['error'])
	{
		die ('Error!<br>Config.cnu file received with errors. Code:'.$_FILES['ppDevCNU']['error']);
	}
}
else 
{
	die ('Error!<br>No information about cnu file received');
}
echo '<br>';
var_dump ($_FILES['ppDevCNU']);
$f = fopen($_FILES['ppDevCNU']['tmp_name'], "r");

/*

$contents = fread($f, $_FILES['ppdevcnu']['size']);
$contents = mb_convert_encoding($contents, "UTF-8", "CP1251");
echo '<br>';
echo nl2br($contents);
*/

//SQLite conn
$db = new SQLite3('/valrond/cpp/_bases/develop.sqlite', SQLITE3_OPEN_READWRITE);
$result = NULL;
$errCode = 0;


$zoneNum = 1;
$zoneDevAddr = 0;
$zoneDevLoop = 0;
$zonePart = 0;
$zoneType = 0;
$currZoneID = 0;

$partNum[] = array();
$partID[] = array();
$currPartID = 0;

$zoneEntity = 0;



$relayNum = 1;
$relayDevAddr = 0;
$relayDevOut = 0;
$relayEntity = 0;
$currRelayID = 0;

$devAddr = 0;
$devVer = 0;
$devTrlt = 0;
$devAct = 0;
$currDevID = 0;

$ppUserNum = 1;
$ppUserCode[] = array();
$ppUserCodeByte = 0;
$currUserID = 0;

$db->exec("BEGIN IMMEDIATE;");

//Curr dev ID
$result = $db->query("SELECT MAX(id) id from ppDev");
while ($row = $result->fetchArray(SQLITE3_ASSOC)) $currDevID = $row['id'];

//Curr part ID
$result = $db->query("SELECT MAX(id) id from ppPart");
while ($row = $result->fetchArray(SQLITE3_ASSOC)) $currPartID = $row['id'];

//Curr zone ID
$result = $db->query("SELECT MAX(id) id from ppZone");
while ($row = $result->fetchArray(SQLITE3_ASSOC)) $currZoneID = $row['id'];

//Curr relay ID
$result = $db->query("SELECT MAX(id) id from ppRelay");
while ($row = $result->fetchArray(SQLITE3_ASSOC)) $currRelayID = $row['id'];

//Curr user ID
$result = $db->query("SELECT MAX(id) id from ppUser");
while ($row = $result->fetchArray(SQLITE3_ASSOC)) $currUserID = $row['id'];



for ($i=0; true; $i++)
{
    $contents = fgets($f);
    if (!$contents) break;

    $tok = strtok($contents, "=");
    $tok = strtok("=");


    echo '<br>';
    echo $i.' -> '.$tok;
    //echo '<br>';
    //echo '<br>';

    /*Dev version*/
    if ($i == 2) $devVer = (int)$tok;

    /*Dev modbus addr*/
    if ($i == 5)
    {
        $paramNum = 0;
        $tok = strtok ($tok," ");
        while ($tok != NULL)
        {
            if (($paramNum == 11))
            {
                $devAddr = (int)$tok;
                break;
            }
            $tok = strtok (" ");
            $paramNum++;
        }
    }

    /*Dev translation mode*/
    if ($i == 6)
    {
        if ($devVer > 200)
        {
            $paramNum = 0;
            $tok = strtok ($tok," ");
            while ($tok != NULL)
            {
                if (($paramNum == 12))
                {
                    $devTrlt = ((int)$tok >= 252) ? 1 : 0;
                    break;
                }
                $tok = strtok (" ");
                $paramNum++;
            }
        }
    }

    /*Dev action mode*/
    if ($i == 7)
    {
        if ($devVer > 200)
        {
            $paramNum = 0;
            $tok = strtok ($tok," ");
            while ($tok != NULL)
            {
                if (($paramNum == 1))
                {
                    switch ((int)$tok)
                    {
                    case  2 :
                    case  3 :
                    case  6 :
                    case  7 :
                        $devAct = 1;
                        break;
                    case 0:
                    case 1:
                    case 4:
                    case 5:
                        $devAct = 0;
                        break;
                    }
                    break;
                }
                $tok = strtok (" ");
                $paramNum++;
            }
        }
		try
		{
			$db->enableExceptions(true);
			
			$db->exec('INSERT INTO ppDev(id,portID,addr,mode,translt,ver,act,desc)
                  VALUES ('.++$currDevID.','.$_POST['ppDevPort'].','.$devAddr.','.(((int)($_POST['ppDevWM'])) ? 1 : 0).','.$devTrlt.','.$devVer.','.$devAct.',"'.$_POST['ppDevDesc'].'");');
		}
		catch (Exception $e) 
		{
			$errCode = $db->lastErrorCode();
			if ($errCode == 19)
			{
				die ('Ошибка!<br>Устройство с адресом '.$devAddr.' привзанное к порту '.$_POST['ppDevPort'].' уже добавлено в систему.');
			}
			else if ($errCode)
			{
				die ('Ошибка!<br>Не удалось добавить устройство. Код ошибки: '.$errCode);
			}
		}
		
		
		
    }


    /*zones*/
    if (($i > 20) && ($i < 101))
    {
        $paramNum = 0;
        $tok = strtok ($tok," "); // pattren: spsp (0x20 0x20)
        while ($tok != NULL)
        {
            if ($paramNum > 31) break;

            switch ($zoneEntity)
            {
            case 0:
                //echo '<br> zone = '.$zoneNum.' addr = '.$tok;
                $zoneDevAddr = (int)$tok;
                break;
            case 1:
                //echo ' loop = '.$tok;
                $zoneDevLoop = (int)$tok;
                break;
            case 2:
                //echo ' part = '.$tok;
                $zonePart = (int)$tok;
                break;
            case 3:
                //echo ' addit = '.$tok;
                break;
            case 4:
                //echo ' type = '.$tok;
                $zoneType = (int)$tok;

                if (((!$zoneType) || ($zoneType > 7) || (!$zonePart) || ($zonePart > 64)) == false)
                {
                    //write to ppPart*
                    $key = array_search($zonePart, $partNum);
                    if ($key === false) //if curr part num doesn't exists in DB
                    {
                        array_push($partNum, $zonePart); // add curr part num
                        array_push($partID, ++$currPartID); // add next part id
                        $key = count($partID) - 1;

                        $db->exec("INSERT INTO ppPart (id, ppDevID, num) VALUES ($partID[$key],$currDevID, $partNum[$key]);"); // ppDevID!!!!!
                    }
                    $db->exec('INSERT INTO ppZone(id,ppDevID,num,orDev,orDevLoop,ppZoneTypeID,ppPartID)
                              VALUES ('.++$currZoneID.','.$currDevID.','.$zoneNum.','.$zoneDevAddr.','.$zoneDevLoop.','.$zoneType.','.$partID[$key].'); '); // ppDevID!!!!!

                }



                $zoneEntity = -1;
                $zoneNum++;
                break;
            }
            $zoneEntity++;

            $tok = strtok (" ");
            $paramNum++;
        }

    }

    /*relays*/
    if (($i > 100) && ($i < 117))
    {
        $paramNum = 0;
        $tok = strtok ($tok," ");
        while ($tok != NULL)
        {
            if ($paramNum > 31) break;

            switch ($relayEntity)
            {
            case 0:
                $relayDevAddr = (int)$tok;
                //echo '<br> relay = '.$relayNum.' addr = '.$relayDevAddr;
                break;
            case 1:
                $relayDevOut = (int)$tok;
                //echo ' out = '.$relayDevOut;

                if (($relayDevAddr > 0) && ($relayDevAddr < 128))
                {
                    $db->exec('INSERT INTO ppRelay(id,ppDevID,num,orDev,orDevRelay)
                              VALUES ('.++$currRelayID.','.$currDevID.','.$relayNum.','.$relayDevAddr.','.$relayDevOut.'); '); // ppDevID!!!!!
                }

                $relayEntity = -1;
                $relayNum++;
                break;
            }
            $relayEntity++;

            $tok = strtok (" ");
            $paramNum++;
        }
    }
    
    /*Users
        if (($i > 116) && ($i < 133))
        {
            int $paramNum = 0;
            $tok = strtok ($tok," ");
			while ($tok != NULL)
			{
                $ppUserCode[$ppUserCodeByte] = (int)$tok;

                $ppUserCodeByte++;

                if ($ppUserCodeByte > 7)
                {
                    $ppUserCodeByte = 0;
                    $ppUserNum++;
                }

                $tok = strtok (" ");
                $paramNum++;
            }
        }
        */
}
$db->exec("COMMIT;");
fclose($f);




echo '<br>END';



?>

