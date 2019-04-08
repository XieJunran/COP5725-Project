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
//$carid='0000-0015';
if($carid==''){
    header("location:main_menu2.php");
}


//$json_data=$_SESSION['json_data'];
//$json1=(array) json_decode($json_data,1);
//$userid=$json1[0]['USERNAME'];
$useridnow=289;
//$useridnow=$json1[0]['USERID'];
//if(isset($_SESSION['userID'])){
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
        //$query="alter session set nls_date_format = 'yyyy-mm-dd hh24:mi:ss'";
        //$stmt = oci_parse($con, $query);
        //oci_execute($stmt);
        //$query="UPDATE VIEW_HISTORY SET DATE=(SELECT SYSDATE FROM DUAL) WHERE USERID=:userid AND CARID=:carid";
        //$stmt = oci_parse($con, $query);
        //oci_bind_by_name($stmt, ":userid",$useridnow);
        //oci_bind_by_name($stmt, ":carid",$carid);
        //oci_execute($stmt);
        //$query="COMMIT";
        //$stmt = oci_parse($con, $query);
        //oci_execute($stmt);
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

<html lang="en"><head>

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">

<title>Home</title>

<!-- Bootstrap Core CSS -->
<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<!-- Custom Fonts -->
<link href="https://fonts.googleapis.com/css?family=Roboto:400,100italic,100,300,300italic" rel="stylesheet" type="text/css">
<!-- Theme CSS -->
<link href="css/agency.min.css" rel="stylesheet">

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body id="page-top" class="index modal-open" style="padding-right: 17px;">

<div class="portfolio-modal modal fade in" id="portfolioModal1" tabindex="-1" role="dialog" aria-hidden="true" style="display: block;">
	<div class="modal-dialog">
		<div class="modal-content">
			
			<div class="container">
				<div class="row">
					<div class="col-lg-8 col-lg-offset-2">
						<div class="modal-body">
							<!-- Project Details Go Here -->
							<h2><?php echo $carinfo[8];?></h2>
							<p class="item-intro text-muted"><?php echo 'Seller name: '.$sellerinfo[2].' &nbsp;Email Address: '.$sellerinfo[1].' &nbsp;Phone Number '.$sellerinfo[4]?></p>
							<img class="img-responsive img-centered" src=<?php echo $carinfo[3];?> alt="">
							<p>
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
									<table class="altrowstable" id="alternatecolor">
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
									</tr>
									<tr>
										<td>Price: <?php echo $carinfo[9];?></td>
										<td>Price For New Car: <?php echo $carinfo[10];?></td>
										<td>Sold Time: <?php echo $carinfo[11];?></td>
										<td>Color: <?php echo $carinfo[13];?></td>
									</tr>
									</table>
								</div>
							</p>
							<button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times"></i> Close Window</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal-backdrop fade in"></div></body></html>