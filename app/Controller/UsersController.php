<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends AppController {
	/**
	 * Login method
	 */
	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow('login', 'result');
	}
	public function login($type = null) {
		if($type == 'facebook'){
			if($this->Auth->loggedIn()){
				$this->Session->setFlash('Logged in.');
//				echo "facebook";
				$this->Auth->logoutRedirect = '/';
				$this->redirect($this->Auth->redirectUrl());
			}
			
			else{
				if($this->Auth->login()){
//					echo "is not facebook";
					$this->Auth->loginRedirect = '/';
					$this->redirect($this->Auth->redirectUrl());
				}
			}
		}
	}
	
	/**
	 * Logout method
	 */
	public function logout(){
		$this->Session->setFlash('Good-Bye');
		$this->Auth->logoutRedirect = '/';
		$this->redirect($this->Auth->logout());
	}
	
	public function result(){
		debug($this->Auth->user());
		//debug($_SESSION);
		$user = (CakeSession::read('Auth.User'));
		$id = Hash::get($user, 'id');
		debug($id);
		$this->render('login');		
	}
	/**
	 * index method
	 *
	 * @return void
	 */
	public function index() {
		$this->User->recursive = 0;
		$this->set('users', $this->paginate());
	}

	/**
	 * view method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function view($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->layout = 'cake';
		$user = $this->User->read();
		
		//debug($user);
		$this->redirect($user['User']['profileUrl']);
		$this->set('user', $this->User->read(null, $id));
	}

	/**
	 * add method
	 *
	 * @return void
	 */
	public function add() {
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		}
		$groups = $this->User->Group->find('list');
		$this->set(compact('groups'));
	}

	/**
	 * edit method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function edit($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->User->read(null, $id);
		}
		$groups = $this->User->Group->find('list');
		$this->set(compact('groups'));
	}

	/**
	 * delete method
	 *
	 * @throws MethodNotAllowedException
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->User->delete()) {
			$this->Session->setFlash(__('User deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('User was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
	public function admin_users_list(){
	$this->checkSessionFrontEnd();
		$this->layout = 'admin';
		$this->User->Behaviors->load('Containable');
		$users = $this->User->find('all', array());
		$this->set(compact('users'));
	}
	public function admin_user_delete($id = null){
	$this->checkSessionFrontEnd();
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid User'));
		}
		if ($this->User->delete()) {
			$this->Session->setFlash(__('User deleted'));
			$this->redirect(array('action' => 'admin_products'));
		}
		$this->Session->setFlash(__('Product was not deleted'));
		$this->redirect(array('action' => 'admin_products'));
	}
	public function admin_send_email(){
	$this->checkSessionFrontEnd();
		$this->layout = "cake";
		//debug($this->User->find("all"));
		if ($this->request->is('post') || $this->request->is('put')) {
			debug($this->request->data);
		}
	}
}