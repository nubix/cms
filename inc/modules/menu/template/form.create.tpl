<script type="text/javascript">
function typSelect(farbe) {
	var selectVal = document.getElementsByName('type')[0].value;
	if (selectVal == 0)
	{
		document.getElementById('intern').style.background = '';
		document.getElementById('extern').style.background = '';
	}
	else if (selectVal == 1)
	{
		document.getElementById('intern').style.background = farbe;
		document.getElementById('extern').style.background = '';
	}
	else if (selectVal == 2)
	{
		document.getElementById('intern').style.background = '';
		document.getElementById('extern').style.background = farbe;
	}
	else if (selectVal == 3)
	{
		document.getElementById('intern').style.background = '';
		document.getElementById('extern').style.background = '';
	}
}
</script>
<form method=post action=%request_uri%>
<table border=0>
	<tr>
		<td>Titel *</td>
		<td><input type=text name=title maxlength=255 value="%title%" /></td>
	</tr>
	<tr>
		<td>Tooltip</td>
		<td><input type=text name=tooltip value="%tooltip%" /></td>
	</tr>
	<tr>
		<td>Typ * </td>
		<td>
			<select name=type onchange="javascript:typSelect('#FFFD84');">
				<option value="0" %sel_nz%>Keine Seite</option>
				<option value="1" %sel_i%>Intern</option>
				<option value="2" %sel_e%>Extern</option>
				<option value="3" %sel_c%>Kontaktformular</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Ziel</td>
		<td>
			<div>
			<div style=width:50px;float:left>Intern</div>
				<select name=intern id=intern>
					%pagelist%
				</select>
			</div>
			<div>
			<div style=width:50px;float:left>Extern</div>
			<input  id=extern type=text name=extern value="%extern%" />
			</div>
		</td>
	</tr>
	<tr>
		<td colspan=2><input type=submit name=submit value=Erstellen /> *) Benötigt</td>
	</tr>
</table>
</form>