<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Second Hand Car Login</title>
    <style>
        * { margin: 0; padding: 0; }
        html { height: 100%; }
        body { height: 100%; background: #fff url(images/KV1440600.jpg) 50% 50% no-repeat; background-size: cover;}
        .dowebok { position: absolute; left: 50%; top: 50%; width: 430px; height: 550px; margin: -300px 0 0 -215px; border: 1px solid #fff; border-radius: 20px; overflow: hidden;}
        .logo { width: 104px; height: 104px; margin: 50px auto 80px; background: url(images/login.png) 0 0 no-repeat; }
        .form-item { position: relative; width: 360px; margin: 0 auto; padding-bottom: 30px;}
        .form-item input { width: 288px; height: 48px; padding-left: 70px; border: 1px solid #fff; border-radius: 25px; font-size: 18px; color: #fff; background-color: transparent; outline: none;}
        .form-item button { width: 360px; height: 50px; border: 0; border-radius: 25px; font-size: 18px; color: #1f6f4a; outline: none; cursor: pointer; background-color: #fff; }
        #username { background: url(images/user.png) 25px 14px no-repeat; }
        #password { background: url(images/password.png) 23px 11px no-repeat; }
        .tip { display: none; position: absolute; left: 20px; top: 52px; font-size: 14px; color: #f50; }
        .reg-bar { width: 360px; margin: 20px auto 0; font-size: 14px; overflow: hidden;}
        .reg-bar a { color: #fff; text-decoration: none; }
        .reg-bar a:hover { text-decoration: underline; }
        .reg-bar .reg { float: left; }
        .reg-bar .forget { float: right; }
        .dowebok ::-webkit-input-placeholder { font-size: 18px; line-height: 1.4; color: #fff;}
        .dowebok :-moz-placeholder { font-size: 18px; line-height: 1.4; color: #fff;}
        .dowebok ::-moz-placeholder { font-size: 18px; line-height: 1.4; color: #fff;}
        .dowebok :-ms-input-placeholder { font-size: 18px; line-height: 1.4; color: #fff;}

        @media screen and (max-width: 500px) {
            * { box-sizing: border-box; }
            .dowebok { position: static; width: auto; height: auto; margin: 0 30px; border: 0; border-radius: 0; }
            .logo { margin: 50px auto; }
            .form-item { width: auto; }
            .form-item input, .form-item button, .reg-bar { width: 100%; }
        }
    </style>
</head>
<body>
<div>
<h1  style="font-family:Times New Roman;position: absolute; left:35%; frot-size:60px"><br><br>Second-hand &nbsp;&nbsp;&nbsp;&nbsp;Car&nbsp;&nbsp;&nbsp;&nbsp; Trading&nbsp;&nbsp;&nbsp;&nbsp; Platform</h1>
</div>
    <div class="dowebok">
        <div class="logo"></div>
        <div class="form-item">
            <input id="username" type="text" autocomplete="off" placeholder="Username">
           
        </div>
        <div class="form-item">
            <input id="password" type="password" autocomplete="off" placeholder="Password">
          
        </div>
        <div class="form-item"><button type="button" onclick="login();">login</button></div>
        <div class="reg-bar">
            <a class="reg" href="register.php">Register</a>
            <a class="forget" href="forgetpassword.php">forgetpassword</a>
        </div>
    </div>
    <script type="text/javascript" src="jquery-3.3.1.js"></script>
    <script src="js/md5.js" type="text/javascript"></script>
    <script>
function login()
{
	
	 var userID=document.getElementById("username").value;
	 var password=document.getElementById("password").value;
	 if(userID=="" || password==""){
	   alert('Please input userID and password!');
	   return;
	 }
	 password=hex_md5(password);
	 var json= '{"action":"logincheck","userid":"'+userID+'","password":"'+password+'"}';	
	 datarequest(json);
}
function datarequest(json){
	var request=new XMLHttpRequest();
	request.open("POST","../part2/dbms.php",false); 
	request.setRequestHeader("Content-type", "application/json");
	request.onreadystatechange=function(){
	  if (request.readyState==4 && request.status==200 || request.status==304){
		  if(request.responseText=="success!")
		  window.location="main_menu2.php";
		  else
			  alert(request.responseText);			  
	  }
	}  
     request.send(json);
}
</script>



</body>
</html>