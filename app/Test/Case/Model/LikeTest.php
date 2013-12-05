<?php
App::uses('Like', 'Model');

/**
 * Like Test Case
 *
 */
class LikeTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.like',
		'app.user',
		'app.item',
		'app.style',
		'app.gender',
		'app.brand',
		'app.size',
		'app.category',
		'app.comment',
		'app.image'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Like = ClassRegistry::init('Like');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Like);

		parent::tearDown();
	}

}
