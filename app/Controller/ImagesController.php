<?php
App::uses('AppController', 'Controller');

/**
 * Images Controller
 *
 * @property Image $Image
 */
class ImagesController extends AppController {
var $uses = array('Cause','Image');
public $helpers = array('Cropimage','Ajax');
public $components = array('JqImgcrop','RequestHandler','Session'); 


	#public $components = array('JqImgcrop'); 
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
		#App::import('Component', 'JqImgcrop');

		$uploadPath = WWW_ROOT . 'img' . DS . 'uploaded' . DS;
		$uploadPath1= 'img' . DS . 'uploaded' . DS;
		
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
				#$this->cropImage(730, 0, 0, 730, 900, 730, 900, $uploadPath1.$image->name, $uploadPath1.$image->name) ;

				#$this->cropImage(240, 0, 0, 200, 240, 200, 240, $uploadPath1.'medium'.DS.$image->name, $uploadPath1.$image->name) ;

								
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
				#$this->cropImage(730, 0, 0, 730, 900, 730, 900, $uploadPath1.$image['Image']['filename'], $uploadPath1.$image['Image']['filename']) ;
				#$this->cropImage(240, 0, 0, 200, 240, 200, 240, $uploadPath1.'medium'.DS.$image['Image']['filename'], $uploadPath1.$image['Image']['filename']) ; 

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
#for thank you

public function thankyou(){
echo "thanku";}

#end
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


	  function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale){
        $newImageWidth = ceil($width * $scale);
        $newImageHeight = ceil($height * $scale);
        $newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
        $ext = strtolower(substr(basename($image), strrpos(basename($image), ".") + 1));
        $source = "";
        if($ext == "png"){
            $source = imagecreatefrompng($image);
        }elseif($ext == "jpg" || $ext == "jpeg"){
            $source = imagecreatefromjpeg($image);
        }elseif($ext == "gif"){
            $source = imagecreatefromgif($image);
        }
        imagecopyresampled($newImage,$source,0,0,$start_width,$start_height,$newImageWidth,$newImageHeight,$width,$height);

        if($ext == "png" || $ext == "PNG"){
            imagepng($newImage,$thumb_image_name,0);
        }elseif($ext == "jpg" || $ext == "jpeg" || $ext == "JPG" || $ext == "JPEG"){
            imagejpeg($newImage,$thumb_image_name,90);
        }elseif($ext == "gif" || $ext == "GIF"){
            imagegif($newImage,$thumb_image_name);
        }

        chmod($thumb_image_name, 0777);
        return $thumb_image_name;
    }

    function cropImage($thumb_width, $x1, $y1, $x2, $y2, $w, $h, $thumbLocation, $imageLocation){
        $scale = $thumb_width/$w;
        $cropped = $this->resizeThumbnailImage(WWW_ROOT.str_replace("/", DS,$thumbLocation),WWW_ROOT.str_replace("/", DS,$imageLocation),$w,$h,$x1,$y1,$scale);
        return $cropped;
    }


/*for image crop*/
function createimage_step3($id=null){

/*echo $this->data['Image']['x1'];
echo "<br>";
echo $this->data['Image']['y1'];
echo "<br>";
echo $this->data['Image']['x2'];
echo "<br>";
echo $this->data['Image']['y2'];
echo "<br>";
exit*/
		  	if(!empty($this->data)){  		 
			  $uploadToTh='img/upload/thumb/prefix_'.$this->data['Image']['val'];
			  $uploadTo='http://www.spreadnest.com/app/webroot/img/uploaded/'.$this->data['Image']['val'];
			  //$upload_dir=WWW_ROOT.str_replace("/", DS, $uploadTo);
			 if($this->JqImgcrop->cropImage(200, $this->data['Image']['x1'], $this->data['Image']['y1'], $this->data['Image']['x2'], $this->data['Image']['y2'], $this->data['Image']['w'], $this->data['Image']['h'], $uploadToTh, $uploadTo)){
			 $this->set('msg','Thumb has been created');
	        }
		   }	
           $this->set(compact('id'));
	  
	}



public function images($id=null) {
#select data according to key
if($id!=null){
$userid = $this->Cause->find('first', array(
        'conditions' => array('Cause.id' => $id)
		));
#End
$uid = $userid['User']['id'];
}

if (!empty($this->data)) {
$this->layout='crop';
#code to save the images
$model='Cause';
$id= $this->data['Image']['id'];
$uid= $this->data['Image']['uid'];
$count = $this->Image->find('count', array('conditions' => array('Image.foreign_key' => $id, 'model' => $model, 'Image.is_primary' => true)));
				$isPrimary = $count == 0 ? true : false;
				$data = array();
				$data['Image'] = array(
					'filename' => $this->data['Image']['image']['name'],
					'user_id' => $uid,
					'foreign_key' => $id,
					'model' => $model,
					'is_primary' => $isPrimary
				);
			$this->Image->save($data);


	
	/*$this->Cause->id = $id;
		if (!$this->Cause->exists()) {
			$this->render('/404');
			return;
		}
		$this->set(compact('id'));*/
	
      $this->set('val',$this->data['Image']['image']['name']);
	  $uploaded = $this->JqImgcrop->uploadImage($this->data['Image']['image'], '/img/uploaded/', ''); 
	  $this->set('uploaded',$uploaded); 
	  }
	
	  $this->set(compact('id'));
	  $this->set(compact('uid'));
	}
	
 /**  
  function to uplaod the multiple images for a single cause
  **/
   function multiimages($id=null){
	     
		 $uplaoded_path='/img/uploaded/';
		 $uplaoded_paths =  WWW_ROOT.str_replace("/", DS,$uplaoded_path);
		 $model="Cause";
		 $tmp_name=$this->data['Image']['images']['tmp_name'];
		//file_put_contents('me.txt',$this->data['Image']['images']['tmp_name']);
		 $uplaoded_target=$uplaoded_paths.$this->data['Image']['images']['name'];
		// file_put_contents('me.txt',$uplaoded_target);
		if(move_uploaded_file($tmp_name,$uplaoded_target))
		 {
           file_put_contents('sme.txt',$id);
				if($id!=null){
					$userid = $this->Cause->find('first', array(
							  'conditions' => array('Cause.id' => $id)	));
							  $uid = $userid['User']['id'];
							 }
					
					 #set values to save the multiple values in the database	      
					
					$count = $this->Image->find('count', array('conditions' => array('Image.foreign_key' => $id, 'model' => $model, 'Image.is_primary' => true)));
						$isPrimary = $count == 0 ? true : false;
						file_put_contents('sme.txt',$id."n".$this->data['Image']['images']['name']."n".$uid."n".$model."n".$isPrimary);
						
						
						$data = array();
						$data['Image'] = array(
							'filename' => $this->data['Image']['images']['name'],
							'user_id' => $uid,
							'foreign_key' => $id,
							'model' => $model,
							'is_primary' => $isPrimary
						);
					 $this->Image->save($data);
	 
	     }
	 }

#function for ajax upload
 function viewImages($id=NULL)
	 {
	   	 if($this->RequestHandler->isAjax())
				{ $this->layout='';}
	  
	    $images = $this->Image->find('all',array('conditions'=>'foreign_key='.$id.' and is_primary=0'));
     	$this->set('images',$images);
	    $this->set('imageid',$id);
	   //$this->loadModel('PropertyImage');
	   //$this->set('images',$this->PropertyImage->findAllByPropertyId($id)); 
	 }  


#function to delete the image
   function deleteImages($id=NULL,$imgid=null)
	   {
	     //$this->loadModel('Image');
	     //$this->autoRender=false;
		 //$this->redirect($this->referer());	   
	     $this->Image->delete($id);
		 ?>
		 <script>
		 window.location="/index.php/images/createimage_step3/<?php echo $imgid ?>";
		 </script>
		 <?php
	  }	
}
