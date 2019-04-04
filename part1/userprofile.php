<?php
function parseUrlParam($query){
    $queryArr = explode('&', $query);
    $params = array();
    if($queryArr[0] !== ''){
        foreach( $queryArr as $param ){
            list($name, $value) = explode('=', $param);
            $params[urldecode($name)] = urldecode($value);
        }
    }
    return $params;
}


function setUrlParams($cparams, $url = ''){
    $parse_url = $url === '' ? parse_url($_SERVER["REQUEST_URI"]) : parse_url($url);
    $query = isset($parse_url['query']) ? $parse_url['query'] : '';
    $params = parseUrlParam($query);
    foreach( $cparams as $key => $value ){
        $params[$key] = $value;
    }
    return $parse_url['path'].'?'.http_build_query($params);
}

function getUrlParam($cparam, $url = ''){
    $parse_url = $url === '' ? parse_url($_SERVER["REQUEST_URI"]) : parse_url($url);
    $query = isset($parse_url['query']) ? $parse_url['query'] : '';
    $params = parseUrlParam($query);
    return isset($params[$cparam]) ? $params[$cparam] : '';
}

$url=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$thisuserid=getUrlParam('userid',$url);
session_start();
//if(!session_is_registered(userID)){
if(!isset($_SESSION['userID'])&&$thisuserid==''){
    header("location:Login.php");
}
$json_data=$_SESSION['json_data'];
$json1=(array) json_decode($json_data,1);
$userid=$json1[0]['USERNAME'];
$useridnow=$json1[0]['USERID'];
if($thisuserid==''){
    $thisuserid=$useridnow;
}
?>


<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" integrity="sha256-7s5uDGW3AHqw6xtJmNNtr+OBRJUlgkNJEo78P4b0yRw= sha512-nNo+yCHEyn0smMxSswnf/OnX6/KwJuZTlNZBjauKhTK0c+zT+q5JOCx0UFhXQ6rJR9jg6Es8gPuD2uZcYDLqSw==" crossorigin="anonymous">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha256-3dkvEK0WLHRJ7/Csr0BZjAWxERc5WH7bdeUya2aXxdU= sha512-+L4yy6FRcDGbXJ9mPG8MT/3UCDzwR9gPeyFNMCtInsol++5m3bk2bXWKdZjvybmohrAsn3Ua5x8gfLnbE1YkOg==" crossorigin="anonymous">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/upload.css">
    </head>
    <body onload="userinfo()">
    <input type="text" id="thisuserid" style="display:none" value=<?php echo '"'.$thisuserid.'"';?>>
    <input type="text" id="useridnow" style="display:none" value=<?php echo '"'.$useridnow.'"';?>>
<!--head bar-->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="width:80%;left:10%">
  <a class="navbar-brand" href="#">SecondHandCar</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="main_menu2.php">Buy</a>
      </li>
       <li class="nav-item">
        <a class="nav-link" href="#">Sell</a>
      </li>
     
      <li class="nav-item">
        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
      </li>
    
 </ul>
    <li class="nav-item dropdown">
    <?php 
    
    if(!isset($_SESSION['userID'])){
        echo "<form class='form-inline my-2 my-lg-0'>";
        echo '<button class="btn btn-outline-success my-2 my-sm-0" type="button" onclick="window.location=\'Login.php\'">Login</button>';
        echo "</form>";
    }
    else{
        echo "<a class='nav-link dropdown-toggle' href='#' id='navbarDropdown' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>";
        echo $userid;
        echo "</a>";
        echo "<div class='dropdown-menu' aria-labelledby='navbarDropdown'>";
        echo "<a class='dropdown-item' href='userprofile.php'>Account</a>";
        echo "<a class='dropdown-item' href='orderhistory.php'>Order history</a>";
        echo "<a class='dropdown-item' href='viewhistory.php'>View history</a>";
        echo "<a class='dropdown-item' href='interestlist.php'>Interesting List</a>";
        echo "<div class='dropdown-divider'></div>";
        echo "<a class='dropdown-item' href='Logout.php'>Exit</a>";
        echo "</div>";
    }
?>
</li>

  </div>
</nav>
<br>
<br>
 



<div align="center" >




<div class="container" style="position:absolute; top:10%;left:30%;display:inline-block">
<div class="row">
<div class="col-md-10 ">
<form class="form-horizontal">
<fieldset>

<!-- Form Name -->
<legend>User profile form requirement</legend>
<br>
<br>
<!-- Text input-->




<div class="form-group">
  <label class="col-md-4 control-label" for="Name (Full name)">Name (Full name)</label>  
  <div class="col-md-4">
 <div class="input-group">
       <div class="input-group-addon">
        <i class="fa fa-user">
        </i>
       </div>
       <input id="Name (Full name)" name="Name (Full name)" readonly="readonly" type="text" placeholder="Name (Full name)" class="form-control input-md">
      </div>

    
  </div>

  
</div>

<!-- File Button --> 
<div class="form-group" id="info1" style="display:none">
 <br>
 <br>
 <label class="col-md-4 control-label" for="Upload photo" id="info1">Upload photo</label>
 <div class="col-md-4">
 <input id="Upload photo" name="Upload photo" class="input-file" type="file">
 </div>
</div>

<br>
<br>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="Phone number ">Phone number </label>  
  <div class="col-md-4">
  <div class="input-group">
       <div class="input-group-addon">
     <i class="fa fa-phone"></i>
        
       </div>
    <input id="Phone number " name="Phone number " type="text" readonly="readonly" placeholder="Primary Phone number " class="form-control input-md">
    
      </div>
   
  
  </div>
</div>
<br>
<br>
<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="Email Address">Email Address</label>  
  <div class="col-md-4">
  <div class="input-group">
       <div class="input-group-addon">
     <i class="fa fa-envelope-o"></i>
        
       </div>
    <input id="Email Address" name="Email Address" type="text" readonly="readonly" placeholder="Email Address" class="form-control input-md">
    
      </div>
  
  </div>
</div>


<!-- Text input-->
<div class="form-group" id="info2" style="display:none">
 <br><br>
 <label class="col-md-4 control-label" for="PASSPORT No.">PASSPORT No.</label>
 <div class="col-md-4">
 <div class="input-group">
 <div class="input-group-addon">
 <i class="fa fa-sticky-note-o"></i>
 </div>
 <input id="PASSPORT No." name="PASSPORT No." type="text" readonly="readonly" placeholder="PASSPORT No." class="form-control input-md">
 </div>
 </div>
</div>


<!-- Text input-->
<div class="form-group" id="info3" style="display:none">
  <br><br>
  <label class="col-md-4 control-label" for="CreditCard No.">CreditCard No.</label>
  <div class="col-md-4"><div class="input-group">
  <div class="input-group-addon">
  <i class="fa fa-sticky-note-o"></i>
  </div><input id="CreditCard No." name="CreditCard No." type="text" readonly="readonly" placeholder="CreditCard No." class="form-control input-md"></div>
  </div>
</div>




<!-- Text input-->
<div class="form-group" id="info4" style="display:none">
  <br><br>
  <label class="col-md-4 control-label" for="SSN No.">SSN No.</label>
  <div class="col-md-4">
  <div class="input-group">
  <div class="input-group-addon">
  <i class="fa fa-sticky-note-o"></i>
  </div>
  <input id="SSN No." name="SSN No." type="text" readonly="readonly" placeholder="SSN No." class="form-control input-md">
  </div>
  </div>
</div>


<br>
<br>
<div class="form-group">
  <label class="col-md-4 control-label" ></label>  
  <div class="col-md-4"  id="button1">
  <?php 
  if($thisuserid==$useridnow){
      echo '<a href="javascript:void(0);" class="btn btn-success" onclick="modifystart();"><span class="glyphicon glyphicon-thumbs-up"></span> Modify</a>';
     // echo '<a href="javascript:void(0);" class="btn btn-danger" value=""><span class="glyphicon glyphicon-remove-sign"></span> Clear</a>';
  }
  
    ?>
  </div>
</div>

</fieldset>
</form>
</div>



</div>
   </div>
   
   
   
   <div style="position:absolute;top:13%;left:15%;width:15%;display:inline-block">
   <div class="card"  id="pict">
  <img src="img/userimg/<?php echo $thisuserid.".png?dummy=".time(); ?>" id="pic" class="card-img-top" alt="...">
  <div class="card-body">
  </div>
</div>
</div>
</div>
<script>
function userinfo(){	
	var json= '{"action":"userinfo","userid":"'+document.getElementById('thisuserid').value+'"}';
	//alert(json);
	var json1=datarequest(json);
    //alert(json1);
	var result =  JSON.parse(json1);
	//alert(result[0]['USERNAME']);
	//alert(document.getElementById("thisuserid").value);
	if(document.getElementById("thisuserid").value==document.getElementById("useridnow").value){
		//alert("11");
		//document.getElementById("info1").style.display='block';
		document.getElementById("info2").style.display='block';
	    document.getElementById("info3").style.display='block';
	    document.getElementById("info4").style.display='block';
	    //alert("??")
		userinfoall();
	}
	else{
		document.getElementById("Name (Full name)").value=result[0]['USERNAME'];
		document.getElementById("Email Address").value=result[0]['EMAIL'];
		document.getElementById("Phone number ").value=result[0]['CONTACT_INFORMATION'];
	}
	//alert("111");
	
}

function userinfoall(){
	var json= '{"action":"userinfo","userid":"'+document.getElementById('thisuserid').value+'"}';
	var json1=datarequest(json);
	var result =  JSON.parse(json1);
	document.getElementById("Name (Full name)").value=result[0]['USERNAME'];
	document.getElementById("Email Address").value=result[0]['EMAIL'];
	if(! ("CONTACT_INFORMATION" in result[0])){
		document.getElementById("Phone number ").value='';
	}
	else
	document.getElementById("Phone number ").value=result[0]['CONTACT_INFORMATION'];
	if(! ("PASSPORT_NUMBER" in result[0])){
		document.getElementById("PASSPORT No.").value='';
	}
	else
	document.getElementById("PASSPORT No.").value=result[0]['PASSPORT_NUMBER'];
	if(! ("PASSPORT_NUMBER" in result[0])){
		document.getElementById("CreditCard No.").value='';
	}
	else
	document.getElementById("CreditCard No.").value=result[0]['CREDIT_CARD_NUMBER'];
	//alert(result[0]['SSN']);
	if(! ("SSN" in result[0])){
		document.getElementById("SSN No.").value='';
	}
	else
		document.getElementById("SSN No.").value=result[0]['SSN'];
	
}
function modifystart(){	
	//userinfoall();
	document.getElementById("info1").style.display='block';
	document.getElementById('Phone number ').readOnly = false;
	document.getElementById("PASSPORT No.").readOnly = false;
	document.getElementById("CreditCard No.").readOnly = false;
	document.getElementById("SSN No.").readOnly = false;
    document.getElementById("button1").innerHTML='<a href="javascript:void(0);" class="btn btn-success" onclick="save();"><span class="glyphicon glyphicon-thumbs-up"></span> Save</a><a href="javascript:void(0);" onclick="cancel();" class="btn btn-danger" value=""><span class="glyphicon glyphicon-remove-sign"></span> Cancel</a>';
}

function cancel(){
	userinfo();
	document.getElementById("info1").style.display='none';
	document.getElementById('Phone number ').readOnly = true;
    document.getElementById("PASSPORT No.").readOnly = true;
	document.getElementById("CreditCard No.").readOnly = true;
	document.getElementById("SSN No.").readOnly = true;
    document.getElementById("button1").innerHTML='<a href="javascript:void(0);" class="btn btn-success" onclick="modifystart();"><span class="glyphicon glyphicon-thumbs-up"></span> Modify</a>';
}
	
function save(){
		uploadpicture();
	//document.getElementById("info1").style.display='none';
	//document.getElementById("pict").innerHTML='<img src="img/userimg/'+document.getElementById('thisuserid').value+'.png" id="pic" class="card-img-top" alt="..."><div class="card-body"></div>';
	var json= '{"action":"updateuserinfo","userid":"'+document.getElementById('thisuserid').value+'"';

	json+=',"CONTACT_INFORMATION":"'+document.getElementById("Phone number ").value+'"';

	json+=',"PASSPORT_NUMBER":"'+document.getElementById("PASSPORT No.").value+'"';

	json+=',"CREDIT_CARD_NUMBER":"'+document.getElementById("CreditCard No.").value+'"';

	if(document.getElementById("SSN No.").value=='')
		json+=',"SSN":null}';
	else
		json+=',"SSN":'+document.getElementById("SSN No.").value+'}';
	//alert(json);
	var json1=datarequest(json);
	//alert(json1);
	//result =  JSON.parse(json1);
	window.location.reload();
	
}
function datarequest(json){
	var request=new XMLHttpRequest();
	request.open("POST","../part2/dbms.php",false); 
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

function fileSelected(userfile) {
	document.getElementById('textfield').value=userfile.files[0].name;
}
function uploadpicture() {
	var userfile=document.getElementById('Upload photo');
	var fd = new FormData();
	fd.append("file",userfile.files[0]);
	var str=window.parent.document.getElementById("useridnow").value;
	fd.append("userid",str);
	fd.append("type","user");
    var xhr = new XMLHttpRequest();
    xhr.upload.addEventListener("progress", uploadProgress, false);
    xhr.addEventListener("load", uploadComplete, false);
    xhr.addEventListener("error", uploadFailed, false);
    xhr.open("POST", "../part2/upload.php");
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


 <script src="jquery-3.3.1.js" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js"></script>
    
</body>
</html>




