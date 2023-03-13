<!--ADD ZONE-->
<div class = "form_add_entity">
<form action = "settings/zone_add_handler.php" method="post">
<input type="hidden" name="ppDevID" value="<?php echo $ppDevID;?>">
<p>Добавить зону</p>
<table>
<tr><td class = "bottom_line">Номер зон</td><td><input type="text" name="num" value=""></td></tr>
<tr><td class = "bottom_line">Адрес прибора</td><td><input type="text" name="orDevAddr" value=""></td></tr>
<tr><td class = "bottom_line">Номер шлейфа</td><td><input type="text" name="orDevLoop" value=""></td></tr>
<tr><td class = "bottom_line">Тип зон</td>
	<td>
		<input type="radio" name="type" value = "1" checked>Состояние ШС<br>
		<input type="radio" name="type" value = "2">Состояние КЦ<br>
		<input type="radio" name="type" value = "3">Состояние прибора<br>
		<input type="radio" name="type" value = "4">Вкл/Выкл автоматики<br>
		<input type="radio" name="type" value = "5">Дистанционный пуск<br>
		<input type="radio" name="type" value = "6">Температура/Влажность<br>
		<input type="radio" name="type" value = "7">Счетчик импульсов<br>
		<input type="radio" name="type" value = "8">РИП напряжение/ток<br>
	</td>
</tr>
<tr><td class = "bottom_line">Номер раздела</td><td><input type="text" name="ppPartNum" value=""></td></tr>
<tr><td class = "bottom_line">Описание</td><td><input type="text" name="desc" value=""></td></tr>
<tr><td class = "bottom_line">Отобразить на главной</td><td><input type="checkbox" name="mainScreen"></td></tr>
<tr><td></td><td><br><input type="submit" value="Добавить"></td></tr>
</table>
</form>
</div>
