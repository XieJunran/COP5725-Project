<?php
session_start();
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
$carid=getUrlParam('carid',$url);
if($carid==''){
    header("location:main_menu2.php");
}


$json_data=$_SESSION['json_data'];
$json1=(array) json_decode($json_data,1);
$userid=$json1[0]['USERNAME'];
$useridnow=$json1[0]['USERID'];
if(isset($_SESSION['userID'])){
    if(true){
        $username="huanbin";
        $password="24361Zhb1152";
        $connection_string="oracle.cise.ufl.edu:1521/orcl";
        global $con;
        $con=oci_connect($username,$password,$connection_string);
        $query="SELECT * FROM VIEW_HISTORY WHERE USERID=:userid AND CARID=:carid";
        $stmt = oci_parse($con, $query);
        oci_bind_by_name($stmt, ":userid",$useridnow);
        oci_bind_by_name($stmt, ":carid",$carid);
        oci_execute($stmt);
        if(oci_fetch_array($stmt,OCI_ASSOC)){
            $query="alter session set nls_date_format = 'yyyy-mm-dd hh24:mi:ss'";
            $stmt = oci_parse($con, $query);
            oci_execute($stmt);
            $query="UPDATE VIEW_HISTORY SET TIME=(SELECT SYSDATE FROM DUAL) WHERE USERID=:userid AND CARID=:carid";
            $stmt = oci_parse($con, $query);
            oci_bind_by_name($stmt, ":userid",$useridnow);
            oci_bind_by_name($stmt, ":carid",$carid);
            oci_execute($stmt);
            $query="COMMIT";
            $stmt = oci_parse($con, $query);
            oci_execute($stmt);
        }
        else{
            $query="alter session set nls_date_format = 'yyyy-mm-dd hh24:mi:ss'";
            $stmt = oci_parse($con, $query);
            oci_execute($stmt);
            $query="INSERT INTO VIEW_HISTORY VALUES (:userid,:carid,(SELECT SYSDATE FROM DUAL))";
            $stmt = oci_parse($con, $query);
            oci_bind_by_name($stmt, ":userid",$useridnow);
            oci_bind_by_name($stmt, ":carid",$carid);
            oci_execute($stmt); 
            $query="COMMIT";
            $stmt = oci_parse($con, $query);
            oci_execute($stmt);
        }
    }
}

if($carid==''){
    return;
}else{
    $query="SELECT * FROM CAR WHERE CARID=:carid";
    $stmt = oci_parse($con, $query);
    oci_bind_by_name($stmt, ":carid",$carid);
    oci_execute($stmt);
    $carinfo=oci_fetch_array($stmt);
    $query="SELECT * FROM USER_INFO, POST WHERE USER_INFO.USERID = POST.USERID AND POST.CARID=:carid";
    $stmt = oci_parse($con, $query);
    oci_bind_by_name($stmt, ":carid",$carid);
    oci_execute($stmt);
    $sellerinfo=oci_fetch_array($stmt);
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
    <body onload="searchviewhistory()">
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
        <a class="nav-link"  href="post.php">Sell</a>
      </li>
     
      <li class="nav-item">
        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
      </li>
    
 </ul>
    <li class="nav-item dropdown">
    <?php 
    
    if(!isset($_SESSION['userID'])){
        echo "<form class='form-inline my-2 my-lg-0'>";
        echo "<button class='btn btn-outline-success my-2 my-sm-0' type='button' onclick='window.location='Login.php';'>Login</button>";
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

<!-- <div class="modal-content" style="width:80%;left:10%;height:auto"> -->
<div class='card mb-3' style='width:80%;left:10%;'>
	<!-- Car Details Go Here -->
	<h2 style="text-align:center;"><?php echo $carinfo[8];?></h2>
	<p style="text-align:center;"><?php echo 'Seller name: '.$sellerinfo[2].' &nbsp;Email Address: '.$sellerinfo[1].' &nbsp;Phone Number '.$sellerinfo[4]?></p>
	<div align="center"><img class="card-img" alt="..." style="max-height:80%;max-width:80%" src=<?php echo $carinfo[3];?> ></div>
	<br>
	<div style="text-align:center">
		<style type="text/css">
		table.altrowstable {
			color:#333333;
			border-width: 1px;
			border-color: #a9c6c9;
			border-collapse: collapse;
			margin: auto;
			
		}
		table.altrowstable td {
			border-width: 1px;
			padding: 8px;
			border-style: solid;
			border-color: #a9c6c9;
		}
		</style>
		 
		<!-- Table goes in the document BODY -->
		<table class="altrowstable" id="alternatecolor" style="max-width:80%">
		<tr>
			<td>Car ID: <?php echo $carinfo[0];?></td>
			<td>Gear Box: <?php echo $carinfo[1];?></td>
			<td>Mileage: <?php echo $carinfo[2];?></td>
			<td>Transaction State: <?php echo $carinfo[4];?></td>
		</tr>
		<tr>
			<td>Transaction City: <?php echo $carinfo[5];?></td>
			<td>Transaction Zip Code: <?php echo $carinfo[6];?></td>
			<td>Brand: <?php echo $carinfo[7];?></td>
			<td>Model: <?php echo $carinfo[8];?></td>
		</tr>
		<tr>
			<td>Price: <?php echo intval($carinfo[9]);?></td>
			<td>Price For New Car: <?php echo intval($carinfo[10]);?></td>
			<td>Sold Time: <?php echo $carinfo[11];?></td>
			<td>Color: <?php echo $carinfo[13];?></td>
		</tr>
		</table>
	</div>
</div> 
<div align="center">
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
  Order
</button>
<?php
$username="huanbin";
$password="24361Zhb1152";
$connection_string="oracle.cise.ufl.edu:1521/orcl";
global $con;
$con=oci_connect($username,$password,$connection_string);
$query="SELECT * FROM INTEREST WHERE USERID=:userid AND CARID=:carid";
$stmt = oci_parse($con, $query);
oci_bind_by_name($stmt, ":userid",$useridnow);
oci_bind_by_name($stmt, ":carid",$carid);
oci_execute($stmt);
if(oci_fetch_array($stmt,OCI_ASSOC)){
    echo '<button type="button" id="inter" class="btn btn-dark" onclick="dislike();">Dislike</button>';   
}
else{
    echo '<button type="button" id="inter" class="btn btn-danger" onclick="interest();">Interest</button>';   
}
?>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Confirm</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Are you sure to  buy it ?
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-primary" onclick="<?php echo "window.location.href='order.php?carid=".$carid."'";?>">Yes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>   
      </div>
    </div>
  </div>
</div>




<script>
function getParams(url) {
    try {
        var index = url.indexOf('?');
        url = url.match(/\?([^#]+)/)[1];
        var obj = {}, arr = url.split('&');
        for (var i = 0; i < arr.length; i++) {
            var subArr = arr[i].split('=');
            var xxx=subArr[1].replace(/%20/g,' ');
            obj[subArr[0]] = xxx;
        }
        return obj;

    } catch (err) {
        return null;
    }
}

function getParam(paramKey){

    var url = location.href; 

    var get = url.indexOf(paramKey +"=");
    if(get == -1){
        return "";   
    }   

    var getParamStr = url.slice(paramKey.length + get + 1);    

    var nextparam = getParamStr.indexOf("&");
    if(nextparam != -1){
        getParamStr = getParamStr.slice(0, nextparam);
    }
    return decodeURIComponent(getParamStr);
}

function delParam(url,paramKey){
    var urlParam = url.substr(url.indexOf("?")+1);
    var beforeUrl = url.substr(0,url.indexOf("?"));
    var nextUrl = "";
     
    var arr = new Array();
    if(urlParam!=""){
        var urlParamArr = urlParam.split("&");
      
        for(var i=0;i<urlParamArr.length;i++){
            var paramArr = urlParamArr[i].split("=");
            if(paramArr[0]!=paramKey){
                arr.push(urlParamArr[i]);
            }
        }
    }
     
    if(arr.length>0){
        nextUrl = "?"+arr.join("&");
    }
    url = beforeUrl+nextUrl;
    return url;
}


function changeURLArg(url,arg,arg_val){ 
    var pattern=arg+'=([^&]*)'; 
    var replaceText=arg+'='+arg_val; 
    if(url.match(pattern)){ 
        var tmp='/('+ arg+'=)([^&]*)/gi'; 
        tmp=url.replace(eval(tmp),replaceText); 
        return tmp; 
    }else{ 
        if(url.match('[\?]')){ 
            return url+'&'+replaceText; 
        }else{ 
            return url+'?'+replaceText; 
        } 
    } 
    return url+'\n'+arg+'\n'+arg_val; 
} 
function dislike(){
	var json= '{"action":"dislike","userid":'+document.getElementById('useridnow').value+',"carid":"'+getParam('carid')+'"}';
	var json1=datarequest(json);
	alert(json);
	document.getElementById("inter").className="btn btn-danger";
	document.getElementById("inter").innerHTML="Interest";
	document.getElementById("inter").onclick=function(){interest();};
	//alert(json1);
	
}
function interest(){
	var json= '{"action":"interest","userid":'+document.getElementById('useridnow').value+',"carid":"'+getParam('carid')+'"}';
	alert(json);
	document.getElementById("inter").className="btn btn-dark";
	document.getElementById("inter").innerHTML="Dislike";
	document.getElementById("inter").onclick=function(){dislike();};
	var json1=datarequest(json);
	//alert(json1);
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

</script>


<script src="jquery-3.3.1.js" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>