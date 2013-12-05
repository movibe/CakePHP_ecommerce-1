<?php
App::uses('Gender', 'Model');

/**
 * Gender Test Case
 *
 */
class GenderTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.gender',
		'app.item',
		'app.style',
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
		$this->Gender = ClassRegistry::init('Gender');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Gender);

		parent::tearDown();
	}

}
