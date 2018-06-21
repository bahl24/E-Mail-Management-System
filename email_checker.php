<?php
$name="";
$icno="";
if(isset($_POST['name']) && $_POST['name']!="")
	$name=$_POST['name'];
if(isset($_POST['icno']) && $_POST['icno']!="")
	$icno=$_POST['icno'];
?>
<html>
	<head><title>Get an email id</title></head>
	<body>
		<h2>Create your E-Mail account</h2>
		<h4>Enter four preferences for username</h4>
		
		<form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
			Enter name:-<input type="text" name="name" value="<?php echo $name; ?>"><br/>
			Enter icno:-<input type="text" name="icno" value="<?php echo $icno; ?>"><br/>
			
			Enter id1:-<input type="text" name="id1" ><br/>
			Enter id2:-<input type="text" name="id2" ><br/>
			Enter id3:-<input type="text" name="id3" ><br/>
			Enter id4:-<input type="text" name="id4" ><br/>
			
			<input type="submit" name="submit" value="check">
		</form>
<?php
			
			$id=array("","","","");
			$id[0]=$_POST["id1"];
			$id[1]=$_POST["id2"];
			$id[2]=$_POST["id3"];
			$id[3]=$_POST["id4"];
		$dbc=mysqli_connect('localhost','root','root@123','proxy_email_app') 			or die ("conn err");
			$query = "SELECT * FROM email";
			echo mysqli_error($dbc);
			$res = mysqli_query($dbc,$query);
			$arr=array("1","1","1","1");
			while($row = mysqli_fetch_array($res)){
				for($i=0;$i<4;$i++){
					if($row['emailid']==$id[$i])
						$arr[$i]=0;
						}
					}
			$temp=1;
			for($i=0;$i<4;$i++){
				if(($arr[$i]==1)&&!empty($id[0])){
					echo "Congratulations! Your email id $id[$i] has been 								successfully created";
					$temp=0;
					$query2=" INSERT INTO email VALUES 									('$name','$icno','$id[$i]')";
					$res2 = mysqli_query($dbc,$query2);
					break;
					}
					}			
			if($temp&&!empty($id[0]))
				echo "Sorry. None of the ids are available, please try 							again.";
			mysqli_close($dbc);			
			
?>
		<a href="email_checker.php"><br/>new query</a>	
</html>		
