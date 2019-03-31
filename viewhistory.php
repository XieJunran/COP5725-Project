<?php
session_start();
//if(!session_is_registered(userID)){
if(!isset($_SESSION['userID'])){
   header("location:Login.php");
}
$json_data=$_SESSION['json_data'];
$json1=(array) json_decode($json_data,1);
$userid=$json1[0]['USERNAME'];
$useridnow=$json1[0]['USERID'];
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
        echo "<a class='dropdown-item' href='interest.php'>Interesting List</a>";
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

function searchviewhistory(){	
	//var paraarray=getParams(window.location.toString());

	var json= '{"action":"searchviewhistory","userid":'+document.getElementById('useridnow').value;
	json=json+'}';
	//alert(json);
	var json1=datarequest(json);
	//alert(json1);
	result =  JSON.parse(json1);
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


	//alert("111");
	for(var i=(currentpage-1)*pagesize;i<currentpage*pagesize&&i<result.length;i++){
		var card="<div class='card mb-3' style='width:80%;left:10%;height:15rem;'>";
		card+="<div class='row no-gutters'>";
		card+="<div class='col-md-4'>";
		
		card+="<img src='"+result[i]['PICTURE']+"' class='card-img' alt='...' style='height:15rem;width:auto'></div>";
		
		var xx=new Number(result[i]['PRICE']);
		card+="<div class='col-md-8'><div class='card-body'>";
		
		card+="<h5 class='card-title'>"+result[i]['MODEL']+"</h5>";
		
		card+="<p class='card-text' >"+toNonExponential(xx)+"</p>";
		
		card+="<p class='card-text' style='text-align:right'><small class='text-muted'>"+result[i]['TIME']+"</small></p>";
		card+="<div align='right'>";
		card+="<a href='car_page.php?carid="+result[i]['CARID']+"' class='btn btn-primary' >View</a>";
		card+="</div></div></div></div></div><br>";
		//alert(document.getElementById('list').innerHTML);
		document.getElementById('list').innerHTML+=card;
	}
	
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