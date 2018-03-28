<?php session_start();?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Home Page</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div id="wrapper">
<div id="header">
<div id="search">
<form id="searchForm" action="">
<p>Search: <input type="text" name="search"></p>
</form>
</div>
<div id="logo">
LOGO HERE
</div>
<div id="signIn">
<form id="logout" action="logout.php" method="post">
<p>Welcome, <?php print_r($_SESSION['username']); ?>      
<input type="submit" value="Logout" name="submit" onClick="logout.php">
</form></p>
</div>
</div>
<div id="navigation-content">
<nav>
	<ul class="nav">
        <li><a id="active" href="index.php">Home</a></li>
        <li><a href=".html">Topics</a></li>
        <li><a href=".html">Sign In/Register</a></li>
        <li><a href=".html">About</a></li>
        <li><a href=".html">Mobile Web App</a></li>
        <li><a href=".html">Contact</a></li>
	</ul>
</nav>
</div>
<div id="main-content">
<div id="col1">
<script>
if (window.XMLHttpRequest)
{// code for IE7+, Firefox, Chrome, Opera, Safari
xmlhttp=new XMLHttpRequest();
}
else
{// code for old browsers
xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
xmlhttp.open("GET","xml.xml",false);
xmlhttp.send();
xmlDoc=xmlhttp.responseXML;
function show_image(src, width, height, alt) {
    var img = document.createElement("img");
    img.src = src;
    img.width = width;
    img.height = height;
    img.alt = alt;
    document.getElementById("col1").appendChild(img);
}
document.write("<h2>The News</h2>");
var F=xmlDoc.getElementsByTagName("News");
for (i=0;i<F.length;i++)
{
	document.write("<h4>");
	document.write(F[i].getElementsByTagName("Title")[0].childNodes[0].nodeValue);
	document.write("</h4><p>");
	document.write(F[i].getElementsByTagName("Body")[0].childNodes[0].nodeValue);
	var link = F[i].getElementsByTagName("Link")[0].childNodes[0].nodeValue;
	document.write("</p><h5>Reference</h5><p><a href='"+link+"' target='_blank'>");
	document.write(link);
	document.write("</a></p>");
	document.write("<hr>");
}
</script></p>
</div>
<div id="col2">
</div>
<div class="clearfloats">
</div>
</div>
<div id="footer">
<p>Copyright�	|	Group Project	|	Home</p>
</div>
</div>
</body>
</html>
