<?php
include("../core.php");
include("../db.php");
//Correct action
$cont = qa($_POST['content']);
if(isset($_POST['taged'])){
	$taged = qa($_POST['taged']);
}
else{
	$taged = '';
}	
$query = mysql_query("SELECT * FROM wh_tag ORDER BY count DESC LIMIT 10");
$array = array();
$count = 0;
while($row = mysql_fetch_array($query,MYSQL_ASSOC)){
	if(strpos($taged,$row['tag'])!==false){
	$count++;
	}
	elseif(strpos($cont,$row['tag'])!==false){
		echo '<span wd="addtag">'.$row['tag'].'</span>';
		$count++;
	}
	else{
		array_push($array,$row);
	}
}
foreach($array as $tag){
	if(strpos($taged,$tag['tag'])!==false){
	}
	else{
		echo '<span wd="addtag">'.$tag['tag'].'</span>';
	}
	$count++;
	if($count>=10){
		break;
	}
}
?>