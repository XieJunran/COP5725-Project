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

<div class='card mb-3' style='width:80%;left:10%;'>
	<div style="width=100%;height=100%;">
		<img class="card-img" alt="..." src="images/back1.jpg" style="max-width=100%;max-height=100%;">
		<div style="position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);font-size:2.5vw;color:white;" >
			<?php
            if(isset($_SESSION['userID'])){
                $username="huanbin";
                $password="24361Zhb1152";
                $connection_string="oracle.cise.ufl.edu:1521/orcl";
                global $con;
                $con=oci_connect($username,$password,$connection_string);
                $query="SELECT * FROM CAR WHERE CARID=:carid";
                $stmt=oci_parse($con, $query);
                oci_bind_by_name($stmt, ":carid",$carid);
                oci_execute($stmt);
                $carinfo=oci_fetch_array($stmt);
                if($carinfo==null){
                    echo "Sorry. We can't find this car.";
                }
                else{
                    $query="SELECT * FROM ORDER_HISTORY WHERE CARID=:carid";
                    $stmt = oci_parse($con, $query);
                    oci_bind_by_name($stmt, ":carid",$carid);
                    oci_execute($stmt);
                    $carorder = oci_fetch_array($stmt);
                    if($carorder == null){
                        echo "Thanks for shopping with us!";
                        //get seller id
                        $query="SELECT * FROM POST WHERE CARID=:carid";
                        $stmt=oci_parse($con, $query);
                        oci_bind_by_name($stmt, ":carid",$carid);
                        oci_execute($stmt);
                        $postinfo=oci_fetch_array($stmt);
                        $sellerid=$postinfo[0];
                        $transactionamount=$carinfo[9];
                        $orderstatus="Arrived";
                        //get order id
                        $orderid="#".strval(rand(1000000000,9999999999));
                        $query="SELECT * FROM ORDER_HISTORY WHERE ORDERID=:orderid";
                        $stmt=oci_parse($con, $query);
                        oci_bind_by_name($stmt, ":orderid",$orderid);
                        oci_execute($stmt);
                        $orderinfo=oci_fetch_array($stmt);
                        while($orderinfo!=null){
                            $orderid="#".strval(rand(1000000000,9999999999));
                            $query="SELECT * FROM ORDER_HISTORY WHERE ORDERID=:orderid";
                            $stmt=oci_parse($con, $query);
                            oci_bind_by_name($stmt, ":orderid",$orderid);
                            oci_execute($stmt);
                            $orderinfo=oci_fetch_array($stmt);
                        }
                        //insert a new record in order history
                        $query="INSERT INTO ORDER_HISTORY VALUES (:orderid,(SELECT SYSDATE FROM DUAL),:sellerid,:buyerid,:carid,:transactionamount,:orderstatus)";
                        $stmt=oci_parse($con, $query);
                        oci_bind_by_name($stmt, ":orderid",$orderid);
                        oci_bind_by_name($stmt, ":sellerid",$sellerid);
                        oci_bind_by_name($stmt, ":buyerid",$useridnow);
                        oci_bind_by_name($stmt, ":carid",$carid);
                        oci_bind_by_name($stmt, ":transactionamount",$transactionamount);
                        oci_bind_by_name($stmt, ":orderstatus",$orderstatus);
                        oci_execute($stmt);
                        $query="COMMIT";
                        $stmt = oci_parse($con, $query);
                        oci_execute($stmt);
                    }else{
                        echo "Sorry. This car has been sold.";
                    }
                }
            }else{
                echo "Sorry. You need to log in first.";
            }
            ?>
		</div>
	</div>

</div>




<script src="jquery-3.3.1.js" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>