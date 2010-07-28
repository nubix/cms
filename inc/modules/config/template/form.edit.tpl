<form action="%request_uri%" method=post>
<div id=modulemenu>Sie können hier Ihre Daten ändern. Wenn Sie Ihr Passwort ändern möchten füllen Sie die Felder Passwort und Passwortwiederholung aus.</div>
<table border=0>
<!--	<tr>
		<td>Loginname</td>
		<td><input class=input  type=text size=10 name=loginname value=%loginname%></td>
	</tr> -->
	<tr>
		<td>Vorname</td>
		<td><input class=input  type=text size=10 name=vorname value=%vorname%></td>
	</tr>
	<tr>
		<td>Nachname</td>
		<td><input class=input type=text size=10 name=nachname value=%nachname%></td>
	</tr>
	<tr>
		<td>Passwort</td>
		<td><input class=input  type=password size=10 name=password1></td>
	</tr>
	<tr>
		<td>Passwort Wiederholung</td>
		<td><input class=input  type=password size=10 name=password2></td>
	</tr>
	<tr>
		<td>Emailadresse</td>
		<td><input class=input type=text size=10 name=email value=%email%></td>
	</tr>
	<tr>
		<td colspan=2>
			<input type=hidden name=id value=%id%>
			<input type=submit name=submit value=Ändern>
		</td>
	</tr>
	</table>
</form>