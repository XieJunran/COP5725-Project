<?php
session_start();
//if(!session_is_registered(userID)){
//if(!isset($_SESSION['userID'])){
 //   header("location:Login.php");
//}
$json_data=$_SESSION['json_data'];
$json1=(array) json_decode($json_data,1);
$userid=$json1[0]['USERNAME'];
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
    <body onload="search()">
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
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <?php 
          $url=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
          $statenow=getUrlParam('state',$url);
          if($statenow!='')
              echo $statenow;
          else 
              echo 'STATE';
          ?>
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
        <form class="px-4 py-3">
        <?php 
                $username="huanbin";
                $password="24361Zhb1152";
                $connection_string="oracle.cise.ufl.edu:1521/orcl";
                $con=oci_connect($username,$password,$connection_string);
                $query="SELECT DISTINCT TRANSACTION_STATE FROM CAR ORDER BY TRANSACTION_STATE";
                $stmt = oci_parse($con, $query);
                oci_execute($stmt);
                $i=0;
                while($re=oci_fetch_array($stmt,OCI_NUM)){
                    if($i%2==0){
                        if($i!=0)
                            echo "</div>";
                        echo "<div class='row'>";
                    }
                    echo "<div class='col'>";
                    echo "<a  href='javascript:void(0);' onclick='changestate(";
                    echo '"'.$re[0].'"';
                    echo ")'>".$re[0]."</a>";
                    echo "</div>"; 
                    $i++;
                }
                echo "</div>";
           ?> 
        </form>
        </div>
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
        echo "<a class='dropdown-item' href='#'>Account</a>";
        echo "<a class='dropdown-item' href='#'>Order history</a>";
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






<!-- search -->
<nav class="navbar navbar-expand-lg navbar-light bg-light" style="width:80%;left:10%">
  
 

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
     
    </ul>
    <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search" id="sear">
      <button class="btn btn-outline-success my-2 my-sm-0" type="button" onclick="searchtxt();">Search</button>
    </form>
  </div>
</nav>




<!-- search option -->
<div>
<ul class="list-group" style="position:relative;left:10%;width:80%" >

<!-- brand choose -->
  <li class="list-group-item">Brand &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
  $brandnow=getUrlParam('brand',$url);
  $username="huanbin";
  $password="24361Zhb1152";
  $connection_string="oracle.cise.ufl.edu:1521/orcl";
  $con=oci_connect($username,$password,$connection_string);
  $query="SELECT BRAND FROM CAR GROUP BY BRAND ORDER BY COUNT(DISTINCT CARID) DESC";
  $stmt = oci_parse($con, $query);
  oci_execute($stmt);
  $i=0;
  while(($re=oci_fetch_array($stmt,OCI_NUM))){
      if($brandnow!=$re[0]){
      echo "<a href='javascript:void(0);' onclick='changebrand(";
      echo '"'.$re[0].'"';
      echo ")'>".$re[0]."</a>";
      echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
      }
      else {
          echo "<a class='btn btn-primary' href='javascript:void(0);' onclick='changebrand(";
          echo '"'.$re[0].'"';
          echo ")'>".$re[0]."</a>";
          echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
      }
      $i++;
      if($i>10) break;
  }
  for($j=0;$j<30;$j++)
      echo "&nbsp;";
  ?>
  
  <a class="btn btn-link" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample" style="text-align: right;display:inline-block">More</a>
  </li>
  
  <div class="collapse" id="collapseExample">
  <ul class="list-group list-group-horizontal-lg">

  <?php 
    $username="huanbin";
  $password="24361Zhb1152";
  $connection_string="oracle.cise.ufl.edu:1521/orcl";
  $con=oci_connect($username,$password,$connection_string);
  $query="SELECT BRAND FROM CAR GROUP BY BRAND HAVING COUNT(DISTINCT CARID)>10 ORDER BY BRAND";
  $stmt = oci_parse($con, $query);
  oci_execute($stmt);
  $i=0;
  
  while(($re=oci_fetch_array($stmt,OCI_NUM))){
      if($i%32==0){
          if($i!=0)
              echo "</li>";
          echo "<li class='list-group-item flex-fill'>";
      }
      if($brandnow!=$re[0]){
      echo "<a href='javascript:void(0);' onclick='changebrand(";
      echo '"'.$re[0].'"';
      echo ")'>".$re[0]."</a>";
      echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
      }
      else {
          echo "<a class='btn btn-primary' href='javascript:void(0);' onclick='changebrand(";
          echo '"'.$re[0].'"';
          echo ")'>".$re[0]."</a>";
         // echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
      }
      //if($i%2==1&&$i>0) 
          echo "<br>";
      $i++;
  }
  echo "</li>";
  ?>
  
</ul>
</div>

<!-- price choose -->
  
  <li class="list-group-item">
 
  Price
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <?php 
  $price=array(0,30000,50000,70000,90000,120000,160000,200000);
  
  for($i=0;$i<7;$i++){
      echo "<a href='javascript:void(0);' onclick='changeprice(".$price[$i].",".$price[$i+1].")'>".$price[$i]."-".$price[$i+1]."</a>";
      echo "&nbsp;&nbsp;&nbsp;&nbsp;";
  }
  echo "<a href='javascript:void(0);' onclick='changeprice(".$price[$i].",999999999)'>&gt;".$price[$i]."</a>&nbsp;&nbsp;&nbsp;&nbsp;";
  
  ?>
   <input  style="width:50px"type="text" id="prmin" placeholder="min" aria-label="min">
   ----
   <input  style="width:50px"type="text" id="prmax" placeholder="max" aria-label="max">
   <button class="btn btn-outline-success my-2 my-sm-0" type="button" onclick="confirmprice();">Confirm</button>
  </li>
  
  
  <!-- more option -->
  <li class="list-group-item"> More &nbsp;&nbsp;&nbsp;
  
  <!-- gearbox -->
  <div class="btn-group">
  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <?php   
    $url=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $gearbox=getUrlParam('gearbox',$url);
    if($gearbox=='Auto'||$gearbox=='Manual')
        echo $gearbox;
    else 
        echo "GearBox";
    ?>
  </button>
  <div class="dropdown-menu">
    <a class="dropdown-item" href="javascript:void(0);" onclick="changegearbox('NoLimit');">No Limit</a>
    <a class="dropdown-item" href="javascript:void(0);" onclick="changegearbox('Auto');">Auto</a>
    <a class="dropdown-item" href="javascript:void(0);" onclick="changegearbox('Manual');">Manual</a>
</div>
  </div>
  
  <!-- km age -->
  <div class="btn-group">
  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <?php   
    $url=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $km_age=getUrlParam('km_age',$url);
    if($km_age!=''){
        echo "&lt;".$km_age."km";
    }
    else 
        echo "KM_AGE";
    ?>
  </button>
  <div class="dropdown-menu">
  <a class="dropdown-item" href="javascript:void(0);" onclick="changekmage('NoLimit');">No Limit</a>
  <?php
  $x=array(1,3,5,8,10);
  for($i=0;$i<5;$i++){
      echo "<a class='dropdown-item' href='javascript:void(0);' onclick='changekmage(".$x[$i].");'>&lt;".$x[$i]."km</a>";
  }
  ?>
</div>
  </div>
  
  <!-- sell time -->
  <div class="btn-group">
  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <?php   
    $url=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $sell_time=getUrlParam('sell_time',$url);
    if($sell_time!=''){
        echo "&lt;".$sell_time;
    }
    else 
        echo "SELL_TIMES";
    ?>
  </button>
  <div class="dropdown-menu">
  <a class="dropdown-item" href="javascript:void(0);" onclick="changeselltime('NoLimit');">No Limit</a>
  <?php
  $x=array(1,2,3,6,8);
  for($i=0;$i<5;$i++){
      echo "<a class='dropdown-item' href='javascript:void(0);' onclick='changeselltime(".$x[$i].");'>&lt;".$x[$i]."</a>";
  }
  ?>
</div>
  </div>
  
  
  </li>
  <li class="list-group-item">remain for extra</li>
 
</ul>
</div>
<br>
<br>

<ul class="list-group"  style="position:relative;left:10%;width:80%;text-align:right">
<li class="list-group-item">Sort &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <a href='javascript:void(0);' onclick='changesort("PRICE")'>Price</a>
</li>
</ul>
<br>
<br>

<div id="list">
</div>

<br>
<br>
<nav aria-label="..." id="page">
 
</nav>





<script type="text/javascript">

var result;
var currentpage;
var pagenumber;
var pagesize=20;
var sortname="";
var sorttype="ASC";
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

function changestate(state){
	var url=window.location.toString();
	var url1=changeURLArg(url,'state',state);
	window.location.href=url1;
}

function changebrand(brand){
	var url=window.location.toString();
	var url1=changeURLArg(url,'brand',brand);
	window.location.href=url1;
}

function changeprice(min,max){
	var url=window.location.toString();
	var url1=changeURLArg(url,'prmin',min);
	var url2=changeURLArg(url1,'prmax',max);
	window.location.href=url2;
}
function confirmprice(){
	var min=document.getElementById("prmin").value;
	var max=document.getElementById("prmax").value;
	var re = /^[0-9]+.?[0-9]*/;
	if(!re.test(min)||!re.test(max)){
		alert("Please input number!");
	}
	else{
		changeprice(min,max);
	}
}
function changegearbox(type){
	if(type=='NoLimit'){
		var url=window.location.toString();
		var url1=delParam(url,'gearbox');
		window.location.href=url1;
	}
	else{
		var url=window.location.toString();
		var url1=changeURLArg(url,'gearbox',type);
		window.location.href=url1;
	}
}

function changekmage(km){
	if(km=='NoLimit'){
		var url=window.location.toString();
		var url1=delParam(url,'km_age');
		window.location.href=url1;
	}
	else{
		var url=window.location.toString();
		var url1=changeURLArg(url,'km_age',km);
		window.location.href=url1;
	}
}

function changesort(type){
	var url=window.location.toString();
	var url1=changeURLArg(url,'sortname',type);
	sortname=getParam('sortname');
	sorttype=getParam('sorttype');
	if(sortname==type){
		if(sorttype=='ASC')
			sorttype='DESC';
		else
			sorttype='ASC';
	}
	else{
		sortname=type;
		sorttype='ASC';
	}
	var url2=changeURLArg(url1,'sorttype',sorttype);
	window.location.href=url2;
	
}

function changeselltime(time){
	if(time=='NoLimit'){
		var url=window.location.toString();
		var url1=delParam(url,'sell_time');
		window.location.href=url1;
	}
	else{
		var url=window.location.toString();
		var url1=changeURLArg(url,'sell_time',time);
		window.location.href=url1;
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

function search(){
	
	var paraarray=getParams(window.location.toString());

	var json= '{"action":"multisearch"';
	for(var key in paraarray){
		json=json+',"'+key+'":';
		if(key!='prmax'&&key!='prmin'&&key!='selltime'&&key!='kmage'){
			json=json+'"'+paraarray[key]+'"';
		}
		else
			json=json+paraarray[key];
			
	}
	json=json+'}';
	//alert(json);
	var json1=datarequest(json);
	result =  JSON.parse(json1);
	//alert(result);	
	pagenumber=Math.ceil(result.length/pagesize);
	currentpage=0;
	pagechange(1);
	
}

function toNonExponential(num) {
    var m = num.toExponential().match(/\d(?:\.(\d*))?e([+-]\d+)/);
    return num.toFixed(Math.max(0, (m[1] || '').length - m[2]));
}

function getFullNum(num){

    if(isNaN(num)){return num};

    var str = ''+num;
    if(!/e/i.test(str)){return num;};
    
    return (num).toFixed(18).replace(/\.?0+$/, "");
}

function pagechange(number){
	if(currentpage==number) return;
	currentpage=number;
	document.getElementById('list').innerHTML="";
	var card="";
	for(var i=(currentpage-1)*pagesize;i<currentpage*pagesize&&i<result.length;i++){
		if(i%5==0)
			card+="<br><br><div class='card-group' style='width:80%;left:10%'>";
		card+="<div class='card' style='left:12.5%;width: 16%;height:20rem;position:relative;display:inline-block'>";
		card+="<img src='"+result[i]['PICTURE']+"' class='card-img-top' alt='...' style='top:0px'>";
		card+="<div class='card-body'>";
		//var mo="";
		//for(var j=result[i]['MODEL'].length;j<50;j++) mo+="&nbsp;";
		card+="<p class='card-text'>"+result[i]['MODEL']+"</p>";
		var xx=new Number(result[i]['PRICE']);
		card+="<p class='card-text' style='text-align:right'>"+toNonExponential(xx)+"</p>";
		card+="<a href='car_page.php?carid="+result[i]['CARID']+"' class='btn btn-primary'>View</a>";
		card+="</div>";
		card+="</div>";
		if(i%5==4)
			card+="</div>";
		
		//alert(document.getElementById('list').innerHTML);
	}
	document.getElementById('list').innerHTML=card;
	var pa="<ul class='pagination justify-content-center'>";
	pa+="<li class='page-item'>";
	pa+="<a class='page-link' href='javascript:void(0);'  onclick='pageminus();' aria-disabled='false'>Previous</a></li>";
	for(var i=1;i<=1;i++){
		if(i!=number){
	    	pa+="<li class='page-item'><a class='page-link' href='javascript:void(0);' onclick='pagechange("+i+");'>"+i+"</a></li>";
		}
		else{
			pa+="<li class='page-item active' aria-current='page'> <a class='page-link' href='javascript:void(0);'>"+i+" <span class='sr-only'>(current)</span></a>";
		    pa+="</li>";		
		}
	}
	if(number>4)
		pa+="&nbsp;&nbsp;&nbsp;&nbsp;.&nbsp;.&nbsp;.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
	var st=2;
	if(number-2>st) st=number-2;
	for(var i=st;i<=number+2&&i<pagenumber;i++){
		if(i!=number){
	    	pa+="<li class='page-item'><a class='page-link' href='javascript:void(0);' onclick='pagechange("+i+");'>"+i+"</a></li>";
		}
		else{
			pa+="<li class='page-item active' aria-current='page'> <a class='page-link' href='javascript:void(0);'>"+i+" <span class='sr-only'>(current)</span></a>";
		    pa+="</li>";		
		}
	}
	if(number<pagenumber-3)
		pa+="...";
	if(pagenumber>1){
	for(var i=pagenumber;i<=pagenumber;i++){
		if(i!=number){
	    	pa+="<li class='page-item'><a class='page-link' href='javascript:void(0);' onclick='pagechange("+i+");'>"+i+"</a></li>";
		}
		else{
			pa+="<li class='page-item active' aria-current='page'> <a class='page-link' href='javascript:void(0);'>"+i+" <span class='sr-only'>(current)</span></a>";
		    pa+="</li>";		
		}
	}
	}
	pa+="<li class='page-item'>";
	pa+="<a class='page-link' href='javascript:void(0);'  onclick='pageplus();'>Next</a></li></ul>";
	document.getElementById('page').innerHTML=pa;
}
function pageminus(){
	if(currentpage>1)
		pagechange(currentpage-1);
}
function pageplus(){
	if(currentpage<pagenumber)
		pagechange(currentpage+1);
}
function searchtxt(){

	
	
}


</script>




    <script src="jquery-3.3.1.js" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>