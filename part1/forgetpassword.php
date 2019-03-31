<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<title>Second Car WebSite</title>
<link rel="stylesheet" media="screen" href="css/css.css" />
</head>
<form id="msform">
	<fieldset>
		<h2 class="fs-title">Second Car WebSite</h2>
		<h3 class="fs-subtitle">Reset paaword</h3>
		<h4 class="fs-subtitle">Enter your userID or email</h4>
		<input type="text" id="userID" name="fname" placeholder="UserID" />
		<input type="text" id="email"  name="lname" placeholder="email" />
		<input type="button" name="send" class="action-button" value="send" onclick="sendemail();"/>
		<input type="button" name="Cancel" class="action-button" value="Cancel" onclick="location.href='main_login.php'" />	
	</fieldset>
</form>
<script type="text/javascript" src="jquery-3.3.1.js"></script>
<script src="js/md5.js" type="text/javascript"></script>
<br><br><br><br><br><br><br><br><br><br>
<br><br><br><br><br><br><br><br><br><br>

</body>
</html>
<script>
function sendemail()
{
	 var userID=document.getElementById("userID").value;
	 var email=document.getElementById("email").value;
	 if(userID=="" && email==""){
	   alert('Please input userID or email!');
	   return;
	 }
	 if(email.indexOf("@")<0){
		 alert('Please Confirm the email!');
		 return;
	 }
	 var json= '{"action":"forgetpassword","userid":"'+userID+'","email":"'+email+'"}';
	 datarequest(json);
}
function datarequest(json){
	var request=new XMLHttpRequest();
	request.open("POST","../part2/dbms.php",false); 
	request.setRequestHeader("Content-type", "application/json");
	request.onreadystatechange=function(){
	  if (request.readyState==4 && request.status==200 || request.status==304){
		  if(request.responseText=="success!"){  
		  	window.location="main_menu.php";
		  }
		  else
			  alert(request.responseText);			  
	  }
	}  
     request.send(json);
}
</script>

