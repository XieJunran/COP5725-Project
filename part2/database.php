<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
@ $json =file_get_contents( "php://input" );
@ $data=json_decode($json,true);

$checkmail=true;



switch($data["action"])
{
    case "deleteit":deleteit();break;
    case "acceptit":acceptit();break;
    case "searchcommunity":searchcommunity();break;
    case "joincommunity":joincommunity();break;
    case "createcommunity":createcommunity();break;
    case "logincheck":logincheck(); break;
    case "registercheck":registercheck();break;
    case "searchdata":searchdata();break;
    case "getdataformat":getdataformat();break;
    case "deletedata":deletedata();break;
    case "checkrequest":checkrequest();break;
    case "changepassword":changepassword();break;
    case "forgetpassword":forgetpassword();break;
    case "adddataformat":adddataformat();break;
    case "deleteformat":deleteformat();break;
    case "sharedata":sharedata();break;
    case "searchchoose":searchchoose();break;
    case "deletesharing":deletesharing();break;
    default:break;
}

function deletesharing(){
    global $data;
    $dataownerid=$data["dataownerID"];
    $datauserid=$data["datauserID"];
    $cid=$data["CID"];
    $type=$data["type"];
    if($type==1||$type==3){
        $udataid=$data["udataid"];
    }
    if($type==2||$type==3){
        $formatname=$data["formatname"];
    }
    if($type==3){
        $dataid=$data["dataid"];
    }
    $table="sharetable".$cid;
    $con=new mysqli("127.0.0.1","root","123456789","medical_database");
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    if($type==0){
        $query="DELETE FROM $table WHERE dataownerID=? AND datauserID=? AND type=0";
        $stmt=$con->prepare($query);
        $stmt->bind_param('ss', $dataownerid,$datauserid);
        $stmt->execute();
    }
    if($type==1){
        $query="DELETE FROM $table WHERE dataownerID=? AND datauserID=? AND type=1 AND udataid=?";
        $stmt=$con->prepare($query);
        $stmt->bind_param('ssi', $dataownerid,$datauserid,$udataid);
        $stmt->execute();
    }
    if($type==2){
        $query="DELETE FROM $table WHERE dataownerID=? AND datauserID=? AND type=2 AND formatname=?";
        $stmt=$con->prepare($query);
        $stmt->bind_param('sss', $dataownerid,$datauserid,$formatname);
        $stmt->execute();
    }
    if($type==3){
        $query="DELETE FROM $table WHERE dataownerID=? AND datauserID=? AND type=3 AND formatname=? AND udataid=? AND dataID=?";
        $stmt=$con->prepare($query);
        $stmt->bind_param('sssii', $dataownerid,$datauserid,$formatname,$udataid,$dataid);
        $stmt->execute();
    }
    echo "success!";
}
function searchchoose(){
    global $data;
    $dataownerid=$data["dataownerid"];
    $datauserid=$data["datauserid"];
    $type=$data["type"];
    $cid=$data["cid"];
    $con=new mysqli("127.0.0.1","root","123456789","medical_database");
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    if($type==0){
        $table="datatable".$cid;
        $table1="sharetable".$cid;
        $query="SELECT * FROM $table1 WHERE dataownerID=? AND datauserID=?";
        $stmt=$con->prepare($query);
        $stmt->bind_param('ss', $dataownerid,$datauserid);
        $stmt->execute();
        $result=$stmt->get_result();
        echo mysqli_num_rows($result);
       return;
    }   
    if($type==1){
        $table="datatable".$cid;
        $table1="sharetable".$cid;
        $query="SELECT DISTINCT udataid FROM $table a WHERE userID=? AND( SELECT COUNT(*) FROM $table1 b WHERE b.udataid=a.udataid AND b.dataownerID=? AND b.datauserID=?)=0";        
        $stmt=$con->prepare($query);
        $stmt->bind_param('sss', $dataownerid,$dataownerid,$datauserid);
        $stmt->execute();
        $result=$stmt->get_result();
        $jso=array();
       // echo mysqli_num_rows($result);
        while ($row = mysqli_fetch_array($result)) {
            $jso[] = $row;
        }
        echo json_encode($jso);
        return;
    }
    if($type==2){
        $table="formattable".$cid;
        $table1="sharetable".$cid;
        $query="SELECT * FROM $table a WHERE ( SELECT COUNT(*) FROM $table1 b WHERE b.Formatname=a.Formatname AND b.dataownerID=? AND b.datauserID=?)=0";        
        $stmt=$con->prepare($query);
        $stmt->bind_param('ss', $dataownerid,$datauserid);
        $stmt->execute();
        $result=$stmt->get_result();
        $jso=array();
        while ($row = mysqli_fetch_array($result)) {
            $jso[] = $row;
        }
        echo json_encode($jso);
        return;
    }
    if($type==3){
        $table="datatable".$cid;
        $table1="sharetable".$cid;
        $query="SELECT * FROM $table a WHERE userID=? AND ( SELECT COUNT(*) FROM $table1 b WHERE b.udataid=a.udataid AND b.dataID=a.dataID AND b.dataownerID=? AND b.datauserID=?)=0";
        $stmt=$con->prepare($query);
        $stmt->bind_param('sss', $dataownerid,$dataownerid,$datauserid);
        $stmt->execute();
        $result=$stmt->get_result();
        $jso=array();
        while ($row = mysqli_fetch_array($result)) {
            $jso[] = $row;
        }
        echo json_encode($jso);
        return;
    }
}

function sharedata(){
    global $data;
    $dataownerid=$data["dataownerid"];
    $datauserid=$data["datauserid"];
    $cid=$data["cid"];
    $type=$data["type"];
    if($type==1||$type==3){
        $udataid=$data["udataid"];
    }
    if($type==2||$type==3){
        $formatname=$data["Formatname"];
        $con=new mysqli("127.0.0.1","root","123456789","medical_database");
        if (mysqli_connect_errno())
        {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }
        $table1="formattable".$cid;
        $query="SELECT * FROM $table1 WHERE Formatname=?";
        $stmt=$con->prepare($query);
        $stmt->bind_param("s", $formatname);
        $stmt->execute();
        $result=$stmt->get_result();
        $re=mysqli_fetch_array($result);
        $fid=$re['FID'];        
    }
    if($type==3){
        $dataid=$data["dataID"];
    }
    $con=new mysqli("127.0.0.1","root","123456789","medical_database");
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    $table="sharetable".$cid;
    if($type==0){
        $query="INSERT INTO $table VALUES(?,?,?,-1,-1,'',-1)";
        $stmt=$con->prepare($query);
        $stmt->bind_param('ssi', $dataownerid,$datauserid,$type);
        $stmt->execute();
    }
    if($type==1){
        $query="INSERT INTO $table VALUES(?,?,?,?,-1,'',-1)";
        $stmt=$con->prepare($query);
        $stmt->bind_param('ssii', $dataownerid,$datauserid,$type,$udataid);
        $stmt->execute();
    }
    if($type==2){
        $query="INSERT INTO $table VALUES(?,?,?,-1,?,?,-1)";
        $stmt=$con->prepare($query);
        $stmt->bind_param('ssiis', $dataownerid,$datauserid,$type,$fid,$formatname);
        $stmt->execute();
    }
    if($type==3){
        $query="INSERT INTO $table VALUES(?,?,?,?,?,?,?)";
        $stmt=$con->prepare($query);
        $stmt->bind_param('ssiiisi', $dataownerid,$datauserid,$type,$udataid,$fid,$formatname,$dataid);
        $stmt->execute();
    }
    echo "success!";
    
    
}
function forgetpassword(){
    global $data;
    $email=$data["email"];
    $userid=$data["userid"];
    $con=new mysqli("127.0.0.1","root","123456789","medical_database");
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    if ($userid!="" && $email!=""){
        $query="SELECT * FROM user WHERE id=? AND email=?";
        $stmt=$con->prepare($query);
        $stmt->bind_param('ss', $userid,$email);
        $stmt->execute();
        $result=$stmt->get_result();
        if(mysqli_num_rows($result)==0){
            @ mysqli_close($con);
            echo 'userID does not match the email,it will be enough if your choose one of them!';
        return;
        }
    }
    if ($userid!=""){
        $query="SELECT * FROM user WHERE id=? AND verified=true";
        $stmt=$con->prepare($query);
        $stmt->bind_param('s', $userid);
        $stmt->execute();
        $result=$stmt->get_result();
        if(mysqli_num_rows($result)==0){
            @ mysqli_close($con);
            echo 'userID does not exist!';
            return;
        }
    }  
    $query="SELECT * FROM user WHERE email=? AND verified=true";
    $stmt=$con->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result=$stmt->get_result();
    if(mysqli_num_rows($result)==0){
        @ mysqli_close($con);
        echo 'email does not exist!';
        return;
    }
    $code=create_guid();
    $query="UPDATE user SET changepw=true,pcode=? WHERE (email=? OR id=?) AND verified=true";
    $stmt=$con->prepare($query);
    $stmt->bind_param('sss',$code,$email,$userid);
    $stmt->execute();
    mysqli_close($con); 
    $message="Please verify the email and activate your account by click the link 10.227.162.163/medicalshare/part1/changepassword.php?code=".$code;
    sendmail($email,$message);
    echo "email has sent!";
}

function changepassword(){
    global $data;
    $code=$data["code"];
    $password=$data["password"];
    $con=new mysqli("127.0.0.1","root","123456789","medical_database");
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    $query="UPDATE user SET pw=?,changepw=false WHERE (pcode=? AND changepw=true)";
    $stmt=$con->prepare($query);
    $stmt->bind_param('ss',$password,$code);
    $stmt->execute(); 
    mysqli_close($con); 
    echo "success!";
}

function checkrequest()
{
    global $data;
    $userid=$data["userid"];
    $communityid=$data["communityid"];
    $len=sizeof($communityid);
    $con=new mysqli("127.0.0.1","root","123456789","medical_database");
    for($i=0;$i<$len;$i++){
        $query="SELECT * FROM joinrequest WHERE CommunityID=? AND userID=?";
        $stmt=$con->prepare($query);
        $stmt->bind_param('ss', $communityid[$i],$userid);
        $stmt->execute();
        $result=$stmt->get_result();
        if(mysqli_num_rows($result)==0){
            echo 0;
        }
        else
            echo 1;
    }
    mysqli_close($con); 
}
function deletedata()
{
    global $data;
    $table="datatable".$data["cid"];
    $dataid=$data["dataid"];
    $udataid=$data["udataid"];
    $con=new mysqli("127.0.0.1","root","123456789","medical_database");
    $query="DELETE FROM $table WHERE dataID=? AND udataid=?";
    $stmt=$con->prepare($query);
    $stmt->bind_param('ss', $dataid,$udataid);
    $stmt->execute();
    mysqli_close($con);    
}
function getdataformat()
{
    global $data;
    $cid=$data["cid"];
    $formatname=$data["Formatname"];
    $con=new mysqli("127.0.0.1","root","123456789","medical_database");
    $table="formattable".$cid;
    $query="SELECT * FROM $table WHERE Formatname=?";
    $stmt=$con->prepare($query);
    $stmt->bind_param('s', $formatname);
    $stmt->execute();
    $result=$stmt->get_result();
    $jso=array();
    while ($row = mysqli_fetch_array($result)) {
        $jso[] = $row;
    }
    echo json_encode($jso);
    mysqli_close($con);    
}
function searchdata(){
    global $data;
    $table="datatable".$data["cid"];
    $table1="sharetable".$data["cid"];
    $formatname=$data["Formatname"];
    $datauserid=$data["datauserid"];
    $con=new mysqli("127.0.0.1","root","123456789","medical_database");
    $flag=0;
    $query="SELECT * FROM $table a WHERE 1=1 AND ";
    $query=$query."(SELECT COUNT(*) FROM $table1 b WHERE a.userID=b.dataownerID AND b.datauserID='$datauserid' AND(b.type=0 OR b.Formatname=a.Formatname OR (a.udataid=b.udataid AND (b.type=1 OR a.dataID=b.dataID))))>0 ";
    if($formatname!=""){
        $query=$query." AND Formatname='".$formatname."'";
    }
   foreach($data as $key=>$value) {
       if(($key!="cid"&& $key!="action"&& $key!="Formatname" && $key!="datauserid")||$flag==4)
        {
            $data1=$value;
            $len=sizeof($data1)/2;
            $ssr=" AND (";
            if($key=="dataID(System)")
            {
                for($i=0;$i<$len;$i++){
                    if($data1[$i*2]=="equal"){
                        $ssr=$ssr."dataID=";
                        $ssr=$ssr.'"'.$data1[$i*2+1].'"';
                    }
                    else{
                        $ssr=$ssr."dataID LIKE";
                        $ssr=$ssr.'"%'.$data1[$i*2+1].'%"';
                    }
                    if($i != $len-1) $ssr=$ssr.' OR ';
                }
            }
            else
            if($key=="udataid(System)")
            {
                for($i=0;$i<$len;$i++){
                    if($data1[$i*2]=="equal"){
                        $ssr=$ssr."udataid=";
                        $ssr=$ssr.'"'.$data1[$i*2+1].'"';
                    }
                    else{
                        $ssr=$ssr."udataid LIKE";
                        $ssr=$ssr.'"%'.$data1[$i*2+1].'%"';
                    }
                    if($i != $len-1) $ssr=$ssr.' OR ';
                }
            }
            else
            if($key=="userID(System)")
            {
                for($i=0;$i<$len;$i++){
                    if($data1[$i*2]=="equal"){
                        $ssr=$ssr."userID=";
                        $ssr=$ssr.'"'.$data1[$i*2+1].'"';
                    }
                    else{
                        $ssr=$ssr."userID LIKE";
                        $ssr=$ssr.'"%'.$data1[$i*2+1].'%"';
                    }
                    if($i != $len-1) $ssr=$ssr.' OR ';
                }          
            }
            else
           {
            
            for($i=0;$i<$len;$i++){
                if($data1[$i*2]=="equal"){
                    $ssr=$ssr."JSON_EXTRACT(unmaskdata,'$.".$key."')=";
                    $ssr=$ssr.'"'.$data1[$i*2+1].'"';
                }
                else{
                    $ssr=$ssr."JSON_EXTRACT(unmaskdata,'$.".$key."') LIKE";
                    $ssr=$ssr.'"%'.$data1[$i*2+1].'%"';
                }
                if($i != $len-1) $ssr=$ssr.' OR ';
            }
           }
           $ssr=$ssr.')';
           $query=$query.$ssr;
        }
        else
            $flag++;
    }

    $result=mysqli_query($con, $query);
    $jso=array();
    while ($row = mysqli_fetch_array($result)) {
        $jso[] = $row;
    }
    echo json_encode($jso);
    mysqli_close($con);    
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
    
    $mail->Subject = "Medical Data Sharing System email verify link";
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

function registercheck() {
    global $data;
    $userID=$data["userid"];
    $password=$data['password'];
    $email=$data['email'];
    $con=new mysqli("127.0.0.1","root","123456789","medical_database");
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    $query="SELECT * FROM user WHERE id=?";
    $stmt=$con->prepare($query);
    $stmt->bind_param('s', $userID);
    $stmt->execute();
    $result=$stmt->get_result();
    if(mysqli_num_rows($result)!=0){
        @ mysqli_close($con);
         echo 'Existed userID. Please Try another!';
         return;
    }
    
    $query="SELECT * FROM user WHERE email=? AND verified=true";
    $stmt=$con->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result=$stmt->get_result();
    if(mysqli_num_rows($result)!=0){
        @ mysqli_close($con);
        echo 'Email already used. Please Try another!';
        return;
    } 
    $code=create_guid();
    $message="Please verify the email and activate your account by click the link 10.227.162.163/medicalshare/part1/verified.php?code=".$code;
    sendmail($email,$message);  
    global $checkmail;
    if(!$checkmail){
        reutrn;
    }
    $query="INSERT INTO user VALUES (?,?,?,false,?,false,'')";
    $stmt=$con->prepare($query);
    $stmt->bind_param('ssss', $userID,$password,$email,$code);
    $stmt->execute();
    mysqli_free_result($result);
   // $subject="Medical Data Sharing System email verify link";
    
  //  echo $code;
     
    echo "success!";
    
    @ mysqli_close($con);
}

function logincheck()
{
    global $data;
    $userID=$data["userid"];
    $password=$data['password'];
    $con=new mysqli("127.0.0.1","root","123456789","medical_database");
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    $query="SELECT * FROM user WHERE id=? and pw=? and verified=true";
    $stmt=$con->prepare($query);
    $stmt->bind_param('ss', $userID,$password);
    $stmt->execute();
    if ($result=$stmt->get_result()) {
        if(mysqli_num_rows($result)==1){
            $records = array();
            while($r = mysqli_fetch_assoc($result))
            {
                $records[] = $r;
            }            
            session_start();
            $json_data=json_encode($records);
            $_SESSION['userID']="";
            $_SESSION['password']="";            
            $_SESSION['json_data']=$json_data;           
            mysqli_free_result($result);
            echo "success!";
            
        }
        else
        {
            echo "Please input correct userID and password or verify your account!";
        }
    }
    @	mysqli_close($con);
    return;
}
function  adddataformat(){
    global $data;
    $formatname=$data["Formatname"];
    $dataformat=$data["type"];
    $noise=$data["noise"];
    $cid=$data["CID"];
    $con=new mysqli("127.0.0.1","root","123456789","medical_database");
    $table="formattable".$cid;
    $query="SELECT * FROM $table WHERE Formatname=?";
    $stmt=$con->prepare($query);
    $stmt->bind_param('s',$formatname);
    $stmt->execute();
    $result=$stmt->get_result();
    if(mysqli_num_rows($result)>0){
        echo "Formatname exists!";
        return;
    }
    $str='[';
    for($i=0;$i<sizeof($dataformat);$i++)
    {
        $str=$str.'"'.$dataformat[$i].'"';
        if($i<sizeof($dataformat)-1) $str=$str.',';
    }
    $str=$str.']';
    mysqli_query($con,'BEGIN');
    $query="SELECT * FROM systemtable WHERE CID=$cid FOR UPDATE";
    $result=mysqli_query($con,$query);
    $re=mysqli_fetch_array($result);
    $query="UPDATE systemtable SET value=value+1 WHERE CID=$cid";
    mysqli_query($con,$query);
    $query="COMMIT";
    mysqli_query($con,$query);
    $query="INSERT INTO $table VALUES (?,?,?,?)";
    $stmt=$con->prepare($query);
    $stmt->bind_param('issi',$re['value'],$formatname,$str,$noise);
    $stmt->execute();
    mysqli_close($con);   
    echo "success!";
}

function deleteformat(){
    global $data;
    $cid=$data["CID"];
    $fid=$data["FID"];
    $table="formattable".$cid;
    $con=new mysqli("127.0.0.1","root","123456789","medical_database");
    $query="DELETE FROM $table WHERE FID=?";
    $stmt=$con->prepare($query);
    $stmt->bind_param('i',$fid);
    $stmt->execute();
    $table="datatable".$cid;
    $query="DELETE FROM $table WHERE FID=?";
    $stmt=$con->prepare($query);
    $stmt->bind_param('i',$fid);
    $stmt->execute();
    mysqli_close($con);
    echo "success!";
    return;
}
function createcommunity()
{
    global $data;
    $userid=$data["userid"];
    $communityid=$data["communityid"];
    $con=new mysqli("127.0.0.1","root","123456789","medical_database");
    $query="SELECT * FROM Communityowner WHERE CommunityID=?";
    $stmt=$con->prepare($query);
    $stmt->bind_param('s',$communityid);
    $stmt->execute();
    $result=$stmt->get_result();
    if(mysqli_num_rows($result)>0){
        echo "CommunityID exists!";
        return;
    }
    mysqli_query($con,'BEGIN');
    $query="SELECT * FROM systemtable WHERE CID=-1 FOR UPDATE";
    $result=mysqli_query($con,$query);
    $re=mysqli_fetch_array($result);
    $query="UPDATE systemtable SET value=value+1 WHERE CID=-1";
    mysqli_query($con,$query);
    $query="COMMIT";
    mysqli_query($con,$query);
    $query="INSERT INTO Communityowner VALUES (?,?,?)";
    $stmt=$con->prepare($query);
    $stmt->bind_param('iss',$re['value'],$communityid,$userid);
    $stmt->execute();
    $query="INSERT INTO Communityuser VALUES (?,?,?)";
    $stmt=$con->prepare($query);
    $stmt->bind_param('iss',$re['value'],$communityid,$userid);
    $stmt->execute();
    $query="INSERT INTO systemtable VALUES (?,0)";
    $stmt=$con->prepare($query);
    $stmt->bind_param('i',$re['value']);
    $stmt->execute();
    $str="datatable".$re['value'];
    $query="CREATE TABLE $str (udataid int,Formatname varchar(200),dataID varchar(200),userID varchar(200),unmaskdata json,maskdata json,noise json)";
    mysqli_query($con,$query);
    $str="formattable".$re['value'];
    $query="CREATE TABLE $str (FID INT,Formatname varchar(200),type json,noise int)";
    mysqli_query($con,$query);
    $str="sharetable".$re['value'];
    $query="CREATE TABLE $str (dataownerID varchar(200),datauserID varchar(200),type INT,udataid INT,FID INT,Formatname varchar(200),dataID INT)";
    mysqli_query($con,$query);
    $query="INSERT INTO $str VALUES (?,?,0,-1,-1,'',-1)";
    $stmt=$con->prepare($query);
    $stmt->bind_param('ss',$userid,$userid);
    $stmt->execute();
    echo "success!";
    mysqli_close($con);
}
function deleteit()
{
    global $data;
    $userid=$data["userid"];
    $communityid=$data["communityid"];
    $con=new mysqli("127.0.0.1","root","123456789","medical_database");
    $query="DELETE FROM Communityuser WHERE CommunityID=? AND userID=?";
    $stmt=$con->prepare($query);
    $stmt->bind_param('ss',$communityid,$userid);
    $stmt->execute();
    $str=$communityid."data";
    $query="DELETE FROM $str WHERE userID=?";
    $stmt=$con->prepare($query);
    $stmt->bind_param('s',$userid);
    $stmt->execute();
    mysqli_close($con);
    return;
}


function acceptit()
{
    global $data;
    $userid=$data["userid"];
    $communityid=$data["communityid"];
    $con=new mysqli("127.0.0.1","root","123456789","medical_database");
    $query="SELECT * FROM Communityowner WHERE CommunityID=?";
    $stmt=$con->prepare($query);
    $stmt->bind_param('s',$communityid);
    $stmt->execute();
    $result=$stmt->get_result();
    $re=mysqli_fetch_array($result);
    $cid=$re['CID'];
    $query="DELETE FROM joinrequest WHERE CommunityID=? AND userID=?";
    $stmt=$con->prepare($query);
    $stmt->bind_param('ss',$communityid,$userid);
    $stmt->execute();
    $query="INSERT INTO Communityuser (CID,CommunityID,userID) VALUES (?,?,?)";
    $stmt=$con->prepare($query);
    $stmt->bind_param('iss',$cid,$communityid,$userid);
    $stmt->execute();
    mysqli_close($con);
    return;
}

function searchcommunity()
{
    global $data;
    $userid=$data["userid"];
    $communityid='%'.$data["communityid"].'%';
    $con=new mysqli("127.0.0.1","root","123456789","medical_database");
    $query="SELECT * FROM Communityowner o WHERE (CommunityID LIKE ?) AND (SELECT count(*) FROM Communityuser u WHERE o.CommunityID=u.CommunityID AND userID=?)=0";
    $stmt=$con->prepare($query);
    $stmt->bind_param('ss',$communityid,$userid);
    $stmt->execute();
    $result=$stmt->get_result();
    //echo($query);
    $json=array();    
    while ($row = mysqli_fetch_assoc($result)) {
        $json[] = $row;
    }
    echo json_encode($json);
    mysqli_close($con);
    return;
}
function joincommunity()
{
    global $data;
    $userid=$data["userid"];
    $communityid=$data["communityid"];
    $con=new mysqli("127.0.0.1","root","123456789","medical_database");
    $query="SELECT * FROM joinrequest WHERE CommunityID=? AND userID=?";
    $stmt=$con->prepare($query);
    $stmt->bind_param('ss',$communityid,$userid);
    $stmt->execute();
    $result=$stmt->get_result();
    if(mysqli_num_rows($result)==0)
    {
        $query="INSERT INTO joinrequest (CommunityID,userID) VALUES ('$communityid','$userid')";
        mysqli_query($con, $query);
        echo $query;
    }
    mysqli_close($con);
    return;
}
?>