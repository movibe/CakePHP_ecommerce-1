<?php
App::uses('AppController', 'Controller');
/**
 * Cats Controller
 *
 * @property Cat $Cat
 */
class CatsController extends AppController {

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Cat->recursive = 0;
		$this->set('cats', $this->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->Cat->id = $id;
		if (!$this->Cat->exists()) {
			throw new NotFoundException(__('Invalid cat'));
		}
		$this->set('cat', $this->Cat->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Cat->create();
			if ($this->Cat->save($this->request->data)) {
				$this->Session->setFlash(__('The cat has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The cat could not be saved. Please, try again.'));
			}
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->Cat->id = $id;
		if (!$this->Cat->exists()) {
			throw new NotFoundException(__('Invalid cat'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Cat->save($this->request->data)) {
				$this->Session->setFlash(__('The cat has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The cat could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Cat->read(null, $id);
		}
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
		$this->Cat->id = $id;
		if (!$this->Cat->exists()) {
			throw new NotFoundException(__('Invalid cat'));
		}
		if ($this->Cat->delete()) {
			$this->Session->setFlash(__('Cat deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Cat was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
	
	public function admin_add(){
	$this->checkSessionFrontEnd();
		$this->layout = 'admin';
		$isSaved= '';
		$category = '';
		if ($this->request->is('post')) {
			$this->Cat->create();
			$data = $this->request->data;
			//var_dump($data);
			//exit();
			$category = $data['Cat']['name'];
			
			if ($this->Cat->save($this->request->data)) {
				$this->Session->setFlash(__('The category has been saved'));
				$isSaved= 1;
				//$this->redirect($this->request->referer());
			} else {
				$this->Session->setFlash(__('The category could not be saved. Please, try again.'));
				$isSaved= -1;
			}
		}
		$this->set(compact('isSaved', 'category'));
	}
}
