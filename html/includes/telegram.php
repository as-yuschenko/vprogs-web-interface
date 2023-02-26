
<H3>Боты<H3>
	<hr>
<a href=\"\">Dобавить бота</a>
<table border=1>
<tr>
	<th class="">ID</td>
	<th class="">Токен</td>
	<th class="">Пароль</td>
	<th class="">Состояние</td>
	<th class="">Описание</td>
	<th class="">Dействия</td>
</tr>
<?php
$result = $db->query("Select id, token, pwd, isActive,desc from botTg where id > 0");
while ($row = $result->fetchArray(SQLITE3_ASSOC)) 
	{
		echo("<td>$row[id]</td>\n");
		echo("<td>$row[token]</td>\n");
		echo("<td>$row[pwd]</td>\n");
		echo("<td>$row[isActive]</td>\n");
		echo("<td>$row[desc]</td>\n");
		echo("<td><a href=\"\">Изменить</a> | <a href=\"\">УDалить</a></td>\n");
		echo ("</tr>\n");		
	}
?>
</table>

<H3>Пользователи<H3>
	<hr>
<a href="./index.php?show=telegram&do=<?php echo BOT_USER_ADD;?>">Dобавить пользователя</a>	
<?php
	//echo '<option value="'.$value.'" '.(($value=='United States')?'selected="selected"':"").'>'.$value.'</option>';
	if (!empty($_GET['do']))
	{
		if ($_GET['do'] == BOT_USER_ADD)
		{
			echo "<br>User add<br>";
		}
		if ($_GET['do'] == BOT_USER_EDIT)
		{
			if ($_GET['id'] > 0)
			{
				$result = $db->query("Select id,botTgID,chatID,name,isActive,monLvlID,manLvlID,desc from botTgUser where id=$_GET[id]");
				$row = $result->fetchArray(SQLITE3_ASSOC);
				if ($row)
				{
					echo "<p><form>";
					echo "<fieldset>";
					echo "<legend>Пользователь номер:$row[id]</legend>";
					echo "<p><label for=\"botTgID\">Номер бота  <em>*</em>  </label><input type=\"text\" id=\"botTgID\" value=\"$row[botTgID]\"></p>";
					echo "<p><label for=\"chatID\">Номер 4ата  <em>*</em>  </label><input type=\"text\" id=\"chatID\" value=\"$row[chatID]\"></p>";
					echo "<p><label for=\"name\">Имя в телеgрам  </label><input type=\"text\" id=\"name\" value=\"$row[name]\"></p>";
					echo "<p><label for=\"desc\">Описание  </label><input type=\"text\" id=\"desc\" value=\"$row[desc]\"></p>";
					
					echo "<p><label for=\"monLvlID\">Уровень мониторинgа  </label><select id=\"monLvlID\">";
					echo "<option label = 'Запрет' value = '0'></option>";
					echo "<option label = 'Все события' value = '1' selected></option>";
					echo "</select>";
					
					echo "<p>Статус";
					echo '<br><label for="isActive">Активен  </label><input type="radio" id="isActive" value="1" name="state"'.(($row['isActive']) ? 'checked' : '').'>';
					echo '<br><label for="disActive">Выклю4ен  </label><input type="radio" id="disActive" value="0" name="state"'.((!$row['isActive']) ? 'checked' : '').'>';
					echo "</p>";
					echo "</fieldset>";
					
					
					echo"</form></p>";
					
				}
				else
				{
					echo "no user";
				}
			}
		}
	}
?>
<table border=1>
<tr>
	<th class="">ID</td>
	<th class="">ID бота</td>
	<th class="">Номер 4ата</td>
	<th class="">Имя</td>
	<th class="">Состояние</td>
	<th class="">Уровень мониторинgа</td>
	<th class="">Уровень управления</td>
	<th class="">Описание</td>
	<th class="">Dействия</td>
</tr>
<?php
$result = $db->query("Select id,botTgID,chatID,name,isActive,monLvlID,manLvlID,desc from botTgUser where id>0");
while ($row = $result->fetchArray(SQLITE3_ASSOC)) 
	{
		echo("<td>$row[id]</td>\n");
		echo("<td>$row[botTgID]</td>\n");
		echo("<td>$row[chatID]</td>\n");
		echo("<td>$row[name]</td>\n");
		echo("<td>$row[isActive]</td>\n");
		echo("<td>$row[monLvlID]</td>\n");
		echo("<td>$row[manLvlID]</td>\n");
		echo("<td>$row[desc]</td>\n");
		echo("<td><a href=\"./index.php?show=telegram&do=".BOT_USER_EDIT."&id=$row[id]\">Изменить</a> | <a href=\"\">УDалить</a></td>\n");
		echo ("</tr>\n");		
	}
?>
</table>
