<?php
App::uses('BaseAuthenticate', 'Controller/Component/Auth');

class FacebookAuthenticate extends BaseAuthenticate{
	public $settings = array(
		'facebook' => array(
			'scope' => null,
			'redirect_uri' => null,
			'display' => 'page'
		),
		'userModel' => 'User',
		'fields' => array(
			'name' => 'name',
			'first_name' => 'first_name',
			'last_name' => 'last_name',
			'birthday' => 'birthday',
			'link' => 'facebook_profile_url',
			'email' => 'email',
			'username' => 'username',
			'id' => 'facebook_id'
		)
	);	
	private $appId;
	private $appSecret;
	
	
	public function __construct(ComponentCollection $collection, $settings) {
		parent::__construct($collection, $settings);
		extract(Configure::read('Facebook'));
		
		if(!isset($appId) || !isset($appSecret)){
			throw new InternalErrorException('You need to configure config/facebook.php properly.');
		}
		$this->appId = $appId;
		$this->appSecret = $appSecret;
		$this->settings['facebook']['redirect_uri'] = Router::url(null, true);
	}
	
	public function authenticate(CakeRequest $request, CakeResponse $response){
		$result = $this->getUser($request);
		
		if(empty($result)){
			$response->header('Location', $this->getLoginUrl());
			$response->statusCode(401);
			$response->send();
			return false;
		}
		
		$user = $this->_findUser(array('facebook_id' => $result['id']));
		if(empty($user)){
			$data = array();
			$userModel = $this->settings['userModel'];
			
			if($this->_saveUser($result)){
				$user = $this->_findUser(array('facebook_id' => $result['id']));
			}
		}
		return $user;
	}
	
	protected function _saveUser($facebookData){
		$userModel = $this->settings['userModel'];
		$data = array(
			$userModel => array()
		);
		foreach($this->settings['fields'] as $facebookField => $databaseField){
			if(isset($facebookData[$facebookField])){
				$data[$userModel][$databaseField] = $facebookData[$facebookField];
			}
		}
		
		return ClassRegistry::init($userModel)->save($data);	
	}
	
	protected function _findUser($conditions, $password = NULL){
		$userModel = $this->settings['userModel'];
		$facebookId = $conditions['facebook_id'];
		$conditions = array(
			$userModel . '.' . $this->settings['fields']['id'] => $facebookId
		);
		$user = ClassRegistry::init($userModel)->find('first', array(
			'conditions' => $conditions
		));
		
		if(empty($user))
			return $user;
		
		foreach($user[$userModel] as $key => $value){
			$user[$key] = $value;
		}
		
		return $user;
	}
	
	public function getUser(CakeRequest $request){
		if(empty($request->query['code']))
			return false;
		$code = $request->query['code'];
		
		$accessTokenUrl = "https://graph.facebook.com/oauth/access_token?"
			. "client_id=" . $this->appId . "&redirect_uri=" . urlencode(Router::url(null, true))
			. "&client_secret=" . $this->appSecret . "&code=" . $code;
		
		$response = file_get_contents($accessTokenUrl);
		parse_str($response, $params);
		$token = $params['access_token'];
		$result = file_get_contents("https://graph.facebook.com/me?access_token=" . $token);
		$user = json_decode($result, true);
		$user['access_token'] = $token;
		
		return $user;
	}
	
	public function getLoginUrl(){		
		$path = 'https://www.facebook.com/dialog/oauth?';	
		$path .= 'client_id=' . $this->appId;
		
		$params = $this->settings['facebook'];
		foreach($params as  $key => $value){
			$path .= '&' . $key . '=' . urlencode($value);
		}
		
		return $path;
	}
}