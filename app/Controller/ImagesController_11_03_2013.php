<?php
App::uses('AppController', 'Controller');
/**
 * Images Controller
 *
 * @property Image $Image
 */
class ImagesController extends AppController {

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Image->recursive = 0;
		$this->set('images', $this->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null, $thumbnail = null) {
		$this->Image->id = $id;
		if (!$this->Image->exists()) {
			throw new NotFoundException(__('Invalid image'));
		}
		$image = $this->Image->read(null, $id);
		
		$file = WEBROOT_DIR . DS . 'img' . DS . 'uploaded' . DS;
		if($thumbnail != null && $thumbnail == 'thumbnail'){
			$file = $file . 'thumbnail' . DS;
		}
		$file = $file . $image['Image']['filename'];
		$this->response->file($file);
		return $this->response;
	}
	
/**
 * 
 * upload method
 */
	public function upload($model = null, $foreignKey) {
		App::import('Vendor', 'UploadHandler');
		$uploadPath = WWW_ROOT . 'img' . DS . 'uploaded' . DS;
		
		$uploadHandler = new UploadHandler(array('upload_dir' => $uploadPath, 'upload_url' => 'uploaded/'), false);
		$files = array();
			
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$arrImages = $uploadHandler->post(false);
			foreach ($arrImages as $key => $image) {
				// If this is the first image, set it as primary
				$count = $this->Image->find('count', array('conditions' => array('Image.foreign_key' => $foreignKey, 'model' => $model, 'Image.is_primary' => true)));
				$isPrimary = $count == 0 ? true : false;
				$data = array();
				$data['Image'] = array(
					'filename' => $image->name,
					'user_id' => $this->Auth->user('id'),
					'foreign_key' => $foreignKey,
					'model' => $model,
					'is_primary' => $isPrimary
				);
				if(!$this->Image->save($data)){
					unset($arrImages[$key]);
				}
				
				$image->url = Router::url(array('controller' => 'images', 'action' => 'view', $this->Image->id), true);
				$image->thumbnail_url = Router::url(array('controller' => 'images', 'action' => 'view', $this->Image->id, 'medium'), true);
				$image->delete_url = Router::url(array('controller' => 'images', 'action' => 'delete', 'ext' => 'json', $this->Image->id), true);
				$image->imageId = $this->Image->id;
				$image->isPrimary = $isPrimary;
				$files[] = $image;
			}
		}
		else{
			$arrImages = $this->Image->find('all', array('conditions' => array('Image.foreign_key' => $foreignKey, 'model' => $model)));
			foreach($arrImages as $image){
				$file = $uploadHandler->get_file_object($image['Image']['filename']);
				$file->url = Router::url(array('controller' => 'images', 'action' => 'view', $image['Image']['id']), true);
				$file->thumbnail_url = Router::url(array('controller' => 'images', 'action' => 'view', $image['Image']['id'], 'medium'), true);
				$file->delete_url = Router::url(array('controller' => 'images', 'action' => 'delete', 'ext' => 'json', $image['Image']['id']), true);
				//$file->primary_image_url = Router::url(array('controller' => 'images', 'action' => 'make_primary', $image['Image']['id']), true);
				$file->imageId = $image['Image']['id'];
				$file->isPrimary = $image['Image']['is_primary'];
				$files[] = $file;
			}
		}
		$this->set(compact('files'));
		$this->set('_serialize', array('files'));
	}

/**
 * add method
 *
 * @return void
 */
	public function add($foreignKey) {
		if ($this->request->is('post')) {
			$this->Image->create();
			if ($this->Image->save($this->request->data)) {
				$this->Session->setFlash(__('The image has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The image could not be saved. Please, try again.'));
			}
		}
		$this->set(compact('itemId'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->Image->id = $id;
		if (!$this->Image->exists()) {
			throw new NotFoundException(__('Invalid image'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Image->save($this->request->data)) {
				$this->Session->setFlash(__('The image has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The image could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Image->read(null, $id);
		}
		$users = $this->Image->User->find('list');
		$items = $this->Image->Item->find('list');
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
		$this->Image->id = $id;
		if (!$this->Image->exists()) {
			throw new NotFoundException(__('Invalid image'));
		}
		if($this->request->is('ajax')){
			$success = $this->Image->delete();
			$this->set(compact('success'));
			$this->set('_serialize', array('success'));
			return;
		}
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		
		if ($this->Image->delete()) {
			$this->Session->setFlash(__('Image deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Image was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
	
	public function make_primary() {
		//$this->layout = 'cake';
		if($this->request->is('post')){
			$receivedData = $this->request->data;
			// debug($receivedData);
			// exit();
			$check = 0;
			$imageId = $receivedData['imageId'];
			$foreignKey = $receivedData['itemId'];
			
			if( $this->Image->updateAll(array('Image.is_primary' => 0), array('Image.foreign_key =' => $foreignKey)) ){
				// debug($this->Image->updateAll( array('Image.is_primary' => 1), array('Image.id =' => $imageId)));
				// exit();
				if( $this->Image->updateAll( array('Image.is_primary' => 1), array('Image.id =' => $imageId)) ){
					$check = 1;
				}
			}
			/* $this->Image->updateAll(array('Image.is_primary' => 0), array('Image.foreign_key =' => $foreignKey));
			$this->Image->updateAll( array('Image.is_primary' => 1), array('Image.id =' => $imageId)); */
			$response['success'] = false;
			if($check == 1){
				$response['success'] = true;
			}
			$this->set(compact('response'));
			//$this->set('_serialize', array('files'));
		}
	}
}
