<?php
App::uses('AppController', 'Controller');
/**
 * Designs Controller
 *
 * @property Design $Design
 */
class DesignsController extends AppController {
/**
 * index method
 *
 * @return void
 */
	public function index($filter = 'top-rated') {
		$this->loadModel('Cause');
     $tests = $this->Cause->query("SELECT id, title, description, likes, comments, modified  FROM causes where import = 0");
			 
		$find_id = $tests['0']['causes']['id'];
		$find_title = $tests['0']['causes']['title'];
		$this->set(compact('find_title'));
		$find_description = $tests['0']['causes']['description'];
		$this->set(compact('find_description'));
		$find_likes = $tests['0']['causes']['likes'];
		$this->set(compact('find_likes'));
		$find_comments = $tests['0']['causes']['comments'];
		$this->set(compact('find_comments'));
		$find_modified = $tests['0']['causes']['modified'];
		$this->set(compact('find_modified'));

        	$this->loadModel('Images');
//		$tests = $this->Cause->query("SELECT filename  FROM images where foreign_key ='".$find_id."'");
		$tests = $this->Cause->query("SELECT filename  FROM images where foreign_key =$find_id");
		$find_filename = $tests['0']['images']['filename'];
		$this->set(compact('find_filename'));		
		
		$this->Design->Behaviors->load('Containable');
		$conditions = array();
		if($this->request->is('post')){
			$data = $this->request->data;
			foreach($data['Design'] as $key => $value){
				if($value > 0 && substr($key, -3, 3) == '_id'){
					$conditions[$key] = $value;
				}
			}
			$conditions['price BETWEEN ? AND ?'] = array($data['Design']['min_price'], $data['Design']['max_price']);
		}
		
		$order = array('likes' => 'desc');
		if($filter == 'most-comments'){
			$order = array('comments' => 'desc');
		}		
		if($filter == 'newest'){
			$order = array('created' => 'desc');
		}
		$this->paginate = array(
			'conditions' => $conditions,
			'order' => $order,
			'contain' => array(
				'CurrentUserLike' => array(
					'conditions' => array('CurrentUserLike.user_id' => $this->Auth->user('id'), 'model' => 'Design'),
				),
				'User',
				'CoverImage'
			),
			'limit' => 15
		);
		
		$designs = $this->paginate();
		
		foreach($designs as &$design){
			$design['Design']['likeText'] = 'Like';
			if(count($design['CurrentUserLike']))
				$design['Design']['likeText'] = 'Liked';
		}
		/*
		debug($designs);
		$this->layout = 'cake';
		*/
		if(is_numeric($filter)){
			$this->set('designId', $filter);
		}
		$this->set(compact('designs'));
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->layout = 'plain';
		
		$this->Design->id = $id;
		$this->Design->recursive = 2;
		if (!$this->Design->exists()) {
			throw new NotFoundException(__('Invalid design'));
		}
		
		$this->loadModel('Cause');
        	$tests = $this->Cause->query("SELECT id, cause_name, title, description, likes, comments, modified  FROM causes where import = 1");    
		$find_id = $tests['0']['causes']['id'];
		$find_causename = $tests['0']['causes']['cause_name'];
		$this->set(compact('find_causename'));		
		$find_title = $tests['0']['causes']['title'];
		$this->set(compact('find_title'));
		$find_description = $tests['0']['causes']['description'];
		$this->set(compact('find_description'));
		$find_likes = $tests['0']['causes']['likes'];
		$this->set(compact('find_likes'));
		$find_comments = $tests['0']['causes']['comments'];
		$this->set(compact('find_comments'));
		$find_modified = $tests['0']['causes']['modified'];
		$this->set(compact('find_modified'));

        	$this->loadModel('Images');
//		$tests = $this->Cause->query("SELECT filename  FROM images where foreign_key ='".$find_id."'");
		$tests = $this->Cause->query("SELECT filename  FROM images where foreign_key =$find_id");
		$this->set(compact('tests'));
		$find_filename = $tests['0']['images']['filename'];
		$this->set(compact('find_filename'));			
		
		$this->Design->Behaviors->load('Containable');
		$designs = $this->Design->find('all', array(
			'conditions' => array('Design.id' => $id),
			'contain' => array(
				'Comment' => array(
					'User',
					'ChildComment' => array(
						'User',
						'order' => array('ChildComment.created' => 'ASC')
					),
					'conditions' => array('Comment.parent_id' => 0, 'Comment.model' => 'Design', 'Comment.foreign_key' => $id),
					'order' => array('Comment.created' => 'DESC'),
					'limit' => 1
				),
				'Image' =>array(
					
				),
				'User'
			),
		));
		
		$design = null;
		if(!empty($designs)){
			$design = $designs[0];
		}
		else{
			$this->render('/404');
		}
		$this->set(compact('design'));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Design->create();
			$data = $this->request->data;
			$data['Design']['user_id'] = $this->Auth->user('id');
			if ($this->Design->save($data)) {
				$this->Session->setFlash(__('The design has been saved'));
				$this->redirect(array('action' => 'images', $this->Design->id));
			} else {
				$this->Session->setFlash(__('The design could not be saved. Please, try again.'));
			}
		}
		$textures = $this->Design->Texture->find('list');
		//debug($textures);
		$this->set(compact('textures'));
	}

/**
 * images method
 *
 * @return void
 */
	public function images($id) {
		$this->Design->id = $id;
		if (!$this->Design->exists()) {
			$this->render('/404');
			return;
		}
		$this->set(compact('id'));
	}

/**
 * comments method
 *
 * @return void
 */
	public function comments($id) {
		$this->Design->id = $id;
		if (!$this->Design->exists()) {
			$this->render('/404');
			return;
		}
		$this->set(compact('id'));
	}
	
/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->Design->id = $id;
		if (!$this->Design->exists()) {
			throw new NotFoundException(__('Invalid design'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Design->save($this->request->data)) {
				$this->Session->setFlash(__('The design has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The design could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Design->read(null, $id);
		}
		$users = $this->Design->User->find('list');
		$this->set(compact('users'));
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
		$this->Design->id = $id;
		if (!$this->Design->exists()) {
			throw new NotFoundException(__('Invalid design'));
		}
		if ($this->Design->delete()) {
			$this->Session->setFlash(__('Design deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Design was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
	public function admin_designs(){
	$this->checkSessionFrontEnd();
		$this->layout = 'admin';
		$this->Design->Behaviors->load('Containable');
		$this->set('designs',$this->Design->find('all',array('contain' => array(
												'Image' => array('fields' => array('title','filename'),
																 'limit' => 1,
																 'offset' => 0)
											),
											'fields' => array('id','title','description')
		)));
	}
	public function admin_designs_delete($id = null){
	$this->checkSessionFrontEnd();
		$this->Design->id = $id;
		if (!$this->Design->exists()) {
			throw new NotFoundException(__('Invalid design'));
		}
		if ($this->Design->delete()) {
			$this->Session->setFlash(__('Design deleted'));
			$this->redirect(array('action' => 'admin_designs'));
		}
		$this->Session->setFlash(__('Design was not deleted'));
		$this->redirect(array('action' => 'admin_designs'));
	}	
	public function admin_designs_edit($id = null){
	$this->checkSessionFrontEnd();
		$this->layout = "admin";
			
		$this->Design->id = $id;
		if (!$this->Design->exists()) {
			throw new NotFoundException(__('Invalid design'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Design->save($this->request->data)) {
				$this->Session->setFlash(__('The design has been saved'));
				$this->redirect(array('action' => 'admin_designs'));
			} else {
				$this->Session->setFlash(__('The design could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Design->read(null, $id);
		}
		$users = $this->Design->User->find('list');
		$textures = $this->Design->Texture->find('list');
		$this->set(compact('users','textures'));
	
	}
}
