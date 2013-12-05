<?php
App::uses('AppController', 'Controller');
App::import('Vendor', 'phpmailer', array('file' => 'phpmailer' . DS .  'class.phpmailer.php'));
/**
 * Emails Controller
 *
 * @property Email $Email
 */
class EmailsController extends AppController {

/**
 * index method
 *
 * @return void
 */
	
	public function index() {
		$this->Email->recursive = 0;
		$this->set('emails', $this->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->Email->id = $id;
		if (!$this->Email->exists()) {
			throw new NotFoundException(__('Invalid email'));
		}
		$this->set('email', $this->Email->read(null, $id));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Email->create();
			if ($this->Email->save($this->request->data)) {
				$this->Session->setFlash(__('The email has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The email could not be saved. Please, try again.'));
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
		$this->Email->id = $id;
		if (!$this->Email->exists()) {
			throw new NotFoundException(__('Invalid email'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Email->save($this->request->data)) {
				$this->Session->setFlash(__('The email has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The email could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Email->read(null, $id);
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
		$this->Email->id = $id;
		if (!$this->Email->exists()) {
			throw new NotFoundException(__('Invalid email'));
		}
		if ($this->Email->delete()) {
			$this->Session->setFlash(__('Email deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Email was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
	public function admin_send_email(){
		$this->layout = "admin";
		//debug($this->User->find("all"));
		if ($this->request->is('post') || $this->request->is('put')) {
			$emailsData = $this->request->data;
			$subject = $emailsData['Users']['Subject'];
			$message = $emailsData['Users']['Message'];
			foreach($emailsData['email'] as $email){
				$data['Email'] = array();
				$data['Email']['to'] = $email;
				$data['Email']['subject'] = $subject;
				$data['Email']['body'] = $message;
				$this->Email->create();
				if ($this->Email->save($data)) {
					$this->Session->setFlash(__('The email has been saved'));
					$this->redirect(array('controller' => 'Users','action' => 'admin_users_list'));
				} else {
					$this->Session->setFlash(__('The email could not be saved. Please, try again.'));
					$this->redirect(array('controller' => 'Users','action' => 'admin_users_list'));
				}
			}
			debug($emailsData);
		}
	}
	
/* 	public function send_email(){
		ini_set('max_execution_time', -1); 
		$this->layout = "cake";
		$emailDetails = $this->Email->find("all",array('condition' => array('Email.is_sent' => 0),'limit' => 10,'offset' => 0));
		$mail = new PHPMailer();
		foreach($emailDetails as $emailDetail){
			$mail->From = $emailDetail['Email']['from'];
			$mail->FromName = $emailDetail['Email']['from'];
			$mail->AddAddress($emailDetail['Email']['to']);
			$mail->WordWrap = 50;   
			$mail->IsHTML(true);
			$mail->Priority = 1;
			if(trim($emailDetail['Email']['headers']) !="")
				$mail->AddCustomHeader($emailDetail['Email']['headers']);
			$mail->Subject = $emailDetail['Email']['subject'];
			$mail->Body = $emailDetail['Email']['body']; 
			$this->Email->id = $emailDetail['Email']['id'];
			$attempts = $emailDetail['Email']['attempts'];
			if($attempts > 10){
				$status['Email'] = array();
				$status['Email']['is_sent'] = $attempts;
				$this->Email->save($status);
			}
			else{
				sleep(10);
				if($mail->Send()){
					$status['Email'] = array();
					$status['Email']['is_sent'] = 1;
					$this->Email->save($status);
				}
				else{
					$status['Email'] = array();
					$status['Email']['attempts'] = $attempts+1;
					$this->Email->save($status);
				}
			}
		}
	} */
	
	public function send(){
		App::uses('CakeEmail', 'Network/Email');
		//$email = new CakeEmail();
		$email = new CakeEmail();
		$email->sender('app@example.com', 'MyApp emailer');
		$email->from(array('me@example.com' => 'My Site'))
			->to('you@example.com')
			->subject('About')
			->send('My message');	
		if($email->send()){
			$this->render('../pages/contact');
		}
	}
	public function send_mail($receiver = null, $name = null, $pass = null) {
		
		//exit();
		$data = $this->request->data;
		debug($data);
		$receiver= 'maturi.kiran@gmail.com';
        $confirmation_link = "http://" . $_SERVER['HTTP_HOST'] . $this->webroot . "users/login/";
        $message = 'Hi,' . $name . ', Your Password is: ' . $pass;
        App::uses('CakeEmail', 'Network/Email');
        $email = new CakeEmail();
		$email->config(array('from' => 'no-reply@earlydove.com'));
        $email->Username='no-reply@earlydove.com';
        $email->to($receiver);
        $email->subject('Mail Confirmation');
        $email->send($message . " " . $confirmation_link);
    }
}
