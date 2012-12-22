<?php
session_start();
if(isset($_POST['cap'])){
	if($_SESSION['cap']==md5(strtolower($_POST['cap']))){
	echo 'yes';
	}
	else{
	echo 'no';
	}
}
else{
	echo '<img src="capinit.php?'.time().'" />';
?>
<form method="post">
<input type="text" name="cap">
<input type="submit">
</form>
<?php
}
?>