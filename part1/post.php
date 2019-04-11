<?php
session_start();
//if(!session_is_registered(userID)){
if(isset($_SESSION['userID'])){
    $json_data=$_SESSION['json_data'];
    $json1=(array) json_decode($json_data,1);
    $userid=$json1[0]['USERNAME'];
    $useridnow=$json1[0]['USERID'];
}
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    </head>
    <body>
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
        <a class="nav-link" href="post.php">Sell</a>
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





<div style="position:relative;width:80%;left:10%">

<br>
<br>
<div align="center">
<h1 >Car Information Table</h1>
</div>
<form>
  <div class="form-row">
    <div class="col-md-6 mb-3">
      <label for="Brand">Car Brand</label>
      <input type="text" class="form-control" id="Brand" placeholder="Car Brand"  required>
    </div>
    <div class="col-md-6 mb-3">
      <label for="Model">Car Model</label>
      <input type="text" class="form-control" id="Model" placeholder="Car Model"  required>
    </div>
  </div>
  <div class="form-row">

 <div class="col-md-6 mb-3">
      <label for="Soldtime">Soldtime</label>
      <input type="text" class="form-control" id="Soldtime" placeholder="Soldtime" required>
    </div>
    <div class="col-md-6 mb-3">
      <label for="Kmage">KM_AGE</label>
      <input type="text" class="form-control" id="Kmage" placeholder="KM_AGE" required>
    </div>
  </div>
  <div class="form-row">
    <div class="col-md-6 mb-3">
      <label for="NewPrice">NewPrice</label>
      <input type="text" class="form-control" id="NewPrice" placeholder="NewPrice"  required>
    </div>
    <div class="col-md-6 mb-3">
      <label for="Price">Price</label>
      <input type="text" class="form-control" id="Price" placeholder="Price"  required>
    </div>
   
  </div>
  <div class="form-row">  
    <div class="col-md-4 mb-3">
      <label for="State">State</label>
      <select class="form-control" id="State" onChange="changestate();">
      <?php
      $username="huanbin";
      $password="24361Zhb1152";
      $connection_string="oracle.cise.ufl.edu:1521/orcl";
      $con=oci_connect($username,$password,$connection_string);
      $query="SELECT DISTINCT ABBREVIATION FROM COUNTRY_INFO ORDER BY ABBREVIATION";
      $stmt = oci_parse($con, $query);
      oci_execute($stmt);
      global $fstate;
      $fstate='';
      while($re=oci_fetch_array($stmt,OCI_NUM)){
          if($fstate=='')
              echo "<option selected>".$re[0]."</option>";
          else
              echo "<option>".$re[0]."</option>";
          if($fstate=='') $fstate=$re[0];
      }
      oci_close($con);
      ?>
      </select>
    </div>
    <div class="col-md-4 mb-3">
      <label for="City">City</label>
      <select class="form-control" id="City" onchange="changecity();">
      <?php
      $username="huanbin";
      $password="24361Zhb1152";
      $connection_string="oracle.cise.ufl.edu:1521/orcl";
      $con=oci_connect($username,$password,$connection_string);
      $query="SELECT DISTINCT CITY FROM COUNTRY_INFO WHERE ABBREVIATION=:state ORDER BY CITY";
      $stmt = oci_parse($con, $query);
      oci_bind_by_name($stmt, ":state",$fstate);
      oci_execute($stmt);
      global $fcity;
      while($re=oci_fetch_array($stmt,OCI_NUM)){
          echo "<option>".$re[0]."</option>";
          if($fcity=='') $fcity=$re[0];
      }
      oci_close($con);
      ?>
      </select>
    </div>   
    <div class="col-md-4 mb-3">
      <label for="Zip">Zip</label>
      <select class="form-control" id="Zip">
      <?php
      $username="huanbin";
      $password="24361Zhb1152";
      $connection_string="oracle.cise.ufl.edu:1521/orcl";
      $con=oci_connect($username,$password,$connection_string);
      $query="SELECT DISTINCT ZIP_CODE FROM COUNTRY_INFO WHERE ABBREVIATION=:state AND CITY=:city ORDER BY ZIP_CODE";
      $stmt = oci_parse($con, $query);
      oci_bind_by_name($stmt, ":state",$fstate);
      oci_bind_by_name($stmt, ":city",$fcity);
      oci_execute($stmt);
      while($re=oci_fetch_array($stmt,OCI_NUM)){
          echo "<option>".$re[0]."</option>";
      }
      oci_close($con);
      ?>
      </select>
    </div>
  </div>
  
  <div class="form-row">
    <div class="col-md-3 mb-3">
      <label for="GearBox">GearBox</label>
  <select class="form-control" id="GearBox">
  <option>Auto</option>
  <option>Manual</option>
</select>
    </div>
    <div class="col-md-3 mb-3">
      <label for="Color">Color</label>
      <input type="text" class="form-control" id="Color" placeholder="Color"  required>
    </div>
    <div class="col-md-3 mb-3">
      <label for="Insurance">Insurance</label>
      <select class="form-control" id="Insurance">
  <option>Yes</option>
  <option>No</option>
</select>
    </div>
    <div class="col-md-3 mb-3">
    
    <label for="Pic">Picture</label>
    <div>
  <input type="file" id="Picture" class="input-default-js" lang="es">
  </div>
  </div>
  </div>
  <div align="center">
  <button class="btn btn-primary" type="button" onclick="submit1();">Submit</button>
  <button class="btn btn-primary" type="button" onclick="window.location.href='main_menu2.php';">Cancel</button>
  </div>
</form>
</div>


<script>
function changestate(){
	var Statep=document.getElementById("State");
	var index=Statep.selectedIndex;
	var state=Statep.options[index].value;
	refreshcity(state);
}
function changecity(){
	var Statep=document.getElementById("State");
	var index=Statep.selectedIndex;
	var state=Statep.options[index].value;
	var Cityp=document.getElementById("City");
	index=Cityp.selectedIndex;
	var city=Cityp.options[index].value;
	refreshzip(state,city);
}
function refreshcity(state){
	var json= '{"action":"searchcity","state":"'+state+'"}';
	
	var json1=datarequest(json);
	
	var result=JSON.parse(json1);
	var city=document.getElementById("City");
	city.innerHTML='';
	var op='';
	for(var i=0;i<result.length;i++){
		op=op+'<option>'+result[i]['CITY']+'</option>';
	}
	
	city.innerHTML=op;
	refreshzip(state,result[0]['CITY']);
}
function refreshzip(state,city){
	var json= '{"action":"searchzip","state":"'+state+'","city":"'+city+'"}';
	var json1=datarequest(json);
	var result=JSON.parse(json1);
	var zip=document.getElementById("Zip");
	zip.innerHTML='';
	var op='';
	for(var i=0;i<result.length;i++){
		op=op+'<option>'+result[i]['ZIP_CODE']+'</option>';
	}
	zip.innerHTML=op;
}
function submit1(){
	var userid=document.getElementById("useridnow").value;
	var soldtime=document.getElementById("Soldtime").value;
	var Kmage=document.getElementById("Kmage").value;
	var Brand=document.getElementById("Brand").value;
	var Model=document.getElementById("Model").value;
	var NewPrice=document.getElementById("NewPrice").value;
	var Price=document.getElementById("Price").value;
	
	var Statep=document.getElementById("State");
	var index=Statep.selectedIndex;	
	var State=Statep.options[index].value;
	
	var Cityp=document.getElementById("City");
	index=Cityp.selectedIndex;	
	var City=Cityp.options[index].value;
	
	var Zipp=document.getElementById("Zip");
	index=Zipp.selectedIndex;	
	var Zip=Zipp.options[index].value;
	
	var Gear=document.getElementById("GearBox");
	index=Gear.selectedIndex;	
	var GearBox=Gear.options[index].value;
	
	var Color=document.getElementById("Color").value;
	
	var Insur=document.getElementById("Insurance");
	index=Insur.selectedIndex;
	var Insurance=Insur.options[index].value;
	
	var re=new RegExp("^(0|[1-9][0-9]*)$");
	var re1=/^[0-9]+.?[0-9]*/;
	if(!re.test(soldtime)){
		alert(Soldtime);
		alert('Please input right Soldtime!')
		return;
	}
	if(!re.test(Kmage)){
		alert('Please input right Kmage!')
		return;
	}
	if(Brand==''){
		alert('Please input Brand!');
		return;
	}
	if(Model==''){
		alert('Please input Model!');
		return;
	}
	if(Color==''){
		alert('Please input Color!');
		return;
	}
	
	

	if(!re1.test(NewPrice)||!re1.test(Price)){
		alert('Please input right Price!');
		return;
	}
	else{
		var nnum=new Number(NewPrice);
		var num=new Number(Price);
		if(num>nnum){
			alert('Please input right Price!');
			return;
		}
	}	
	var json= '{"action":"addcar"';
	json+=',"Userid":"'+userid+'"';
	json+=',"Soldtime":"'+soldtime+'"';
	json+=',"Kmage":"'+Kmage+'"';
	json+=',"Brand":"'+Brand+'"';
	json+=',"Model":"'+Model+'"';
	json+=',"NewPrice":"'+NewPrice+'"';
	json+=',"Price":"'+Price+'"';
	json+=',"State":"'+State+'"';
	json+=',"City":"'+City+'"';
	json+=',"Zip":"'+Zip+'"';
	json+=',"GearBox":"'+GearBox+'"';
	json+=',"Color":"'+Color+'"';
	json+=',"Insurance":"'+Insurance+'"';
	json+='}';
	var uurl=document.getElementById('Picture').value;
	if(uurl.value==''){
		alert('Please choose a picture!');
		return;
	}
	var ind= uurl.lastIndexOf(".");
	var ext = uurl.substr(ind+1);
	if(ext!='png'){
		alert('Please choose a valid picture!');
		return;
	}
	var json1=datarequest(json);
	if(json1.substr(0,7)!='success')
		alert(json1);
	else
	{
		var carid=json1.substr(8);
		//alert(carid);
		uploadpicture(carid);
	}
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

function uploadpicture(str) {
	var userfile=document.getElementById('Picture');
	var fd = new FormData();
	fd.append("file",userfile.files[0]);
	fd.append("carid",str);
	fd.append("type","car");
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