<?php
//var_dump($_FILES);
//print_r($_FILES);
$file = $_FILES["file"];
if ($file["error"] == 0) {
    $typeArr = explode("/", $file["type"]);
    if($typeArr[0]== "image"){
        $imgType = array("png");
        if(in_array($typeArr[1], $imgType)){ 
            $imgname='';
            if($_REQUEST['type']=='user')
                $imgname = "../part1/img/userimg/".$_REQUEST['userid'].".png";
            else
                if($_REQUEST['type']=='car')
                    $imgname = "../part1/img/carimg/".$_REQUEST['carid'].".png";
                    else {
                        echo "failed";
                        return;
                    }
            @unlink($imgname);
            $bol = move_uploaded_file($file["tmp_name"], $imgname);
            if($bol){
                echo "success！";
            } else {
                echo "fail！";
            }
        }
    } else {
        echo "no picture";
    }
} else {
    echo $file["error"];
}
   
?>