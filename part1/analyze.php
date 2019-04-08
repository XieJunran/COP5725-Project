<?php
session_start();
//if(!session_is_registered(userID)){
if(isset($_SESSION['userID'])){
$json_data=$_SESSION['json_data'];
$json1=(array) json_decode($json_data,1);
$userid=$json1[0]['USERNAME'];
$useridnow=$json1[0]['USERID'];
}
else 
    $useridnow='';
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
    <body onload="test()">
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
<div style="position:relative;width:80%;left:10%">
<ul class="list-group" >
  <li class="list-group-item list-group-item-primary">
  <a  data-toggle="collapse" href="#collapse1" role="button" aria-expanded="false" aria-controls="collapse1" style="color:inherit">
    Analyze top car brand for the top 50 buyers who bought most cars 
  </a>
  </li>
  <div class="collapse" id="collapse1">
  <div class="card card-body">
   <canvas id="myChart1" ></canvas>
  </div>
  </div>  
  
  
  <li class="list-group-item list-group-item-secondary">
  <a  data-toggle="collapse" href="#collapse2" role="button" aria-expanded="false" aria-controls="collapse2" style="color:inherit">
    Analyze relationship between price reduce rate and km_age for popular car brand 
  </a>
  </li>
  <div class="collapse" id="collapse2">
  <div align="center" class="card card-body">
   <div id="myChart2"  style="width: 1200px;height:600px;"></div>
  </div>
  </div>  
  
  
  <li class="list-group-item list-group-item-success">
  <a  data-toggle="collapse" href="#collapse3" role="button" aria-expanded="false" aria-controls="collapse3" style="color:inherit">
    Analyze relationship between price reduce rate and sold_time for popular car brand 
  </a>
  </li>
  <div class="collapse" id="collapse3">
  <div align="center" class="card card-body">
   <div id="myChart3"  style="width: 1200px;height:600px;"></div>
  </div>
  </div> 
  
  <li class="list-group-item list-group-item-danger">
  <a  data-toggle="collapse" href="#collapse4" role="button" aria-expanded="false" aria-controls="collapse4" style="color:inherit">
    Analyze top post car brand in a ceartain year  and a ceartain state
  </a>
  </li>
  <div class="collapse" id="collapse4">
  <div class="btn-group">
  <button type="button" id="btn4" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">2015
  </button>
  <div class="dropdown-menu">
    <a class="dropdown-item" href="javascript:void(0);" onclick="chart4action('2015')">2015</a>
    <a class="dropdown-item" href="javascript:void(0);" onclick="chart4action('2016')">2016</a>
    <a class="dropdown-item" href="javascript:void(0);" onclick="chart4action('2017')">2017</a>
    <a class="dropdown-item" href="javascript:void(0);" onclick="chart4action('2018')">2018</a>
    <a class="dropdown-item" href="javascript:void(0);" onclick="chart4action('2019')">2019</a>
  </div>
</div>
<div class="btn-group">
 <button type="button" id="btn41" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">FL
  </button>
  <div class="dropdown-menu">
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
                    echo "<a  href='javascript:void(0);' onclick='chart41action(";
                    echo '"'.$re[0].'"';
                    echo ")'>".$re[0]."</a>";
                    echo "</div>"; 
                    $i++;
                }
                echo "</div>";
           ?> 
        </form>
  </div>
</div>

  <div align="center" class="card card-body" id="ch4">
   <canvas id="myChart4" ></canvas>
  </div>
  </div>  
  
  <li class="list-group-item list-group-item-warning">
  <a  data-toggle="collapse" href="#collapse5" role="button" aria-expanded="false" aria-controls="collapse5" style="color:inherit">
    Analyze brand which have top average car sold time 
  </a>
  </li>
  <div class="collapse" id="collapse5">
  <div class="card card-body">
   <canvas id="myChart5" ></canvas>
  </div>
  </div>  
  
  
  <li class="list-group-item list-group-item-info">
  <a  data-toggle="collapse" href="#collapse6" role="button" aria-expanded="false" aria-controls="collapse6" style="color:inherit">
    Analyze top order car brand in a ceartain year  
  </a>
  </li>
  <div class="collapse" id="collapse6">
  <div class="btn-group">
  <button type="button" id="btn6" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">2015
  </button>
  <div class="dropdown-menu">
    <a class="dropdown-item" href="javascript:void(0);" onclick="chart6action('2015')">2015</a>
    <a class="dropdown-item" href="javascript:void(0);" onclick="chart6action('2016')">2016</a>
    <a class="dropdown-item" href="javascript:void(0);" onclick="chart6action('2017')">2017</a>
    <a class="dropdown-item" href="javascript:void(0);" onclick="chart6action('2018')">2018</a>
    <a class="dropdown-item" href="javascript:void(0);" onclick="chart6action('2019')">2019</a>
  </div>
</div>
  <div align="center" class="card card-body" id="ch6">
   <canvas id="myChart6" ></canvas>
  </div>
  </div> 
 
 
 <li class="list-group-item list-group-item-light">
  <a  data-toggle="collapse" href="#collapse7" role="button" aria-expanded="false" aria-controls="collapse7" style="color:inherit">
    Analyze top state which have top amount of car 
  </a>
  </li>
  <div class="collapse" id="collapse7">
  <div class="card card-body">
   <canvas id="myChart7" ></canvas>
  </div>
  </div>  
  
  <li class="list-group-item list-group-item-dark">
  <a  data-toggle="collapse" href="#collapse8" role="button" aria-expanded="false" aria-controls="collapse8" style="color:inherit">
    Analyze top state for different brand in a ceartain year  
  </a>
  </li>
  <div class="collapse" id="collapse8">
  <div class="btn-group">
  <button type="button" id="btn8" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">2015
  </button>
  <div class="dropdown-menu">
    <a class="dropdown-item" href="javascript:void(0);" onclick="chart8action('2015')">2015</a>
    <a class="dropdown-item" href="javascript:void(0);" onclick="chart8action('2016')">2016</a>
    <a class="dropdown-item" href="javascript:void(0);" onclick="chart8action('2017')">2017</a>
    <a class="dropdown-item" href="javascript:void(0);" onclick="chart8action('2018')">2018</a>
    <a class="dropdown-item" href="javascript:void(0);" onclick="chart8action('2019')">2019</a>
  </div>

  <input class="form-control mr-sm-2" type="search" placeholder="Brand" aria-label="Search" id="sear">
  <button class="btn btn-outline-success my-2 my-sm-0" type="button" onclick="chart81action();">Confirm</button>
</div>
  
  <div align="center" class="card card-body" id="ch8">
   <canvas id="myChart8" ></canvas>
  </div>
  </div> 

</ul>
</div>


<div style="position:relative;width:80%;left:10%">

</div>
<script src="js/chartjs/dist/Chart.js"></script>
<script src="js/echart/dist/echarts.js"></script>
<script>
var yearnow="2015";
var yearnow1="2015";
var yearnow2="2015";
var state="FL";
var brandnow="";
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

function chart1(){
	var ctx = document.getElementById('myChart1');
	var json= '{"action":"analyze1"}';
	//alert(json);
	var json1=datarequest(json);
	//alert(json1);
	result =  JSON.parse(json1);
	var brand=new Array();
	var number=new Array();
	for(var i=0;i<result.length;i++){
		brand[i]=result[i]['BRAND'];
		number[i]=new Number(result[i]['NUM']);
	}
	var myChart = new Chart(ctx, {
	    type: 'bar',
	    data: {
	        labels: brand,
	        datasets: [{
	            label: 'number of Cars',
	            data: number,
	            backgroundColor: [
	                'rgba(255, 99, 132, 0.2)',
	                'rgba(54, 162, 235, 0.2)',
	                'rgba(255, 206, 86, 0.2)',
	                'rgba(75, 192, 192, 0.2)',
	                'rgba(153, 102, 255, 0.2)',
	                'rgba(255, 159, 64, 0.2)'
	            ],
	            borderColor: [
	                'rgba(255, 99, 132, 1)',
	                'rgba(54, 162, 235, 1)',
	                'rgba(255, 206, 86, 1)',
	                'rgba(75, 192, 192, 1)',
	                'rgba(153, 102, 255, 1)',
	                'rgba(255, 159, 64, 1)'
	            ],
	            borderWidth: 1
	        }]
	    },
	    options: {
	        scales: {
	            yAxes: [{
	                ticks: {
	                    beginAtZero: true
	                }
	            }]
	        }
	    }
	});
	
}

function chart2(){
	var json= '{"action":"analyze2"}';
	//alert(json);
	var json1=datarequest(json);
	//alert(json1);
	var result =  JSON.parse(json1);
	var brand=new Array();
	var number=new Array();
	var j=0;
	var l=0;
	
	for(var i=0;i<result.length;i++){		
		if(i==0||result[i-1]['BRAND']!=result[i]['BRAND']){
			number[j]=new Array();
			brand[j++]=result[i]['BRAND'];
			l=0;
		}
		
		var km=new Number(result[i]['KM']);
		if(km==l*100000+50000){
			number[j-1][l++]=Math.round(new Number(result[i]['PER']));
			
		}
		else
			if(i==0||result[i]['BRAND']!=result[i-1]['BRAND'])
				number[j-1][l++]=Math.round(new Number(result[i+1]['PER']));
			else
				number[j-1][l++]=Math.round(new Number(result[i-1]['PER']));			
	}
	
	//varkmarray=new Array();
	var kmarray=['50000','150000','250000','350000','450000','550000','650000','750000','850000'];
	//for(var i=0;i<9;i++)
	//	kmarray=(i*100000+50000)+"";
	var myChart2 = echarts.init(document.getElementById('myChart2'));
	var option = {
		    title: {
		        text: 'result'
		    },
		    tooltip: {
		        trigger: 'axis'
		    },
		    legend: {
		        data:brand
		    },
		    grid: {
		        left: '3%',
		        right: '4%',
		        bottom: '3%',
		        containLabel: true
		    },
		    toolbox: {
		        feature: {
		            saveAsImage: {}
		        }
		    },
		    xAxis: {
		        type: 'category',
		        boundaryGap: false,
		        data: kmarray
		    },
		    yAxis: {
		        type: 'value'
		    },
		    series: [ 
		    ]
		};
	 option.series=new Array();
	    //alert("11");
	    for(var i=0;i<brand.length;i++){
	    	option.series[i]={};
	    	option.series[i].name=brand[i];
	    	
	    	option.series[i].type='line';
	    	
	    	option.series[i].stack='avg';
	    	
	    	option.series[i].data=number[i];
	    }
	    //alert(JSON.stringify(option.yAxis));
	myChart2.setOption(option);
}


function chart3(){
	var json= '{"action":"analyze3"}';
	//alert(json);
	var json1=datarequest(json);
	//alert(json1);
	var result =  JSON.parse(json1);
	var brand=new Array();
	var number=new Array();
	var j=0;
	var l=0;
	
	for(var i=0;i<result.length;i++){		
		if(i==0||result[i-1]['BRAND']!=result[i]['BRAND']){
			number[j]=new Array();
			brand[j++]=result[i]['BRAND'];
			l=0;
		}
		
		var num=new Number(result[i]['SOLD_TIME']);
		if(num>7) continue;
		if(num==l){
			number[j-1][l++]=Math.round(new Number(result[i]['PER']));
			
		}
		else
			if(i==0||result[i]['BRAND']!=result[i-1]['BRAND'])
				number[j-1][l++]=Math.round(new Number(result[i+1]['PER']));
			else
				number[j-1][l++]=Math.round(new Number(result[i-1]['PER']));			
	}
	
	//varkmarray=new Array();
	var kmarray=['0','1','2','3','4','5','6','7'];
	//for(var i=0;i<9;i++)
	//	kmarray=(i*100000+50000)+"";
	var myChart3 = echarts.init(document.getElementById('myChart3'));
	var option = {
		    title: {
		        text: 'result'
		    },
		    tooltip: {
		        trigger: 'axis'
		    },
		    legend: {
		        data:brand
		    },
		    grid: {
		        left: '3%',
		        right: '4%',
		        bottom: '3%',
		        containLabel: true
		    },
		    toolbox: {
		        feature: {
		            saveAsImage: {}
		        }
		    },
		    xAxis: {
		        type: 'category',
		        boundaryGap: false,
		        data: kmarray
		    },
		    yAxis: {
		        type: 'value'
		    },
		    series: [ 
		    ]
		};
	 option.series=new Array();
	    //alert("11");
	    for(var i=0;i<brand.length;i++){
	    	option.series[i]={};
	    	option.series[i].name=brand[i];
	    	
	    	option.series[i].type='line';
	    	
	    	option.series[i].stack='avg';
	    	
	    	option.series[i].data=number[i];
	    }
	    //alert(JSON.stringify(option.yAxis));
	myChart3.setOption(option);
}

function chart4action(yearn){
	if(yearnow!=yearn){
		yearnow=yearn;
		document.getElementById("btn4").innerHTML=yearnow;
		chart4();
	}
}

function chart41action(stn){
	if(state!=stn){
		state=stn;
		document.getElementById("btn41").innerHTML=state;
		chart4();
	}
}
function chart4(){
	document.getElementById('ch4').innerHTML='';
	document.getElementById('ch4').innerHTML='<canvas id="myChart4" ></canvas>';
	var ctx = document.getElementById('myChart4');
	var json= '{"action":"analyze4","year":"'+yearnow+'","state":"'+state+'"}';
	//alert(json);
	var json1=datarequest(json);
	//alert(json1);
	result =  JSON.parse(json1);
	var brand=new Array();
	var number=new Array();
	for(var i=0;i<result.length;i++){
		brand[i]=result[i]['BRAND'];
		number[i]=new Number(result[i]['NUM']);
	}
	var myChart = new Chart(ctx, {
	    type: 'bar',
	    data: {
	        labels: brand,
	        datasets: [{
	            label: 'number of Cars',
	            data: number,
	            backgroundColor: [
	                'rgba(255, 99, 132, 0.2)',
	                'rgba(54, 162, 235, 0.2)',
	                'rgba(255, 206, 86, 0.2)',
	                'rgba(75, 192, 192, 0.2)',
	                'rgba(153, 102, 255, 0.2)',
	                'rgba(255, 159, 64, 0.2)'
	            ],
	            borderColor: [
	                'rgba(255, 99, 132, 1)',
	                'rgba(54, 162, 235, 1)',
	                'rgba(255, 206, 86, 1)',
	                'rgba(75, 192, 192, 1)',
	                'rgba(153, 102, 255, 1)',
	                'rgba(255, 159, 64, 1)'
	            ],
	            borderWidth: 1
	        }]
	    },
	    options: {
	        scales: {
	            yAxes: [{
	                ticks: {
	                    beginAtZero: true
	                }
	            }]
	        }
	    }
	});
	
}

function chart5(){
	var ctx = document.getElementById('myChart5');
	var json= '{"action":"analyze5"}';
	//alert(json);
	var json1=datarequest(json);
	//alert(json1);
	result =  JSON.parse(json1);
	var brand=new Array();
	var number=new Array();
	for(var i=0;i<result.length;i++){
		brand[i]=result[i]['BRAND'];
		number[i]=new Number(result[i]['NUM']);
	}
	var myChart = new Chart(ctx, {
	    type: 'bar',
	    data: {
	        labels: brand,
	        datasets: [{
	            label: 'number of Cars',
	            data: number,
	            backgroundColor: [
	                'rgba(255, 99, 132, 0.2)',
	                'rgba(54, 162, 235, 0.2)',
	                'rgba(255, 206, 86, 0.2)',
	                'rgba(75, 192, 192, 0.2)',
	                'rgba(153, 102, 255, 0.2)',
	                'rgba(255, 159, 64, 0.2)'
	            ],
	            borderColor: [
	                'rgba(255, 99, 132, 1)',
	                'rgba(54, 162, 235, 1)',
	                'rgba(255, 206, 86, 1)',
	                'rgba(75, 192, 192, 1)',
	                'rgba(153, 102, 255, 1)',
	                'rgba(255, 159, 64, 1)'
	            ],
	            borderWidth: 1
	        }]
	    },
	    options: {
	        scales: {
	            yAxes: [{
	                ticks: {
	                    beginAtZero: true
	                }
	            }]
	        }
	    }
	});
	
}

function chart6action(yearn){
	if(yearnow1!=yearn){
		yearnow1=yearn;
		document.getElementById("btn6").innerHTML=yearnow1;
		chart6();
	}
}
function chart6(){
	document.getElementById('ch6').innerHTML='';
	document.getElementById('ch6').innerHTML='<canvas id="myChart6" ></canvas>';
	var ctx = document.getElementById('myChart6');
	var json= '{"action":"analyze6","year":"'+yearnow1+'"}';
	//alert(json);
	var json1=datarequest(json);
	//alert(json1);
	result =  JSON.parse(json1);
	var brand=new Array();
	var number=new Array();
	for(var i=0;i<result.length;i++){
		brand[i]=result[i]['BRAND'];
		number[i]=new Number(result[i]['NUM']);
	}
	var myChart = new Chart(ctx, {
	    type: 'bar',
	    data: {
	        labels: brand,
	        datasets: [{
	            label: 'number of Cars',
	            data: number,
	            backgroundColor: [
	                'rgba(255, 99, 132, 0.2)',
	                'rgba(54, 162, 235, 0.2)',
	                'rgba(255, 206, 86, 0.2)',
	                'rgba(75, 192, 192, 0.2)',
	                'rgba(153, 102, 255, 0.2)',
	                'rgba(255, 159, 64, 0.2)'
	            ],
	            borderColor: [
	                'rgba(255, 99, 132, 1)',
	                'rgba(54, 162, 235, 1)',
	                'rgba(255, 206, 86, 1)',
	                'rgba(75, 192, 192, 1)',
	                'rgba(153, 102, 255, 1)',
	                'rgba(255, 159, 64, 1)'
	            ],
	            borderWidth: 1
	        }]
	    },
	    options: {
	        scales: {
	            yAxes: [{
	                ticks: {
	                    beginAtZero: true
	                }
	            }]
	        }
	    }
	});
	
}

function chart7(){
	var ctx = document.getElementById('myChart7');
	var json= '{"action":"analyze7"}';
	//alert(json);
	var json1=datarequest(json);
	//alert(json1);
	result =  JSON.parse(json1);
	var brand=new Array();
	var number=new Array();
	for(var i=0;i<result.length;i++){
		brand[i]=result[i]['STATE'];
		number[i]=new Number(result[i]['NUM']);
	}
	var myChart = new Chart(ctx, {
	    type: 'bar',
	    data: {
	        labels: brand,
	        datasets: [{
	            label: 'number of Cars',
	            data: number,
	            backgroundColor: [
	                'rgba(255, 99, 132, 0.2)',
	                'rgba(54, 162, 235, 0.2)',
	                'rgba(255, 206, 86, 0.2)',
	                'rgba(75, 192, 192, 0.2)',
	                'rgba(153, 102, 255, 0.2)',
	                'rgba(255, 159, 64, 0.2)'
	            ],
	            borderColor: [
	                'rgba(255, 99, 132, 1)',
	                'rgba(54, 162, 235, 1)',
	                'rgba(255, 206, 86, 1)',
	                'rgba(75, 192, 192, 1)',
	                'rgba(153, 102, 255, 1)',
	                'rgba(255, 159, 64, 1)'
	            ],
	            borderWidth: 1
	        }]
	    },
	    options: {
	        scales: {
	            yAxes: [{
	                ticks: {
	                    beginAtZero: true
	                }
	            }]
	        }
	    }
	});
	
}


function chart8action(yearn){
	if(yearnow2!=yearn){
		yearnow2=yearn;
		document.getElementById("btn8").innerHTML=yearnow2;
		chart8();
	}
}

function chart81action(){	
	brandnow=document.getElementById("sear").value;
	chart8();
}
function chart8(){
	document.getElementById('ch8').innerHTML='';
	document.getElementById('ch8').innerHTML='<canvas id="myChart8" ></canvas>';
	var ctx = document.getElementById('myChart8');
	//alert(brandnow);
	var json= '{"action":"analyze8","year":"'+yearnow2+'","brand":"'+brandnow+'"}';
	//alert(json);
	var json1=datarequest(json);
	//alert(json1);
	result =  JSON.parse(json1);
	var brand=new Array();
	var number=new Array();
	for(var i=0;i<result.length;i++){
		brand[i]=result[i]['STATE'];
		number[i]=new Number(result[i]['NUM']);
	}
	var myChart = new Chart(ctx, {
	    type: 'bar',
	    data: {
	        labels: brand,
	        datasets: [{
	            label: 'number of Cars',
	            data: number,
	            backgroundColor: [
	                'rgba(255, 99, 132, 0.2)',
	                'rgba(54, 162, 235, 0.2)',
	                'rgba(255, 206, 86, 0.2)',
	                'rgba(75, 192, 192, 0.2)',
	                'rgba(153, 102, 255, 0.2)',
	                'rgba(255, 159, 64, 0.2)'
	            ],
	            borderColor: [
	                'rgba(255, 99, 132, 1)',
	                'rgba(54, 162, 235, 1)',
	                'rgba(255, 206, 86, 1)',
	                'rgba(75, 192, 192, 1)',
	                'rgba(153, 102, 255, 1)',
	                'rgba(255, 159, 64, 1)'
	            ],
	            borderWidth: 1
	        }]
	    },
	    options: {
	        scales: {
	            yAxes: [{
	                ticks: {
	                    beginAtZero: true
	                }
	            }]
	        }
	    }
	});
	
}


function test(){
	chart1();
	chart2();
	chart3();
	chart4();
	chart5();
	chart6();
	chart7();
	chart8();
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