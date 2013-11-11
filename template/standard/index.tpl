<html>
<head>
	<title>%pagetitle%</title>
<style type="text/css">
body {
	margin: 0px;
}
* {
	font-family:Verdana;
	font-size:12px;
}
ul {
	padding-bottom: 4px;
}
ol {
	padding-bottom: 5px;
}
h1 { font-size:18px;color:#000000;margin-bottom:0px;}
h2 { font-size:16px;color:#000000;}
h3 { font-size:14px;color:#000000;}
a:link, a:visited, a:active, a:hover {
	text-decoration:underline;
	font-weight:normal;
	color: #222433;
	font-size:12px;
}
.input {
	width: 150px;
}
a:active, a:hover {
	color:#009999;
}
a.menu {
	display:block;
	padding:3px;
	border-top:1px solid #000;
	background-color:#737994;
	text-align: left;
	color:#eeeeee;
	text-decoration:none;
	font-weight:bold;
}
a.menu:link, a.menu:visited {
	color:#eeeeee;
	background-color:#737994;
	text-decoration:none;
	font-weight: bold;
}
a.menu:active, a.menu:hover {
	color:#737994;
	background-color:#eeeeee;
}
.menutitle{
	font-weight:bold;
	text-align:center;
	margin:2px;
}
.menucontainer {
	border:1px solid #000;
	background-color:#7b7d8e;
	width:156px;
	margin:10px;
}

.attachement:link, .attachement:visited {
	color: #0F0F0F;
}
.attachement:active, .attachement:hover {
	color: #FF0d00;
}

#title {
	height:34px;
	padding:5px;
	background-color:#7b7d8e;
	border-bottom:1px solid #000;
}
#left {
	background-color:#bdbec6;
	width:178px;
	float:left;
}
#content {
	border:1px solid #efeff7;
	background-color:#efeff7;
	width:620px;
	float:right;
	padding:0px 10px;
	min-height:600px;
}
#modulemenu {
	background: #BdBEC4;
	border: 1px solid #424244;
	padding: 4px;
	margin-top:2px;
	margin-bottom: 10px;
}
#footer {
	background-color:#bdbec6;
	text-align:center;
	border-top: 1px solid #000;
}


</style>
</head>
<body>
	<div style="width:820px;margin:0px auto;text-align:left;background-color:#efeff7;border:1px solid #000000;">
		<div id=title>
			<h1>Content Management System 
		</div>
		<div id=left>
			%modulelist%
			%navigation%
		</div>
		<div id=content>
			<h1>%pagetitle%</h1>
			<span>%error% %note% %success%</span>
			<div >%content%</div>
		</div>
		<br style="clear:both;" />
		<div id=footer>Copyright 2010 - modern-IT</div>
	</div>
</html>
</body>
