<?php
App::uses('AppController', 'Controller');



/**
 * Causes Controller
 *
 * @property Cause $Cause
 */
class CausesController extends AppController {
//var $components = array('RequestHandler','Session');  
/**
 * index method
 *
 * @return void
 */
	public function index($filter = 'top-rated') {
       $user = (CakeSession::read('Auth.User'));
		$id = Hash::get($user, 'id');
     
	    $this->set('id',$id);
  
		$this->Cause->Behaviors->load('Containable');
		$conditions = array();
		if($this->request->is('post')){
			$data = $this->request->data;
			foreach($data['Cause'] as $key => $value){
				if($value > 0 && substr($key, -3, 3) == '_id'){
					$conditions[$key] = $value;
				}
			}
			$conditions['price BETWEEN ? AND ?'] = array($data['Cause']['min_price'], $data['Cause']['max_price']);
		}
		
		$order = array('likes' => 'desc');
		if($filter == 'most-comments'){
			$order = array('comments' => 'desc');
		}		
		if($filter == 'newest'){
			//$order = array('created' => 'desc');
			  $order = array('Cause.created DESC');
		}
		
		$this->paginate = array(
			'conditions' => $conditions,
			'order' => $order,
			'limit' =>16,
			'contain' => array(
				'CurrentUserLike' => array(
					'conditions' => array('CurrentUserLike.user_id' => $this->Auth->user('id'), 'model' => 'Cause'),
				),
				'User',
				'CoverImage'
			)
		);
		
		$causes = $this->paginate();
	
		foreach($causes as &$cause){
			$cause['Cause']['likeText'] = 'Like';
			if(count($cause['CurrentUserLike']))
				$cause['Cause']['likeText'] = 'Liked';
		}
		
		/*
		debug($causes);
		$this->layout = 'cake';
		*/
		$this->set(compact('causes'));
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
		
		$this->Cause->Behaviors->load('Containable');
		$causes = $this->Cause->find('all', array(
			'conditions' => array('Cause.id' => $id),
			'contain' => array(
				'Comment' => array(
					'conditions' => array('Comment.parent_id' => 0, 'Comment.model' => 'Cause', 'Comment.foreign_key' => $id),
					'ChildComment' => array('User'),
					'User'
				),
				'Image',
				'User'
			),
		));
		
		if(!empty($causes)){
			$cause = $causes[0];
		}
		else{
			$this->render('/404');
		}
		//debug($cause);
		//debug($design);
		$this->set(compact('cause'));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() { 
		if ($this->request->is('post')) {
			$this->Cause->create();
			$data = $this->request->data;
			$data['Cause']['user_id'] = $this->Auth->user('id');
			if ($this->Cause->save($data)) {
				$this->Session->setFlash(__('The cause has been saved'));
				$this->redirect(array('controller'=>'images','action' => 'images', $this->Cause->id));
			} else {
				$this->Session->setFlash(__('The cause could not be saved. Please, try again.'));
			}
		}
		$users = $this->Cause->User->find('list');
		$this->set(compact('users'));
	}

	public function admin_causes(){
    	$this->checkSessionFrontEnd();
		$this->layout = 'admin';
		$this->Cause->Behaviors->load('Containable');
		$this->set('causes',$this->Cause->find('all',array('contain' => array(
												'CoverImage' => array('fields' => array('title','filename'),
																 'limit' => 1,
																 'offset' => 0)
											),
											'fields' => array('id','title','description')
		)));
	}

	public function admin_causes_delete($id = null){
       $this->checkSessionFrontEnd();
		$this->Cause->id = $id;
		if (!$this->Cause->exists()) {
			throw new NotFoundException(__('Invalid cause'));
		}
		if ($this->Cause->delete()) {
			$this->Session->setFlash(__('Cause deleted'));
			$this->redirect(array('action' => 'admin_causes'));
		}
		$this->Session->setFlash(__('Cause was not deleted'));
		$this->redirect(array('action' => 'admin_causes'));
	}
	public function admin_causes_edit($id = null){
	$this->checkSessionFrontEnd();
		$this->layout = "admin";
		$this->Cause->id = $id;
		if (!$this->Cause->exists()) {
			throw new NotFoundException(__('Invalid cause'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Cause->save($this->request->data)) {
				$this->Session->setFlash(__('The cause has been saved'));
				$this->redirect(array('action' => 'admin_causes'));
			} else {
				$this->Session->setFlash(__('The cause could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Cause->read(null, $id);
		}
	}
	
	public function admin_causes_import($id = null){
       $this->checkSessionFrontEnd();
		$this->layout = "admin";
		$this->Cause->updateAll(array('import'=>0));
		$this->Cause->id = $id;
		if (!$this->Cause->exists()) {
			throw new NotFoundException(__('Invalid cause'));
		}
		$this->Cause->save(array('import'=>1));
		$this->redirect(array('action' => 'admin_causes'));
	}	

/**
 * images method
 *
 * @return void
 */

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->Cause->id = $id;
		if (!$this->Cause->exists()) {
			throw new NotFoundException(__('Invalid cause'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Cause->save($this->request->data)) {
				$this->Session->setFlash(__('The cause has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The cause could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Cause->read(null, $id);
		}
		$users = $this->Cause->User->find('list');
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
		$this->Cause->id = $id;
		if (!$this->Cause->exists()) {
			throw new NotFoundException(__('Invalid cause'));
		}
		if ($this->Cause->delete()) {
			$this->Session->setFlash(__('Cause deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Cause was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
	
	public function month($id = null){
		$this->Cause->Behaviors->load('Containable');
		if($id == null){
			$month = date('m');
		}
		else{
			$month = $id;
		}
		$cause = $this->Cause->find('first', array(
			'fields' => array('title', 'description', "MONTH(Cause.created) as month"),
			'conditions' => array('MONTH(Cause.created)' => $month),
			'contain' => array(
				'Image',
				'User'
			),
			'order' => array('Cause.created DESC','Cause.likes DESC')
		));
		$causes = array();
		for($i= 1; $i <= 12; $i++){
			$causes[] = $this->Cause->find('first', array(
				'fields' => array('title', 'description', "MONTH(Cause.created) as month"),
				'conditions' => array('MONTH(Cause.created)' => $i),
				'contain' => array(
					'Image',
					'User'
					),
				'order' => array('YEAR(Cause.created) DESC','Cause.likes DESC')
				)
			);
		}
		/*
		$this->layout = 'cake';
		debug($cause);
		debug($causes);
		*/
		$this->set('cause', $cause);
		$this->set('causes', $causes);
	}

  /*custom function for admin login */
  
    function login()
				 {
  	   			 $this->loadModel('AdminUser');
					if(!empty($this->data)){
							 $username=$this->data['AdminUser']['username'];
			
							 $Password=$this->data['AdminUser']['password'];
			
							 $result=$this->AdminUser->find('first',array('conditions'=>'username="'.$username.'" and password="'.md5($Password).'"'));
				
							 #check wheather the merchnat account is active or not				 
			
							  if(!empty($result))
			
							  {
			
			
									 $uId=$result['AdminUser']['id'];   
									 #set the session for the logged in user
									 $this->Session->write('SessionId',$uId);
									 $this->redirect('/causes/admin_causes');
							  }
								 else
								   {
									$this->Session->setFlash('Invalid username and password','flash_failure');
									$this->redirect('/causes/login/');
								  }
			
						   }
                    }
	 
 /*logout function*/

   function logout()

     {

        $this->Session->delete('SessionId');
		$this->checkSessionFrontEnd();

     }

}
?>
