<?php
App::uses('Brand', 'Model');

/**
 * Brand Test Case
 *
 */
class BrandTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.brand',
		'app.item',
		'app.style',
		'app.gender',
		'app.size',
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
		$this->Brand = ClassRegistry::init('Brand');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Brand);

		parent::tearDown();
	}

}
