<?php
require('zldap.php');
$zldap = new zlap;
//$ic=$_REQUEST['ic'];
//$ic="5694";
//$igemail="mohitkumar@igcar.gov.in";
//$filter='(uid='.$ic.')';
//$filter='(uid=10211)';

$conn=mysqli_connect('localhost','root','root@123','mydb') or die ("conn err");
$command = exec("awk '{print $2}' iptab.txt", $userid);

for($i=0;$i< sizeof($userid);$i++)
{


$igemail=$userid[$i]."@igcar.gov.in";


$justthese = array("cn", "idesig", "gidnumber", "intercom1", "uidnumber");
   $info = $zldap->searchnew("igemail=".$igemail,$dn="ou=People",$required=$justthese,$sortby=false);
   $lab=$zldap->getOrgLabel($info[0]["gidnumber"][0]);
   $x = explode("/", $lab);
   $unit = $x[0];
$infosec=$zldap->searchnew("gidnumber=".$info[0]["gidnumber"][0],$dn="ou=Group",$required=array("itype", "cn", "iparnt", "ihead"));
if($infosec[0]["itype"][0] == 3) {
	$sec = $infosec[0]["cn"][0];
	$parnt = $infosec[0]["iparnt"][0];
	$infodv=$zldap->searchnew("gidnumber=".$parnt,$dn="ou=Group",$required=array("itype", "cn", "iparnt", "ihead"));
	if($infodv[0]["itype"][0] == 6 || $infodv[0]["itype"][0] == 8) {
		$dv = $infodv[0]["cn"][0];
		$parnt = $infodv[0]["iparnt"][0];
		$headic = $infodv[0]["ihead"][0];
	}
	$infogrp=$zldap->searchnew("gidnumber=".$parnt,$dn="ou=Group",$required=array("itype", "cn", "iparnt"));
	if($infogrp[0]["itype"][0] == 10) {
		$grp = $infogrp[0]["cn"][0];
	}
	elseif($infogrp[0]["itype"][0] == 8) {
		$parnt = $infogrp[0]["iparnt"][0];
		$infogrp=$zldap->searchnew("gidnumber=".$parnt,$dn="ou=Group",$required=array("itype", "cn", "iparnt"));
		$grp = $infogrp[0]["cn"][0];
	}
}
elseif($infosec[0]["itype"][0] == 6) {
	$sec = "NIL";
	$dv = $infosec[0]["cn"][0];
	$parnt = $infosec[0]["iparnt"][0];
	$headic = $infosec[0]["ihead"][0];
	$infogrp=$zldap->searchnew("gidnumber=".$parnt,$dn="ou=Group",$required=array("itype", "cn", "iparnt"));
	if($infogrp[0]["itype"][0] == 10) {
		$grp = $infogrp[0]["cn"][0];
	}
	elseif($infogrp[0]["itype"][0] == 8) {
		$parnt = $infogrp[0]["iparnt"][0];
		$infogrp=$zldap->searchnew("gidnumber=".$parnt,$dn="ou=Group",$required=array("itype", "cn", "iparnt"));
		$grp = $infogrp[0]["cn"][0];
	}
}
elseif($infosec[0]["itype"][0] == 8) {
	$sec = "NIL";
	$dv = $infosec[0]["cn"][0];
	$parnt = $infosec[0]["iparnt"][0];
	$headic = $infosec[0]["ihead"][0];
	$infogrp=$zldap->searchnew("gidnumber=".$parnt,$dn="ou=Group",$required=array("itype", "cn", "iparnt"));
	$grp = $infogrp[0]["cn"][0];
}
elseif($infosec[0]["itype"][0] == 10) {
	$sec = "NIL";
	$dv = "NIL";
	$headic = $infosec[0]["ihead"][0];
	if($infosec[0]["cn"][0] == "IGCAR") {
		$grp = "NIL";
	}
	else {
		$grp = $infosec[0]["cn"][0];
	}
}

   
   
   if($grp == "ACCDCA") {
   	$grp = "ACCOUNTS";   
   }
   if($grp == "ADMIN") {
   	$grp = "ADMINISTRATION";   
   }
   $headname = $zldap->searchnew("uidnumber=".$headic,$dn="ou=People",$required=array("cn"),$sortby=false);
   $name=str_replace(".", " ", $info[0]["cn"][0]);
   $name=str_replace("  ", " ", $name);
   $headname=str_replace(".", " ", $headname[0]["cn"][0]);
   $headname=str_replace("  ", " ", $headname);
   $des=str_replace(".", " ", $info[0]["idesig"][0]);
   $des=strtoupper(str_replace("  ", " ", $des));
   $grp=strtoupper($grp);
	/*echo $name.','.$des.','.$unit.','.$grp.','.$dv.','.$sec.','.$info[0]["intercom1"][0].
	','.$info[0]["uidnumber"][0]."\n";*/
	//print_r(sizeof($heads));
	//adding into database
	$intercom=$info[0]["intercom1"][0];
	$uidnumber=$info[0]["uidnumber"][0];
	$query2=" INSERT INTO ip_add (name,des,grp,dv,sec,phone,icno,emailid) VALUES ('$name','$des','$grp','$dv','$sec','$intercom','$uidnumber','$igemail') ";
	$res2 = mysqli_query($conn,$query2);
	
}	
?>
