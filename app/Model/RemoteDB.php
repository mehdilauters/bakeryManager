<?php
App::uses('AppModel', 'Model');
/**
 * Result Model
 *
 * @property Shop $Shop
 */
class RemoteDB extends AppModel {
	public $useTable = false;
	
	private $curlHandler;
	
	public function login($login, $password)
	{
		$loginUrl = Configure::read('dbBackupUrl').'users/login';
		$postParams = 'data[User][email]='.$login.'&data[User][password]='.$password;
		
		$this->curlHandler = curl_init();
		$timeout = 5;
		debug($loginUrl);
		
		// LOGIN
		//Set the URL to work with
		curl_setopt($this->curlHandler, CURLOPT_URL, $loginUrl);

		// https certificate
		curl_setopt($this->curlHandler, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($this->curlHandler, CURLOPT_SSL_VERIFYHOST, false);

		// ENABLE HTTP POST
		curl_setopt($this->curlHandler, CURLOPT_POST, 1);

		//Set the post parameters
		curl_setopt($this->curlHandler, CURLOPT_POSTFIELDS, $postParams);

		//Handle cookies for the login
		curl_setopt($this->curlHandler, CURLOPT_COOKIEJAR, TMP.'cookie.txt');

		//Setting CURLOPT_RETURNTRANSFER variable to 1 will force cURL
		//not to print out the results of its query.
		//Instead, it will return the results as a string return value
		//from curl_exec() instead of the usual true/false.
		curl_setopt($this->curlHandler, CURLOPT_RETURNTRANSFER, 1);

		//execute the request (the login)
		$res = curl_exec($this->curlHandler);
		debug(curl_error($this->curlHandler));

		//the login is now done and you can continue to get the
		//protected content.
	}
	
	public function download($demo = true)
	{
		$timeout = 5;
		$demoString = '1';
		if(!$demo)
		{
			$demoString = '0';
		}
		$backupUrl = Configure::read('dbBackupUrl').'config/dbBackup/'.$demoString.'/1';
		debug($backupUrl);
		curl_setopt($this->curlHandler, CURLOPT_URL, $backupUrl);
		curl_setopt($this->curlHandler, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($this->curlHandler, CURLOPT_CONNECTTIMEOUT, $timeout);
		$sql = curl_exec($this->curlHandler);
		curl_close($this->curlHandler);
 		//debug($sql);
		return $sql;
	}
	
	public function applyToDB($sql)
	{
	    if(Configure::read('demo.active'))
	    {
		App::uses('ConnectionManager', 'Model'); 
		$db = ConnectionManager::getDataSource('default');
// 		$db = $this->getDataSource ( ) ;
		$res = $db->execute($sql);
		debug($res);
		return $res;
	    }
	    return false;
	}
}

?>