<?php

$results = passthru('eval "{ 
        sleep 3;
        echo nitish; 
        sleep 1; 
        echo nitish@123; 
        sleep 1; 
        echo whoami; 
        sleep 10000; }" | telnet 10.1.100.90');
print_r($results);

?>	
