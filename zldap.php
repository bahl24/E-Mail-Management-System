<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * this is model class
  //
 * @author raju
 */
class zlap {
     // ldap server ip address
	 const LDAP_IP = "10.30.1.18";
	 // ldap port
	 const LDAP_PORT = 389;
	 // const manager 
	 const MAN_DN = "cn=Manager,dc=igcar,dc=com";
	 // const Manager password 
	 const MAN_PD = "root@kms";
	 // const for domain component with ,
	 const DC=",dc=igcar,dc=com";
	 
	//  const for domain component without the ,
	const DC1="dc=igcar,dc=com";
	 
	 //  link resource
	 public $ds = False;
	 
	 // 
	 public $orgdata=array();
	 
	 
	 
	 
	 
	 /*
	 *  constructor function fires when the object is created
	 *  in this function it tries to connect to given ldap server
	 *
	 *  @param 		$ip        ipaddress of the ldap server
	 *  the set option fucntion sets the version of ldap server using 
	 */
	 public function __construct($ip=NULL){		 
		 // getting the ip addresses
		 $ipaddr = $ip == NULL ? self::LDAP_IP : $ip;
		 // 
		 
		 // connecting to ldap server
		 
		 $this->ds = ldap_connect($ipaddr,self::LDAP_PORT) or die ("not connected");
		 ldap_set_option($this->ds, LDAP_OPT_PROTOCOL_VERSION, 3);
		 
	 }//constructor closed 
	 
	 
	 /*
	  *  a bind function has been created and declared
	  *  @param  $username  just the name of the user that needs to binded with ldap server
	  *  @param  $password  password of the respective user
	  *  @param  $flag      temporary variable
	  *  
	  */
	  public function bind($username,$password,$flag=false){
        if($this->ds){
			// get the username passed for the binding of individual users
			$username= $flag==true?self::MAN_DN:"uid=".$username.",ou=People".self::DC;
			// ldap binding is taking place
			return ldap_bind($this->ds,$username,$password);
		}else{
			return false;
		}
	  }//bind function closed
	 
	 
	 /*
	 *  create a function to add an user to the ldap server
	 *  @param      $userdata  comprises the complete information about the user 
	 *  @param      $username  the name of the user that has to be added to ldap server
	 *  
	 *
	 *
	 */
	 public function addldapuser($userdata,$username){
		
		// check whether connection is avalilable or not and successfully binding with manager
	    if($this->ds&&$this->bind(self::MAN_DN,self::MAN_PD,true)){
					return ldap_add($this->ds,$username.self::DC,$userdata);
			}
		else{
			return "Not connected";
		}
		 
		 
	 }//addldapuser function closed
	 
	 
	 /*
	  *create and declare a function nameed replace to modify any user details in the server
	  *@param  $userdata    comprises the complete information about the user  
	  *@param  $username    the name of the user specified to modify any information of the respective user
	  */
	   public function replace($userdata,$username){
		
		// check whether connection is avalilable or not and successfully binding with manager
	    if($this->ds&&$this->bind(self::MAN_DN,self::MAN_PD,true)){
					return ldap_mod_replace($this->ds,$username.self::DC,$userdata);
			}
		else{
			return "Not connected";
		}
		 
		 
	 }//replace function closed
	 
	 
	 /*
	  *create and declare a function to execute delete operation in ldap server 
	  *@param  $username   specify the name of the user that has to be deleted from the ldap server
	  *
	  */
	   public function delete($username){
		//echo $username;
		// check whether connection is avalilable or not and successfully binding with manager
	    if($this->ds&&$this->bind(self::MAN_DN,self::MAN_PD,true)){
					return ldap_delete($this->ds,$username.self::DC);
			}
		else{
			return "Not connected";
		}
		 
		 
	 }//delete function closed
	 
	 
	 /*
	  *create and declare a function named search 
	  *@param  $query  give the necessary query to search the ldap server with the parameter passed 
	  *
	  */
	   public function search($query){
		
		// check whether connection is avalilable or not and successfully binding with manager
	    if($this->ds&&$this->bind(self::MAN_DN,self::MAN_PD,true)){
			$result=ldap_search($this->ds,self::DC1,"(|(cn=*$query*)(uid=*$query*))") or die ("error searching!!");
			$data=ldap_get_entries($this->ds,$result);
			return $data;
		}
		else{
			return "Not connected";
		}
	   }
           /**
            * 
            * @param type $query
            * @param type $dn
            * @return string
            */
	public function searchexact($query,$dn="ou=People"){
			$dnn = $dn.",".self::DC1;
		// check whether connection is avalilable or not and successfully binding with manager
	    if($this->ds&&$this->bind(self::MAN_DN,self::MAN_PD,true)){
			$result=ldap_search($this->ds,$dnn,$query) or die ("error searching!!");
			$data=ldap_get_entries($this->ds,$result);
			return $data;
		}
		else{
			return "Not connected";
		}
		 
	 }
	 
	 /*
	 *
	 *
	 */
	 public function getAuthors($term){
		 
			$query="(|(gidnumber=$term)(cn=*$term*))";
			
			$data=$this->searchnew($query);
			
			$out = array();
		 
		 // looping over the result 
		 foreach($data as $key=>$db){
			 if($key!=="count"){
			 // dummy object
			 $dbdata = new stdClass();
			 // employee name
			 $dbdata->text = isset($db["cn"]) ? $db["cn"][0] : " ";
			  // employee icnumber
			 $dbdata->id = isset($db["uidnumber"]) ? $db["uidnumber"][0] : " ";
			 
			 // placing the object in dummy array	
			 $out[] = $dbdata;
			 }
		 }
         return $out;
		
		}
		
	/*
	*  searching ldap with appropriate DN and tree node
	*
	*  @param		$query  = search string 
	*  @param	    $dn     = null
	*  @param		$required = required elements specified 
	*  @param		$sortby    = sorting the elements of ldap
	*/
	public function searchnew($query,$dn="ou=People",$required=array(),$sortby=false){
		
		if($this->ds&&$this->bind(self::MAN_DN,self::MAN_PD,true)){
			// dn bulidng
			$dnn = $dn.",".self::DC1;
			if(sizeof($required) > 0 ){
				 // if required parameters are specified
				$result=ldap_search($this->ds,$dnn,$query,$required);
			}else{
				//
				$result=ldap_search($this->ds,$dnn,$query);
			}
			//  sorting the result
		    if($sortby!==false) {ldap_sort($this->ds, $result, $sortby); }
			// getting the entities 
			$data=ldap_get_entries($this->ds,$result);
			// return the data
			return $data;			
		}
		else{
			return "Not connected";
		}
		
	}
        
        /**
         * 
         * 
         */
        public function getOrgLabel($gid){
            // prepare the query
            $query="(|(gidnumber=$gid)(cn=$gid))";
            // searching the ladap
            $data=$this->searchnew($query,"ou=Group");
            // returning the labal
            return isset($data[0]) && isset($data[0]["igrp1"]) && isset($data[0]["igrp1"][0]) ? $data[0]["igrp1"][0] : 0;
        }
        
        /**
         *  get employee name with icno
         * 
         */
        public function getNameWithIcno($uid){
            // prepare the query
            $query="(|(uidnumber=$uid)(cn=*$uid*))";
            // search the db
	    $data=$this->searchnew($query);
            // return the name
            return isset($data[0]) && isset($data[0]["cn"]) && isset($data[0]["cn"][0]) ? $data[0]["cn"][0] : 0;
       
        }
        
        
		 /*
      *
      *
      *
      */
      public function getNewGid(){
		  $query="(gidnumber=*)";
		  $required = array('gidnumber');
		  $sort='gidnumber';
		  $data=$this->searchnew($query,"ou=Group",$required,$sort);
		  
			// getting the last element if the result is array
			if(is_array($data)){
                           // var_dump($data);
			//	$last = end($data);
			//	return isset($last["gidnumber"]) ? intval($last["gidnumber"][0]) + 1 : 1;
                            return isset($data["count"]) ? intval($data["count"]) + 200 : 1;
			}else{
				return 1;
			}
			// return the data
			
		  }
		 /*
	 *
	 *
	 */
	 public function getOrg($term){
			
			$query="(|(gidnumber=$term)(igrp1=*$term*))";
			$data=$this->searchnew($query,"ou=Group");			
			$out = array();
		 
		 // looping over the result 
		 foreach($data as $key=>$db){
			 if($key!=="count"){
			 // dummy object
			 $dbdata = new stdClass();
                         
                         $dbdata->id = isset($db["gidnumber"]) ? $db["gidnumber"][0] : " ";
			 // employee name
			 $dbdata->text = isset($db["igrp1"]) ? $db["igrp1"][0] : " ";
			  // employee icnumber
				

		   // $dbdata->text = "sample";
			//$dbdata->id = 1;
			 // placing the object in dummy array	
			 $this->orgdata[] = $dbdata;
			// $this->getorgchild($dbdata->id);
			 
			 }
		 }
		// var_dump($data);
         return $this->orgdata;
		
		}
		
	/*
	*
	*
	*
	*/
	public function getorgchild($gid){
		$query="(iparnt=*$gid*)";
		$required=array('gidNumber','cn');
		$data=$this->searchnew($query,"ou=Group",$required);
		
		 foreach($data as $key=>$db){
			 if($key!=="count"){
						
			 // dummy object
			 $dbdata = new stdClass();
			 // employee name
			 $dbdata->text = isset($db["cn"]) ? $db["cn"][0] : " ";
			  // employee icnumber
			 $dbdata->id = isset($db["gidnumber"]) ? $db["gidnumber"][0] : " ";			 
			 // placing the object in dummy array	
			 $this->orgdata[] = $dbdata;
			 
			 $this->getorgchild($dbdata->id);
			 }
		 }
		 return $this->orgdata;
	}
        
        /**
         * 
         * @return string
         */
        public function getHeads(){            
             $query="(|(itype=6)(itype=8)(itype=10))";
             //$query="(|(itype=6)(itype=8))";  //This is Raju's version.
             $data=$this->searchnew($query,"ou=Group");
            // var_dump($data);
             $out = [];
             foreach($data as $key=>$db)
		 {
                     if($key!=="count"){
                            $label = isset($db["cn"]) ? $db["cn"][0] : " "; 
                            $type = isset($db["itype"]) ? intval($db["itype"][0]) : " ";
                            $grouphead = isset($db["ihead"]) ? $db["ihead"][0] : " ";
                            $empname = $this->getNameWithIcno(intval($grouphead));
                            if($type==8){
                                $out[$grouphead] = $empname." ,AD,".$label;
                            }else{
                                $out[$grouphead] = $empname." ,Head,".$label;
                            }
                           // echo $empname.", Head ".$label."<br />";
                     }
                 }
               //  var_dump($out);
             return $out;
         }
         /**
          * 
          * @param type $icno
          */
         public function getUserData($icno){
             // query preparation
            $query="(uid=".intval($icno).")";
            // search for the data
            $data=$this->searchnew($query,"ou=People");  
           
            // take the corresponsing values
            if(isset($data[0])){
                $db = $data[0];
                 // create a dummmy object
                 $obj = new stdClass();
                 $obj->icno = $icno;
                 $obj->email = isset($db["igemail"]) ? $db["igemail"][0] : " ";
                 $obj->name = isset($db["cn"]) ? $db["cn"][0] : " ";
                 $obj->gid = isset($db["iorg1"]) ? $db["iorg1"][0] : " ";
                 $obj->org = $this->getOrgLabel($obj->gid);  
                return $obj;
            }else{
                return false;
            }
            
         }
		  
	  	  
}


?>