<?php
session_start();
//if(!session_is_registered(userID)){
if(!isset($_SESSION['userID'])){
    header("location:main_login.php");
}
setcookie("cline","-1");
?>
<head>
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
<link href="css/buttons.css" rel="stylesheet" type="text/css" />
</head>
<div align="center">
<form name="form3" method="post">
<h1 style="font-family:Tempus Sans ITC">Join a new Community</h1>
<table class="style9">
<tr>
<td>
<input name="communityid" type="text" id="communityid" value="">
</td>
<td>
<a class="button button-raised button-pill button-inverse" type="button"  onclick="searchcommunity();">search</a></td>
<td>
<a class="button button-raised button-pill button-inverse" type="button"  onclick="joincommunity();">join</a></td>
<td>
<a class="button button-raised button-pill button-inverse" type="button"  onclick="finish();">finish</a></td>
</tr>
</table>

<table class=simpletable id='communitytable'>
<tr>
<th>communityID</th>
<th>ownerID</th>
<th>status</th>
</tr>
</table>
</form>
</div>

<script>
function freshtable(tableid,json){
	var json1=json;
	var cdata =  JSON.parse(json1);
    var len=cdata.length;
    var now=document.getElementById(tableid);
    var str=window.parent.document.getElementById("useridnow").innerText;
	var arr=str.split(":");
	var userid=arr[1];
    now.innerHTML='<tr><th>communityID</th><th>ownerID</th><th>status</th></tr>';
    var jso= '{"action":"checkrequest","userid":"'+userid+'","communityid":[';
    for(var j=0;j<len;j++){
        jso=jso+'"'+cdata[j]["CommunityID"]+'"';
        if(j!=len-1) jso=jso+',';
        }
    jso=jso+']}';
  //  alert(jso);
    var ss=datarequest(jso);
    var str1="";
    for(var i=0;i<len;i++){
  	  var tr=now.insertRow(-1);
  	  tr.setAttribute('id',i);
  	  tr.setAttribute('onclick','chooseit('+i+');');
  	  var td1=tr.insertCell(-1);
  	  var td2=tr.insertCell(-1);
  	  var td3=tr.insertCell(-1);
  	  if(ss[i]=="1")
  	  	  str1="Pending";
  	  	  else
  	  	  	  str1="Unsend";
  	  td1.innerHTML="<td>"+cdata[i]["CommunityID"]+"</td>";
  	  td2.innerHTML="<td>"+cdata[i]["ownerID"]+"</td>";
  	  td3.innerHTML="<td>"+str1+"</td>";
  	 // alert(cdata[i]["CommunityID"]);
    }
  return;
}
	function getCookie(name){
		var str=document.cookie;
		var arr=str.split("; ");
		for (var i=0; i<arr.length; i++){
			var arry = arr[i].split("=");
			if (arry[0] == name){
				return arry[1];
			}
		}
		return "";
	}
	function chooseit(ctr){
		var now=getCookie("cline");
		if (now!=-1){
		document.getElementById(now).style.backgroundColor="#F5F5F5";
		}
		document.getElementById(ctr).style.backgroundColor="#6959CD";
	    document.cookie="cline"+"="+ctr;
		return;
	}
	
function searchcommunity(){
	 var com=document.getElementById("communityid").value;
	 var str=window.parent.document.getElementById("useridnow").innerText;
	 var arr=str.split(":");
	 var userid=arr[1];
	 var json= '{"action":"searchcommunity","communityid":"'+com+'","userid":"'+userid+'"}';
	 var re=datarequest(json);
	// alert(re);
     freshtable("communitytable",re);
     document.cookie="cline=-1";
	 return;
}
function joincommunity(){
	var now=getCookie("cline");
	if (now==-1){
		alert("Please choose a Community!");
		return;
	}
	var com=document.getElementById(now).cells;
	var str=window.parent.document.getElementById("useridnow").innerText;
	var arr=str.split(":");
	var userid=arr[1];
	var json= '{"action":"joincommunity","communityid":"'+com[0].innerHTML+'","userid":"'+userid+'"}';
	datarequest(json);
	//alert(com[0].innerText);
	alert("success!");
	searchcommunity();
	return;
}

function datarequest(json){
	var request=new XMLHttpRequest();
	request.open("POST","../part2/database.php",false); 
	request.setRequestHeader("Content-type", "application/json");
	var ttttt;
	request.onreadystatechange=function(){
	  if (request.readyState==4 && request.status==200 || request.status==304){
		    ttttt=request.responseText;
	  }
	}  
     request.send(json);
     return ttttt;
}
function finish(){
	window.parent.gopage("main_menu.php");
}

</script>
