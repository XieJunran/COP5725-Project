<?php
session_start();
//if(!session_is_registered(userID)){
if(!isset($_SESSION['userID'])){
    header("location:main_login.php");
}
$json_data=$_SESSION['json_data'];
$json1=(array) json_decode($json_data,1);
@   $userID=$json1[0]['id'];
?>



<!DOCTYPE html>
<html xmlns:z="http://www.fengyun.org">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Medical data sharing System</title>
<style type="text/css">
body{ font: 4mm/7mm simsun; margin: 0mm }
a{ text-decoration: none; color: #123456 }
.row{ width: 100%; margin: 0mm auto; display: block }
z\:Banner{ height: 1.28in; background-color: #99A394; text-indent: 1cm; font: bold 5mm/2cm FZYaoTi,Tempus Sans ITC; color: #456789 }
z\:Menu{ text-indent: 4mm; background-color: #abcdef }
z\:Foot{ text-align: center; color: #abc }
</style>

<style type="text/css">
z\:Description{ background-color: #f0f7ff }
z\:Description div{ padding: 3mm 1in }
VoteList{ background-color: #f0f7ff }
VoteList h3{ margin: 0mm; padding: 0mm 5mm; background-color: #abcdef; color: #123456; font: bold 4mm/7mm simsun }
div.list{ padding: 5mm 0mm 5mm 1cm }
Vote{ float: left; width: 150px; padding: 12px 12px 0mm 12px; height: 2in; margin: 4px; border: 1px solid #def; font: 9pt/18px simsun; text-align: center; background-color: #fff }
Vote img{ width: 120px; height: 160px; background-color: #def }
Vote input{ font: 9pt/9pt simsun; height: 18px }
</style>

<style type="text/css">
table.simpletable {
  margin-top:15px;
  border-collapse:collapse;
  border:1px solid #aaa;
  width:60%;
  align:center;
}
table.simpletable th {
  vertical-align:baseline;
  padding:5px 15px 5px 6px;
  background-color:#3F3F3F;
  border:1px solid #3F3F3F;
  text-align:center;
  color:#fff;
}
table.simpletable td {
  vertical-align:text-top;
  padding:6px 15px 6px 6px;
  border:1px solid #aaa;
  text-align:center;
}
table.simpletable tr:nth-child{
  background-color:#F5F5F5;
}


        .iitem {
			position: relative;
			display: inline-block;
			background: #D0EEFF;
			border: 1px solid #99D3F5;
			border-radius: 4px;
			padding: 4px 12px;
			overflow: hidden;
			color: #1E88C7;
			text-decoration: none;
			text-indent: 0;
			text-align: center;
			font-size:40px;
			font-family:Tempus Sans ITC;
			line-height: 30px;
			width:440px;
		}
		.iitem input[type="button"] {
			position: absolute;
			font-size: 100px;
			right: 0;
			top: 0;
			opacity: 1;
			width:440px;
			
		}
		.iitem:hover {
			background: #AADFFD;
			border-color: #78C3F3;
			color: #004974;
			text-decoration: none;
		}

</style>
<link href="css/buttons.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="http://cdn.bootcss.com/font-awesome/4.6.0/css/font-awesome.min.css">
<link rel="stylesheet" href="css/index.css" />
</head><body>
<z:Banner class="row"></z:Banner>
<img src="images/Primary_card.jpg" style="position: absolute; width: 2.29in; height: 1.28in; top:0; left:0;" />
<h1  style="font-family:Tempus Sans ITC;position: absolute; top:-0.2in; left:7.8in; frot-size:40px"><br><br>Medical &nbsp;&nbsp;&nbsp;&nbsp;Data&nbsp;&nbsp;&nbsp;&nbsp; Sharing&nbsp;&nbsp;&nbsp;&nbsp; System</h1>
<div align="right">
<h2 id="useridnow" style="font-family:Tempus Sans ITC; color:black;">USERID:<?php echo $userID?></h2>
</div>
<div style="align:center; position: absolute; top:3.5in; left:4.0in" >
<a class="button button-glow button-rounded button-raised button-primary" style="height:400px;width:280px;font-family:Tempus Sans ITC;line-height: 100px;font-size: 40px;" onclick="openWin('tomanage.php');">Manage<br> your<br> own<br>Community</a>
<a class="button button-glow button-rounded button-highlight" style="height:400px;width:280px;font-family:Tempus Sans ITC;line-height: 100px;font-size: 40px;" onclick="openWin('community_create.php')">Create<br> a<br> new<br>Community</a>
<a class="button button-glow button-rounded button-caution" style="height:400px;width:280px;font-family:Tempus Sans ITC;line-height: 100px;font-size: 40px;" onclick="openWin('tocommunity.php');">Enter<br> <br>your<br> Community</a>
<a class="button button-glow button-rounded button-royal" style="height:400px;width:280px;font-family:Tempus Sans ITC;line-height: 100px;font-size: 40px;" onclick="openWin('joincommunity.php');">Join<br> a<br> new<br>Community</a>
<a class="button button-glow button-border button-rounded button-primary" style="height:400px;width:280px;font-family:Tempus Sans ITC;line-height: 100px;font-size: 40px;" onclick="location.href='Logout.php'">exit<br> <br>this<br> System</a>
</div>

 


<div class="s-side" style="position: absolute; top:1.28in; left:0; width:2.0in">
	<ul>
		<li class="s-firstItem first">
			<a href="#">
				<i class="fa fa-home"></i>
				<span>Main Page</span>
			</a>
		</li>
		
		
	</ul>
</div>




</body>

<script type="text/javascript" src="common.js"></script>
<script type="text/javascript" src="jquery-3.3.1.js"></script>
<script type="text/javascript" src="popwin.js"></script>
<script type="text/javascript" src="js/index.js" ></script>
 <script type="text/javascript">
 var ss;
function openWin(url){
        popWin.showWin(url,1000,600,function(){
            });
}
function gopage(url1)
{
    popWin.close();
	window.location.href=url1;
}
</script>
<script>

</script>