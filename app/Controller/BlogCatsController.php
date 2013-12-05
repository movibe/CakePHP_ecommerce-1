<?php
App::uses('AppController', 'Controller');
/**
 * BlogCats Controller
 *
 * @property BlogCat $BlogCat
 */
class BlogCatsController extends AppController {

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->BlogCat->recursive = 0;
		$this->set('blogCats', $this->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->BlogCat->id = $id;
		if (!$this->BlogCat->exists()) {
			throw new NotFoundException(__('Invalid blog cat'));
		}
		$this->set('blogCat', $this->BlogCat->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->BlogCat->create();
			if ($this->BlogCat->save($this->request->data)) {
				$this->Session->setFlash(__('The blog cat has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The blog cat could not be saved. Please, try again.'));
			}
		}
		$blogs = $this->BlogCat->Blog->find('list');
		$cats = $this->BlogCat->Cat->find('list');
		$this->set(compact('blogs', 'cats'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->BlogCat->id = $id;
		if (!$this->BlogCat->exists()) {
			throw new NotFoundException(__('Invalid blog cat'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->BlogCat->save($this->request->data)) {
				$this->Session->setFlash(__('The blog cat has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The blog cat could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->BlogCat->read(null, $id);
		}
		$blogs = $this->BlogCat->Blog->find('list');
		$cats = $this->BlogCat->Cat->find('list');
		$this->set(compact('blogs', 'cats'));
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
		$this->BlogCat->id = $id;
		if (!$this->BlogCat->exists()) {
			throw new NotFoundException(__('Invalid blog cat'));
		}
		if ($this->BlogCat->delete()) {
			$this->Session->setFlash(__('Blog cat deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Blog cat was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
