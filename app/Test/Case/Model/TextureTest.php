<?php
App::uses('Texture', 'Model');

/**
 * Texture Test Case
 *
 */
class TextureTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.texture',
		'app.design',
		'app.user',
		'app.group',
		'app.comment',
		'app.item',
		'app.image',
		'app.product',
		'app.style',
		'app.gender',
		'app.brand',
		'app.size',
		'app.category',
		'app.like',
		'app.cause'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Texture = ClassRegistry::init('Texture');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Texture);

		parent::tearDown();
	}

}
