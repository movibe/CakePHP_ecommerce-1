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
	public $components = array(
		/*
		'Acl',
		*/
		'Auth' => array(
			/*		
			'authorize' => array(
				'Actions' => array('actionPath' => 'controllers')
			),
			*/
			'authenticate' => array(
				'FacebookConnect.Facebook' => array(
					'facebook' => array(
						'scope' => 'email, user_birthday, read_friendlists'
					)
				)
			)
		),
		'Session',
		'RequestHandler'
	);
	public $helpers = array('Html', 'Form', 'Session', 'Time');
	
	public function beforeFilter(){
		/*
		 * Auth component set up and redirection
		 */
		switch ($this->request->params['controller']){
			case 'users':
				break;
			case 'comments':
				break;
			case 'likes':
				break;
			default:
				$this->Session->write('Redirect.controller', $this->request->params['controller']);
				$this->Session->write('Redirect.action', $this->request->params['action']);
		}
		/*
		if($this->request->params['controller'] != 'users'){	
			$this->Session->write('Redirect.controller', $this->request->params['controller']);
			$this->Session->write('Redirect.action', $this->request->params['action']);
		}
		*/
		$this->Auth->loginAction = array('controller' => 'users', 'action' => 'login');
		$this->Auth->logoutRedirect = array('controller' => 'users', 'action' => 'login');
		
		if($this->Session->read('Redirect.controller') != null){
			$this->Auth->loginRedirect = array('controller' => $this->Session->read('Redirect.controller'), 'action' => $this->Session->read('Redirect.action'));
		}
		else{
			$this->Auth->loginRedirect = array('controller' => 'users', 'action' => 'profile');
		}
 		/*
 		 * Set login status
 		 */
		$this->set('loggedIn', $this->Auth->loggedIn());
		$this->set('user', $this->Auth->user());
		
		/*
		 * Allow all actions except add
		 */
		$this->Auth->allow();
		$this->Auth->deny('add');
		
		
		/*
		 * Buttons active states
		 */
		$buttons = array();
		$buttons['causes'] = array(
			'text' => 'Causes',
			'isActive' => false
		);
		$buttons['designs'] = array(
			'text' => 'Designs',
			'isActive' => false
		);
		$buttons['shop'] = array(
			'text' => 'Shop',
			'isActive' => false
		);
		$this->set(compact('buttons'));
	}
	
	public function afterFilter(){
		
		
	}

  function checkSessionFrontEnd()
    {
	 if(!$this->Session->Check('SessionId'))
	  {
	     $this->redirect('/causes/login/');
	  }
	}

}
