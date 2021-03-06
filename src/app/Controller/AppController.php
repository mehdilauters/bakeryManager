<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Controller', 'Controller');
App::uses('CakeEmail', 'Network/Email');



/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	var $uses=array('User', 'EventType', 'Company', 'ImapEmail');
	public $helpers = array('Text','Rss', 'Html', 'MyHtml');
	  
	  var $publicActions = array('exportExcel', 'backupDb', 'getCompanyId');
	  var $memberActions = array();
	  var $administratorActions = array();

  public $components = array(
 			  'DebugKit.Toolbar',
			   'Session', 'Cookie',
			    // 'Security', // getNews requestAction redirection
			    'Auth' => array(
				'authenticate' => array(
				      'Form' => array(
					  'fields' => array('username' => 'email'),
					  'passwordHasher' => array(
					      'className' => 'Simple',
					      'hashType' => 'sha1'
					      ),
// 					    'scope' => array('User.company_id' => -1),
					    )
				  ),
				  'loginRedirect' => '/',
				  'logoutRedirect' => '/',
				   'authorize' => array('Controller'),
				),
			  'RequestHandler',
			  'Functions',
			  'Acl',
			  );
  public $menu = array('Menu' => 
			array( 
				'Magasins' => 
				  array( 'url' => 'WEBROOT/', 'active' => false ),
				 'Categories' => 
				    array( 'url' => 'WEBROOT/typesProduits', 'active' => false ),
				 'Produits' => 
				    array( 'url' => 'WEBROOT/produits', 'active' => false ),
// 				 'Contact' => 
// 				    array( 'url' => 'WEBROOT/users/add', 'active' => false ),
		    )
			);
  
  
  /**
    * Returns true if the action was called with requestAction()
    *
    * @return boolean
    * @access public
    */
    public function isRequestedAction() {
      return array_key_exists('requested', $this->params);
    }
  

  public function backupDb()
  {
    App::uses('CakeTime', 'Utility');
    App::uses('Folder', 'Utility');
    App::uses('File', 'Utility');

    $backupExists = false;
    $dirPath = Configure::read('Settings.dbBackupPath');
    $dir = new Folder($dirPath);
    $files = $dir->find('.*backup.*\.sql', true);
    foreach( $files as $file )
    {
	$matches = array();
	preg_match ( '/.*backup-(\d+)-(\d+)-(\d+)\.sql/', $file , $matches);
	$date = CakeTime::fromString("$matches[1]/$matches[2]/$matches[3]");
	$isToday = CakeTime::isToday($date);
	$backupExists |= $isToday;
	if(!$isToday)
	{
	    $file = new File($dirPath.$file);
	    $file->delete();
	}
    }
    if(!$backupExists)
    {
       $this->requestAction('/config/dbBackup');
    }
  }

  public function exportExcel()
  {
    App::uses('CakeTime', 'Utility');
    App::uses('Folder', 'Utility');
    App::uses('File', 'Utility');

    $backupExists = false;
    $dirPath = Configure::read('Settings.excelExportPath');
    $dir = new Folder($dirPath);
    $files = $dir->find('.*backup.*\.xls', true);
    $d = new DateTime();
    $d->modify( 'previous month' );

    foreach( $files as $file )
    {
	$matches = array();
	preg_match ( '/.*backup-(\d+)-(\d+)\.xls/', $file , $matches);
	$date = CakeTime::fromString("$matches[1]/$matches[2]/01");

	$isThisMonth = (date('m/Y', $date) == date('m/Y', $d->getTimestamp()));
	$backupExists |= $isThisMonth;
	if(!$isThisMonth)
	{
	    $file = new File($dirPath.$file);
	    $file->delete();
	}
    }
    if(!$backupExists)
    {
      $d = new DateTime();
      $d->modify( 'previous month' );
      $dateStart = date('01/m/Y',$d->getTimestamp()); // hard-coded '01' for first day
      $dateEnd  = date('t/m/Y', $d->getTimestamp());
      $fileName = $dirPath.'backup-'.date('Y-m', $d->getTimestamp()).'.xls';
       $this->requestAction(array('controller' => 'results', 'action' => 'index'), 
				  array('pass' => 
				      array($dateStart, $dateEnd, $fileName)
					    ));
    }
  }

  
  public function sendMail($config)
  {
	$configDefault = array('user'=>NULL,
							'email' => NULL,
							'view' => 'default',
							'data' => array(),
							'subject' => '',
							'message' => 'Hello World',
							'attachment' => NULL,
							'layout' => 'default',
					);
					
	$config = array_merge($configDefault, $config);


    // debug($config);
	
	$emailAddr = '';
	if($config['user'] == NULL)
	{
		if($config['email'] == NULL)
		{
			$this->log("Email not sent: no email or user specified", 'error');
			$this->Session->setFlash(__('Email not sent: no email or user specified'),'flash/fail');
		}
		else
		{
			$emailAddr = $config['email'];
		}
	}
	else
	{
		$emailAddr = $config['user']['User']['email'];
	}
	
	$normalAddr = '';
	if( Configure::read('Settings.demo.active') && ! Configure::read('Settings.email.debug.status') )
	{
	     if($this->Session->check('demoEmail'))
	     {
		$normalAddr = ' (demo)';
		$emailAddr = $this->Session->read('demoEmail');
	    }
	    else
	    {
		$this->log('demo mode: email not sent', 'email');
		$this->Session->setFlash('Email non envoyé. Pour tester et recevoir des emails d\'exemple, veuillez d\'abord <a href="'.$this->webroot.'users/setDemoEmail" >saisir</a> votre adresse.','flash/fail');
		return;
	    }
	}

	if( Configure::read('Settings.email.debug.status') )
	{
		$normalAddr = ' (debug dest : '.$emailAddr.')';
		$emailAddr = Configure::read('Settings.email.debug.email');
	}
	
    $email = new CakeEmail('default');
	if($config['attachment'] != NULL )
	{
		$email->attachments($config['attachment']);
	}
    $email->from(array(Configure::read('Settings.email.from.email') => Configure::read('email.from.name')))
	->sender(Configure::read('Settings.email.from.email'), Configure::read('email.from.name'))
        ->to($emailAddr)
        ->template($config['view'], $config['layout'])
        ->viewVars($config['data'])
        ->subject($config['subject']);

    $email->send($config['message']);

	if(is_array($emailAddr))
	{
	  $emailAddr = implode(', ', $emailAddr);
	}
	
	$attachment = 'none';
	if(is_array($config['attachment']))
	{
	  $attachment = join(', ', array_keys($config['attachment']));
	}
	$this->log('Email to '.$emailAddr.' : '.$config['subject'].', attachment: '.$attachment, 'email');
	$this->Session->setFlash('Email to '.$emailAddr.$normalAddr.' : '.$config['subject'],'flash/ok');
  }
  
  public function beforeRender()
  {
   //debug($this->Auth->user());
    
//    $this->backupDb();
//     $this->exportExcel();
      try
      {
	$this->theme = 'Company'.$this->getCompanyId();
      }
      catch (Exception $e)
      {
      }

  
	if($this->Auth->user())
	{
		$this->menu['Menu']['Deconnexion ('.$this->Auth->user('name').')'] = array( 'url' => $this->webroot.'users/logout', 'active' => false, 'id'=>'logout' );
	}
	else
	{
		$this->menu['Menu']['Connexion'] = array( 'url' => $this->webroot.'users/login', 'active' => false, 'id'=>'login' );
	}
  
     $this->set('menu', $this->menu);
    
    // check if we need to start intro.js
    if(!$this->Session->check('intro'))
    {
      $this->Session->write('intro', array());
    }
    $introAutostart = false;
    $introKey = 'intro.'.$this->request->params['controller'].'.'.$this->request->params['action'];
    if(!$this->Session->check($introKey))
    {
      $introAutostart = true;
      $this->Session->write($introKey, true);
    }
    if(!$this->Auth->user('autostart_help'))
    {
      $introAutostart = false;
    }
      $this->set('introAutostart', $introAutostart);
// $this->set('introAutostart', true);


     // debug($this->Auth->user('isRoot'));

    $tokens = $this->getUserTokens();
    $this->set('tokens', $tokens);
    if($this->Auth->user() && $this->Auth->user('company_id') != $this->getCompanyId() && !$tokens['isRoot'])
    {
      $this->Session->setFlash('This is not your company, logout','flash/fail');
      return $this->requestAction(array('plugin'=>'', 'controller'=>'users', 'action'=>'logout' ));      
    }

    //Import controller
    App::import('Controller', 'News');
    $newsController = new NewsController();
    //Load model, components...
    $newsController->constructClasses();
    $news = $newsController->getNews();
//     $news =  $this->requestAction(array('plugin'=>'', 'controller'=>'news', 'action'=>'getNews' ));
    $this->set('news',$news);
    $emails = array();
      try
      {
	$company = $this->Company->find('first',array('conditions'=>array('Company.id'=>$this->getCompanyId())));
	$this->set('company',$company);
	$now = new DateTime();
	if( $tokens['isAdmin'] && isset($company['Company']))
	{
	if(! Configure::read('Settings.demo.active') )
	{
            if(
              $company['Company']['imap_server'] != ''
              &&
              ( ! $this->Session->check('company.emails.lastCheck') || ($this->Session->check('company.emails.lastCheck') && $this->Session->read('company.emails.lastCheck')->diff($now)->i > 1 ) )
              )
            {
              $imapSource = array(
                  'datasource' => 'ImapSource',
                  'server' => $company['Company']['imap_server'],
                  'username' => $company['Company']['imap_username'],
                  'password' => $company['Company']['imap_password'],
          //         'port' => 'IMAPServerPort',
          //         'ssl' => true,
                  'encoding' => 'UTF-8',
                  'error_handler' => false,
          //         'connect' => 'INBOX'
                  'auto_mark_as' => array(
          //             'Seen',
                      // 'Answered',
                      // 'Flagged',
                      // 'Deleted',
                      // 'Draft',
                  ),
              );
              
              ClassRegistry::init('ConnectionManager');

              $nds = 'imap_' .$company['Company']['id'];
              if($ds = ConnectionManager::create($nds, $imapSource)) 
              {
                $this->ImapEmail->setDatasource($nds);
  //               $emails = $this->ImapEmail->find('all',array('conditions'=>array('seen'=>false)));
              }

              if($emails == false)
              {
                $emails = array();
              }
              $this->Session->write('company.emails.lastCheck',new DateTime());
              $this->Session->write('company.emails.data',$emails);
            }
            else
            {
              $emails = $this->Session->read('company.emails.data');
            }
          }
          else
          {
            $emails=array(
              array(
                'ImapEmail' => array(
                  'id' => '',
                  'subject' => 'Devis',
                  'from' => 'bob@lauters.fr'
                  ),
                ),
              array(
                'ImapEmail' => array(
                  'id' => '',
                  'subject' => 'Remerciements',
                  'from' => 'jose@lauters.fr'
                  ),
                ),
              array(
                'ImapEmail' => array(
                  'id' => '',
                  'subject' => 'Prise de contact',
                  'from' => 'odile@lauters.fr'
                  ),
                )
              );
          }
        }
      }
      catch (Exception $e)
      {
      }
      $this->set('receivedEmails', $emails);
      
      $this->set('isMobile', $this->RequestHandler->isMobile());
	
  }
  
public function getUserTokens($userId = NULL)
{
        $tokens = array(
		     'isRoot'=> false,
		     'isAdmin'=> false,
		     'member'=>false);
  if($userId == NULL)
  {
    $userId = $this->Auth->user('id');
  }
  if($this->Auth->loggedIn())
  {
      try
      {
	$tokens['member'] = $this->Acl->check(array('model' => 'User', 'foreign_key'=>$userId), 'memberActions');
	$tokens['isRoot']= $this->Acl->check(array('model' => 'User', 'foreign_key'=>$userId), 'rootActions');
	$tokens['isAdmin']= $this->Acl->check(array('model' => 'User', 'foreign_key'=>$userId), 'administratorActions');
      }
      catch(Exception $e)
      {
	$this->log('ACL error '.$e, 'debug');
      }
  }
// debug($tokens);
  return $tokens;
}
  
  public function isAuthorized($user = null) {
	$ret = false;
	if(in_array('*',$this->publicActions) || $this->isCommandLineInterface())
	{
		return true;
	}
	if(in_array($this->request->params['action'],$this->publicActions))
	{
		return true;
	}
	$tokens = $this->getUserTokens();
	if($this->Auth->loggedIn())
	{
		if( $tokens['member'] )
		{
		  if(in_array('*',$this->memberActions))
		  {
			  return true;
		  }
		  if(in_array($this->request->params['action'],$this->memberActions))
		  {
			  return true;
		  }
		}
		
		if( $tokens['isAdmin'] )
		{
		  if(in_array('*',$this->administratorActions))
		  {
			  return true;
		  }
		  if(in_array($this->request->params['action'],$this->administratorActions))
		  {
			  return true;
		  }
		}
		if( $tokens['isRoot'] )
		{		
			return true;
		}
	}
	

    debug($this->request->params['controller'].'/'.$this->request->params['action']);
    // Default deny
    return false;
}
  
  public function getCompanyId()
  {
    $companyId = NULL;
    $name = '';
    if(is_file(TMP.'companyId'))
    {
      $companyId = file_get_contents(TMP.'companyId');
      return $companyId;
    }
// debug($this->request->params);
    $companyCount = $this->Company->find('count');
    if($companyCount == 1 || $companyCount == 0)
    {
	if($companyCount == 1)
	{
	      $company = $this->Company->find('first');
	      $companyId = $company['Company']['id'];
	}
	else
	{
          return NULL;
	}
    }
    else
    {
	$subdomain = explode('.', $_SERVER['HTTP_HOST']);
	if(count($subdomain) != 1)
	{
	  //     debug($subdomain);
	  $subdomain = array_shift($subdomain);
	  $name = $subdomain;
	  $this->Company->contain();
	  $company = $this->Company->find('first', array('conditions'=>array('Company.domain_name' => $subdomain)));
	  if(isset($company['Company']['id']))
	  {
	    $companyId = $company['Company']['id'];
	  }
	}
	else
	{
	  if($this->Session->check('companyId') )
	  {
	    $companyId = $this->Session->read('companyId');
	  }
	  else
	  {
	    $company = $this->Company->find('first');
	    if(isset($company['Company']['id']))
	    {
		  $companyId = $company['Company']['id'];
	    }
	    else
	    {
	      return NULL;
	    }
	  }
	}
      }
    if( $companyId == NULL )
    {
      throw new NotFoundException('Company '.$name. ' does not exists');
    }
    return $companyId;
  }

  
 public function isCommandLineInterface()
{
    return (php_sapi_name() === 'cli');
}
  

 function blackHole($error) {
    switch ($error) {
      case 'secure':
	$this->log(Router::url( $this->here, true ).' redirected to https', 'debug');
        $this->redirect('https://' . env('SERVER_NAME') . $this->here);
        break;
      default:
           $this->log('BlackHole error: '.$error.' for address '.$this->here, 'debug');
        break;
    }
}


// TODO move into a helper/components
public function getFunctionText($coefficients)
	{
		$functionText = "f(x) = ";
		if($coefficients === false)
		{
			$functionText .= 'error';
			return $functionText;
		}
			foreach($coefficients as $power => $coefficient)

			{

				$functionText .= ($coefficient > 0) ? " + " : " - ";

			  $functionText .= abs(round($coefficient, 4));

			  if ($power > 0)

			  {

				$functionText .= "x";

				if ($power > 1)

				  $functionText .= "^" . $power;

			  }

			}
		return $functionText;
		}


  public function beforeFilter()
  {
   if( $this->isCommandLineInterface()   )
   {
	$this->Auth->allow();
   }

    $this->Auth->flash['element'] = 'flash/auth';
    $this->RequestHandler->addInputType('json', array('json_decode', true));
// if(!($this->request->params['controller'] == 'users' && $this->request->params['action'] == 'autologin'))

	  // $user = $this->User->find('first',array('conditions'=>array('User.email' => 'mehdilauters@gmail.com')));
			// if(isset($user['User']['id']))
			// {

					// $this->Auth->login($user['User']);
			// }
	  // session_destroy();
	  
	if($this->Session->check('debugMode') && $this->Session->read('debugMode'))
	{
      $this->set('debugMode',true);
      Configure::write('debug', 2);
    }
//  	debug($this->request->params['controller']);
//  	debug($this->request->params['action']);
	parent::beforeFilter();


	
	if( count($this->publicActions) != 0)
	{
		// debug($this->publicActions);
		if(Configure::read('Settings.public'))
		{
                  $this->Auth->allow($this->publicActions);
		}
		else
		{
                  if($this->request->params['controller'] == 'users' && ( $this->request->params['action'] == 'autologin' || $this->request->params['action'] == 'login' ) || $this->Auth->loggedIn())
                  {
                      $this->Auth->allow($this->publicActions);
                  }
		}
		
	}
	
	try
	{
	  $this->Cookie->name= Configure::read('Settings.Cookie.Name').'_'.$this->getCompanyId();
	}
	catch (Exception $e)
	{
	}
	
	
	if(!$this->Auth->loggedIn())
	{
	  if(!($this->request->params['controller'] == 'users' && $this->request->params['action'] == 'autologin'))
	  {
		  $this->requestAction(array('plugin'=> '', 'controller' => 'users', 'action' => 'autologin'));
	  }
	}
	if($this->Auth->loggedIn())
	{
	  if( Configure::read('Settings.Security.ssl') && !($this->Session->check('noSSL') && $this->Session->read('noSSL')) && $this->action != 'noSSL')
	  {
	    if($this->Acl->check(array('model' => 'User', 'foreign_key'=>$this->Auth->user('id')), 'rootActions'))
	    {
			if(!$this->request->is('ssl'))
			{
				$this->blackHole('secure');
			}
	    }
	  }
	}


// 		if(!($this->request->params['controller'] == 'config' && $this->request->params['action'] == 'upgradeDbStructure'))
// 		{
// 			$this->requestAction(array('controller' => 'config', 'action' => 'upgradeDbStructure'));
// 		}


	
	
  }
}
