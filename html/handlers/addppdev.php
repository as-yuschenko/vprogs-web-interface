<?php

var_dump ($_POST);
//var_dump ($_FILE);
if (isset($_FILES['ppdevcnu'])) echo '<br>'.$_FILES['ppdevcnu']['error'];
echo '<br>';
var_dump ($_FILES['ppdevcnu']);
$f = fopen($_FILES['ppdevcnu']['tmp_name'], "r");
/*

$contents = fread($f, $_FILES['ppdevcnu']['size']);
$contents = mb_convert_encoding($contents, "UTF-8", "CP1251");
echo '<br>';
echo nl2br($contents);
*/

//SQLite conn
$db = new SQLite3('/valrond/cpp/_bases/develop.sqlite', SQLITE3_OPEN_READWRITE);
$result = NULL;


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
        
        $db->exec('INSERT INTO ppDev(id,portID,addr,mode,translt,ver,act,desc)
		VALUES ('.++$currDevID.',1,'.$devAddr.', 0,'.$devTrlt.','.$devVer.','.$devAct.',"Test");');
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
						VALUES ('.++$currZoneID.','.$currDevID.','.$zoneNum.','.$zoneDevAddr.','.$zoneDevLoop.','.$zoneType.','.$partID[$key].');'); // ppDevID!!!!!
                    
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
						VALUES ('.++$currRelayID.','.$currDevID.','.$relayNum.','.$relayDevAddr.','.$relayDevOut.');'); // ppDevID!!!!!
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
}
$db->exec("COMMIT;");
fclose($f);

echo '<br>END';
?>

