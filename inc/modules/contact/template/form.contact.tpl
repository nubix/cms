<form method=post action="#">
<table>
	<tr>
		<td>Ihr Name</td>
		<td><input class=content type=text name=name value="%name%" /></td>
	</tr>
	<tr>
		<td>Ihre Telefonnummer</td>
		<td><input class=content type=text name=phone value="%phone%" /></td>
	</tr>
	<tr>
		<td>Ihre Emailadresse</td>
		<td><input class=content type=text name=mail value="%mail%" /></td>
	</tr>
	<tr>
		<td valign=top>Ihre Nachricht</td>
		<td><textarea style="width: 250px; height: 300px;" name=message>%message%</textarea></td>
	</tr>
	<tr >
		<td width="150px">Zählen Sie bitte alle <b>%countchar%</b> im nachfolgenden Wort.<i>[Minimal 1 - Maximal 5]</i></td>
		<td style="border: 1px solid #000;" align=center>
			<span style="padding: 4px; border: 1px solid #c0c0c0; background:#FFF;">%randomstring%</span>
			Anzahl:<input class=content type=text maxlength=1 size=1 name=cap value="" />
		</td>
	</tr>
	<tr>
		<td align=center colspan=2><input style="margin:10px;" type=submit name=submit value="Nachricht abschicken" /></td>
	</tr>
</table>
</form>