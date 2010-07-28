
<p>Editieren von vorhandener Datei
Wenn Sie die Datei ersetzten wollen, laden Sie eine neue Datei hoch.
</p>
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
			<td>Aktuelle Datei: </td>
			<td>%file%</td>
		</tr>
		<tr>
			<td>Datei * </td>
			<td>
				<input type=hidden name=max_file_size value=10000 />
				<input name=file type=file />
			</td>
		</tr>
		<tr>
			<td colspan=2>
			<input type=hidden name=id value=%id% />
			<input name=submit type=submit value=Editieren />
			<span style=font-size:10px;>*) Benötigt</span></td>
		</tr>
	</table>
</form>