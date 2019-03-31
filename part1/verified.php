<?php
$code = $_GET['code'];
$username="huanbin";
$password="24361Zhb1152";
$connection_string="oracle.cise.ufl.edu:1521/orcl";
global $con;
$con=oci_connect($username,$password,$connection_string);

if($code=='') return;
$query="SELECT * FROM USER_INFO WHERE vcode=:code";
$stmt = oci_parse($con, $query);
oci_bind_by_name($stmt, ":code",$code);
oci_execute($stmt);
if(oci_fetch_all($stmt, $records)!=1){
    oci_close($con);
    echo 'ERROR!';
}
else{
    if($records["VERIFIED"][0]==1){
        echo "Already have verified";       
    }
    else{
        $query="UPDATE USER_INFO SET verified=1 Where vcode=:code";
        $stmt = oci_parse($con, $query);
        oci_bind_by_name($stmt, ":code",$code);
        oci_execute($stmt);
        $stmt=oci_parse($con, 'commit');
        oci_execute($stmt);
        echo "Verify success!";   
}
oci_close($con);
} 
?>