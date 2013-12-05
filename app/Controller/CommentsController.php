<?php
App::uses('AppController', 'Controller');
/**
 * Comments Controller
 *
 * @property Comment $Comment
 */
class CommentsController extends AppController {
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
	public function index($model = null, $foreignKey = null) {
		$this->Comment->Behaviors->load('Containable');
		
		$this->paginate = array(
			'conditions' => array('Comment.parent_id' => 0, 'Comment.model' => $model, 'Comment.foreign_key' => $foreignKey),
			'order' => array('Comment.created' => 'ASC'),
			'limit' => 100,
			'contain' => array(
				'User',
				'ChildComment' => array('User')
			)
		);
		
		$this->layout = 'plain';
		$this->set('comments', $this->paginate());
		$this->set(compact('foreignKey'));
		$this->set('_serialize', 'comments');		
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->Comment->id = $id;
		if (!$this->Comment->exists()) {
			throw new NotFoundException(__('Invalid comment'));
		}
		$this->set('comment', $this->Comment->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$result = array();
		if($this->Auth->loggedIn()){
			$this->request->data['Comment']['user_id'] = $this->Auth->user('id');
			$this->Comment->create();
		}
		if(!$this->Auth->loggedIn()){
			$result['success'] = false;
			$result['responseCode'] = 2002;
		}
		else if ($this->Comment->save($this->request->data)) {
			$result['success'] = true;
			$result['responseCode'] = 1000;
			$result['data'] = $this->Comment->id;
		}
		else{
			$result['success'] = false;
			$result['responseCode'] = 2001;
		}
		
		$this->layout = 'plain';
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
		$this->Comment->id = $id;
		if (!$this->Comment->exists()) {
			throw new NotFoundException(__('Invalid comment'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Comment->save($this->request->data)) {
				$this->Session->setFlash(__('The comment has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The comment could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Comment->read(null, $id);
		}
		$users = $this->Comment->User->find('list');
		$items = $this->Comment->Item->find('list');
		$parentComments = $this->Comment->ParentComment->find('list');
		$this->set(compact('users', 'items', 'parentComments'));
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
		$this->Comment->id = $id;
		if (!$this->Comment->exists()) {
			throw new NotFoundException(__('Invalid comment'));
		}
		if ($this->Comment->delete()) {
			$this->Session->setFlash(__('Comment deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Comment was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
	
	public function admin_products_comments(){
	$this->checkSessionFrontEnd();
		$this->layout = 'admin';
		$this->Comment->Behaviors->load('Containable');
		$this->set('productsComments',$this->Comment->find('all',array('contain' => array(
												'Product' => array('fields' => array('title'),
																	)
											),
											'conditions' => array('Comment.Model' => 'Product', 'Comment.is_active' => true),
											'fields' => array('id','comment')
		)));
	}
	public function admin_causes_comments(){
	$this->checkSessionFrontEnd();
		$this->layout = 'admin';
		$this->Comment->Behaviors->load('Containable');
		$this->set('causesComments',$this->Comment->find('all',array('contain' => array(
												'Cause' => array('fields' => array('title'),
																 )
											),
											'conditions' => array('Comment.Model' => 'Cause', 'Comment.is_active' => true),
											'fields' => array('id','comment')
		)));
	}
	public function admin_designs_comments(){
	$this->checkSessionFrontEnd();
		$this->layout = 'admin';
		$this->Comment->Behaviors->load('Containable');
		$this->set('designsComments',$this->Comment->find('all',array('contain' => array(
												'Design' => array('fields' => array('title'),
																	)
											),
											'conditions' => array('Comment.Model' => 'Design', 'Comment.is_active' => true),
											'fields' => array('id','comment')
		)));
	}
	public function admin_comment_edit($id = null){
	$this->checkSessionFrontEnd();
		$this->layout = 'admin';
		$this->Comment->id = $id;
		if (!$this->Comment->exists()) {
			throw new NotFoundException(__('Invalid comment'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Comment->save($this->request->data)) {
				$this->Session->setFlash(__('The comment has been saved'));
				$comment = $this->Comment->read(null, $id);
				$model = strtolower($comment['Comment']['model']);
				$model = $model.'s';
				$this->redirect(array('controller' => 'comments', 'action' => 'admin_'.$model.'_comments'));
			} else {
				$this->Session->setFlash(__('The comment could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Comment->read(null, $id);
		}
		//$users = $this->Comment->User->find('list');
		//$items = $this->Comment->Item->find('list');
		//$parentComments = $this->Comment->ParentComment->find('list');
		//$this->set(compact('users', 'items', 'parentComments'));
	}
	public function admin_comment_delete($id = null){
	$this->checkSessionFrontEnd();
		$this->layout = 'admin';
		$comment = $this->Comment->read(null, $id);
		$this->Comment->id = $id;
		if (!$this->Comment->exists()) {
			throw new NotFoundException(__('Invalid comment'));
		}
		if ($this->Comment->delete()) {
			$this->Session->setFlash(__('Comment deleted'));
			$model = strtolower($comment['Comment']['model']);
			$model = $model.'s';
			$this->redirect(array('controller' => 'comments', 'action' => 'admin_'.$model.'_comments'));
		}
		$this->Session->setFlash(__('Comment was not deleted'));
		$model = strtolower($comment['Comment']['model']);
		$model = $model.'s';
		//$this->redirect(array('controller' => 'comments', 'action' => 'admin_'.$model.'_comments'));
	}
}
