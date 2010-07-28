<p>Menüpunkt auswählen</p>
<form action=%request_uri% method=post>
<table>
	<tr>
		<td>
			Menüpunkt
		</td>
		<td>
		<select name=id>
			%menulist%
		</select>
		</td>
	</tr>
	<tr>
		<td colspan=2>
		<input type=submit value="Bearbeiten" name=choose>
		</td>
	</tr>
</table>
</form>