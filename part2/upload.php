<?php
$allowedExts=array("txt");
$temp = explode(".", $_FILES["file"]["name"]);
//echo $_FILES["file"]["size"];

$extension = end($temp); 
if ($_FILES["file"]["type"] == "text/plain" && in_array($extension, $allowedExts))
{
    if ($_FILES["file"]["error"] > 0)
    {
        echo "Error: " . $_FILES["file"]["error"] . "<br>";
    }
    else
    {
        $myfile = fopen($_FILES['file']['tmp_name'], "r") or die("Unable to open file!");
        $n=intval(fgets($myfile));
        $userid=$_REQUEST['userID'];
        $communityid=$_REQUEST['CommunityID'];
        $formatname=$_REQUEST['formatname'];
        $con=mysqli_connect("127.0.0.1","root","123456789","medical_database");
        $query="SELECT * FROM Communityowner WHERE CommunityID=?";
        $stmt=$con->prepare($query);
        $stmt->bind_param('s', $communityid);
        $stmt->execute();
        $result=$stmt->get_result();
        $r = mysqli_fetch_array($result);
        $cid=$r['CID'];
        $table="formattable".$cid;
        $query="SELECT type,noise FROM $table WHERE Formatname=?";
        $stmt=$con->prepare($query);
        $stmt->bind_param('s', $formatname);
        $stmt->execute();
        $result=$stmt->get_result();
        $r = mysqli_fetch_array($result);
        $json=json_decode($r['type'],true);
        $leng=sizeof($json)/2;
        $noise=$r['noise'];
        for($i=0;$i<$n;$i++){
            $str=fgets($myfile);
            $arr=array();
            $arr=explode(" ",$str);
            if(sizeof($arr)!=($leng+$noise+1)){
                echo "length error!";
                return;
            }
            $rt='[';
            $len=sizeof($arr);
            $len1=strlen($arr[$len-1]);
            if($arr[$len-1][$len1-2]<' ') $arr[$len-1]=substr($arr[$len-1],0,$len1-2);
            else
                if($arr[$len-1][$len1-1]<' ') $arr[$len-1]=substr($arr[$len-1],0,$len1-1);
           // echo($arr[$len-1]);
            for($j=0;$j<$leng;$j++){
                if(!checktype($json[$j*2+1],$arr[$j+1]))
                {
                    echo "type error!";
                    return;
                }
            }
            for($j=$leng;$j<sizeof($arr)-1;$j++){
                if(!checktype('Mask(real)',$arr[$j+1]))
                {
                    echo "type error!";
                    return;
                }
            }
        }
        fclose($myfile);
        $myfile = fopen($_FILES['file']['tmp_name'], "r") or die("Unable to open file!");
        $n=intval(fgets($myfile));
        for($i=0;$i<$n;$i++){
            $str=fgets($myfile);
            $arr=array();
            $arr=explode(" ",$str);
            $rt='{';
            $rt1='{';
            $len=sizeof($arr);
            $len1=strlen($arr[$len-1]);
            if($arr[$len-1][$len1-2]<' ') $arr[$len-1]=substr($arr[$len-1],0,$len1-2);
            else
                if($arr[$len-1][$len1-1]<' ') $arr[$len-1]=substr($arr[$len-1],0,$len1-1);
            for($j=0;$j<$leng;$j++){
                if($json[$j*2+1]!='Mask(real)'){
                   // echo $json[$j];
                    if($rt!='{') $rt=$rt.',';
                    $rt=$rt.'"'.$json[$j*2].'":"'.$arr[$j+1].'"';
                }
                else
                {
                    if($rt1!='{') $rt1=$rt1.',';
                    $rt1=$rt1.'"'.$json[$j*2].'":"'.$arr[$j+1].'"';
                }
            }
            $rt2='[';
            for($j=$leng;$j<sizeof($arr)-1;$j++){
                if($rt2!='[') $rt2=$rt2.',';
                $rt2=$rt2.'{"value":"'.$arr[$j+1].'"}';           
            }
            $rt=$rt.'}';
            $rt1=$rt1.'}';
            $rt2=$rt2.']';
            $ssr="datatable".$cid;
            $query="DELETE FROM $ssr WHERE dataID=? AND userID=?";
            $stmt=$con->prepare($query);
            $stmt->bind_param('ss', $arr[0], $userid);
            $stmt->execute();
            $query="INSERT INTO $ssr VALUES (0,?,?,?,?,?,?)";
            $stmt=$con->prepare($query);
            $stmt->bind_param('ssssss',$formatname, $arr[0], $userid, $rt, $rt1,$rt2);
            $stmt->execute();
          //  echo $query;
        }
        mysqli_close($con);
        fclose($myfile);
        echo "success!";
    //    echo "Filename: " . $_FILES["file"]["name"] . "<br>";
     //   echo "Filetype: " . $_FILES["file"]["type"] . "<br>";
     //   echo "Filesize: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
     //   echo "Filelocation: " . $_FILES["file"]["tmp_name"] . "<br>";
       // if (file_exists("upload/" . $_FILES["file"]["name"]))
       // {
       //     echo $_FILES["file"]["name"] . " ";
       // }
      //  else
      //  {
            //move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $_FILES["file"]["name"]);
      //      echo "location: " . "upload/" . $_FILES["file"]["name"];
      //  }
    }
}
else
{
    echo "Invalid File";
}


function checktype($ty,$st)
{
    if($ty=='text') return true;
    if($ty=='Mask(real)')
    {
        if(is_numeric($st)) return true;
        else 
            return false;
    }
    if($ty=='0/1')
    {
        if($st=='0'||$st=='1') return true;
        else 
            return false;
    }
    if($ty=='integer')
    {
        if(!is_numeric($st)) return false;
        if(strripos($st,'.')===false) return true;
        else 
            return true;
    }
}
   
?>