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
$carid=getUrlParam('carid',$url);
if($carid==''){
    header("location:main_menu2.php");
}


$json_data=$_SESSION['json_data'];
$json1=(array) json_decode($json_data,1);
$userid=$json1[0]['USERNAME'];
$useridnow=$json1[0]['USERID'];
if(isset($_SESSION['userID'])){
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
        $query="UPDATE VIEW_HISTORY SET DATE=(SELECT SYSDATE FROM DUAL) WHERE USERID=:userid AND CARID=:carid";
        $stmt = oci_parse($con, $query);
        oci_bind_by_name($stmt, ":userid",$useridnow);
        oci_bind_by_name($stmt, ":carid",$carid);
        oci_execute($stmt);
    }
    else{
        $query="INSERT INTO VIEW_HISTORY VALUES (:userid,:carid,(SELECT SYSDATE FROM DUAL))";
        $stmt = oci_parse($con, $query);
        oci_bind_by_name($stmt, ":userid",$useridnow);
        oci_bind_by_name($stmt, ":carid",$carid);
        oci_execute($stmt); 
    }
    }
}
?>