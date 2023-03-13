<div class = "form_add_entity">
	<form enctype="multipart/form-data" action = "settings/ppdev_add_handler.php" method="post">	
		<table>
			<tr>
				<td class = "bottom_line">Путь к файлу конфигураии С2000-ПП </td>
				<td><input type="file" name="ppDevCNU"></td>
			</tr>
			<tr>
				<td class = "bottom_line">Путь к файлу конфигураии С2000М </td>
				<td><input type="file" name="ppDevTXT"></td>
			</tr>
			<tr>
				<td class = "bottom_line">Номер порта</td>
				<td><input type="text" name="port"></td>
			</tr>
			<tr>
				<td class = "bottom_line">Режим работ </td>
				<td>
					<input name="mode" type="radio" value="1" >Master<br>
					<input name="mode" type="radio" value="0" checked>Slave<br>
				</td>
			</tr>
			<tr>
				<td class = "bottom_line">Описание </td>
				<td><input type="text" name="desc"></td>
			</tr>
			<tr>
				<td><br>Нижеследуюие настройки применютс дл создани истой конфигураии<br></td>
			</tr>
			<tr>
				<td class = "bottom_line">Адрес на шине Modbus </td>
				<td><input type="text" name="addr"></td>
			</tr>
			<tr>
				<td class = "bottom_line">Прма трансли </td>
				<td>
					<input name="translt" type="radio" value="1" >On<br>
					<input name="translt" type="radio" value="0" checked>Off<br>
				</td>
			</tr>
			<tr>
				<td class = "bottom_line">Верси</td>
				<td><input type="text" name="ver"></td>
			</tr>
			<tr>
				<td></td>
				<td><br><input type="submit" value="Добавить!"></td>
			</tr>	
		</table>
	</form>
</div>
