<?php
$name="";
$icno="";
$email="";
if(isset($_POST['name']) && $_POST['name']!="")
	$name=$_POST['name'];
if(isset($_POST['icno']) && $_POST['icno']!="")
	$icno=$_POST['icno'];
if(isset($_POST['email']) && $_POST['email']!="")
	$email=$_POST['email'];
?>
<?php		
		$dbc=mysqli_connect('localhost','root','root@123','proxy_email_app') or die ("conn err");
		$query = "SELECT * FROM email";
		echo mysqli_error($dbc);
		$res = mysqli_query($dbc,$query);
		$temp=0;
		while($row = mysqli_fetch_array($res))
			{
				if($row['emailid']==$email){
					$email="";
					$temp=1;
					break;
					}
			}
		mysqli_close($dbc);		
		?>
<html>
	<head><title>Validating email</title></head>
	<body>
		<h2>Email avilability checker</h2>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
			Enter name:-<input type="text" name="name" value="<?php echo 				$name; ?>"><br/>
			Enter icno:-<input type="text" name="icno" value="<?php echo 				$icno; ?>"><br/>
			Enter email id:-<input type="text" name="email" value="<?php echo 				$email; ?>"><br/>
			<input type="submit" name="submit" value="check">
		</form>
<?php
		if($temp==0&&!empty($email))
			echo "<br/> id is avail";
		elseif(isset($_POST['email']) && $_POST['email']!="")
			echo "<br/> id not available";
?>
	<a href="email_validator.php"><br/>new query</a>		
	</body>		
</html>	
