<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
@ $json =file_get_contents( "php://input" );
@ $data=json_decode($json,true);

$checkmail=true;

$username="huanbin";
$password="24361Zhb1152";
$connection_string="oracle.cise.ufl.edu:1521/orcl";
global $con;
$con=oci_connect($username,$password,$connection_string);
switch($data["action"])
{
    case "logincheck":logincheck(); break;
    case "registercheck":registercheck();break;
    case "changepassword":changepassword();break;
    case "forgetpassword":forgetpassword();break;
    case "userinfo":userinfo();break;
    case "updateuserinfo":updateuserinfo();break;
    case "multisearch":multisearch();break;
    case "searchorderhistory":searchorderhistory();break;
    case "searchinterest":searchinterest();break;
    case "searchviewhistory":searchviewhistory();break;
    case "addcar":addcar();break;
    case "searchzip":searchzip();break;
    case "searchcity":searchcity();break;
    case "dislike":dislike();break;
    case "interest":interest();break;
    case "analyze1":analyze1();break;
    case "analyze2":analyze2();break;
    case "analyze3":analyze3();break;
    case "analyze4":analyze4();break;
    case "analyze5":analyze5();break;
    case "analyze6":analyze6();break;
    case "analyze7":analyze7();break;
    case "analyze8":analyze8();break;
    default:break;
}

oci_close($con);

function dislike(){
    global $data;
    global $con;
    $userid=$data['userid'];
    $carid=$data['carid'];
    $query="DELETE FROM INTEREST WHERE USERID=:userid AND CARID=:carid";
    $stmt = oci_parse($con, $query);
    oci_bind_by_name($stmt, ":userid",$userid);
    oci_bind_by_name($stmt, ":carid",$carid);
    oci_execute($stmt);
    $stmt = oci_parse($con, "commit");
    oci_execute($stmt);
    echo 'success!'; 
}

function interest(){
    global $data;
    global $con;
    $userid=$data['userid'];
    $carid=$data['carid'];
    $query="INSERT INTO INTEREST VALUES(:userid,:carid)";
    $stmt = oci_parse($con, $query);
    oci_bind_by_name($stmt, ":userid",$userid);
    oci_bind_by_name($stmt, ":carid",$carid);
    oci_execute($stmt);
    $stmt = oci_parse($con, "commit");
    oci_execute($stmt);
    echo 'success!';
}

function searchcity(){
    global $data;
    global $con;
    $state=$data['state'];
    $query="SELECT DISTINCT CITY FROM COUNTRY_INFO WHERE ABBREVIATION=:state ORDER BY CITY";
    $stmt = oci_parse($con, $query);
    oci_bind_by_name($stmt, ":state",$state);
    oci_execute($stmt);
    $jso=array();
    while ($row = oci_fetch_array($stmt,OCI_ASSOC)) {
        $jso[] = $row;
    }
    echo json_encode($jso); 
}

function searchzip(){
    global $data;
    global $con;
    $state=$data['state'];
    $city=$data['city'];
    $query="SELECT DISTINCT ZIP_CODE FROM COUNTRY_INFO WHERE ABBREVIATION=:state AND CITY=:city ORDER BY ZIP_CODE";
    $stmt = oci_parse($con, $query);
    oci_bind_by_name($stmt, ":state",$state);
    oci_bind_by_name($stmt, ":city",$city);
    oci_execute($stmt);
    $jso=array();
    while ($row = oci_fetch_array($stmt,OCI_ASSOC)) {
        $jso[] = $row;
    }
    echo json_encode($jso);
}


function addcar(){
    global $data;
    global $con;
    $userid=$data["Userid"];
    $soldtime=$data["Soldtime"];
    $kmage=$data["Kmage"];
    $brand=$data["Brand"];
    $model=$data["Model"];
    $NewPrice=$data["NewPrice"];
    $Price=$data["Price"];
    $State=$data["State"];
    $City=$data["City"];
    $Zip=$data["Zip"];
    $GearBox=$data["GearBox"];
    $Color=$data["Color"];
    $Insurance=$data["Insurance"];
    $query="UPDATE INFO SET MAXCARID=MAXCARID+1";
    $stmt = oci_parse($con, $query);
    oci_execute($stmt);
    $query="SELECT * FROM INFO";
    $stmt = oci_parse($con, $query);
    oci_execute($stmt);
    $row = oci_fetch_array($stmt,OCI_ASSOC);
    $caridnum=$row['MAXCARID']+1;
    $stmt=oci_parse($con, 'commit');
    oci_execute($stmt);
    $carid='';
    for($i=0;$i<4;$i++){
        $carid=($caridnum%10).$carid;
        $caridnum=$caridnum/10;
    }
    $carid='-'.$carid;
    for($i=0;$i<4;$i++){
        $carid=($caridnum%10).$carid;
        $caridnum=$caridnum/10;
    }
    $query="INSERT INTO CAR VALUES(:carid,:gearbox,:kmage,:picture,:state,:city,:zip,:brand,:model,:price,:newprice,:soldtime,:insurance,:color)";
    $stmt = oci_parse($con, $query);
    oci_bind_by_name($stmt, ":carid",$carid);
    
    if($GearBox=='Auto')
        $GearBox='Automatic';
    else 
        $GearBox='Manual';
        oci_bind_by_name($stmt, ":gearbox",$GearBox);
    oci_bind_by_name($stmt, ":kmage",$kmage);
    $uurl='10.227.162.163/SecondCarWeb/part1/img/carimg/'.$carid.'png';
    oci_bind_by_name($stmt, ":picture",$uurl);
    oci_bind_by_name($stmt, ":state",$State);
    oci_bind_by_name($stmt, ":city",$City);
    oci_bind_by_name($stmt, ":zip",$Zip);
    oci_bind_by_name($stmt, ":brand",$brand);
    oci_bind_by_name($stmt, ":model",$model);
    oci_bind_by_name($stmt, ":price",$Price);
    oci_bind_by_name($stmt, ":newprice",$NewPrice);
    oci_bind_by_name($stmt, ":soldtime",$soldtime);
    if($Insurance=='YES')
        $Insurance='Y';
    else
        $Insurance='N';
    oci_bind_by_name($stmt, ":insurance",$Insurance);
    oci_bind_by_name($stmt, ":color",$Color);
    oci_execute($stmt);
    $stmt=oci_parse($con, 'commit');
    oci_execute($stmt);
    
    $query="alter session set nls_date_format = 'yyyy-mm-dd hh24:mi:ss'";
    $stmt = oci_parse($con, $query);
    oci_execute($stmt);
    $query="INSERT INTO POST VALUES (:userid,:carid,(SELECT SYSDATE FROM DUAL))";
    $stmt = oci_parse($con, $query);
    oci_bind_by_name($stmt, ":userid",$userid);
    oci_bind_by_name($stmt, ":carid",$carid);
    oci_execute($stmt);
    $query="COMMIT";
    $stmt = oci_parse($con, $query);
    oci_execute($stmt);   
    echo "success!".$carid;
}
function updateuserinfo(){
    global $data;
    global $con;
    $userid=$data["userid"];
    $contact=$data["CONTACT_INFORMATION"];
    $ssn=$data["SSN"];
    $passport=$data["PASSPORT_NUMBER"];
    $credit=$data["CREDIT_CARD_NUMBER"];  
    $query="UPDATE USER_INFO SET SSN=:ssn,CONTACT_INFORMATION=:contact,PASSPORT_NUMBER=:passport,CREDIT_CARD_NUMBER=:credit WHERE USERID=:userid";
    $stmt = oci_parse($con, $query);
    oci_bind_by_name($stmt, ":userid",$userid);
    oci_bind_by_name($stmt, ":contact",$contact);
    oci_bind_by_name($stmt, ":ssn",$ssn);
    oci_bind_by_name($stmt, ":passport",$passport);
    oci_bind_by_name($stmt, ":credit",$credit);
    oci_execute($stmt);
    $stmt=oci_parse($con, 'commit');
    oci_execute($stmt);
    echo "success!";
}

function userinfo(){
    global $data;
    global $con;
    $userid=$data["userid"];
    $query="SELECT * FROM USER_INFO WHERE USERID=:userid";
    $stmt = oci_parse($con, $query);
    oci_bind_by_name($stmt, ':userid', $userid);
    oci_execute($stmt);
    $jso=array();
    while ($row = oci_fetch_array($stmt,OCI_ASSOC)) {
        $jso[] = $row;
    }
    echo json_encode($jso);  
    
}
function analyze1(){
    global $data;
    global $con;
    $query="select brand,count(distinct c.carid) as num from car c,order_history o where c.carid=o.carid and buyer in( select buyer from( select buyer,rank() over(order by count(distinct carid) desc) as rank from order_history group by  buyer) where rank<50) group by brand having count(distinct c.carid)>10 order by count(distinct c.carid) desc";
    $stmt = oci_parse($con, $query);
    oci_execute($stmt);
    $jso=array();
    while ($row = oci_fetch_array($stmt,OCI_ASSOC)) {
        $jso[] = $row;
    }
    echo json_encode($jso);    
}

function analyze2(){
    global $data;
    global $con;
    $query="select BRAND,(floor(KM_AGE/100000)*100000+50000) as KM,avg((PRICE_FOR_NEW_CAR-PRICE)/PRICE_FOR_NEW_CAR*100) as PER from CAR where brand in(select brand from (select brand,rank() over(order by count(DISTINCT CARID) desc) as ranked from car group by brand) where ranked<=10)group by brand,floor(KM_AGE/100000)*100000+50000 order by brand asc,floor(KM_AGE/100000)*100000+50000 asc";
    $stmt = oci_parse($con, $query);
    oci_execute($stmt);
    $jso=array();
    while ($row = oci_fetch_array($stmt,OCI_ASSOC)) {
        $jso[] = $row;
    }
    echo json_encode($jso);
}

function analyze3(){
    global $data;
    global $con;
    $query="select BRAND,SOLD_TIME,avg((PRICE_FOR_NEW_CAR-PRICE)/PRICE_FOR_NEW_CAR*100) as per from CAR where brand in(select brand from (select brand,rank() over(order by count(DISTINCT CARID) desc) as ranked from car group by brand) where ranked<=10)group by brand,SOLD_TIME order by brand asc,SOLD_TIME asc ";
    $stmt = oci_parse($con, $query);
    oci_execute($stmt);
    $jso=array();
    while ($row = oci_fetch_array($stmt,OCI_ASSOC)) {
        $jso[] = $row;
    }
    echo json_encode($jso);
}
function analyze4(){
    global $data;
    global $con;
    $year=$data['year'];
    $state=$data['state'];
    $query="select * from (select BRAND,count(distinct c.carid) as num from car c,post p where c.transaction_state=:state and c.carid=p.carid and p.time between  TO_DATE ('".$year."-JAN-1 00:00:00','yyyy-mon-dd hh24:mi:ss') and TO_DATE ('".$year."-DEC-31 23:59:59','yyyy-mon-dd hh24:mi:ss') group by brand order by count(distinct c.carid) desc) t where rownum<=10";
    $stmt = oci_parse($con, $query);
    oci_bind_by_name($stmt, ":state",$state);
    oci_execute($stmt);
    $jso=array();
    while ($row = oci_fetch_array($stmt,OCI_ASSOC)) {
        $jso[] = $row;
    }
    echo json_encode($jso);
}

function analyze5(){
    global $data;
    global $con;
    $query="select * from (select BRAND,avg(SOLD_TIME)as num from car where brand in(select brand from car group by brand having count(distinct carid)>10)group by BRAND order by avg(SOLD_TIME) desc) t where rownum<=10";
    $stmt = oci_parse($con, $query);
    oci_execute($stmt);
    $jso=array();
    while ($row = oci_fetch_array($stmt,OCI_ASSOC)) {
        $jso[] = $row;
    }
    echo json_encode($jso);
}

function analyze6(){
    global $data;
    global $con;
    $year=$data['year'];
    $query="select * from (select BRAND,count(distinct c.carid) as num from car c,order_history o where c.carid=o.carid and o.transaction_time between  TO_DATE ('".$year."-JAN-1 00:00:00','yyyy-mon-dd hh24:mi:ss') and TO_DATE ('".$year."-DEC-31 23:59:59','yyyy-mon-dd hh24:mi:ss') group by brand order by count(distinct c.carid) desc) t where rownum<=10";
    $stmt = oci_parse($con, $query);
    oci_execute($stmt);
    $jso=array();
    while ($row = oci_fetch_array($stmt,OCI_ASSOC)) {
        $jso[] = $row;
    }
    echo json_encode($jso);
}

function analyze7(){
    global $data;
    global $con;
    
    $query="select distinct c.state,t.num from country_info c,(select * from (select transaction_state as state, count(distinct carid) as num from car group by transaction_state order by count(distinct carid) desc) where rownum<=10) t where c.abbreviation=t.state order by t.num desc";
    $stmt = oci_parse($con, $query);
    
    oci_execute($stmt);
    $jso=array();
    while ($row = oci_fetch_array($stmt,OCI_ASSOC)) {
        $jso[] = $row;
    }
    echo json_encode($jso);   
}

function analyze8(){
    global $data;
    global $con;
    $brand='%'.$data['brand'].'%';
    $query="select distinct c.state,t.num from country_info c,(select * from (select transaction_state as state, count(distinct carid) as num from car where brand like :brand group by transaction_state order by count(distinct carid) desc) where rownum<=10) t where c.abbreviation=t.state order by t.num desc";
    $stmt = oci_parse($con, $query);
    oci_bind_by_name($stmt, ":brand",$brand);
    oci_execute($stmt);
    $jso=array();
    while ($row = oci_fetch_array($stmt,OCI_ASSOC)) {
        $jso[] = $row;
    }
    echo json_encode($jso);
}


function searchorderhistory(){
    global $data;
    global $con;
    $userid=$data["userid"];
    $query="alter session set nls_date_format = 'yyyy-mm-dd hh24:mi:ss'";
    $stmt = oci_parse($con, $query);
    oci_execute($stmt);
    $query="SELECT * FROM (CAR NATURAL JOIN ORDER_HISTORY) WHERE BUYER=:userid ORDER BY TRANSACTION_TIME DESC ";
    $stmt = oci_parse($con, $query);
    oci_bind_by_name($stmt, ":userid",$userid);
    oci_execute($stmt);
    $jso=array();
    while ($row = oci_fetch_array($stmt,OCI_ASSOC)) {
        $jso[] = $row;
    }
    echo json_encode($jso);
}

function searchviewhistory(){
    global $data;
    global $con;
    $userid=$data["userid"];
    $query="alter session set nls_date_format = 'yyyy-mm-dd hh24:mi:ss'";
    $stmt = oci_parse($con, $query);
    oci_execute($stmt);
    $query="SELECT * FROM (CAR NATURAL JOIN VIEW_HISTORY) WHERE USERID=:userid ORDER BY TIME DESC ";
    $stmt = oci_parse($con, $query);
    oci_bind_by_name($stmt, ":userid",$userid);
    oci_execute($stmt);
    $jso=array();
    while ($row = oci_fetch_array($stmt,OCI_ASSOC)) {
        $jso[] = $row;
    }
    echo json_encode($jso); 
}

function searchinterest(){
    global $data;
    global $con;
    $userid=$data["userid"];
    $query="SELECT * FROM (CAR NATURAL JOIN INTEREST) WHERE USERID=:userid";
    $stmt = oci_parse($con, $query);
    oci_bind_by_name($stmt, ":userid",$userid);
    oci_execute($stmt);
    $jso=array();
    while ($row = oci_fetch_array($stmt,OCI_ASSOC)) {
        $jso[] = $row;
    }
    echo json_encode($jso);
}

function multisearch(){
    global $data;
    global $con;
    $query="SELECT CARID,PICTURE,PRICE,MODEL FROM CAR WHERE (CARID NOT IN (SELECT DISTINCT CARID FROM ORDER_HISTORY)) ";
   
    if(array_key_exists('txt',$data)){
        $txt='%'.$data['txt'].'%';
        $query=$query." AND MODEL LIKE :txt ";
        $stmt=oci_parse($con, $query);
 
        oci_bind_by_name($stmt, ":txt",$txt);
       
        oci_execute($stmt);
        $jso=array();
        while ($row = oci_fetch_array($stmt,OCI_ASSOC)) {
            $jso[] = $row;
        }
        echo json_encode($jso);
        return;
    }
    if(array_key_exists('brand',$data)){
        $query=$query." AND BRAND=:brand ";   
    }
    if(array_key_exists('color',$data)){
        $query=$query." AND COLOR=:color ";
    }
    if(array_key_exists('prmin',$data)){
        $query=$query." AND PRICE>=:prmin ";
    }
    if(array_key_exists('prmax',$data)){
        $query=$query." AND PRICE<=:prmax ";
    }
    if(array_key_exists('gearbox',$data)){
        $query=$query." AND GEARBOX=:gearbox ";
    }
    if(array_key_exists('selltime',$data) ){
        $query=$query." AND SOLD_TIME<=:selltime ";
    }
    if(array_key_exists('kmage',$data)){
        $query=$query." AND KM_AGE<=:kmage ";
    }
    if(array_key_exists('state',$data)){
        $query=$query." AND TRANSACTION_STATE=:state ";
    }
    if(array_key_exists('sortname',$data)){
        $query=$query." ORDER BY ".$data['sortname']." ".$data['sorttype']." ";
    }
    $stmt=oci_parse($con, $query);
    if(array_key_exists('brand',$data)){
        oci_bind_by_name($stmt, ":brand",$data['brand']);
    }
    if(array_key_exists('color',$data)){
        oci_bind_by_name($stmt, ":color",$data['color']);
    }
    if(array_key_exists('prmin',$data)){
        oci_bind_by_name($stmt, ":prmin",$data['prmin']);
    }
    if(array_key_exists('prmax',$data)){
        oci_bind_by_name($stmt, ":prmax",$data['prmax']);
    }
    if(array_key_exists('gearbox',$data)){
        oci_bind_by_name($stmt, ":gearbox",$data['gearbox']);
    }
    if(array_key_exists('selltime',$data)){
        oci_bind_by_name($stmt, ":selltime",$data['selltime']);
    }
    if(array_key_exists('kmage',$data)){
        oci_bind_by_name($stmt, ":kmage",$data['kmage']);
    }
    if(array_key_exists('state',$data)){
        oci_bind_by_name($stmt, ":state",$data['state']);
    }
 //   echo "111";
   // return;
    //echo "SELECT CARID,PICTURE,PRICE,MODEL FROM CAR WHERE 1=1";
    //return;
    oci_execute($stmt);
    
   $jso=array();
    while ($row = oci_fetch_array($stmt,OCI_ASSOC)) {
        $jso[] = $row;
    }
    echo json_encode($jso); 
}
function changepassword(){
    global $data;
    global $con;
    $code=$data["code"];
    $password=$data["password"];
    $query="UPDATE USER_INFO SET password=:pw,changepw=0 WHERE (pcode=:code AND changepw=1)";
    $stmt = oci_parse($con, $query);
    oci_bind_by_name($stmt, ":pw",$password);
    oci_bind_by_name($stmt, ":code",$code);
    oci_execute($stmt);
    $stmt=oci_parse($con, 'commit');
    oci_execute($stmt);
    echo "success!";
}

function forgetpassword(){
    global $data;
    global $con;
    $email=$data["email"];
    $userid=$data["userid"];
   // echo $user;
    if ($userid!="" && $email!=""){
        $query="SELECT * FROM USER_INFO WHERE username=:id AND email=:email AND verified=1";
        $stmt = oci_parse($con, $query);
        oci_bind_by_name($stmt, ":id",$userid);
        oci_bind_by_name($stmt, ":email",$email);
        oci_execute($stmt);
        if(oci_fetch_all($stmt,$records)==0){
            echo 'userID does not match the email,it will be enough if your choose one of them!';
            return;
        }
    }
    else
    if ($userid!=""){
        $query="SELECT * FROM USER_INFO WHERE username=:id AND verified=1";
        $stmt = oci_parse($con, $query);
        oci_bind_by_name($stmt, ":id",$userid);
        oci_execute($stmt);
        if(oci_num_rows($stmt)==0){
            echo 'userID does not exist!';
            return;
        }
    }
    else{
    $query="SELECT * FROM USER_INFO WHERE email=:email AND verified=1";
    $stmt = oci_parse($con, $query);
    oci_bind_by_name($stmt, ":email",$email);
    oci_execute($stmt);
    if(oci_num_rows($stmt)==0){
        echo 'email does not exist!';
        return;
    }
    }
    $code=create_guid();
    $query="UPDATE USER_INFO SET changepw=1,pcode=:code WHERE (email=:email OR username=:id) AND verified=1";
    $stmt = oci_parse($con, $query);
    oci_bind_by_name($stmt, ":id",$userid);
    oci_bind_by_name($stmt, ":email",$email);
    oci_bind_by_name($stmt, ":code",$code);
    oci_execute($stmt);
    $stmt=oci_parse($con, 'commit');
    oci_execute($stmt);
    $message="Please verify the email and activate your account by click the link 10.227.162.163/medicalshare/part1/changepassword.php?code=".$code;
    sendmail($email,$message);
    echo "email has sent!";
}

function sendmail($email,$link){
    //  require 'PHPMailerAutoload.php';
    global $checkmail;
    $checkmail=true;
    $mail = new PHPMailer;
    //  $mail->SMTPDebug = 2;                               // Enable verbose debug output
    
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com;smtp.gmail.com';  // Specify main and backup SMTP servers.
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'mcyworkmail@gmail.com';                 // SMTP username
    $mail->Password = 'efcggdyzbthacgqi';                           // SMTP password
    $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 465;                                    // TCP port to connect to
    
    $mail->setFrom('mcyworkmail@gmail.com');
    $mail->addAddress($email);     // Add a recipient
    //  $mail->addAddress('ellen@example.com');               // Name is optional
    //  $mail->addReplyTo('info@example.com', 'Information');
    //  $mail->addCC('cc@example.com');
    //  $mail->addBCC('bcc@example.com');
    
    //  $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //   $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
    $mail->isHTML(true);                                  // Set email format to HTML
    
    $mail->Subject = "Second Car Website email verify link";
    $mail->Body    = $link;
    //  $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    @  $mail->SMTPOptions = array(
        @      'ssl' => array(
            @          'verify_peer' => false,
            @          'verify_peer_name' => false,
            @          'allow_self_signed' => true
        )
    );
    if(!$mail->send()){
        echo "Mail Address Error!";
        $checkmail=false;
    }
}

function create_guid($namespace = null) {
    static $guid = '';
    $uid = uniqid ( "", true );
    
    $data = $namespace;
    $data .= $_SERVER ['REQUEST_TIME'];
    $data .= $_SERVER ['HTTP_USER_AGENT'];
    // $data .= $_SERVER ['SERVER_ADDR'];
    //  $data .= $_SERVER ['SERVER_PORT'];
    $data .= $_SERVER ['REMOTE_ADDR'];
    $data .= $_SERVER ['REMOTE_PORT'];
    
    $hash = strtoupper ( hash ( 'ripemd128', $uid . $guid . md5 ( $data ) ) );
    $guid = substr ( $hash, 0, 8 ) . '-' . substr ( $hash, 8, 4 ) . '-' . substr ( $hash, 12, 4 ) . '-' . substr ( $hash, 16, 4 ) . '-' . substr ( $hash, 20, 12 );
    
    return $guid;
}

function logincheck()
{
    global $data;
    global $con;
    $userID=$data["userid"];
    $password=$data['password'];
    $stmt = oci_parse($con, 'SELECT * FROM USER_INFO WHERE username=:id and password=:pw and verified=1');
    oci_bind_by_name($stmt, ":id",$userID);
    oci_bind_by_name($stmt, ":pw",$password);
    oci_execute($stmt);
    $records=array();
    if($r = oci_fetch_array($stmt,OCI_ASSOC)){
        $records[] = $r;
        session_start();
        $json_data=json_encode($records);
        $_SESSION['userID']="";
        $_SESSION['password']="";
        $_SESSION['json_data']=$json_data;
        oci_free_statement($stmt);
        echo "success!";
    }
    else
    {
        echo "Please input correct userID and password or verify your account!";
    }
    return;
}

function registercheck() {
    global $data;
    global $con;
    $userID=$data["userid"];
    $password=$data['password'];
    $email=$data['email'];
    $contact=$data['contact'];
    
    $query="SELECT * FROM USER_INFO WHERE username=:id";
    $stmt = oci_parse($con, $query);
    oci_bind_by_name($stmt, ":id",$userID);
    oci_execute($stmt);
    if( oci_num_rows($stmt)!=0){
        echo 'Existed userID. Please Try another!';
        return;
    }
   // echo $email;
    
    $query="SELECT * FROM USER_INFO WHERE EMAIL=:email AND verified=0";
    $stmt = oci_parse($con, $query);
    oci_bind_by_name($stmt, ":email",$email);
    oci_execute($stmt);
    if(oci_num_rows($stmt)!=0){
        echo 'Email already used. Please Try another!';
        return;
    }
    $code=create_guid();
    $message="Please verify the email and activate your account by click the link 10.227.162.163/SecondCarWeb/part1/verified.php?code=".$code;
    sendmail($email,$message);
    global $checkmail;
    if(!$checkmail){
        reutrn;
    }
    $query="INSERT INTO USER_INFO VALUES ((SELECT MAX(USERID)+1 FROM USER_INFO),:email,:id,:pw,:contact,null,null,null,0,:code,0,'')";
    $stmt = oci_parse($con, $query);
    oci_bind_by_name($stmt, ":code",$code);
    oci_bind_by_name($stmt, ":pw",$password);
    oci_bind_by_name($stmt, ":contact",$contact);
    oci_bind_by_name($stmt, ":id",$userID);
    oci_bind_by_name($stmt, ":email",$email);
    oci_execute($stmt);
 
    $stmt=oci_parse($con, 'commit');
    oci_execute($stmt);
 
    
    echo "success!";
}


?>