<?php
/*/	$json =file_get_contents( "php://input" );
	$data=json_decode($json,true);
	$rownum=9;
	$columnnum=5;
	$con=mysqli_connect("127.0.0.1","root","455732257mcy","medical_database");
        // Check connection
        if (mysqli_connect_errno())
        {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }

	$groupID=$data["0"];
	$votesuccess=0;

	$selectquery="SELECT * FROM data WHERE id='$groupID'";
	$selectrecords = array();
	if ($selectresult = mysqli_query($con, $selectquery)) {
	    if(mysqli_num_rows($selectresult)==1){		
		while($sr = mysqli_fetch_assoc($selectresult))
		{
		    $selectrecords[] = $sr;
		}	
	    }
	    else{
		$message = "Something Wrong";
		//echo "<script type='text/javascript'>alert('$message');</script>";
	
	    }
            mysqli_free_result($result);			
	}
	else{
		//create row
	}
	$XaB=array();
	for($i=1;$i<=$rownum; $i++){
	    $XaB_row=array();	
            for($j=1; $j<=$columnnum; $j++){
                $index=($i-1)*$columnnum+$j;
                $formmer=$selectrecords[0]["v$i$j"]+0;
                $addnum=$data["$index"]+0;		
                $encrypted_data=$formmer+$addnum;
                $XaB_row[]=$encrypted_data;
                $query="UPDATE data SET v$i$j='$encrypted_data' WHERE id='$groupID'";//$query="SELECT * FROM data WHERE id=1";
                if ($result = mysqli_query($con, $query)) {
                    $votesuccess=1;
                }
                else{	
                    break;
                }
                mysqli_free_result($result);
            }
            $XaB[]=$XaB_row;
	}
	if($votesuccess==1){
		echo "DONE"."<hr>";
	}
        mysqli_close;
	$matrixA=array();
	$matrixA[]=array(0.5877,0.5864,0.1526,-0.2059,0.1468,-0.2190,-0.2985,-0.0410,0.2910);
	$matrixA[]=array(-0.3121,0.3284,-0.1570,-0.1780,0.5286,-0.0909,0.6281,0.0163,0.2364);
	$matrixA[]=array(0.0404,0.1271,-0.2012,0.3955,0.5935,0.3222,-0.3335,0.3571,-0.3010);
	$matrixA[]=array(0.2139,0.1464,0.5409,0.3596,0.0974,0.1176,0.3653,-0.4371,-0.4039);
	$matrixA[]=array(-0.3916,0.2642,0.6419,-0.1421,-0.1545,0.3136,-0.0892,0.4504,0.1074);
	$matrixA[]=array(0.3651,-0.3184,0.0042,-0.2805,0.1367,0.7321,0.1431,-0.1007,0.3184);
	$matrixA[]=array(-0.0848,0.2887,-0.2090,0.6512,-0.3192,0.2253,0.0472,-0.1211,0.5218);
	$matrixA[]=array(0.4440,0.0773,-0.1482,0.0636,-0.3248,-0.0263,0.4885,0.6224,-0.1965);
	$matrixA[]=array(0.1374,-0.5002,0.3758,0.3366,0.2955,-0.3746,0.0491,0.2537,0.4265);

	$matrixB=array();
	$matrixB[]=array('-0.0417', ' 0.8559', '0.4556', '-0.0309', '-0.2389');
	$matrixB[]=array('-0.2300', '-0.1341', '0.3547', '0.8880', '0.1213');
	$matrixB[]=array('-0.0966', '0.2334', '0.0450', '-0.1384', '0.9566');
	$matrixB[]=array('0.6893', '-0.2890', '0.6442', '-0.1348', '0.0903');
	$matrixB[]=array('0.6789', '0.3338', '-0.4995', '0.4161', '0.0708');/*/

    $matrixC=array();
    $matrixC[]=array('1', '-1', '1' );
    $matrixC[]=array('-1', '1', '1' );
    $matrixC[]=array('0', '1', '1' );
    $org=gs($matrixC,3,3);
    echoMatrix(3, 3, $org);
    
  /*/  echo "C^-1"."<br>";
    $detC=det($matrixC,4,4,-1,-1);
    echo $detC;
    echo "<br>";
    $inv=matrixinverse(4,4,$matrixC);
    echoMatrix(4,4,$inv);
    echo "<hr>";
	echo "XaB , 9 by 5"."<br>";
	echoMatrix($rownum,$columnnum,$XaB);
	echo "<hr>";
	columnsum($rownum,$columnnum, $XaB);
	echo "<hr>";

	$AXaB=matrixMultiply($rownum,$rownum,$columnnum,$matrixA,$XaB);
	
	echo "AXaB , 9 by 5"."<br>";
	echoMatrix($rownum,$columnnum,$AXaB);
	echo "<hr>";
	columnsum($rownum,$columnnum, $AXaB);
	echo "<hr>";	

	$AXa=matrixMultiply($rownum,$columnnum,$columnnum,$AXaB,transpose($columnnum, $matrixB));
	//$AXa=matrixMultiply($rownum,$rownum,$rownum,$matrixA,transpose($rownum, $matrixA));
	echo "AXa , 9 by 5"."<br>";
	echoMatrix($rownum,$columnnum,$AXa);
	//echoMatrix($rownum,$rownum,$AXa);
	echo "<hr>";
	columnsum($rownum,$columnnum, $AXa);
	//columnsum($rownum,$rownum, $AXa);
	echo "<hr>";/*/
	
	
	function gs($x,$row,$column){
	    $z = array();
	    for($i=0; $i<$row; $i++){
	        $t = array();
	        for($j=0; $j<$column; $j++){
	            $t[] = $x[$i][$j];
	            }
	        $z[] = $t;
	    }
	    $t=array();
	    for($j=0;$j<$column;$j++)
	        $t[]=0;
	    $tt=array();
	    for($j=0;$j<$row;$j++)
	        $tt[]=0;
	    for($j=0;$j<$column;$j++){	
	        for($i=0;$i<$row;$i++)
	            $tt[$i]=0;
	        for($l=0;$l<$j;$l++){
	            for($s=0,$i=0;$i<$row;$i++){
	                $s+=$z[$i][$l]*$z[$i][$j];
	            }
	            $r=$s/$t[$l];
	            for($i=0;$i<$row;$i++){
	                $tt[$i]+=$r*$z[$i][$l];
	            }
	        }
	        for($l=0;$l<$row;$l++){
	            $z[$l][$j]-=$tt[$l];
	            $t[$j]+=$z[$l][$j]*$z[$l][$j];
	        }  
	    }
	    for($j=0;$j<$column;$j++)
	    {
	        $t[$j]=sqrt($t[$j]);
	        for($i=0;$i<$row;$i++){
	            $z[$i][$j]/=$t[$j];
	        } 
	    }
	    return $z;
	}
	function det($x,$row,$column,$m,$n){	 
	    $z = array();
	    for($i=0; $i<$row; $i++)
	        if($m==-1||$i != $m) {
	            $t = array();
	            for($j=0; $j<$column; $j++) 
	            if($n==-1||$j!=$n){
	                $t[] = $x[$i][$j];
	            }
	        $z[] = $t;
	    }
	    $row1=$row;
	    $column1=$column;
	    if($m!=-1)
	    {
	        $row1--;
	        $column1--;        
	    }
	    $p=1.0;
	    for($j=0;$j<$column1;$j++)
	    {
	        if (($z[$j][$j]<0.0000001)&&($z[$j][$j]>-0.0000001)){
	            $l=$j+1;
	            while ($l<$row1&&$z[$l][$j]<0.0000001&&$z[$l][$j]>-0.0000001) {$l++;}
	            if($l==$column1) {return 0.0;}
	            if(($l+$j)%2==1) $op*=-1.0;
	            for($i=0;$i<$column1;$i++)
	            {
	                $o=$z[$l][$i];
	                $z[$l][$i]=$z[$j][$i];
	                $z[$j][$i]=$o;    
	            }            
	        }
	        for($i=0;$i<$row1;$i++){
	            if($i==$j) continue;
	            if($z[$i][$j]<0.0000001&&$z[$i][$j]>-0.0000001) continue;
	            $op=$z[$i][$j]/$z[$j][$j];
	            for($l=$j;$l<$column1;$l++){
	                $z[$i][$l]-=$op*$z[$j][$l];           
	            }
	        }
	    }
	    $detx=$p;
	    for($i=0;$i<$row1;$i++)
	    {
	        $detx*=$z[$i][$i];
	    }
	    return $detx;
	}
	
	
	function matrixInverse($row, $column, $x){
	    $z = array();
	    if ( $row!=$column){echo "ERROR!";return z;}
	    $detx=det($x,$row,$column,-1,-1);
	    if (($detx<0.0000001)&&($detx>-0.0000001)) {echo "No inversement!"; return z;}	    
	    for($i=0; $i<$row; $i++) {
	        $t = array();
	        for($j=0; $j<$column; $j++) {
	            $t[] = 0;
	        }
	        $z[] = $t;
	        $z[$i][$i]=1;
	    }
	    $r = array();
	    for($i=0; $i<$row; $i++) {
	        $t = array();
	        for($j=0; $j<$column; $j++) {
	            $t[] = $x[$i][$j];
	        }
	        $r[] = $t;
	    }    
	    for($j=0;$j<$column;$j++)
	    {
	        if (($r[$j][$j]<0.0000001)&&($r[$j][$j]>-0.0000001)){
	            $l=$j+1;
	            while ($l<$row&&$r[$l][$j]<0.0000001&&$r[$l][$j]>-0.0000001) {$l++;}
	            for($i=0;$i<$column;$i++)
	            {
	                $o=$z[$l][$i];
	                $z[$l][$i]=$z[$j][$i];
	                $z[$j][$i]=$o;
	                $o=$r[$l][$i];
	                $r[$l][$i]=$r[$j][$i];
	                $r[$j][$i]=$o;
	            }
	        }
	        $op=1.0/$r[$j][$j];
	        for($i=0;$i<$column;$i++){
	            $r[$j][$i]*=$op;
	            $z[$j][$i]*=$op;
	        }
	        for($i=0;$i<$row;$i++){
	            if($i==$j) continue;
	            if($r[$i][$j]<0.0000001&&$r[$i][$j]>-0.0000001) continue;
	            $op=$r[$i][$j]/$r[$j][$j];
	            for($l=$j;$l<$column;$l++){
	                $r[$i][$l]-=$op*$r[$j][$l];
	            }
	            for($l=0;$l<$column;$l++){
	                $z[$i][$l]-=$op*$z[$j][$l];
	            }
	        }
	    }
	    return $z;
	}    

	function matrixMultiply($row1,$row2,$column2, $p, $q) {
	    //init result
	    $r = array();
	    for($i=0; $i<$row1; $i++) {
		$t = array();
		for($j=0; $j<$column2; $j++) {
		    $t[] = 0;            
		}
		$r[] = $t;        
	    }
	    
	    //do the matrix multiply
	    for($i=0; $i<$row1; $i++) {
	        for($k=0; $k<$row2; $k++){
	            for($j=0; $j<$column2; $j++)
	            {
		        $r[$i][$j] += $p[$i][$k] * $q[$k][$j];  
	            }            
	        }
	    }   
	    //return result
	    return $r;
	}

	function echoMatrix($row,$column, $r) {
	    for($i=0; $i<$row; $i++) {
		for($j=0; $j<=$column; $j++) {
		    if ($j==$column)
		        echo "<br>";
		    else
		    {
		        $format_num = sprintf("%10.4f",$r[$i][$j]);
		        echo $format_num;
		    }
		    if ($j<($column-1))
		        echo "  ,  ";            
		}   
	    }    
	}

	function transpose($N, $rt) {
	    $r=array();
	    for($i=0; $i<$N; $i++) {
		$t = array();
		for($j=0; $j<$N; $j++) {
		    $t[] = 0;            
		}
		$r[] = $t;        
	    }


	    for($i=0; $i<$N; $i++) {
		for($j=0; $j<=$N; $j++) {
			 $r[$i][$j] = $rt[$j][$i];         
		}   
	    } 
	    return $r;
	}

	function columnsum($row,$column, $r) {
	    for($j=0; $j<$column; $j++) {
		$sum=0;
		for($i=0; $i<$row; $i++){
			$sum+=$r[$i][$j];          
		}
		echo $sum;
		if ($j<($column-1))
			echo "  ,  ";     
	    } 
	    echo "<br>";
	}
	
?>
