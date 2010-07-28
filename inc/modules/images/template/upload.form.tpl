<form enctype="multipart/form-data" method=post action=%request_uri%>
	<table>
		<tr>
			<td>Name * </td>
			<td><input type=text name=name value="%name%" /></td>
		</tr>
		<tr>
			<td>Beschreibung</td>
			<td><textarea type=text name=description />%description%</textarea></td>
		</tr>
		<tr>
			<td>Bild * </td>
			<td>
				<input type=hidden name=max_file_size value=100000000 />
				<input name=file type=file />
			</td>
		</tr>
		<tr>
			<td colspan=2><input name=submit type=submit value=Hochladen /> <span style=font-size:10px;>*) Benötigt</span></td>
		</tr>
	</table>
</form>