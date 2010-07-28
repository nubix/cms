		<p>Benutzer auswählen</p>
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
				<input type=submit value=Bearbeiten name=choose>
				</td>
			</tr>
		</table>
		</form>