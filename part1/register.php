<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<title>Second Car WebSite</title>
<link rel="stylesheet" media="screen" href="css/css.css" />
</head>
<form id="msform">
	<fieldset>
		<h2 class="fs-title">Second Car WebSite</h2>
		<h3 class="fs-subtitle">Register</h3>
		<input type="text" id="userID" name="fname" placeholder="UserID" />
		<input type="password" id="password" name="lname" placeholder="Password" />
		<input type="password" id="cpassword" name="rname" placeholder="ConfirmPassword" />
		<input type="text" id="email" name="email" placeholder="email" />
		<input type="text" id="contact" name="contact" placeholder="contact" />
		<input type="button" name="submit" class="action-button" value="Register" onclick="register();"/>
		<input type="button" name="Register" class="action-button" value="Cancel" onclick="location.href='main_login.php'" />	
	</fieldset>
</form>
<script type="text/javascript" src="jquery-3.3.1.js"></script>
<br><br><br><br><br><br><br><br><br><br>
<br><br><br><br><br><br><br><br><br><br>
</body>
</html>
<script src="js/md5.js" type="text/javascript"></script>
<script>
function register()
{
	 var userID=document.getElementById("userID").value;
	 var password=document.getElementById("password").value;
	 var cpassword=document.getElementById("cpassword").value;
	 var email=document.getElementById("email").value;
	 var contact=document.getElementById("contact").value;
	 if(userID=="" || password==""){
	   alert('Please input userID and password!');
	   return;
	 }
	 if(cpassword!=password){
		   alert('Please Confirm the password!');
		   return;
		 }
	 if(email.indexOf("@")<0){
		 alert('Please Confirm the email!');
		 return;
	 }
	 password=hex_md5(password);
	 var json= '{"action":"registercheck","userid":"'+userID+'","password":"'+password+'","email":"'+email+'","contact":"'+contact+'"}';
	 datarequest(json);
}
function datarequest(json){
	var request=new XMLHttpRequest();
	request.open("POST","../part2/dbms.php",false); 
	request.setRequestHeader("Content-type", "application/json");
	request.onreadystatechange=function(){
	  if (request.readyState==4 && request.status==200 || request.status==304){
		  alert(request.responseText);
		  if(request.responseText=="success!")
		  window.location="main_login.php";
	  }
	}  
     request.send(json);
}
</script>