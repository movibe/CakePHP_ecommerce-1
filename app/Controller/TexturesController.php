<?php
App::uses('AppController', 'Controller');
/**
 * Textures Controller
 *
 * @property Texture $Texture
 */
class TexturesController extends AppController {

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Texture->recursive = 0;
		$this->set('textures', $this->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->Texture->id = $id;
		if (!$this->Texture->exists()) {
			throw new NotFoundException(__('Invalid texture'));
		}
		$this->set('texture', $this->Texture->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Texture->create();
			if ($this->Texture->save($this->request->data)) {
				$this->Session->setFlash(__('The texture has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The texture could not be saved. Please, try again.'));
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
		$this->Texture->id = $id;
		if (!$this->Texture->exists()) {
			throw new NotFoundException(__('Invalid texture'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Texture->save($this->request->data)) {
				$this->Session->setFlash(__('The texture has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The texture could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Texture->read(null, $id);
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
		$this->Texture->id = $id;
		if (!$this->Texture->exists()) {
			throw new NotFoundException(__('Invalid texture'));
		}
		if ($this->Texture->delete()) {
			$this->Session->setFlash(__('Texture deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Texture was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
