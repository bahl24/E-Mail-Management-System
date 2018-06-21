<html>
<head>
<title>Password ageing information </title>
</head>
<body>
<h2>Enter username for account information</h2>
<form method="POST" action=" <?php echo $_SERVER['PHP_SELF']; ?>">
	Enter user:-<input type="text" name="user"></br>
	<input type="submit" name="submit" value="Get Info">
</form>
<?php
$user = $_POST['user'];
echo nl2br("$user\n");
$results="";
if(!empty($user))
{

$res = exec("eval '{ 
        sleep 1;
        echo nitish; 
        sleep 0.2; 
        echo nitish@123; 
        sleep 0.4; 
        echo sudo -s;
        sleep 0.2;
        echo nitish@123;
        sleep 0.2; 
        echo chage -l $user;
        sleep 0.2; 
        exit; 
        exit; }' | telnet 10.1.100.90", $results, $return);
//print_r($results);        
if(strcmp($results[12],"[root@localhost nitish]#")!=0){
for($i=11;$i<18;$i++)
	echo nl2br("$results[$i]\n");
}
else
	echo "No such user exists.";
}
?>
</body>
</html>
