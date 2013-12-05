<?php
App::uses('AppModel', 'Model');
/**
 * Like Model
 *
 * @property User $User
 * @property Item $Item
 */
class Like extends AppModel {


	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Product' => array(
			'className' => 'Product',
			'foreignKey' => 'foreign_key',
			'fields' => '',
			'order' => '',
			'counterCache' => array(
				'likes' => array('Like.like_type' => true, 'Like.model' => 'Product')
			)
		),
		'Design' => array(
			'className' => 'Design',
			'foreignKey' => 'foreign_key',
			'fields' => '',
			'order' => '',
			'counterCache' => array(
				'likes' => array('Like.like_type' => true, 'Like.model' => 'Design')
			)
		),
		'Cause' => array(
			'className' => 'Cause',
			'foreignKey' => 'foreign_key',
			'fields' => '',
			'order' => '',
			'counterCache' => array(
				'likes' => array('Like.like_type' => true, 'Like.model' => 'Cause')
			)
		)
	);
}
