		<p>Benutzer zum L�schen</p>
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
				Achtung, der Benutzer wird entg�ltig gel�scht
				<p>Bitte geben Sie zur Best�tigung ihr Passwort ein:</p>
				<input type=password name=confirm_pass />
				<input type=submit value=L�schen />
				</form>
				</td>
			</tr>
		</table>
		</form>