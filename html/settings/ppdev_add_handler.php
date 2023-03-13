<?php
require ("../db/db_settings.php");
require ("../includes/functions.php");
require ("../includes/header.php");

//printr($_FILES);
//printr($_POST);


/*S2000-PP entities*/
class Dev
{
    public $id = 0;
    public $addr = 0;
    public $ver = 0;
    public $trlt = 0;
    public $mode = 0;
    public $portID = 0;
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

class User
{
    public $id = 0;
    public $num = 0;
    public $pwd = array();
};

/*S2000M entities*/
class OrPult
{
    public $part = 0;
    public $desc = "";
}

class OrDev
{
    public $addr = 0;
    public $part = 0;
    public $desc = "";
    public $offset = 0;
};

class OrLoop
{
    public $num = 0;
    public $addr = 0;
    public $part = 0;
    public $desc = "";
};

class OrPart
{
    public $id = 0;
    public $num = 0;
    public $desc = "";
};

class OrRelay
{

    public $num = 0;
    public $addr = 0;
    public $desc = "";
};

class OrUser
{
    public $id = 0;
    public $num = 0;
    public $pwd = "";
    public $desc = "";
}


$ppDev = new Dev;
$pult = new OrPult;

$arrZones = array();
$arrParts = array();
$arrRelays = array();
$arrUsers = array();


$arrOrLoops = array();
$arrOrParts = array();
$arrOrDevs = array();
$arrOrRelays = array();
$arrOrUsers = array();

$currDevID = 0;
$currPortID = 0;
$currDevAddr = 0;
$currPartID = 0;
$currZoneID = 0;
$currRelayID = 0;
$currUserID = 0;
$currOrPartID = 0;
$currOrUserID = 0;


//SQLite conn
$db = new SQLite3($db_path, SQLITE3_OPEN_READWRITE);
$db->enableExceptions(true);
$db->exec('PRAGMA foreign_keys = ON;');	
$result = NULL;



//ppDev id
$result = $db->query("SELECT MAX(id) id from ppDev");
$row = $result->fetchArray(SQLITE3_ASSOC);
$ppDev->id = $row['id'] + 1;


//ppDev portID
if ((int)$_POST['port'] < 1)
{
	htmlOutRedirect ("settings", PPDEV_ADD, $ppDev->id , -1, "Не допустимое знаение номера порта");	
	exit();
}
else $ppDev->portID = (int)$_POST['port'];

//ppDev mode
$ppDev->mode = (int)$_POST['mode'];

//ppdev ver
$ppDev->ver = (int)$_POST['ver'];

//ppDev translt
$ppDev->trlt = (int)$_POST['translt'];

//ppDev desc
$ppDev->desc = $_POST['desc'];



//Handle LOAD File error
if (isset($_FILES['ppDevCNU']) && ($_FILES['ppDevCNU']['error'] != UPLOAD_ERR_NO_FILE) && ($_FILES['ppDevCNU']['error'] != UPLOAD_ERR_OK))
{
	htmlOutRedirect ("settings", PPDEV_ADD, $ppDev->id , -1, "Ошибка при загрузке файла конфигураии С2000-ПП");	
	exit();
}

//BLANK configuration------------------------------------------------------------------------------

if (isset($_FILES['ppDevCNU']) && ($_FILES['ppDevCNU']['error'] == UPLOAD_ERR_NO_FILE))
{
	//ppDev addr
	if (((int)$_POST['addr'] < 1) || ((int)$_POST['addr'] > 255)) 
	{
		htmlOutRedirect ("settings", PPDEV_ADD, $ppDev->id , -1, "Не допустимое знаение адреса на шине Modbus");	
		exit();
	}
	else $ppDev->addr = (int)$_POST['addr'];
	
	
	try
	{		
		$db->exec('INSERT INTO ppDev(id,portID,addr,mode,translt,ver,desc)
		VALUES ('.$ppDev->id.','.$ppDev->portID.','.$ppDev->addr.','.$ppDev->mode.','.$ppDev->trlt.','.$ppDev->ver.',"'.$ppDev->desc.'");');
	}
	catch (Exception $e) 
	{

		if ($db->lastErrorCode() == 19)
		{
			htmlOutRedirect ("settings", PPDEV_ADD, $ppDev->id , $db->lastErrorCode(), "Устройство с указаннм адресом уже привзано к порту<br> или указаннй номер порта отсутствует в БД.");	
			exit();
		}
		else 
		{
			htmlOutRedirect ("settings", PPDEV_ADD, $ppDev->id , $db->lastErrorCode(), $db->lastErrorMsg());	
			exit();
		}
	}
	
	htmlOutRedirect ("settings");	
	exit();
}

//CNU configuration


//Curr part ID
$result = $db->query("SELECT MAX(id) id from ppPart");
$row = $result->fetchArray(SQLITE3_ASSOC);
$currPartID = $row['id'];

//Curr zone ID
$result = $db->query("SELECT MAX(id) id from ppZone");
$row = $result->fetchArray(SQLITE3_ASSOC);
$currZoneID = $row['id'];

//Curr relay ID
$result = $db->query("SELECT MAX(id) id from ppRelay");
$row = $result->fetchArray(SQLITE3_ASSOC);
$currRelayID = $row['id'];

//Curr user ID
$result = $db->query("SELECT MAX(id) id from ppUser");
$row = $result->fetchArray(SQLITE3_ASSOC);
$currUserID = $row['id'];

//Curr orPart ID
$result = $db->query("SELECT MAX(id) id from orPart");
$row = $result->fetchArray(SQLITE3_ASSOC);
$currOrPartID = $row['id'];

//Curr orUser ID
$result = $db->query("SELECT MAX(id) id from orUser");
$row = $result->fetchArray(SQLITE3_ASSOC);
$currOrUserID = $row['id'];

//proc C2000ПП config file
$f = fopen($_FILES['ppDevCNU']['tmp_name'], "r");

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

    //Dev version
    if ($i == 2) $ppDev->ver = (int)$tok;

    //Dev modbus addr
    if ($i == 5)
    {
        $paramNum = 0;
        $tok = strtok ($tok," ");
        while ($tok != NULL)
        {
            if (($paramNum == 11))
            {
                $ppDev->addr = (int)$tok;
                break;
            }
            $tok = strtok (" ");
            $paramNum++;
        }
    }

    //Dev translation mode
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

    //Dev action mode
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


    //zones
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
                    $arrZones[$cntr]->id = ++$currZoneID;
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
                        else
                        {
                            $arrZones[$cntr]->ppPartID = $arrParts[$key]->id;
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

    //relays
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

    //Users
    if (($i > 116) && ($i < 133))
    {
        $paramNum = 0;
        if ($i == 117)
        {
            $cntr = 0;
            $entityNum = 1;
            $param = 0;
        }
        $tok = strtok ($tok," ");

        while ($tok != NULL)
        {
            if ($paramNum > 31) break;

            if ($param == 0)
            {
                $arrUsers[$cntr] = new User;
                $arrUsers[$cntr]->num = $entityNum;
            }
            $arrUsers[$cntr]->pwd[$param] = (int)$tok;
            $param++;

            if ($param > 7)
            {
                if (($arrUsers[$cntr]->pwd[0] > 0) && ($arrUsers[$cntr]->pwd[0] <255))
                {
                    $arrUsers[$cntr]->id = ++$currUserID;
                    $cntr++;
                }
                else unset ($arrUsers[$cntr]);


                $param = 0;
                $entityNum++;

            }

            $tok = strtok (" ");
            $paramNum++;
        }
    }



}
fclose($f);

/*
echo '<h1>DEV</h1>';
printr($ppDev);
echo '<h1>PARTS</h1>';
printr($arrParts);
echo '<h1>ZONES</h1>';
printr($arrZones);
echo '<h1>RELAYS</h1>';
printr($arrRelays);
echo '<h1>USERS</h1>';
printr($arrUsers);
*/



//proc C2000M config file

if (isset($_FILES['ppDevTXT']))
{
	if  ($_FILES['ppDevTXT']['error'] == UPLOAD_ERR_OK)
	{
        
	   

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
		//Parse orion users
		preg_match_all('/Номер:\s*(\d+)\s*,[^,]+,\s*Пароль:\s*([^\s,]+)\s*,\s*Хозорган:\s*\"([^\"]+)\"/m', $contents, $matches);
		//printr($matches);
		for ($i = 0; $i < count ($matches[0]); $i++)
		{
			$arrOrUsers[$i] = new OrUser;
			$arrOrUsers[$i]->id = ++$currOrUserID;
			$arrOrUsers[$i]->num = (int)$matches[1][$i];
			$arrOrUsers[$i]->pwd = $matches[2][$i];
			$arrOrUsers[$i]->desc = $matches[3][$i];


		}

		//Parse orion pult
		preg_match_all('/Состояние:[^,]*,\s*(?>Раздел:\s*(\d+))*(?>\s*,*\s*Описание:\s*\"([^\"]+)\"\s*)*/m', $contents, $matches);
		//printr($matches);
		$pult->part = (int)$matches[1][0];
		$pult->desc = $matches[2][0];

		fclose($f);


		//FILL ZONES DESC AND ORION PART ID
		foreach ($arrZones as $z)
		{
			$dp = 0; //orion part

			if ($z->orDevLoop == 0)
			{
				if ($z->orDevAddr == 0) //pult
				{
					$z->desc = $pult->desc; // desc

					foreach($arrOrParts as $p)
					{
						if ($p->num == $pult->part)
						{
							$z->orPartID = $p->id; // orPartID
							break;
						}
					}
				}
				else //orion devs
				{
					foreach ($arrOrDevs as $d)//desc
					{
						if ($z->orDevAddr == $d->addr)
						{
							$z->desc = $d->desc;
							$dp = $d->part;
							break;
						}
					}
					foreach($arrOrParts as $p)// orPartID
					{
						if ($p->num == $dp)
						{
							$z->orPartID = $p->id;
							break;
						}
					}
				}
			}
			else //orion loops
			{
				foreach ($arrOrLoops as $l) //desc
				{
					if (($z->orDevAddr == $l->addr) && ($z->orDevLoop == $l->num))
					{
						$z->desc = $l->desc;
						$dp = $l->part;
						break;
					}
				}
				foreach($arrOrParts as $p)// orPartID
				{
					if ($p->num == $dp)
					{
						$z->orPartID = $p->id;
						break;
					}
				}
			}
		}


		//FILL RELAY DESC
		foreach ($arrRelays as $r)
		{
			foreach ($arrOrRelays as $or) //desc
			{
				if (($r->orDevAddr == $or->addr) && ($r->orDevRelay == $or->num))
				{
					$r->desc = $or->desc;
					break;
				}
			}
		}
	}
	else if ($_FILES['ppDevTXT']['error'] != UPLOAD_ERR_NO_FILE)
	{
		htmlOutRedirect ("settings", PPDEV_ADD, $ppDev->id , -1, "Ошибка при загрузке файла конфигураии С2000М");	
		exit();
	}
}

/*
echo '<h1>ORION PULT</h1>';
printr($pult);
echo '<h1>ORION DEVS</h1>';
printr($arrOrDevs);
echo '<h1>ORION LOOPS</h1>';
printr($arrOrLoops);
echo '<h1>ORION PARTS</h1>';
printr($arrOrParts);
echo '<h1>ORION REALYS</h1>';
printr($arrOrRelays);
echo '<h1>ORION USERS</h1>';
printr($arrOrUsers);
*/

//STORE TO DB
$db->exec("BEGIN IMMEDIATE;");

//INSERT ppDev
try
{		
	$db->exec('INSERT INTO ppDev(id,portID,addr,mode,translt,ver,desc)
	VALUES ('.$ppDev->id.','.$ppDev->portID.','.$ppDev->addr.','.$ppDev->mode.','.$ppDev->trlt.','.$ppDev->ver.',"'.$ppDev->desc.'");');
}
catch (Exception $e) 
{

	if ($db->lastErrorCode() == 19)
	{
		htmlOutRedirect ("settings", PPDEV_ADD, $ppDev->id , $db->lastErrorCode(), "Устройство с указаннм адресом уже привзано к порту<br> или указаннй номер порта отсутствует в БД.");	
	}
	else 
	{
		htmlOutRedirect ("settings", PPDEV_ADD, $ppDev->id , $db->lastErrorCode(), $db->lastErrorMsg());	
	}
	$db->exec("ROLLBACK;");
	exit();
}


//INSERT ppParts
$stmt = $db->prepare('INSERT INTO ppPart(id, ppDevID, num) VALUES (:id, :ppDevID, :num)');
foreach ($arrParts as $p)
{
	$stmt->bindValue(':id', $p->id, SQLITE3_INTEGER);
	$stmt->bindValue(':ppDevID', $ppDev->id, SQLITE3_INTEGER);
	$stmt->bindValue(':num', $p->num, SQLITE3_INTEGER);
	try
	{
		$stmt->execute();
	}
	catch (Exception $e) 
	{
		htmlOutRedirect ("settings", PPDEV_ADD, $ppDev->id , $db->lastErrorCode(), $db->lastErrorMsg());
		$db->exec("ROLLBACK;");
	}
	$stmt->reset();
}

//INSERT orPart
if (!empty ($arrOrParts))
{
	$stmt = $db->prepare('INSERT INTO orPart(id,ppDevID,num,desc) VALUES (:id,:ppDevID,:num,:desc)');
	foreach ($arrOrParts as $op)
	{
		$stmt->bindValue(':id', $op->id, SQLITE3_INTEGER);
		$stmt->bindValue(':ppDevID', $ppDev->id, SQLITE3_INTEGER);
		$stmt->bindValue(':num', $op->num, SQLITE3_INTEGER);
		$stmt->bindValue(':desc', $op->desc, SQLITE3_TEXT);
		try
		{
			$stmt->execute();
		}
		catch (Exception $e) 
		{
		htmlOutRedirect ("settings", PPDEV_ADD, $ppDev->id , $db->lastErrorCode(), $db->lastErrorMsg());
		$db->exec("ROLLBACK;");
		}
		$stmt->reset();
	}
}


//INSERT ppZones
$stmt = $db->prepare('INSERT INTO ppZone(id,ppDevID,num,orDev,orDevLoop,ppZoneTypeID,ppPartID,orPartID,desc)
					VALUES (:id,:ppDevID,:num,:orDev,:orDevLoop,:ppZoneTypeID,:ppPartID,:orPartID,:desc)');
foreach ($arrZones as $z)
{
	$stmt->bindValue(':id', $z->id, SQLITE3_INTEGER);
	$stmt->bindValue(':ppDevID', $ppDev->id, SQLITE3_INTEGER);
	$stmt->bindValue(':num', $z->num, SQLITE3_INTEGER);
	$stmt->bindValue(':orDev', $z->orDevAddr, SQLITE3_INTEGER);
	$stmt->bindValue(':orDevLoop', $z->orDevLoop, SQLITE3_INTEGER);
	$stmt->bindValue(':ppZoneTypeID', $z->type, SQLITE3_INTEGER);
	$stmt->bindValue(':ppPartID', $z->ppPartID, SQLITE3_INTEGER);
	$stmt->bindValue(':orPartID', $z->orPartID, SQLITE3_INTEGER);
	$stmt->bindValue(':desc', $z->desc, SQLITE3_TEXT);
	try
	{
		$stmt->execute();
	}
	catch (Exception $e) 
	{
		htmlOutRedirect ("settings", PPDEV_ADD, $ppDev->id , $db->lastErrorCode(), $db->lastErrorMsg());
		$db->exec("ROLLBACK;");
	}
	$stmt->reset();
}

//INSERT ppRelays
$stmt = $db->prepare('INSERT INTO ppRelay(id,ppDevID,num,orDev,orDevRelay,desc)
					VALUES (:id,:ppDevID,:num,:orDev,:orDevRelay,:desc)');
foreach ($arrRelays as $r)
{
	$stmt->bindValue(':id', $r->id, SQLITE3_INTEGER);
	$stmt->bindValue(':ppDevID', $ppDev->id, SQLITE3_INTEGER);
	$stmt->bindValue(':num', $r->num, SQLITE3_INTEGER);
	$stmt->bindValue(':orDev', $r->orDevAddr, SQLITE3_INTEGER);
	$stmt->bindValue(':orDevRelay', $r->orDevRelay, SQLITE3_INTEGER);
	$stmt->bindValue(':desc', $r->desc, SQLITE3_TEXT);
	try
	{
		$stmt->execute();
	}
	catch (Exception $e) 
	{
		htmlOutRedirect ("settings", PPDEV_ADD, $ppDev->id , $db->lastErrorCode(), $db->lastErrorMsg());
		$db->exec("ROLLBACK;");
	}
	$stmt->reset();
}

//INSERT ppUser
$stmt = $db->prepare('INSERT INTO ppUser(id,ppDevID,num,pwd)
					VALUES (:id,:ppDevID,:num,:pwd)');
foreach ($arrUsers as $u)
{
	$stmt->bindValue(':id', $u->id, SQLITE3_INTEGER);
	$stmt->bindValue(':ppDevID', $ppDev->id, SQLITE3_INTEGER);
	$stmt->bindValue(':num', $u->num, SQLITE3_INTEGER);
	$stmt->bindValue(':pwd', serialize($u->pwd), SQLITE3_BLOB);
	try
	{
		$stmt->execute();
	}
	catch (Exception $e) 
	{
		htmlOutRedirect ("settings", PPDEV_ADD, $ppDev->id , $db->lastErrorCode(), $db->lastErrorMsg());
		$db->exec("ROLLBACK;");
	}
	$stmt->reset();
}



//INSERT orUsers
if (!empty ($arrOrUsers))
{
	$stmt = $db->prepare('INSERT INTO orUser(id,ppDevID,num,pwd,desc) VALUES (:id,:ppDevID,:num,:pwd,:desc)');
	foreach ($arrOrUsers as $ou)
	{
		$stmt->bindValue(':id', $ou->id, SQLITE3_INTEGER);
		$stmt->bindValue(':ppDevID', $ppDev->id, SQLITE3_INTEGER);
		$stmt->bindValue(':num', $ou->num, SQLITE3_INTEGER);
		$stmt->bindValue(':pwd', $ou->pwd, SQLITE3_TEXT);
		$stmt->bindValue(':desc', $ou->desc, SQLITE3_TEXT);
		try
		{
			$stmt->execute();
		}
		catch (Exception $e) 
		{
		htmlOutRedirect ("settings", PPDEV_ADD, $ppDev->id , $db->lastErrorCode(), $db->lastErrorMsg());
		$db->exec("ROLLBACK;");
		}
		$stmt->reset();
	}
}

htmlOutRedirect ("settings");
$db->exec("COMMIT;");
?>

