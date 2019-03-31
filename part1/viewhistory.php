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
    <body >
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
<?php 
$username="huanbin";
$password="24361Zhb1152";
$connection_string="oracle.cise.ufl.edu:1521/orcl";
global $con;
$con=oci_connect($username,$password,$connection_string);
$query="SELECT * FROM (CAR NATURAL JOIN VIEW_HISTORY) WHERE USERID=:userid ORDER BY TIME DESC ";
$stmt = oci_parse($con, $query);
oci_bind_by_name($stmt, ":userid",$useridnow);
oci_execute($stmt);
while($r=oci_fetch_array($stmt, OCI_ASSOC)){
    echo "<div class='card mb-3' style='width:80%;left:10%'>";
    echo "<div class='row no-gutters'>";
    echo "<div class='col-md-4'>";
    echo "<img src='".$r['PICTURE']."' class='card-img' alt='...'>";
    echo "</div>";
    echo "<div class='col-md-8'>";
    echo "<div class='card-body'>";
    echo "<h5 class='card-title'>Card title</h5>";
    echo "<p class='card-text'>".$r['MODEL']."</p>";
    echo "<p class='card-text'><small class='text-muted'>".$r['TIME']."</small></p></div></div></div></div>";
    
}

    oci_close($con);
?>

<br>
<br>

<div id="list">
</div>

<br>
<br>
<nav aria-label="..." id="page">
 
</nav>





<script type="text/javascript">



</script>




    <script src="jquery-3.3.1.js" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>