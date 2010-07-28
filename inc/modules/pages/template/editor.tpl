<script type="text/javascript" src="editor/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "textareas",
		theme : "advanced",
		language : "de",
		plugins : "inlinepopups,contextmenu,directionality,noneditable,visualchars,nonbreaking,xhtmlxtras,wordcount",
		
		// Theme options
		theme_advanced_buttons1 : "help, bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "bullist,numlist,|,outdent,indent,|,undo,redo,|,image,|,link,unlink,cleanup,code,|,forecolor,backcolor,removeformat,|,charmap",
		theme_advanced_buttons3 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,
	});
	
function insertImg(imgUrl, imgName)
{
	var el = tinyMCE.activeEditor.dom.create('img', {src : imgUrl, width : 200});
	if(tinyMCE.isOpera) {
		tinyMCE.activeEditor.selection.setNode(el);
	} else {
		tinyMCE.activeEditor.dom.add(tinyMCE.activeEditor.getBody(), el);
	}
	showEditor();
	alert('Sie haben Bild '+imgName+' eingefügt.\nWenn Sie das Bild bearbeiten möchten, klicken Sie auf das Bild und danach auf den Button "Bild einfügen/verändern". Dort können Sie das Bild auch im Textfluss positionieren.');
}
function showFileList()
{
	document.getElementById('editor').style.display = 'none';
	document.getElementById('fileList').style.display = 'block';
}
function showImageList()
{
	document.getElementById('editor').style.display = 'none';
	document.getElementById('imageList').style.display = 'block';
}
function showEditor()
{
	document.getElementById('editor').style.display = 'block';
	document.getElementById('imageList').style.display = 'none';
	document.getElementById('fileList').style.display = 'none';
}
</script>

<form method=post action=%request_uri%>
	<div id=editor style=display:block;>
		<input type=hidden name=id value=%id% />
		<table border=0>
			<tr>
				<td valign=top>Titel</td>
				<td valign=top><input type=text name=title maxlength=255 size=50 value="%title%" /></td>
			</tr>
			<tr>
				<td valign=top>
					<p>Inhalt</p>
					<div onclick="javascript:showImageList();" style="font-weight: bold; text-align:center;border: 2px solid #0c0c0c; background: #c0c0c0; padding:2px; margin: 2px;">
						Bilder
					</div>
					<div onclick="javascript:showFileList();" style="font-weight: bold; text-align:center;border: 2px solid #0c0c0c; background: #c0c0c0; padding:2px; margin: 2px;">
						Dateien
					</div>
				</td>
				<td><textarea name=content style=height:350px; class=textarea  id=page_content>%content%</textarea></td>

			</tr>
			<tr>
				<td colspan=2>
					<div>%cur_firstpage%</div>
					<label for=firstpage>
						<div>
							<input id=firstpage type=checkbox name=firstpage value=1 %firstpage_selected% />
							Diese Seite als Startseite
						</div>
					</label>
				</td>
			</tr>
			<tr>
				<td colspan=2><input type=submit value=Speichern /></td>
			</tr>
		</table>
		
	</div>

	<div id=fileList style=display:none;>
	<p>
		Wählen Sie die Datei aus die Sie einfügen möchten.<br/>Wenn Sie dabei mehr als eine Datei auswählen möchten, drücken Sie dabei die STRG-Taste.
	</p>
	<div onclick="javascript:showEditor();" style="width: 150px; font-weight: bold; text-align:center;border: 2px solid #0c0c0c; background: #c0c0c0; padding:2px; margin: 10px;">
	Zurück zum Editor
	</div>

		<select name="files[]" size="15" multiple>
		<option value=0>-- Keine Datei --</option>
						%file%
		</select>
	</div>
</form>

<div id=imageList style=display:none;>
<p>
Um ein Bild einzufügen klicken Sie es einfach an.
</p>
<div onclick="javascript:showEditor();" style="width: 150px; font-weight: bold; text-align:center;border: 2px solid #0c0c0c; background: #c0c0c0; padding:2px; margin: 10px;">
Zurück zum Editor
</div>

	%images%
	
</div>




