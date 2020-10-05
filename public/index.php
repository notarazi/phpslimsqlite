<?php
require 'Slim/Slim.php';
require 'NotORM.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

$app->container->singleton('db', function () {
	include 'data_conn.php';
    return new NotORM($connection);
});

//api-buddies
$app->get('/persons(/:personid)','getPersons');
$app->post('/persons(/:personid)','setPersons');

//api-users
$app->get('/users(/:userid)','getUsers');
$app->post('/users(/:userid)','setUsers');


$app->run();

function getPersons($personid="") {
	// echo "getPersons ".$personid;
	//create app instance object
	$app = \Slim\Slim::getInstance();
	//create db object from apps instance object
	$db1=$app->db;
	//create variables for returning values
	$response=array();
		$action="none";
		$actionstatus="none";
		$result=array();

	//getting data
	if ($personid!=""){
		$action="selectone";
		//fetch as field arrays
		$tblperson = $db1->tblperson("regemail= ?", $personid)->fetch();
		if (!empty($tblperson)){
			$result[]=array(
				"id"=> $tblperson["id"],
				"regname"=> $tblperson["regname"],				
				"regemail"=> $tblperson["regemail"]
				);
		}
		$actionstatus="done";				
	}
	else{
		$action="selectall";
		//fetch row arrays
		$tblperson = $db1->tblperson();
		if (!empty($tblperson)){
		 	foreach ($tblperson as $item) {
            	$result[]  = array(
	            	"id" => $item["id"],
    	           "regname" => $item["regname"],
        	       "regemail" => $item["regemail"]
            	);
        	}
        }
		$actionstatus="done";						
	}

	//create app response
    $response = $app->response;
    //set response sontent type as json
    $response['Content-Type'] = 'application/json';
    //set response body
    //use json_encode to format the output
    $response->body( json_encode([
        'action' => $action,
        'actionstatus' => $actionstatus,
        'result' =>$result
    ]));	
}

function setPersons($personid="") {
	//echo "setPersons ".$personid;
	//create app instance object
	$app = \Slim\Slim::getInstance();
	//create db object from apps instance object
	$db1=$app->db;
	//create variables for returning values
	$response=array();
		$action="none";
		$actionstatus="none";
		$result=array();	

	//create variables to store form param values
	$regname = $app->request->post('regname');
	$regemail = $app->request->post('regemail');

	//setting data
	if ($personid!=""){	//update
		$action="update";	

		//if variables are not empty
		//then assign variable values to data array
		if (!(empty($regname)) && !(empty($regemail))  &&  ($regemail==$personid))  {
			//echo 'valid param';
			//find matching useremail to param email
			$registeredperson = $db1->tblperson("regemail = ?", $regemail)->fetch();
			//echo $registereduser;
			//if matched(registered) then update record
			//else update failed
			if (!empty($registeredperson)) {//registered person
				$action="update";
    			$data = array(
					"regname" => $regname
    			);
    			$result = $registeredperson->update($data);
    			$actionstatus="success";
    		}else{
    			$result="person not found";
    			$actionstatus="failed";
    		}

		}else{
    			$result="field errors";
    			$actionstatus="failed";
			}			
		}
	 
	else{	//insert
		$action="insert";
		//if variables are not empty
		//then assign variable values to data array
		if (!(empty($regname)) && !(empty($regemail)))  {
			//echo 'valid param';
			//find matching useremail to param email
			$registeredperson = $db1->tblperson("regemail = ?", $regemail)->fetch();
			//echo $registereduser;
			//if matched(registered) then insert record
			//else insert failed
			if (empty($registeredperson)) {//registered person
				$newperson = $db1->tblperson();
				$action="insert";
				$data = array(
    				"regname" => $regname,
    				"regemail" => $regemail
				);
				$result = $newperson->insert($data);	
    			$actionstatus="success";
			}else{
				$result = "email has been used";	
    			$actionstatus="failed";				
			}
		}else{
    			$result="field errors";
    			$actionstatus="failed";
			}	
	}

	//create app response
    $response = $app->response;
    //set response sontent type as json
    $response['Content-Type'] = 'application/json';
    //set response body
    //use json_encode to format the output
    $response->body( json_encode([
        'action' => $action,
        'actionstatus' => $actionstatus,
        'result' =>strval($result)
    ]));	

}


function getUsers($userid="") {
	// echo "getUsers ".$userid;
	//create app instance object
	$app = \Slim\Slim::getInstance();
	//create db object from apps instance object
	$db1=$app->db;
	//create variables for returning values
	$response=array();
		$action="none";
		$actionstatus="none";
		$result=array();

	//getting data
	if ($userid!=""){
		$action="selectone";
		//fetch as field arrays
		$tbluser = $db1->tbluser("regemail= ?", $userid)->fetch();
		if (!empty($tbluser)){
			$result[]=array(
				"id"=> $tbluser["id"],
				"regname"=> $tbluser["regname"],				
				"regemail"=> $tbluser["regemail"]
				);
		}
		$actionstatus="done";				
	}
	else{
		$action="selectall";
		//fetch row arrays
		$tbluser = $db1->tbluser();
		if (!empty($tbluser)){
		 	foreach ($tbluser as $item) {
            	$result[]  = array(
	            	"id" => $item["id"],
    	           "regname" => $item["regname"],
        	       "regemail" => $item["regemail"]
            	);
        	}
        }
		$actionstatus="done";						
	}

	//create app response
    $response = $app->response;
    //set response sontent type as json
    $response['Content-Type'] = 'application/json';
    //set response body
    //use json_encode to format the output
    $response->body( json_encode([
        'action' => $action,
        'actionstatus' => $actionstatus,
        'result' =>$result
    ]));	
}

function setUsers($userid="") {
	//echo "setUsers ".$userid;
	//create app instance object
	$app = \Slim\Slim::getInstance();
	//create db object from apps instance object
	$db1=$app->db;
	//create variables for returning values
	$response=array();
		$action="none";
		$actionstatus="none";
		$result=array();	

	//create variables to store form param values
	$regname = $app->request->post('regname');
	$regemail = $app->request->post('regemail');

	//setting data
	if ($userid!=""){	//update
		$action="update";	

		//if variables are not empty
		//then assign variable values to data array
		if (!(empty($regname)) && !(empty($regemail))  &&  ($regemail==$userid))  {
			//echo 'valid param';
			//find matching useremail to param email
			$registereduser = $db1->tbluser("regemail = ?", $regemail)->fetch();
			//echo $registereduser;
			//if matched(registered) then update record
			//else update failed
			if (!empty($registereduser)) {//registered person
				$action="update";
    			$data = array(
					"regname" => $regname
    			);
    			$result = $registereduser->update($data);
    			$actionstatus="success";
    		}else{
    			$result="person not found";
    			$actionstatus="failed";
    		}

		}else{
    			$result="field errors";
    			$actionstatus="failed";
			}			
		}
	 
	else{	//insert
		$action="insert";
		//if variables are not empty
		//then assign variable values to data array
		if (!(empty($regname)) && !(empty($regemail)))  {
			//echo 'valid param';
			//find matching useremail to param email
			$registereduser = $db1->tbluser("regemail = ?", $regemail)->fetch();
			//echo $registereduser;
			//if matched(registered) then insert record
			//else insert failed
			if (empty($registereduser)) {//registered person
				$newuser = $db1->tbluser();
				$action="insert";
				$data = array(
    				"regname" => $regname,
    				"regemail" => $regemail
				);
				$result = $newuser->insert($data);	
    			$actionstatus="success";
			}else{
				$result = "email has been used";	
    			$actionstatus="failed";				
			}
		}else{
    			$result="field errors";
    			$actionstatus="failed";
			}	
	}

	//create app response
    $response = $app->response;
    //set response sontent type as json
    $response['Content-Type'] = 'application/json';
    //set response body
    //use json_encode to format the output
    $response->body( json_encode([
        'action' => $action,
        'actionstatus' => $actionstatus,
        'result' =>strval($result)
    ]));	

}


?>