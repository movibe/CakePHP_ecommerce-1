<?php
App::uses('Style', 'Model');

/**
 * Style Test Case
 *
 */
class StyleTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.style',
		'app.item',
		'app.gender',
		'app.brand',
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
		$this->Style = ClassRegistry::init('Style');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Style);

		parent::tearDown();
	}

}
