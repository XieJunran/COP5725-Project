<?php
$code = $_GET['code'];
$username="huanbin";
$password="24361Zhb1152";
$connection_string="oracle.cise.ufl.edu:1521/orcl";
global $con;
$con=oci_connect($username,$password,$connection_string);

if($code=='') return;
$query="SELECT * FROM USER_INFO WHERE pcode=:code";
$stmt = oci_parse($con, $query);
oci_bind_by_name($stmt, ":code",$code);
oci_execute($stmt);
if(oci_fetch_all($stmt, $records)==0){
    oci_close($con);
    echo 'ERROR!';
}
else{
    if(!$records["CHANGEPW"][0]){
        return;
    }
    oci_close($con);
} 
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<title>Second Car WebSite</title>
<link rel="stylesheet" media="screen" href="css/css.css" />
</head>
<input type="text" id="code" style="display:none" value="<?php echo $code?>"/>
<form id="msform">
	<fieldset>
		<h2 class="fs-title">Second Hand Car</h2>
		<h3 class="fs-subtitle">Changepassword</h3>
		<input type="password" id="password" name="lname" placeholder="NewPassword" />
		<input type="password" id="cpassword" name="rname" placeholder="ConfirmNewPassword" />
		<input type="button" name="Confirm" class="action-button" value="Confirm" onclick="change();"/>
		<input type="button" name="Cancel" class="action-button" value="Cancel" onclick="close();" />	
	</fieldset>
</form>
<script type="text/javascript" src="jquery-3.3.1.js"></script>
<script src="js/md5.js" type="text/javascript"></script>
<br><br><br><br><br><br><br><br><br><br>
<br><br><br><br><br><br><br><br><br><br>

</body>
</html>
<script>
function close(){
	window.opener=null;
	window.open('','_self');
	window.close();
}
function change()
{
	 var code=document.getElementById("code").value;
	 var password=document.getElementById("password").value;
	 var cpassword=document.getElementById("cpassword").value;
	 if(password==""){
	   alert('Please input Newpassword!');
	   return;
	 }
	 if(cpassword!=password){
		   alert('Please Confirm the Newpassword!');
		   return;
		 }
	 password=hex_md5(password);
	 var json= '{"action":"changepassword","password":"'+password+'","code":"'+code+'"}';
	 datarequest(json);	
}
function datarequest(json){
	var request=new XMLHttpRequest();
	request.open("POST","../part2/dbms.php",false); 
	request.setRequestHeader("Content-type", "application/json");
	request.onreadystatechange=function(){
	  if (request.readyState==4 && request.status==200 || request.status==304){
		  if(request.responseText=="success!"){
			  alert("success!");			  
		  	  window.location="main_login.php";
		  }
		  else
			  alert(request.responseText);			  
	  }
	}  
     request.send(json);
}
</script>