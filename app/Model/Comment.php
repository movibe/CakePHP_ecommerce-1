<?php
App::uses('AppModel', 'Model');
/**
 * Comment Model
 *
 * @property User $User
 * @property Item $Item
 * @property Comment $ParentComment
 * @property Comment $ChildComment
 */
class Comment extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'comment' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

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
				'comments' => array('Comment.model' => 'Product')
			)
		),
		'Design' => array(
			'className' => 'Design',
			'foreignKey' => 'foreign_key',
			'fields' => '',
			'order' => '',
			'counterCache' => array(
				'comments' => array('Comment.model' => 'Design')
			)
		),
		'Cause' => array(
			'className' => 'Cause',
			'foreignKey' => 'foreign_key',
			'fields' => '',
			'order' => '',
			'counterCache' => array(
				'comments' => array('Comment.model' => 'Cause')
			)
		),
		'ParentComment' => array(
			'className' => 'Comment',
			'foreignKey' => 'parent_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'ChildComment' => array(
			'className' => 'Comment',
			'foreignKey' => 'parent_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

}
