<?php
/**
 * ItemFixture
 *
 */
class ItemFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'key' => 'primary'),
		'title' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'description' => array('type' => 'text', 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
		'price' => array('type' => 'float', 'null' => false, 'default' => null, 'length' => '6,2'),
		'style_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'gender_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'brand_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'size_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'category_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null),
		'likes' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'comments' => array('type' => 'integer', 'null' => true, 'default' => '0'),
		'is_active' => array('type' => 'boolean', 'null' => false, 'default' => '1'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'title' => 'Lorem ipsum dolor sit amet',
			'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'price' => 1,
			'style_id' => 1,
			'gender_id' => 1,
			'brand_id' => 1,
			'size_id' => 1,
			'category_id' => 1,
			'user_id' => 1,
			'likes' => 1,
			'comments' => 1,
			'is_active' => 1,
			'created' => '2013-02-04 22:35:45',
			'modified' => '2013-02-04 22:35:45'
		),
	);

}
