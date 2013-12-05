<?php
App::uses('AppController', 'Controller');
/**
 * Genders Controller
 *
 * @property Gender $Gender
 */
class GendersController extends AppController {

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Gender->recursive = 0;
		$this->set('genders', $this->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->Gender->id = $id;
		if (!$this->Gender->exists()) {
			throw new NotFoundException(__('Invalid gender'));
		}
		$this->set('gender', $this->Gender->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Gender->create();
			if ($this->Gender->save($this->request->data)) {
				$this->Session->setFlash(__('The gender has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The gender could not be saved. Please, try again.'));
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
		$this->Gender->id = $id;
		if (!$this->Gender->exists()) {
			throw new NotFoundException(__('Invalid gender'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Gender->save($this->request->data)) {
				$this->Session->setFlash(__('The gender has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The gender could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Gender->read(null, $id);
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
		$this->Gender->id = $id;
		if (!$this->Gender->exists()) {
			throw new NotFoundException(__('Invalid gender'));
		}
		if ($this->Gender->delete()) {
			$this->Session->setFlash(__('Gender deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Gender was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
