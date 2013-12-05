<?php
App::uses('Size', 'Model');

/**
 * Size Test Case
 *
 */
class SizeTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.size',
		'app.item',
		'app.style',
		'app.gender',
		'app.brand',
		'app.category',
		'app.user',
		'app.group',
		'app.comment',
		'app.image',
		'app.like'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Size = ClassRegistry::init('Size');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Size);

		parent::tearDown();
	}

}
