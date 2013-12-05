<?php
App::uses('AppController', 'Controller');
/**
 * Products Controller
 *
 * @property Product $Product
 */
class ProductsController extends AppController {

/**
 * index method
 *
 * @return void
 */
	public function index($view = null) {
		$this->Product->Behaviors->load('Containable');
		$conditions = array();
		if($this->request->is('post')){
			$data = $this->request->data;
			foreach($data['Product'] as $key => $value){
				if($value > 0 && substr($key, -3, 3) == '_id'){
					$conditions[$key] = $value;
				}
			}
			$conditions['price BETWEEN ? AND ?'] = array($data['Product']['min_price'], $data['Product']['max_price']);
		}
		$this->paginate = array(
			'conditions' => $conditions,
			'contain' => array(
				'CurrentUserLike' => array(
					'conditions' => array('CurrentUserLike.user_id' => $this->Auth->user('id'), 'CurrentUserLike.model' => 'Product'),
				),
				'User',
				'CoverImage'
			)
		);
		
		$products = $this->paginate();
		
		foreach($products as &$product){
			$product['Product']['likeText'] = 'Like';
			if(count($product['CurrentUserLike']))
				$product['Product']['likeText'] = 'Liked';
		}
		
		
		
		
		$this->set(compact('products'));
		
		/*
		 * To generate select dropdowns
		 */
		$styles = $this->Product->Style->find('list');
		$styles[0] = 'All Styles';
		
		$genders = $this->Product->Gender->find('list');
		$genders[0] = 'Both';
		$brands = $this->Product->Brand->find('list');
		$brands[0] = 'All Brands';
		
		$sizes = $this->Product->Size->find('list');
		$sizes[0] = 'All Sizes';
		
		$categories = $this->Product->Category->find('list');
		$categories[0] = 'All Categories';
		$this->Product->recursive = -1;
		$prices = $this->Product->find('first', array('fields' => array('MIN(Product.price) as min_price, MAX(Product.price) as max_price')));
		$minPrice = 0;
		$maxPrice = PHP_INT_MAX;
		if(!empty($prices)){
			//$minPrice = $prices[0]['min_price'];
			$maxPrice = $prices[0]['max_price'];
		}
		$this->set(compact('styles', 'genders', 'brands', 'sizes', 'categories', 'minPrice', 'maxPrice'));
		
		//$this->layout = 'cake';
		
		//debug($products);
		//return;
		
		$this->render('shop');
	}
	public function facebook_test() {
	
	}	
	public function admin_products(){
	$this->checkSessionFrontEnd();
		$this->layout = 'admin';
		$this->Product->Behaviors->load('Containable');
		$this->set('products',$this->Product->find('all',array('contain' => array(
												'Image' => array('fields' => array('title','filename'),
																 'limit' => 1,
																 'offset' => 0)
											),
											'fields' => array('id','title','description')
		)));
	}
	public function admin_products_delete($id = null){
	$this->checkSessionFrontEnd();
		$this->Product->id = $id;
		if (!$this->Product->exists()) {
			throw new NotFoundException(__('Invalid item'));
		}
		if ($this->Product->delete()) {
			$this->Session->setFlash(__('Product deleted'));
			$this->redirect(array('action' => 'admin_products'));
		}
		$this->Session->setFlash(__('Product was not deleted'));
		$this->redirect(array('action' => 'admin_products'));
	}
	public function admin_products_edit($id){
	$this->checkSessionFrontEnd();
		$this->layout = 'admin';
		$this->Product->id = $id;
		if (!$this->Product->exists()) {
			throw new NotFoundException(__('Invalid item'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Product->save($this->request->data)) {
				$this->Session->setFlash(__('The item has been saved'));
				$this->redirect(array('action' => 'admin_products'));
			} else {
				$this->Session->setFlash(__('The item could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Product->read(null, $id);
			
		}
		$styles = $this->Product->Style->find('list');
		$genders = $this->Product->Gender->find('list');
		$brands = $this->Product->Brand->find('list');
		$sizes = $this->Product->Size->find('list');
		$categories = $this->Product->Category->find('list');
		$users = $this->Product->User->find('list');
		$this->set(compact('styles', 'genders', 'brands', 'sizes', 'categories', 'users'));
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
		
		$this->Product->id = $id;
		$this->Product->recursive = 2;
		if (!$this->Product->exists()) {
			throw new NotFoundException(__('Invalid product'));
		}
		
		$this->Product->Behaviors->load('Containable');
		$products = $this->Product->find('all', array(
			'conditions' => array('Product.id' => $id),
			'contain' => array(
				'Comment' => array(
					'conditions' => array('Comment.parent_id' => 0, 'Comment.model' => 'Product', 'Comment.foreign_key' => $id),
					'ChildComment' => array('User'),
					'User'
				),
				'Image',
				'User',
				'Style',
				'Gender',
				'Cause',
				'Design',
				'Size'
			),
		));
		
		if(!empty($products)){
			$product = $products[0];
		}
		else{
			$this->render('/404');
		}
		//debug($product);
		$this->set(compact('product'));
	}

/**
 * add method
 *
 * @return void
 */
	
 
	public function add() {
		if ($this->request->is('post')) {
			$this->Product->create();
			$data = $this->request->data;
			debug($data);
			$data['Product']['user_id'] = $this->Auth->user('id');
			if ($this->Product->save($data)){
				$this->Session->setFlash(__('The item has been saved'));
				$this->redirect(array('action' => 'images', $this->Product->id));
			} else {
				$this->Session->setFlash(__('The item could not be saved. Please, try again.'));
			}
		}
		$styles = $this->Product->Style->find('list');
		$genders = $this->Product->Gender->find('list');
		$brands = $this->Product->Brand->find('list');
		$sizes = $this->Product->Size->find('list');
		$causes = $this->Product->Cause->find('list');
		$designs = $this->Product->Design->find('list');
		$categories = $this->Product->Category->find('list');
		$this->set(compact('styles', 'genders', 'brands', 'sizes', 'categories', 'causes', 'designs'));
	}
	
/**
 * images method
 *
 * @return void
 */
	public function images($id) {
		$this->Product->id = $id;
		if (!$this->Product->exists()) {
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
		$this->Product->id = $id;
		if (!$this->Product->exists()) {
			throw new NotFoundException(__('Invalid item'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Product->save($this->request->data)) {
				$this->Session->setFlash(__('The item has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The item could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Product->read(null, $id);
		}
		$styles = $this->Product->Style->find('list');
		$genders = $this->Product->Gender->find('list');
		$brands = $this->Product->Brand->find('list');
		$sizes = $this->Product->Size->find('list');
		$categories = $this->Product->Category->find('list');
		$users = $this->Product->User->find('list');
		$this->set(compact('styles', 'genders', 'brands', 'sizes', 'categories', 'users'));
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
		$this->Product->id = $id;
		if (!$this->Product->exists()) {
			throw new NotFoundException(__('Invalid item'));
		}
		if ($this->Product->delete()) {
			$this->Session->setFlash(__('Product deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Product was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
	public function admin_products_add(){
	$this->checkSessionFrontEnd();
		$this->layout = 'admin';
		if ($this->request->is('post')) {
			$this->Product->create();
			$data = $this->request->data;
			$data['Product']['user_id'] = 0;
			if ($this->Product->save($data)){
				$this->Session->setFlash(__('The item has been saved'));
				$this->redirect(array('action' => 'images', $this->Product->id));
			} else {
				$this->Session->setFlash(__('The item could not be saved. Please, try again.'));
			}
		}
		$styles = $this->Product->Style->find('list');
		$genders = $this->Product->Gender->find('list');
		$brands = $this->Product->Brand->find('list');
		$sizes = $this->Product->Size->find('list');
		$categories = $this->Product->Category->find('list');
		$this->set(compact('styles', 'genders', 'brands', 'sizes', 'categories'));
	}
}
