		<p>Benutzer zum Löschen</p>
		<form action=%request_uri% method=post>
		<table>
			<tr>
				<td>
					Benutzer
				</td>
				<td>
				<select name=user>
					%user%
				</select>
				</td>
			</tr>
			<tr>
				<td colspan=2>
				Achtung, der Benutzer wird entgültig gelöscht
				<p>Bitte geben Sie zur Bestätigung ihr Passwort ein:</p>
				<input type=password name=confirm_pass />
				<input type=submit value=Löschen />
				</form>
				</td>
			</tr>
		</table>
		</form>