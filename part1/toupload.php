<?php
session_start();
//if(!session_is_registered(userID)){
if(!isset($_SESSION['userID'])){
    header("location:main_login.php");
}

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
z\:VoteList{ background-color: #f0f7ff }
z\:VoteList h3{ margin: 0mm; padding: 0mm 5mm; background-color: #abcdef; color: #123456; font: bold 4mm/7mm simsun }
div.list{ padding: 5mm 0mm 5mm 1cm }
z\:Vote{ float: left; width: 150px; padding: 12px 12px 0mm 12px; height: 2in; margin: 4px; border: 1px solid #def; font: 9pt/18px simsun; text-align: center; background-color: #fff }
z\:Vote img{ width: 120px; height: 160px; background-color: #def }
z\:Vote input{ font: 9pt/9pt simsun; height: 18px }
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
</style>

<style>
      .a-upload {
			padding: 4px 10px;
			height: 20px;
			line-height: 20px;
			position: relative;
			cursor: pointer;
			color: #888;
			background: #fafafa;
			border: 1px solid #ddd;
			border-radius: 4px;
			overflow: hidden;
			display: inline-block;
			*display: inline;
			*zoom: 1
		}
		
		.a-upload  input {
			position: absolute;
			font-size: 100px;
			right: 0;
			top: 0;
			opacity: 0;
			filter: alpha(opacity=0);
			cursor: pointer
		}
		
		.a-upload:hover {
			color: #444;
			background: #eee;
			border-color: #ccc;
			text-decoration: none
		}

		.file {
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
			line-height: 20px;
		}
		.file input[type="file"] {
			position: absolute;
			font-size: 100px;
			right: 0;
			top: 0;
			opacity: 0;
		}
		.file:hover {
			background: #AADFFD;
			border-color: #78C3F3;
			color: #004974;
			text-decoration: none;
		}

.ttext {
    border-radius: 4px;
    border: 1px solid #99D3F5;
    font-size: 20px;
    color: #444;
    width: 300px;
    line-height: 30px;
    border-style:none none none none;
    background: transparent;
}

.file-box{ 
position:relative;
width:340px;
margin:20px;
}
.txt{ 
height:28px;
line-height:28px; 
border:1px solid #cdcdcd; 
width:180px;
}
.btn{
width:70px; 
color:#fff;
background-color:#3598dc; 
border:0 none;
height:28px; 
line-height:16px!important;
cursor:pointer;
}
.btn:hover{
background-color:#63bfff;
color:#fff;
}
.file{ 
position:absolute; 
top:0; 
right:85px; 
height:30px;
line-height:30px; 
filter:alpha(opacity:0);
opacity: 0;
width:254px 
}
</style>
<link href="css/buttons.css" rel="stylesheet" type="text/css" />
</head>

<div align="center" class="file-box"> 
<form>
        <input type="text" id="textfield" class="txt" />
        <input type="button" class="btn" value="choose" /> 
        <input type="file" name="file" class="file" id="fileField" onchange="fileSelected(this);"/> 
        <input type="button" class="btn" value="upload" onclick="uploadFile();"/> 
</form>
</div>


<script>   
function fileSelected(userfile) {
	document.getElementById('textfield').value=userfile.files[0].name;
}
function uploadFile() {
	var userfile=document.getElementById('fileField');
	var fd = new FormData();
	fd.append("file",userfile.files[0]);
	var str=window.parent.document.getElementById("useridnow").innerText;
	var arr=str.split(":");
	var userid=arr[1];
	fd.append("userID",userid);
	str=window.parent.document.getElementById('communityidnow').innerText;
	arr=str.split(":");
	var communityid=arr[1];
	fd.append("CommunityID",communityid);
    var xhr = new XMLHttpRequest();
    xhr.upload.addEventListener("progress", uploadProgress, false);
    xhr.addEventListener("load", uploadComplete, false);
    xhr.addEventListener("error", uploadFailed, false);
    xhr.open("POST", "http://localhost/medicalshare/part2/upload.php");
    xhr.send(fd);
}

function uploadProgress(evt) {
    if (evt.lengthComputable) {
        var percentComplete = Math.round(evt.loaded * 100 / evt.total);
        console.log(percentComplete)
    }else {
    }
}


function uploadComplete(evt) {
   // var json = eval('(' +evt.target.responseText  + ')');
   // console.log(json)
     alert(evt.target.responseText);
}

function uploadFailed(evt) {
    alert("failed!");
}

</script>




