<?php session_start();

$host = "localhost";
$userName = "root";
$password = "password";
$db = "userdata";

$connect = mysqli_connect($host,$userName,$password, $db);
if(!isset($_SESSION['username']))
{
	header("Location:index.php");
}
if(!isset($_SESSION['admin']))
{
	header("Location:index.php");
}
?>
<html>
		<head>
		<title>NewsWebsite</title>
		<meta name "viewport" content="width=device-width, initial-scale=1.0">
		<link href = "css/bootstrap.min.css" rel = "stylesheet">
		<link href = "css/styles.css" rel = "stylesheet"><style type='text/css'>
span.link a {
	font-size:150%;
	color: #000000;
	text-decoration:none;
}
a.vote_up, a.vote_down {
	display:inline-block;
	background-repeat:none;
	background-position:center;
	height:16px;
	width:16px;
	margin-left:4px;
	text-indent:-900%;
}

a.vote_up {
	background:url("images/thumb_up.png");
}

a.vote_down {
	background:url("images/thumb_down.png");
}	
</style>
		</head>
	<body>
	
	
		<div class = "navbar navbar-inverse navbar-static-top">
		<div class ="container">
			
			<div class="navbar-header">
					<button class="navbar-toggle" data-toggle="collapse" data-target=".navHeaderCollapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="#">
						<img id="brand-image" alt="Website Logo" src="images/Meshii.png"  />
					</a>
			</div>
				
			
			<div class ="collapse navbar-collapse navHeaderCollapse">
			
				<ul class = "nav navbar-nav navbar-right">
					<li class="divider-vertical"></li>
					<li class="active"><a href = "index.php">Home</a></li>
					<li class="divider-vertical"></li>
					<li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Topics <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
							<li><a href = "mostRecent.php">Most Recent</a></li>
							<li><a href = "mostPopular.php">Most Popular</a></li>
                            <li><a href = "sports.php">Sports</a></li>
                            <li><a href = "fashion.php">Fashion</a></li>
                            <li><a href = "discounts.php">Discounts</a></li>
                            <li><a href = "socialEvents.php">Social Events</a></li>
                            <li><a href = "travel.php">Travel</a></li>
                            <li><a href = "university.php">University</a></li>
						</ul>
					<li class="divider-vertical"></li>
					<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown"><b>Login</b> <span class="caret"></span></a>
			<ul id="login-dp" class="dropdown-menu">
				<li>
					 <div class="row">
							<div class="col-md-12">
							<?php if(isset($_SESSION['username'])): 
					 	echo "<form id='logout' action='logout.php' method='post'>
						Welcome, ";
						echo $_SESSION['username'];
						echo "<input type='submit' value='Logout' name='submit' onClick='logout.php'>
					</form>";
					 else: 
					 ?>
								Login using:
								
								 <form class="form" role="form" method="post" action="connect.php" accept-charset="UTF-8" id="signInForm">
										<div class="form-group">
											 <label class="sr-only" for="userNameText">Username</label>
											 <input type="username" class="form-control" id="userNameText" name="username" placeholder="Username" required>
										</div>
										<div class="form-group">
											 <label class="sr-only" for="passwordText">Password</label>
											 <input type="password" class="form-control" id="passwordText" name="password" placeholder="Password" required>
                                             
										</div>
										<div class="form-group">
											 <button type="submit" value="submit" name="submit" onClick="connect.php" class="btn btn-primary btn-block" id="button" >Sign in</button>
										</div>
										</div>
							<div class="bottom text-center">
								New here ? <a href="registerForm.php"><b>Press here to register</b></a>
							</div>
								 </form>
								 <?php		
					endif; 
					 ?>   	
							
					 </div>
				</li>
			</ul>
					<li class="divider-vertical"></li>
					<li class><a href = "postNews.php">Post News</a></li>
					<li class="divider-vertical"></li>
					<li><a href = "registerForm.php">Register an account</a></li>
					<li class="divider-vertical"></li>
					<li><a href = "about.php">About</a></li>
					<li class="divider-vertical"></li>
					<li><a href = "contactUs.php">Contact</a></li>	
					<li class="divider-vertical"></li><br>
				<br><br><br><br>
				</ul>
				<div class="col-sm-3 col-md-3 pull-left">
					
				</div>
			</div>
		</div>
	</div>
	
	<div class = "container">
	
		<div class = "row">
			
				<div class = "col-lg-9">
			
						
						<div class = "panel panel-default">
							
							<div class = "panel-body">
								
								<div class ="page-header">
									<h3>Admin</h3>
								</div>
																		<script type='text/javascript' src='jquery.pack.js'></script>
<script type='text/javascript'>
$(function(){
	$("a.vote_up").click(function(){
	//get the id
	the_id = $(this).attr('id');
	
	// show the spinner
	$(this).parent().html("<img src='images/spinner.gif'/>");
	
	//fadeout the vote-count 
	$("span#votes_count"+the_id).fadeOut("fast");
	
	//the main ajax request
		$.ajax({
			type: "POST",
			data: "action=vote_up&id="+$(this).attr("id"),
			url: "votes.php",
			success: function(msg)
			{
				$("span#votes_count"+the_id).html(msg);
				//fadein the vote count
				$("span#votes_count"+the_id).fadeIn();
				//remove the spinner
				$("span#vote_buttons"+the_id).remove();
			}
		});
	});
	
	$("a.vote_down").click(function(){
	//get the id
	the_id = $(this).attr('id');
	
	// show the spinner
	$(this).parent().html("<img src='images/spinner.gif'/>");
	
	//the main ajax request
		$.ajax({
			type: "POST",
			data: "action=vote_down&id="+$(this).attr("id"),
			url: "votes.php",
			success: function(msg)
			{
				$("span#votes_count"+the_id).fadeOut();
				$("span#votes_count"+the_id).html(msg);
				$("span#votes_count"+the_id).fadeIn();
				$("span#vote_buttons"+the_id).remove();
			}
		});
	});
});	
</script>

</head>
<body>

<?php
/**
Display the results from the database
**/
$host = "localhost";
$userName = "root";
$password = "password";
$db = "userdata";

$connect = mysqli_connect($host,$userName,$password, $db);
$q = "SELECT * FROM news";
$r = mysqli_query($connect, $q);

if(mysqli_num_rows($r)>0): //table is non-empty
	while($row = mysqli_fetch_assoc($r)):
		$net_vote = $row['votesUp'] - $row['votesDown']; //this is the net result of voting up and voting down
		//of votesUp - votesDown => -5	
		$idStore = $row['postID'];
?>
<div class='entry'>
	<span class='link'>
		<h1 id="title"><?php echo $row['title'];?></h1>
		<p id="userName"><?php echo "By "; echo $row['userName'];?></p>
		<p id="pBody"><?php echo $row['body']?></p>
		<div id="container"><a href="show-image.php?postID=<?php echo $idStore?>"><img src="data:image/jpeg;base64,<?php echo base64_encode($row['imageData']); ?> " /></a></div>
		<p id="pLink"><a href='<?php echo $row['link']?>' target='_blank'><?php echo $row['link']?></p></a>
		<p id="pLink"><?php echo $row['topic']?></p>
	</span>
	
	<span class='votes_count' id='votes_count<?php echo $row['postID'];?>'><?php echo $net_vote." votes"; ?></span>
	
	<span class='vote_buttons' id='vote_buttons<?php echo $row['postID']; ?>'>
		<a href='javascript:;' class='vote_up' id='<?php echo $row['postID']; ?>'></a>
		<a href='javascript:;' class='vote_down' id='<?php echo $row['postID']; ?>'></a>
		
	</span>
	<form id='delete' action='delete.php' method='post'>
			<button value="delete" name="delete" onClick="delete.php">Delete this item</button>
			<input type="text" name="id" value="<?php echo $row['postID'];?>" style="visibility:hidden" readonly class="noselect">
		</form>
	<hr>
</div>
<?php
 endwhile;
endif;
?>
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
var F=xmlDoc.getElementsByTagName("newss");
for (i=0;i<F.length;i++)
{
	document.write("<h4>");
	document.write(F[i].getElementsByTagName("title")[0].childNodes[0].nodeValue);
	document.write("</h4><p>");
	document.write(F[i].getElementsByTagName("body")[0].childNodes[0].nodeValue);
	var link = F[i].getElementsByTagName("link")[0].childNodes[0].nodeValue;
	document.write("</p><h5>Reference</h5><p><a href='"+link+"' target='_blank'>");
	document.write(link);
	document.write("</a></p>");
	document.write("<hr>");
}
</script></p>
					
							</div>
							
						</div>
				</div>
				
				<div class = "col-lg-3">
						<div class = "list-group-item"><h1>Hot News</h1></div>
					<?php
/**
Display the results from the database
**/
$host = "localhost";
$userName = "root";
$password = "password";
$db = "userdata";

$connect = mysqli_connect($host,$userName,$password, $db);
$q = "SELECT * FROM news ORDER BY numberOfVotes DESC LIMIT 3";
$r = mysqli_query($connect, $q);

if(mysqli_num_rows($r)>0): //table is non-empty
	while($row = mysqli_fetch_assoc($r)):
		$net_vote = $row['votesUp'] - $row['votesDown']; //this is the net result of voting up and voting down
		//of votesUp - votesDown => -5	
		$idStore = $row['postID'];
?>
<!--need to figure out how to display the image, but only the image to be content-type image/jpg-->
	<span class='link'>
		
	</span>
	
	
						<div class = "list-group">
						
							<div class = "list-group-item">
								<span class='link'>
		<h1 id="title"><?php echo $row['title'];?></h1>
		<p id="userName"><?php echo "By "; echo $row['userName'];?></p>
		<p id="pBody"><?php echo $row['body']?></p>
		<div id="container"><a href="show-image.php?postID=<?php echo $idStore?>"><img src="data:image/jpeg;base64,<?php echo base64_encode($row['imageData']); ?> " /></a></div>
		<p id="pLink"><a href='<?php echo $row['link']?>' target='_blank'><?php echo $row['link']?></p></a>
		<p id="pLink"><?php echo $row['topic']?></p>
	</span>
	
	<span class='votes_count' id='votes_count<?php echo $row['postID'];?>'><?php echo $net_vote." votes"; ?></span>
						</div><?php
 endwhile;
endif;?>
<div class = "list-group-item">
	<h4 class = "list-group-item-heading">News provided by BBC</h4>
                        </a>
                        <!-- start feedwind code --><script type="text/javascript">document.write('\x3Cscript type="text/javascript" src="' + ('https:' == document.location.protocol ? 'https://' : 'http://') + 'feed.mikle.com/js/rssmikle.js">\x3C/script>');</script><script type="text/javascript">(function() {var params = {rssmikle_url: "http://feeds.bbci.co.uk/news/rss.xml",rssmikle_frame_width: "180",rssmikle_frame_height: "400",frame_height_by_article: "0",rssmikle_target: "_blank",rssmikle_font: "Arial, Helvetica, sans-serif",rssmikle_font_size: "12",rssmikle_border: "off",responsive: "off",rssmikle_css_url: "",text_align: "left",text_align2: "left",corner: "off",scrollbar: "on",autoscroll: "on",scrolldirection: "up",scrollstep: "3",mcspeed: "20",sort: "Off",rssmikle_title: "on",rssmikle_title_sentence: "BBC news",rssmikle_title_link: "http://feeds.bbci.co.uk/news/rss.xml",rssmikle_title_bgcolor: "#ea0101",rssmikle_title_color: "#FFFFFF",rssmikle_title_bgimage: "",rssmikle_item_bgcolor: "#FFFFFF",rssmikle_item_bgimage: "",rssmikle_item_title_length: "55",rssmikle_item_title_color: "#ea0101",rssmikle_item_border_bottom: "on",rssmikle_item_description: "on",item_link: "off",rssmikle_item_description_length: "150",rssmikle_item_description_color: "#666666",rssmikle_item_date: "gl1",rssmikle_timezone: "Etc/GMT",datetime_format: "%b %e, %Y %l:%M %p",item_description_style: "text+tn",item_thumbnail: "full",item_thumbnail_selection: "auto",article_num: "15",rssmikle_item_podcast: "off",keyword_inc: "",keyword_exc: ""};feedwind_show_widget_iframe(params);})();</script><div style="font-size:10px; text-align:center; width:300px;"><a href="http://feed.mikle.com/" target="_blank" style="color:#CCCCCC;">RSS Feed Widget</a><!--Please display the above link in your web page according to Terms of Service.--></div><!-- end feedwind code --><!--  end  feedwind code -->
						</div>
				
		</div>
			
			<div class = "navbar navbar-default navbar-fixed-bottom">
				<a href = "#" class = "navbar-text pull-left"><p>|Group Project  |</p>

				<a href = "#" class = "navbar-text pull-left"><p>|Home|   </p>
				<a href = "https://www.copyrightservice.co.uk/protect/p11_web_design_copyright"class = "navbar-btn btn-info btn pull-right">COPYRIGHT</a>
			</div>
	</div>
	
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
		<script src = "js/bootstrap.js"></script>?
		<script src="js/main(2).js"></script>
		<script src="js/scroll-top.js"></script>
		<script src="js/dropdown.js"></script>

		<a class="scroll-top" href="#" title=Scrolltotop" style="display: inline;"></a>


	</body>
</html>
