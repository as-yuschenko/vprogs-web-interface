<!--ADD PART-->
<div class = "form_add_entity">
<form action = "settings/part_add_handler.php" method="post">
<input type="hidden" name="ppDevID" value="<?php echo $ppDevID;?>">
<p>Добавить раздел</p>
<table>
<tr><td class = "bottom_line">Номер раздела</td><td><input type="text" name="num" value=""></td></tr>
<tr><td class = "bottom_line">Описание</td><td><input type="text" name="desc" value=""></td></tr>
<tr><td class = "bottom_line">Отобразить на главной</td><td><input type="checkbox" name="mainScreen"></td></tr>
<tr><td></td><td><br><input type="submit" value="Добавить"></td></tr>
</table>
</form>
</div>
