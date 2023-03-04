<?php
include ("../includes/functions.php");

class Dev
{
	public $id = 0;
	public $mbAddr = 0;
	public $ver = 0;
	public $trlt = 0;
	public $act = 0;
};

class Zone
{
	public $id = 0;
	public $num = 0;
	public $type = 0;
	public $orDevAddr = 0;
	public $orDevLoop = 0;
	public $ppPart = 0;
	public $ppPartID = 0;
	
	public $orPartID = 0;
	public $desc = "";
	
};

class Part
{
	public $id = 0;
	public $num = 0;
	
};

class Relay
{
	public $id = 0;
	public $num = 0;
	public $orDevAddr = 0;
	public $orDevRelay = 0;
	public $desc = "";
	
};

class OrLoop
{

	public $num = 0;
	public $addr = 0;
	public $part = 0;
	public $desc = "";
}

class OrPart
{
	public $id = 0;
	public $num = 0;
	public $desc = "";
}

class OrRelay
{

	public $num = 0;
	public $addr = 0;
	public $desc = "";
}

class OrDev
{
	public $addr = 0;
	public $part = 0;
	public $desc = "";
	public $offset = 0;
}


$ppDev = new Dev;

$arrZones = array();
$arrParts = array();
$arrRelays = array();
$arrOrLoops = array();
$arrOrParts = array();
$arrOrDevs = array();
$arrOrRelays = array();

$currDevID = 0;
$currPartID = 0;
$currZoneID = 0;
$currRelayID = 0;
$currUserID = 0;
$currOrPartID = 0;
$currOrUserID = 0;



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


//SQLite conn
$db = new SQLite3('/valrond/cpp/_bases/develop.sqlite', SQLITE3_OPEN_READWRITE);
$result = NULL;
$errCode = 0;


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

//Curr orPart ID
$result = $db->query("SELECT MAX(id) id from orPart");
while ($row = $result->fetchArray(SQLITE3_ASSOC)) $currOrPartID = $row['id'];

//Curr orUser ID
$result = $db->query("SELECT MAX(id) id from orUser");
while ($row = $result->fetchArray(SQLITE3_ASSOC)) $currOrUserID = $row['id'];

//set ppDevID
$ppDev->id = ++$currDevID;


//proc C2000ПП config file
$entityNum = 1;
$cntr = 0;
$param = 0;

for ($i=0; true; $i++)
{
    $contents = fgets($f);
    if (!$contents) break;
	
	//echo '<br><b>'.$contents.'</b>';

    $tok = strtok($contents, "=");
    $tok = strtok("=");


    //echo '<br>';
    //echo $i.' -> '.$tok;

    /*Dev version*/
    if ($i == 2) $ppDev->ver = (int)$tok;

    /*Dev modbus addr*/
    if ($i == 5)
    {
        $paramNum = 0;
        $tok = strtok ($tok," ");
        while ($tok != NULL)
        {
            if (($paramNum == 11))
            {
                $ppDev->mbAddr = (int)$tok;
                break;
            }
            $tok = strtok (" ");
            $paramNum++;
        }
    }

    /*Dev translation mode*/
    if ($i == 6)
    {
        if ($ppDev->ver > 200)
        {
            $paramNum = 0;
            $tok = strtok ($tok," ");
            while ($tok != NULL)
            {
                if (($paramNum == 12))
                {
                    $ppDev->trlt = ((int)$tok >= 252) ? 1 : 0;
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
        if ($ppDev->ver > 200)
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
                        $ppDev->act = 1;
                        break;
                    case 0:
                    case 1:
                    case 4:
                    case 5:
                        $ppDev->act = 0;
                        break;
                    }
                    break;
                }
                $tok = strtok (" ");
                $paramNum++;
            }
        }
       
    }


    /*zones*/
    if (($i > 20) && ($i < 101))
    {
        $paramNum = 0;
        
        if ($i == 21)
        {
			$cntr = 0;
			$entityNum = 1;
			$param = 0;
		}
        
        $tok = strtok ($tok," "); // pattren: spsp (0x20 0x20)
        while ($tok != NULL)
        {
            if ($paramNum >31) break;
            
            switch ($param)
            {
            case 0:
                //echo '<br> zone = '.$entityNum.' addr = '.$tok;
                
                $arrZones[$cntr] = new Zone; //create zone in array
                $arrZones[$cntr]->num = $entityNum;
                
                $arrZones[$cntr]->orDevAddr = (int)$tok;
                break;
            case 1:
                //echo ' loop = '.$tok;
                $arrZones[$cntr]->orDevLoop = (int)$tok;
                break;
            case 2:
                //echo ' part = '.$tok;
                $arrZones[$cntr]->ppPart = (int)$tok;
                break;
            case 3:
                //echo ' addit = '.$tok;
                break;
            case 4:
                //echo ' type = '.$tok;
                $arrZones[$cntr]->type = (int)$tok;

                if (((!$arrZones[$cntr]->type) || ($arrZones[$cntr]->type > 7) || (!$arrZones[$cntr]->ppPart) || ($arrZones[$cntr]->ppPart > 64)) == false)
                {
                    $key = -1;
                    //write to ppPart
                    if (empty($arrParts)) 
                    {
						$key = array_push($arrParts, new Part); 
						$arrParts[$key - 1]->num = $arrZones[$cntr]->ppPart;
						$arrParts[$key - 1]->id = $arrZones[$cntr]->ppPartID = ++$currPartID;	
					}
                    else
                    {
						for ($n = 0; $n < count($arrParts); $n++)
						{
							if ($arrParts[$n]->num == $arrZones[$cntr]->ppPart) 
							{
								$key = $n;
								break;
							}
						}
						if ($key < 0)
						{
							$key = array_push($arrParts, new Part); 
							$arrParts[$key - 1]->num = $arrZones[$cntr]->ppPart;
							$arrParts[$key - 1]->id = $arrZones[$cntr]->ppPartID = ++$currPartID;	
						}
						
					}

					$cntr++;
                }
                else
                {
					unset ($arrZones[$cntr]);
				}



                $param = -1;
                $entityNum++;
                break;
            }
            $param++;
			$paramNum++;
            $tok = strtok (" ");
        }

    }

	/*relays*/
    if (($i > 100) && ($i < 117))
    {
        $paramNum = 0;
        if ($i == 101)
        {
			$cntr = 0;
			$entityNum = 1;
			$param = 0;
		}
        $tok = strtok ($tok," ");
        while ($tok != NULL)
        {
            if ($paramNum > 31) break;

            switch ($param)
            {
            case 0:
				$arrRelays[$cntr] = new Relay;
				$arrRelays[$cntr]->num = $entityNum;
                $arrRelays[$cntr]->orDevAddr = (int)$tok;
                //echo '<br> relay = '.$relayNum.' addr = '.$relayDevAddr;
                break;
            case 1:
                $arrRelays[$cntr]->orDevRelay = (int)$tok;
                //echo ' out = '.$relayDevOut;

                if (($arrRelays[$cntr]->orDevAddr > 0) && ($arrRelays[$cntr]->orDevAddr < 128))
                {
					$arrRelays[$cntr]->id = ++$currRelayID;
					$cntr++;
				} 
                else unset 
					($arrRelays[$cntr]);

                $param = -1;
                $entityNum++;
                break;
            }
            $param++;

            $tok = strtok (" ");
            $paramNum++;
        }
    }
}
fclose($f);


//proc C2000M config file

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
//var_dump ($_FILES['ppDevTXT']);
$f = fopen($_FILES['ppDevTXT']['tmp_name'], "r");


$contents = fread($f, $_FILES['ppDevTXT']['size']);
$contents = mb_convert_encoding($contents, "UTF-8", "CP1251");



// Parse orion devs info and offset for loops finding
preg_match_all('/Адрес:\s*(\d+)\s*,\s*Тип_прибора:[^,]+,\s*Версия:[^,\n]+\s*(?>,\s*Описание:\s*\"([^\"]+)\")*\s*Состояние прибора с адресом:\s*\d*\s*(?>,\s*Раздел:\s*(\d+))*/m', $contents, $matches, PREG_OFFSET_CAPTURE);
//vardump($matches);

for ($i = 0; $i < count ($matches[0]); $i++)
{
	$arrOrDevs[$i] = new OrDev;
	$arrOrDevs[$i]->offset = (int)$matches[0][$i][1]; 
	$arrOrDevs[$i]->addr = (int)$matches[1][$i][0]; 
	$arrOrDevs[$i]->desc = $matches[2][$i][0]; 
	$arrOrDevs[$i]->part = (int)$matches[3][$i][0]; 
		
}

//Parse orion loops
$cntr = 0;
for ($devCntr = 0; $devCntr < count($arrOrDevs); $devCntr++)
{
	
	$str = substr($contents, $arrOrDevs[$devCntr]->offset, (($devCntr != (count($arrOrDevs) - 1)) ? ($arrOrDevs[$devCntr + 1]->offset - $arrOrDevs[$devCntr]->offset) : strlen($contents)));
	//vardump($str);
	preg_match_all('/Шлейф:\s*(\d+)\s*(?>,\s*Раздел:\s*(\d+)\s*)*(?>,\s*Тип_шлейфа:\s*\d+\s*)*(?>\s*,Зона_Contact_ID:\s*\d+\s*)*(?>,\s*Описание:\s*\"([^\"]*)\")*/m', $str, $matches);
	//vardump($matches);
	
	for ($i = 0; $i < count ($matches[0]); $i++)
	{
		if ($matches[2][$i] || $matches[3][$i])
		{
			$arrOrLoops[$cntr] = new OrLoop;
			
			$arrOrLoops[$cntr]->addr = $arrOrDevs[$devCntr]->addr;
			
			$arrOrLoops[$cntr]->num = $matches[1][$i];
			$arrOrLoops[$cntr]->part = $matches[2][$i];
			$arrOrLoops[$cntr]->desc = $matches[3][$i];
			
			
			$cntr++;
		}
	}
		
} 

//Parse orion parts
preg_match_all('/^Раздел:\s*(\d+)\s*(?>,\s*Описание:\s*\"([^\"]+)\")*/m', $contents, $matches, /*PREG_OFFSET_CAPTURE*/);
//vardump($matches);


for ($i = 0; $i < count ($matches[0]); $i++)
{
	$arrOrParts[$i] = new OrPart;
	$arrOrParts[$i]->id = ++$currOrPartID;
	$arrOrParts[$i]->num = (int)$matches[1][$i]; 
	$arrOrParts[$i]->desc = $matches[2][$i]; 
	
	
}

//Parse orion relays
$cntr = 0;
for ($devCntr = 0; $devCntr < count($arrOrDevs); $devCntr++)
{
	
	$str = substr($contents, $arrOrDevs[$devCntr]->offset, (($devCntr != (count($arrOrDevs) - 1)) ? ($arrOrDevs[$devCntr + 1]->offset - $arrOrDevs[$devCntr]->offset) : strlen($contents)));
	//vardump($str);
	preg_match_all('/Реле:\s+(\d+)\s*(?>,\s*Сценарий_упр:\s*\d+\s*)*(?>,\s*Программа:\s*\d+\s*)*(?>,\s*Задержка включения:\s*\d+[.]\d+\s*)*(?>,\s*Время управления:\s*\d+[.]\d+\s*)*(?>,\s*Описание:\s*\"([^\"]+)\")*/m', $str, $matches);
	//vardump($matches);
	
	for ($i = 0; $i < count ($matches[0]); $i++)
	{
		if ($matches[1][$i])
		{
			$arrOrRelays[$cntr] = new OrRelay;
			$arrOrRelays[$cntr]->addr = $arrOrDevs[$devCntr]->addr;
			
			$arrOrRelays[$cntr]->num = $matches[1][$i];
			$arrOrRelays[$cntr]->desc = $matches[2][$i];
			$cntr++;
		}
	}
		
} 


$db->exec("COMMIT;");


echo "<h1>DEV</h1>";
printr($ppDev);
echo "<h1>PARTS</h1>";
printr($arrParts);
echo "<h1>ZONES</h1>";
printr($arrZones);
echo "<h1>RELAYS</h1>";
printr($arrRelays);
echo '<h1>ORION DEVS</h1>';
printr($arrOrDevs);
echo '<h1>ORION LOOPS</h1>';
printr($arrOrLoops);
echo '<h1>ORION PARTS</h1>';
printr($arrOrParts);
echo '<h1>ORION REALYS</h1>';
printr($arrOrRelays);
echo '<br>END';



?>

