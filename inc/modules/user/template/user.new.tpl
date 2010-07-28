<form action="%request_uri%" method=post>
<table border=0>
	<tr>
		<th colspan=2>Bitte geben Sie die Benutzerdaten ein</th>
	</tr>
	<tr>
		<td>Loginname</td>
		<td><input class=input  type=text size=10 name=loginname value=%loginname%></td>
	</tr>
	<tr>
		<td>Vorname</td>
		<td><input class=input  type=text size=10 name=vorname value=%vorname%></td>
	</tr>
	<tr>
		<td>Nachname</td>
		<td><input class=input  type=text size=10 name=nachname value=%nachname%></td>
	</tr>
	<tr>
		<td>Passwort</td>
		<td><input class=input  type=password size=10 name=password value=%password%></td>
	</tr>
	<tr>
		<td>Emailadresse</td>
		<td><input class=input  type=text size=10 name=email value=%email%></td>
	</tr>
	<tr>
		<td valign=top>Rechtelevel</td>
		<td valign=top>
			<select name=rightlevel
					onmouseover="javascript:document.getElementById(document.forms[0].rightlevel.value).style.display=\'block\'"
					onmouseout="javascript:document.getElementById(document.forms[0].rightlevel.value).style.display=\'none\'">
			%rightlevel%
			</select>
		</td>
		<td>
			%right_description%
		</td>
	</tr>
	<tr>
		<td>Aktiv</td>
		<td>
			<select name=mode>%mode%</select>
		</td>
	</tr>
	<tr>
		<td colspan=2><input type="submit" name="insert" value="Anlegen"></td>
	</tr>
	</table>
</form>