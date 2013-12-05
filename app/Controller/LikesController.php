<?php
App::uses('AppController', 'Controller');
/**
 * Likes Controller
 *
 * @property Like $Like
 */
class LikesController extends AppController {
/**
 * beforeFilter method
 */
	public function beforeFilter(){
		parent::beforeFilter();
		$this->Auth->allow('add');
	}
/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Like->recursive = 0;
		$this->set('likes', $this->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->Like->id = $id;
		if (!$this->Like->exists()) {
			throw new NotFoundException(__('Invalid like'));
		}
		$this->set('like', $this->Like->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if($this->Auth->loggedIn()){
			$this->request->data['Like']['user_id'] = $this->Auth->user('id');
			$this->Like->create();
			
			$like = $this->Like->find('first', array('conditions' => array(
				'Like.user_id' => $this->request->data['Like']['user_id'],
				'Like.foreign_key' => $this->request->data['Like']['foreign_key'],
				'Like.model' => $this->request->data['Like']['model']
			)));
			if(!empty($like)){
				$this->Like->id = $like['Like']['id'];
			}
		}
		
		$result = array();
		if(!$this->Auth->loggedIn()){
			$result['success'] = false;
			$result['responseCode'] = 2002;
		}
		else if(!empty($like)){
			if($like['Like']['like_type'] == $this->request->data['Like']['like_type']){
				$result['success'] = false;
				$result['responseCode'] = 2012;
			}
			else if ($this->Like->save($this->request->data)) {
				$result['success'] = true;
				$result['responseCode'] = 1000;
			}
		}
		else if ($this->Like->save($this->request->data)) {
			$result['success'] = true;
			$result['responseCode'] = 1000;
		}
		else{
			$result['success'] = false;
			$result['responseCode'] = 2001;
		}
		
		$users= $this->Like->User->find('list');

		$this->set(compact('result'));
		$this->set('_serialize', 'result');
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->Like->id = $id;
		if (!$this->Like->exists()) {
			throw new NotFoundException(__('Invalid like'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Like->save($this->request->data)) {
				$this->Session->setFlash(__('The like has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The like could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Like->read(null, $id);
		}
		$users = $this->Like->User->find('list');
		$items = $this->Like->Item->find('list');
		$this->set(compact('users', 'items'));
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
		$this->Like->id = $id;
		if (!$this->Like->exists()) {
			throw new NotFoundException(__('Invalid like'));
		}
		if ($this->Like->delete()) {
			$this->Session->setFlash(__('Like deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Like was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
