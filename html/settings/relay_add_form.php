<!--ADD RELAY-->
<div class = "form_add_entity">
<form action = "settings/relay_add_handler.php" method="post">
<input type="hidden" name="ppDevID" value="<?php echo $ppDevID;?>">
<p>Добавить реле</p>
<table>
<tr><td class = "bottom_line">Номер реле</td><td><input type="text" name="num" value=""></td></tr>
<tr><td class = "bottom_line">Адрес прибора</td><td><input type="text" name="orDevAddr" value=""></td></tr>
<tr><td class = "bottom_line">Номер викода</td><td><input type="text" name="orDevRelay" value=""></td></tr>
<tr><td class = "bottom_line">Описание</td><td><input type="text" name="desc" value=""></td></tr>
<tr><td class = "bottom_line">Отобразить на главной</td><td><input type="checkbox" name="mainScreen"></td></tr>
<tr><td></td><td><br><input type="submit" value="Добавить"></td></tr>
</table>
</form>
</div>
