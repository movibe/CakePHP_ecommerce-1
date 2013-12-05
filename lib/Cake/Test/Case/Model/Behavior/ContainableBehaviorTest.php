<?php
/**
 * ContainableBehaviorTest file
 *
 * PHP 5
 *
 * CakePHP(tm) Tests <http://book.cakephp.org/2.0/en/development/testing.html>
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://book.cakephp.org/2.0/en/development/testing.html CakePHP(tm) Tests
 * @package       Cake.Test.Case.Model.Behavior
 * @since         CakePHP(tm) v 1.2.0.5669
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Model', 'Model');
App::uses('AppModel', 'Model');
require_once dirname(dirname(__FILE__)) . DS . 'models.php';

/**
 * ContainableTest class
 *
 * @package       Cake.Test.Case.Model.Behavior
 */
class ContainableBehaviorTest extends CakeTestCase {

/**
 * Fixtures associated with this test case
 *
 * @var array
 */
	public $fixtures = array(
		'core.article', 'core.article_featured', 'core.article_featureds_tags',
		'core.articles_tag', 'core.attachment', 'core.category',
		'core.comment', 'core.featured', 'core.tag', 'core.user',
		'core.join_a', 'core.join_b', 'core.join_c', 'core.join_a_c', 'core.join_a_b'
	);

/**
 * Method executed before each test
 *
 */
	public function setUp() {
		parent::setUp();
		$this->User = ClassRegistry::init('User');
		$this->Article = ClassRegistry::init('Article');
		$this->Tag = ClassRegistry::init('Tag');

		$this->User->bindModel(array(
			'hasMany' => array('Article', 'ArticleFeatured', 'Comment')
		), false);
		$this->User->ArticleFeatured->unbindModel(array('belongsTo' => array('Category')), false);
		$this->User->ArticleFeatured->hasMany['Comment']['foreignKey'] = 'article_id';

		$this->Tag->bindModel(array(
			'hasAndBelongsToMany' => array('Article')
		), false);

		$this->User->Behaviors->attach('Containable');
		$this->Article->Behaviors->attach('Containable');
		$this->Tag->Behaviors->attach('Containable');
	}

/**
 * Method executed after each test
 *
 */
	public function tearDown() {
		unset($this->Article);
		unset($this->User);
		unset($this->Tag);
		parent::tearDown();
	}

/**
 * testContainments method
 *
 * @return void
 */
	public function testContainments() {
		$r = $this->_containments($this->Article, array('Comment' => array('conditions' => array('Comment.user_id' => 2))));
		$this->assertTrue(Set::matches('/Article/keep/Comment/conditions[Comment.user_id=2]', $r));

		$r = $this->_containments($this->User, array(
			'ArticleFeatured' => array(
				'Featured' => array(
					'id',
					'Category' => 'name'
				)
		)));
		$this->assertEquals(array('id'), Hash::extract($r, 'ArticleFeatured.keep.Featured.fields'));

		$r = $this->_containments($this->Article, array(
			'Comment' => array(
				'User',
				'conditions' => array('Comment' => array('user_id' => 2)),
			),
		));
		$this->assertTrue(Set::matches('/User', $r));
		$this->assertTrue(Set::matches('/Comment', $r));
		$this->assertTrue(Set::matches('/Article/keep/Comment/conditions/Comment[user_id=2]', $r));

		$r = $this->_containments($this->Article, array('Comment(comment, published)' => 'Attachment(attachment)', 'User(user)'));
		$this->assertTrue(Set::matches('/Comment', $r));
		$this->assertTrue(Set::matches('/User', $r));
		$this->assertTrue(Set::matches('/Article/keep/Comment', $r));
		$this->assertTrue(Set::matches('/Article/keep/User', $r));
		$this->assertEquals(array('comment', 'published'), Hash::extract($r, 'Article.keep.Comment.fields'));
		$this->assertEquals(array('user'), Hash::extract($r, 'Article.keep.User.fields'));
		$this->assertTrue(Set::matches('/Comment/keep/Attachment', $r));
		$this->assertEquals(array('attachment'), Hash::extract($r, 'Comment.keep.Attachment.fields'));

		$r = $this->_containments($this->Article, array('Comment' => array('limit' => 1)));
		$this->assertEquals(array('Comment', 'Article'), array_keys($r));
		$result = Hash::extract($r, 'Comment[keep]');
		$this->assertEquals(array('keep' => array()), array_shift($result));
		$this->assertTrue(Set::matches('/Article/keep/Comment', $r));
		$result = Hash::extract($r, 'Article.keep');
		$this->assertEquals(array('limit' => 1), array_shift($result));

		$r = $this->_containments($this->Article, array('Comment.User'));
		$this->assertEquals(array('User', 'Comment', 'Article'), array_keys($r));

		$result = Hash::extract($r, 'User[keep]');
		$this->assertEquals(array('keep' => array()), array_shift($result));

		$result = Hash::extract($r, 'Comment[keep]');
		$this->assertEquals(array('keep' => array('User' => array())), array_shift($result));

		$result = Hash::extract($r, 'Article[keep]');
		$this->assertEquals(array('keep' => array('Comment' => array())), array_shift($result));

		$r = $this->_containments($this->Tag, array('Article' => array('User' => array('Comment' => array(
			'Attachment' => array('conditions' => array('Attachment.id >' => 1))
		)))));
		$this->assertTrue(Set::matches('/Attachment', $r));
		$this->assertTrue(Set::matches('/Comment/keep/Attachment/conditions', $r));
		$this->assertEquals(array('Attachment.id >' => 1), $r['Comment']['keep']['Attachment']['conditions']);
		$this->assertTrue(Set::matches('/User/keep/Comment', $r));
		$this->assertTrue(Set::matches('/Article/keep/User', $r));
		$this->assertTrue(Set::matches('/Tag/keep/Article', $r));
	}

/**
 * testInvalidContainments method
 *
 * @expectedException PHPUnit_Framework_Error
 * @return void
 */
	public function testInvalidContainments() {
		$this->_containments($this->Article, array('Comment', 'InvalidBinding'));
	}

/**
 * testInvalidContainments method with suppressing error notices
 *
 * @return void
 */
	public function testInvalidContainmentsNoNotices() {
		$this->Article->Behaviors->attach('Containable', array('notices' => false));
		$this->_containments($this->Article, array('Comment', 'InvalidBinding'));
	}

/**
 * testBeforeFind method
 *
 * @return void
 */
	public function testBeforeFind() {
		$r = $this->Article->find('all', array('contain' => array('Comment')));
		$this->assertFalse(Set::matches('/User', $r));
		$this->assertTrue(Set::matches('/Comment', $r));
		$this->assertFalse(Set::matches('/Comment/User', $r));

		$r = $this->Article->find('all', array('contain' => 'Comment.User'));
		$this->assertTrue(Set::matches('/Comment/User', $r));
		$this->assertFalse(Set::matches('/Comment/Article', $r));

		$r = $this->Article->find('all', array('contain' => array('Comment' => array('User', 'Article'))));
		$this->assertTrue(Set::matches('/Comment/User', $r));
		$this->assertTrue(Set::matches('/Comment/Article', $r));

		$r = $this->Article->find('all', array('contain' => array('Comment' => array('conditions' => array('Comment.user_id' => 2)))));
		$this->assertFalse(Set::matches('/Comment[user_id!=2]', $r));
		$this->assertTrue(Set::matches('/Comment[user_id=2]', $r));

		$r = $this->Article->find('all', array('contain' => array('Comment.user_id = 2')));
		$this->assertFalse(Set::matches('/Comment[user_id!=2]', $r));

		$r = $this->Article->find('all', array('contain' => 'Comment.id DESC'));
		$ids = $descIds = Hash::extract($r, 'Comment[1].id');
		rsort($descIds);
		$this->assertEquals($ids, $descIds);

		$r = $this->Article->find('all', array('contain' => 'Comment'));
		$this->assertTrue(Set::matches('/Comment[user_id!=2]', $r));

		$r = $this->Article->find('all', array('contain' => array('Comment' => array('fields' => 'comment'))));
		$this->assertFalse(Set::matches('/Comment/created', $r));
		$this->assertTrue(Set::matches('/Comment/comment', $r));
		$this->assertFalse(Set::matches('/Comment/updated', $r));

		$r = $this->Article->find('all', array('contain' => array('Comment' => array('fields' => array('comment', 'updated')))));
		$this->assertFalse(Set::matches('/Comment/created', $r));
		$this->assertTrue(Set::matches('/Comment/comment', $r));
		$this->assertTrue(Set::matches('/Comment/updated', $r));

		$r = $this->Article->find('all', array('contain' => array('Comment' => array('comment', 'updated'))));
		$this->assertFalse(Set::matches('/Comment/created', $r));
		$this->assertTrue(Set::matches('/Comment/comment', $r));
		$this->assertTrue(Set::matches('/Comment/updated', $r));

		$r = $this->Article->find('all', array('contain' => array('Comment(comment,updated)')));
		$this->assertFalse(Set::matches('/Comment/created', $r));
		$this->assertTrue(Set::matches('/Comment/comment', $r));
		$this->assertTrue(Set::matches('/Comment/updated', $r));

		$r = $this->Article->find('all', array('contain' => 'Comment.created'));
		$this->assertTrue(Set::matches('/Comment/created', $r));
		$this->assertFalse(Set::matches('/Comment/comment', $r));

		$r = $this->Article->find('all', array('contain' => array('User.Article(title)', 'Comment(comment)')));
		$this->assertFalse(Set::matches('/Comment/Article', $r));
		$this->assertFalse(Set::matches('/Comment/User', $r));
		$this->assertTrue(Set::matches('/Comment/comment', $r));
		$this->assertFalse(Set::matches('/Comment/created', $r));
		$this->assertTrue(Set::matches('/User/Article/title', $r));
		$this->assertFalse(Set::matches('/User/Article/created', $r));

		$r = $this->Article->find('all', array('contain' => array()));
		$this->assertFalse(Set::matches('/User', $r));
		$this->assertFalse(Set::matches('/Comment', $r));
	}

/**
 * testBeforeFindWithNonExistingBinding method
 *
 * @expectedException PHPUnit_Framework_Error
 * @return void
 */
	public function testBeforeFindWithNonExistingBinding() {
		$this->Article->find('all', array('contain' => array('Comment' => 'NonExistingBinding')));
	}

/**
 * testContain method
 *
 * @return void
 */
	public function testContain() {
		$this->Article->contain('Comment.User');
		$r = $this->Article->find('all');
		$this->assertTrue(Set::matches('/Comment/User', $r));
		$this->assertFalse(Set::matches('/Comment/Article', $r));

		$r = $this->Article->find('all');
		$this->assertFalse(Set::matches('/Comment/User', $r));
	}

/**
 * testContainFindList method
 *
 * @return void
 */
	public function testContainFindList() {
		$this->Article->contain('Comment.User');
		$result = $this->Article->find('list');
		$expected = array(
			1 => 'First Article',
			2 => 'Second Article',
			3 => 'Third Article'
		);
		$this->assertEquals($expected, $result);

		$result = $this->Article->find('list', array('fields' => array('Article.id', 'User.id'), 'contain' => array('User')));
		$expected = array(
			1 => '1',
			2 => '3',
			3 => '1'
		);
		$this->assertEquals($expected, $result);
	}

/**
 * Test that mixing contain() and the contain find option.
 *
 * @return void
 */
	public function testContainAndContainOption() {
		$this->Article->contain();
		$r = $this->Article->find('all', array(
			'contain' => array('Comment')
		));
		$this->assertTrue(isset($r[0]['Comment']), 'No comment returned');
	}

/**
 * testFindEmbeddedNoBindings method
 *
 * @return void
 */
	public function testFindEmbeddedNoBindings() {
		$result = $this->Article->find('all', array('contain' => false));
		$expected = array(
			array('Article' => array(
				'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
				'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
			)),
			array('Article' => array(
				'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
				'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31'
			)),
			array('Article' => array(
				'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
				'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'
			))
		);
		$this->assertEquals($expected, $result);
	}

/**
 * testFindFirstLevel method
 *
 * @return void
 */
	public function testFindFirstLevel() {
		$this->Article->contain('User');
		$result = $this->Article->find('all', array('recursive' => 1));
		$expected = array(
			array(
				'Article' => array(
					'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
					'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
				),
				'User' => array(
					'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'
				)
			),
			array(
				'Article' => array(
					'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
					'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31'
				),
				'User' => array(
					'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31'
				)
			),
			array(
				'Article' => array(
					'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
					'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'
				),
				'User' => array(
					'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'
				)
			)
		);
		$this->assertEquals($expected, $result);

		$this->Article->contain('User', 'Comment');
		$result = $this->Article->find('all', array('recursive' => 1));
		$expected = array(
			array(
				'Article' => array(
					'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
					'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
				),
				'User' => array(
					'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'
				),
				'Comment' => array(
					array(
						'id' => 1, 'article_id' => 1, 'user_id' => 2, 'comment' => 'First Comment for First Article',
						'published' => 'Y', 'created' => '2007-03-18 10:45:23', 'updated' => '2007-03-18 10:47:31'
					),
					array(
						'id' => 2, 'article_id' => 1, 'user_id' => 4, 'comment' => 'Second Comment for First Article',
						'published' => 'Y', 'created' => '2007-03-18 10:47:23', 'updated' => '2007-03-18 10:49:31'
					),
					array(
						'id' => 3, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Third Comment for First Article',
						'published' => 'Y', 'created' => '2007-03-18 10:49:23', 'updated' => '2007-03-18 10:51:31'
					),
					array(
						'id' => 4, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Fourth Comment for First Article',
						'published' => 'N', 'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31'
					)
				)
			),
			array(
				'Article' => array(
					'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
					'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31'
				),
				'User' => array(
					'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31'
				),
				'Comment' => array(
					array(
						'id' => 5, 'article_id' => 2, 'user_id' => 1, 'comment' => 'First Comment for Second Article',
						'published' => 'Y', 'created' => '2007-03-18 10:53:23', 'updated' => '2007-03-18 10:55:31'
					),
					array(
						'id' => 6, 'article_id' => 2, 'user_id' => 2, 'comment' => 'Second Comment for Second Article',
						'published' => 'Y', 'created' => '2007-03-18 10:55:23', 'updated' => '2007-03-18 10:57:31'
					)
				)
			),
			array(
				'Article' => array(
					'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
					'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'
				),
				'User' => array(
					'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'
				),
				'Comment' => array()
			)
		);
		$this->assertEquals($expected, $result);
	}

/**
 * testFindEmbeddedFirstLevel method
 *
 * @return void
 */
	public function testFindEmbeddedFirstLevel() {
		$result = $this->Article->find('all', array('contain' => array('User')));
		$expected = array(
			array(
				'Article' => array(
					'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
					'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
				),
				'User' => array(
					'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'
				)
			),
			array(
				'Article' => array(
					'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
					'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31'
				),
				'User' => array(
					'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31'
				)
			),
			array(
				'Article' => array(
					'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
					'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'
				),
				'User' => array(
					'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'
				)
			)
		);
		$this->assertEquals($expected, $result);

		$result = $this->Article->find('all', array('contain' => array('User', 'Comment')));
		$expected = array(
			array(
				'Article' => array(
					'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
					'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
				),
				'User' => array(
					'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'
				),
				'Comment' => array(
					array(
						'id' => 1, 'article_id' => 1, 'user_id' => 2, 'comment' => 'First Comment for First Article',
						'published' => 'Y', 'created' => '2007-03-18 10:45:23', 'updated' => '2007-03-18 10:47:31'
					),
					array(
						'id' => 2, 'article_id' => 1, 'user_id' => 4, 'comment' => 'Second Comment for First Article',
						'published' => 'Y', 'created' => '2007-03-18 10:47:23', 'updated' => '2007-03-18 10:49:31'
					),
					array(
						'id' => 3, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Third Comment for First Article',
						'published' => 'Y', 'created' => '2007-03-18 10:49:23', 'updated' => '2007-03-18 10:51:31'
					),
					array(
						'id' => 4, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Fourth Comment for First Article',
						'published' => 'N', 'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31'
					)
				)
			),
			array(
				'Article' => array(
					'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
					'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31'
				),
				'User' => array(
					'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31'
				),
				'Comment' => array(
					array(
						'id' => 5, 'article_id' => 2, 'user_id' => 1, 'comment' => 'First Comment for Second Article',
						'published' => 'Y', 'created' => '2007-03-18 10:53:23', 'updated' => '2007-03-18 10:55:31'
					),
					array(
						'id' => 6, 'article_id' => 2, 'user_id' => 2, 'comment' => 'Second Comment for Second Article',
						'published' => 'Y', 'created' => '2007-03-18 10:55:23', 'updated' => '2007-03-18 10:57:31'
					)
				)
			),
			array(
				'Article' => array(
					'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
					'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'
				),
				'User' => array(
					'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'
				),
				'Comment' => array()
			)
		);
		$this->assertEquals($expected, $result);
	}

/**
 * testFindSecondLevel method
 *
 * @return void
 */
	public function testFindSecondLevel() {
		$this->Article->contain(array('Comment' => 'User'));
		$result = $this->Article->find('all', array('recursive' => 2));
		$expected = array(
			array(
				'Article' => array(
					'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
					'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
				),
				'Comment' => array(
					array(
						'id' => 1, 'article_id' => 1, 'user_id' => 2, 'comment' => 'First Comment for First Article',
						'published' => 'Y', 'created' => '2007-03-18 10:45:23', 'updated' => '2007-03-18 10:47:31',
						'User' => array(
							'id' => 2, 'user' => 'nate', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
							'created' => '2007-03-17 01:18:23', 'updated' => '2007-03-17 01:20:31'
						)
					),
					array(
						'id' => 2, 'article_id' => 1, 'user_id' => 4, 'comment' => 'Second Comment for First Article',
						'published' => 'Y', 'created' => '2007-03-18 10:47:23', 'updated' => '2007-03-18 10:49:31',
						'User' => array(
							'id' => 4, 'user' => 'garrett', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
							'created' => '2007-03-17 01:22:23', 'updated' => '2007-03-17 01:24:31'
						)
					),
					array(
						'id' => 3, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Third Comment for First Article',
						'published' => 'Y', 'created' => '2007-03-18 10:49:23', 'updated' => '2007-03-18 10:51:31',
						'User' => array(
							'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
							'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'
						)
					),
					array(
						'id' => 4, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Fourth Comment for First Article',
						'published' => 'N', 'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31',
						'User' => array(
							'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
							'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'
						)
					)
				)
			),
			array(
				'Article' => array(
					'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
					'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31'
				),
				'Comment' => array(
					array(
						'id' => 5, 'article_id' => 2, 'user_id' => 1, 'comment' => 'First Comment for Second Article',
						'published' => 'Y', 'created' => '2007-03-18 10:53:23', 'updated' => '2007-03-18 10:55:31',
						'User' => array(
							'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
							'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'
						)
					),
					array(
						'id' => 6, 'article_id' => 2, 'user_id' => 2, 'comment' => 'Second Comment for Second Article',
						'published' => 'Y', 'created' => '2007-03-18 10:55:23', 'updated' => '2007-03-18 10:57:31',
						'User' => array(
							'id' => 2, 'user' => 'nate', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
							'created' => '2007-03-17 01:18:23', 'updated' => '2007-03-17 01:20:31'
						)
					)
				)
			),
			array(
				'Article' => array(
					'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
					'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'
				),
				'Comment' => array()
			)
		);
		$this->assertEquals($expected, $result);

		$this->Article->contain(array('User' => 'ArticleFeatured'));
		$result = $this->Article->find('all', array('recursive' => 2));
		$expected = array(
			array(
				'Article' => array(
					'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
					'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
				),
				'User' => array(
					'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
					'ArticleFeatured' => array(
						array(
							'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
							'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
						),
						array(
							'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
							'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'
						)
					)
				)
			),
			array(
				'Article' => array(
					'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
					'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31'
				),
				'User' => array(
					'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31',
					'ArticleFeatured' => array(
						array(
						'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31'
						)
					)
				)
			),
			array(
				'Article' => array(
					'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
					'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'
				),
				'User' => array(
					'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
					'ArticleFeatured' => array(
						array(
							'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
							'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
						),
						array(
							'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
							'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'
						)
					)
				)
			)
		);
		$this->assertEquals($expected, $result);

		$this->Article->contain(array('User' => array('ArticleFeatured', 'Comment')));
		$result = $this->Article->find('all', array('recursive' => 2));
		$expected = array(
			array(
				'Article' => array(
					'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
					'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
				),
				'User' => array(
					'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
					'ArticleFeatured' => array(
						array(
							'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
							'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
						),
						array(
							'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
							'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'
						)
					),
					'Comment' => array(
						array(
							'id' => 3, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Third Comment for First Article',
							'published' => 'Y', 'created' => '2007-03-18 10:49:23', 'updated' => '2007-03-18 10:51:31'
						),
						array(
							'id' => 4, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Fourth Comment for First Article',
							'published' => 'N', 'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31'
						),
						array(
							'id' => 5, 'article_id' => 2, 'user_id' => 1, 'comment' => 'First Comment for Second Article',
							'published' => 'Y', 'created' => '2007-03-18 10:53:23', 'updated' => '2007-03-18 10:55:31'
						)
					)
				)
			),
			array(
				'Article' => array(
					'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
					'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31'
				),
				'User' => array(
					'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31',
					'ArticleFeatured' => array(
						array(
						'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31'
						)
					),
					'Comment' => array()
				)
			),
			array(
				'Article' => array(
					'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
					'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'
				),
				'User' => array(
					'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
					'ArticleFeatured' => array(
						array(
							'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
							'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
						),
						array(
							'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
							'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'
						)
					),
					'Comment' => array(
						array(
							'id' => 3, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Third Comment for First Article',
							'published' => 'Y', 'created' => '2007-03-18 10:49:23', 'updated' => '2007-03-18 10:51:31'
						),
						array(
							'id' => 4, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Fourth Comment for First Article',
							'published' => 'N', 'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31'
						),
						array(
							'id' => 5, 'article_id' => 2, 'user_id' => 1, 'comment' => 'First Comment for Second Article',
							'published' => 'Y', 'created' => '2007-03-18 10:53:23', 'updated' => '2007-03-18 10:55:31'
						)
					)
				)
			)
		);
		$this->assertEquals($expected, $result);

		$this->Article->contain(array('User' => array('ArticleFeatured')), 'Tag', array('Comment' => 'Attachment'));
		$result = $this->Article->find('all', array('recursive' => 2));
		$expected = array(
			array(
				'Article' => array(
					'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
					'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
				),
				'User' => array(
					'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
					'ArticleFeatured' => array(
						array(
							'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
							'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
						),
						array(
							'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
							'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'
						)
					)
				),
				'Comment' => array(
					array(
						'id' => 1, 'article_id' => 1, 'user_id' => 2, 'comment' => 'First Comment for First Article',
						'published' => 'Y', 'created' => '2007-03-18 10:45:23', 'updated' => '2007-03-18 10:47:31',
						'Attachment' => array()
					),
					array(
						'id' => 2, 'article_id' => 1, 'user_id' => 4, 'comment' => 'Second Comment for First Article',
						'published' => 'Y', 'created' => '2007-03-18 10:47:23', 'updated' => '2007-03-18 10:49:31',
						'Attachment' => array()
					),
					array(
						'id' => 3, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Third Comment for First Article',
						'published' => 'Y', 'created' => '2007-03-18 10:49:23', 'updated' => '2007-03-18 10:51:31',
						'Attachment' => array()
					),
					array(
						'id' => 4, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Fourth Comment for First Article',
						'published' => 'N', 'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31',
						'Attachment' => array()
					)
				),
				'Tag' => array(
					array('id' => 1, 'tag' => 'tag1', 'created' => '2007-03-18 12:22:23', 'updated' => '2007-03-18 12:24:31'),
					array('id' => 2, 'tag' => 'tag2', 'created' => '2007-03-18 12:24:23', 'updated' => '2007-03-18 12:26:31')
				)
			),
			array(
				'Article' => array(
					'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
					'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31'
				),
				'User' => array(
					'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31',
					'ArticleFeatured' => array(
						array(
						'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31'
						)
					)
				),
				'Comment' => array(
					array(
						'id' => 5, 'article_id' => 2, 'user_id' => 1, 'comment' => 'First Comment for Second Article',
						'published' => 'Y', 'created' => '2007-03-18 10:53:23', 'updated' => '2007-03-18 10:55:31',
						'Attachment' => array(
							'id' => 1, 'comment_id' => 5, 'attachment' => 'attachment.zip',
							'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31'
						)
					),
					array(
						'id' => 6, 'article_id' => 2, 'user_id' => 2, 'comment' => 'Second Comment for Second Article',
						'published' => 'Y', 'created' => '2007-03-18 10:55:23', 'updated' => '2007-03-18 10:57:31',
						'Attachment' => array()
					)
				),
				'Tag' => array(
					array('id' => 1, 'tag' => 'tag1', 'created' => '2007-03-18 12:22:23', 'updated' => '2007-03-18 12:24:31'),
					array('id' => 3, 'tag' => 'tag3', 'created' => '2007-03-18 12:26:23', 'updated' => '2007-03-18 12:28:31')
				)
			),
			array(
				'Article' => array(
					'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
					'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'
				),
				'User' => array(
					'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
					'ArticleFeatured' => array(
						array(
							'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
							'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
						),
						array(
							'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
							'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'
						)
					)
				),
				'Comment' => array(),
				'Tag' => array()
			)
		);
		$this->assertEquals($expected, $result);
	}

/**
 * testFindEmbeddedSecondLevel method
 *
 * @return void
 */
	public function testFindEmbeddedSecondLevel() {
		$result = $this->Article->find('all', array('contain' => array('Comment' => 'User')));
		$expected = array(
			array(
				'Article' => array(
					'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
					'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
				),
				'Comment' => array(
					array(
						'id' => 1, 'article_id' => 1, 'user_id' => 2, 'comment' => 'First Comment for First Article',
						'published' => 'Y', 'created' => '2007-03-18 10:45:23', 'updated' => '2007-03-18 10:47:31',
						'User' => array(
							'id' => 2, 'user' => 'nate', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
							'created' => '2007-03-17 01:18:23', 'updated' => '2007-03-17 01:20:31'
						)
					),
					array(
						'id' => 2, 'article_id' => 1, 'user_id' => 4, 'comment' => 'Second Comment for First Article',
						'published' => 'Y', 'created' => '2007-03-18 10:47:23', 'updated' => '2007-03-18 10:49:31',
						'User' => array(
							'id' => 4, 'user' => 'garrett', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
							'created' => '2007-03-17 01:22:23', 'updated' => '2007-03-17 01:24:31'
						)
					),
					array(
						'id' => 3, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Third Comment for First Article',
						'published' => 'Y', 'created' => '2007-03-18 10:49:23', 'updated' => '2007-03-18 10:51:31',
						'User' => array(
							'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
							'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'
						)
					),
					array(
						'id' => 4, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Fourth Comment for First Article',
						'published' => 'N', 'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31',
						'User' => array(
							'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
							'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'
						)
					)
				)
			),
			array(
				'Article' => array(
					'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
					'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31'
				),
				'Comment' => array(
					array(
						'id' => 5, 'article_id' => 2, 'user_id' => 1, 'comment' => 'First Comment for Second Article',
						'published' => 'Y', 'created' => '2007-03-18 10:53:23', 'updated' => '2007-03-18 10:55:31',
						'User' => array(
							'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
							'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'
						)
					),
					array(
						'id' => 6, 'article_id' => 2, 'user_id' => 2, 'comment' => 'Second Comment for Second Article',
						'published' => 'Y', 'created' => '2007-03-18 10:55:23', 'updated' => '2007-03-18 10:57:31',
						'User' => array(
							'id' => 2, 'user' => 'nate', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
							'created' => '2007-03-17 01:18:23', 'updated' => '2007-03-17 01:20:31'
						)
					)
				)
			),
			array(
				'Article' => array(
					'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
					'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'
				),
				'Comment' => array()
			)
		);
		$this->assertEquals($expected, $result);

		$result = $this->Article->find('all', array('contain' => array('User' => 'ArticleFeatured')));
		$expected = array(
			array(
				'Article' => array(
					'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
					'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
				),
				'User' => array(
					'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
					'ArticleFeatured' => array(
						array(
							'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
							'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
						),
						array(
							'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
							'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'
						)
					)
				)
			),
			array(
				'Article' => array(
					'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
					'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31'
				),
				'User' => array(
					'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31',
					'ArticleFeatured' => array(
						array(
						'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31'
						)
					)
				)
			),
			array(
				'Article' => array(
					'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
					'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'
				),
				'User' => array(
					'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
					'ArticleFeatured' => array(
						array(
							'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
							'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
						),
						array(
							'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
							'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'
						)
					)
				)
			)
		);
		$this->assertEquals($expected, $result);

		$result = $this->Article->find('all', array('contain' => array('User' => array('ArticleFeatured', 'Comment'))));
		$expected = array(
			array(
				'Article' => array(
					'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
					'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
				),
				'User' => array(
					'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
					'ArticleFeatured' => array(
						array(
							'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
							'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
						),
						array(
							'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
							'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'
						)
					),
					'Comment' => array(
						array(
							'id' => 3, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Third Comment for First Article',
							'published' => 'Y', 'created' => '2007-03-18 10:49:23', 'updated' => '2007-03-18 10:51:31'
						),
						array(
							'id' => 4, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Fourth Comment for First Article',
							'published' => 'N', 'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31'
						),
						array(
							'id' => 5, 'article_id' => 2, 'user_id' => 1, 'comment' => 'First Comment for Second Article',
							'published' => 'Y', 'created' => '2007-03-18 10:53:23', 'updated' => '2007-03-18 10:55:31'
						)
					)
				)
			),
			array(
				'Article' => array(
					'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
					'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31'
				),
				'User' => array(
					'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31',
					'ArticleFeatured' => array(
						array(
						'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31'
						)
					),
					'Comment' => array()
				)
			),
			array(
				'Article' => array(
					'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
					'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'
				),
				'User' => array(
					'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
					'ArticleFeatured' => array(
						array(
							'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
							'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
						),
						array(
							'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
							'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'
						)
					),
					'Comment' => array(
						array(
							'id' => 3, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Third Comment for First Article',
							'published' => 'Y', 'created' => '2007-03-18 10:49:23', 'updated' => '2007-03-18 10:51:31'
						),
						array(
							'id' => 4, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Fourth Comment for First Article',
							'published' => 'N', 'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31'
						),
						array(
							'id' => 5, 'article_id' => 2, 'user_id' => 1, 'comment' => 'First Comment for Second Article',
							'published' => 'Y', 'created' => '2007-03-18 10:53:23', 'updated' => '2007-03-18 10:55:31'
						)
					)
				)
			)
		);
		$this->assertEquals($expected, $result);

		$result = $this->Article->find('all', array('contain' => array('User' => 'ArticleFeatured', 'Tag', 'Comment' => 'Attachment')));
		$expected = array(
			array(
				'Article' => array(
					'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
					'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
				),
				'User' => array(
					'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
					'ArticleFeatured' => array(
						array(
							'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
							'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
						),
						array(
							'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
							'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'
						)
					)
				),
				'Comment' => array(
					array(
						'id' => 1, 'article_id' => 1, 'user_id' => 2, 'comment' => 'First Comment for First Article',
						'published' => 'Y', 'created' => '2007-03-18 10:45:23', 'updated' => '2007-03-18 10:47:31',
						'Attachment' => array()
					),
					array(
						'id' => 2, 'article_id' => 1, 'user_id' => 4, 'comment' => 'Second Comment for First Article',
						'published' => 'Y', 'created' => '2007-03-18 10:47:23', 'updated' => '2007-03-18 10:49:31',
						'Attachment' => array()
					),
					array(
						'id' => 3, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Third Comment for First Article',
						'published' => 'Y', 'created' => '2007-03-18 10:49:23', 'updated' => '2007-03-18 10:51:31',
						'Attachment' => array()
					),
					array(
						'id' => 4, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Fourth Comment for First Article',
						'published' => 'N', 'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31',
						'Attachment' => array()
					)
				),
				'Tag' => array(
					array('id' => 1, 'tag' => 'tag1', 'created' => '2007-03-18 12:22:23', 'updated' => '2007-03-18 12:24:31'),
					array('id' => 2, 'tag' => 'tag2', 'created' => '2007-03-18 12:24:23', 'updated' => '2007-03-18 12:26:31')
				)
			),
			array(
				'Article' => array(
					'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
					'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31'
				),
				'User' => array(
					'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31',
					'ArticleFeatured' => array(
						array(
						'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31'
						)
					)
				),
				'Comment' => array(
					array(
						'id' => 5, 'article_id' => 2, 'user_id' => 1, 'comment' => 'First Comment for Second Article',
						'published' => 'Y', 'created' => '2007-03-18 10:53:23', 'updated' => '2007-03-18 10:55:31',
						'Attachment' => array(
							'id' => 1, 'comment_id' => 5, 'attachment' => 'attachment.zip',
							'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31'
						)
					),
					array(
						'id' => 6, 'article_id' => 2, 'user_id' => 2, 'comment' => 'Second Comment for Second Article',
						'published' => 'Y', 'created' => '2007-03-18 10:55:23', 'updated' => '2007-03-18 10:57:31',
						'Attachment' => array()
					)
				),
				'Tag' => array(
					array('id' => 1, 'tag' => 'tag1', 'created' => '2007-03-18 12:22:23', 'updated' => '2007-03-18 12:24:31'),
					array('id' => 3, 'tag' => 'tag3', 'created' => '2007-03-18 12:26:23', 'updated' => '2007-03-18 12:28:31')
				)
			),
			array(
				'Article' => array(
					'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
					'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'
				),
				'User' => array(
					'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31',
					'ArticleFeatured' => array(
						array(
							'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
							'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
						),
						array(
							'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
							'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'
						)
					)
				),
				'Comment' => array(),
				'Tag' => array()
			)
		);
		$this->assertEquals($expected, $result);
	}

/**
 * testFindThirdLevel method
 *
 * @return void
 */
	public function testFindThirdLevel() {
		$this->User->contain(array('ArticleFeatured' => array('Featured' => 'Category')));
		$result = $this->User->find('all', array('recursive' => 3));
		$expected = array(
			array(
				'User' => array(
					'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'
				),
				'ArticleFeatured' => array(
					array(
						'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
						'Featured' => array(
							'id' => 1, 'article_featured_id' => 1, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
							'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
							'Category' => array(
								'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
								'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31'
							)
						)
					),
					array(
						'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
						'Featured' => array()
					)
				)
			),
			array(
				'User' => array(
					'id' => 2, 'user' => 'nate', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:18:23', 'updated' => '2007-03-17 01:20:31'
				),
				'ArticleFeatured' => array()
			),
			array(
				'User' => array(
					'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31'
				),
				'ArticleFeatured' => array(
					array(
						'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
						'Featured' => array(
							'id' => 2, 'article_featured_id' => 2, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
							'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
							'Category' => array(
								'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
								'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31'
							)
						)
					)
				)
			),
			array(
				'User' => array(
					'id' => 4, 'user' => 'garrett', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:22:23', 'updated' => '2007-03-17 01:24:31'
				),
				'ArticleFeatured' => array()
			)
		);
		$this->assertEquals($expected, $result);

		$this->User->contain(array('ArticleFeatured' => array('Featured' => 'Category', 'Comment' => array('Article', 'Attachment'))));
		$result = $this->User->find('all', array('recursive' => 3));
		$expected = array(
			array(
				'User' => array(
					'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'
				),
				'ArticleFeatured' => array(
					array(
						'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
						'Featured' => array(
							'id' => 1, 'article_featured_id' => 1, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
							'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
							'Category' => array(
								'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
								'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31'
							)
						),
						'Comment' => array(
							array(
								'id' => 1, 'article_id' => 1, 'user_id' => 2, 'comment' => 'First Comment for First Article',
								'published' => 'Y', 'created' => '2007-03-18 10:45:23', 'updated' => '2007-03-18 10:47:31',
								'Article' => array(
									'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
									'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
								),
								'Attachment' => array()
							),
							array(
								'id' => 2, 'article_id' => 1, 'user_id' => 4, 'comment' => 'Second Comment for First Article',
								'published' => 'Y', 'created' => '2007-03-18 10:47:23', 'updated' => '2007-03-18 10:49:31',
								'Article' => array(
									'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
									'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
								),
								'Attachment' => array()
							),
							array(
								'id' => 3, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Third Comment for First Article',
								'published' => 'Y', 'created' => '2007-03-18 10:49:23', 'updated' => '2007-03-18 10:51:31',
								'Article' => array(
									'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
									'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
								),
								'Attachment' => array()
							),
							array(
								'id' => 4, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Fourth Comment for First Article',
								'published' => 'N', 'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31',
								'Article' => array(
									'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
									'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
								),
								'Attachment' => array()
							)
						)
					),
					array(
						'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
						'Featured' => array(),
						'Comment' => array()
					)
				)
			),
			array(
				'User' => array(
					'id' => 2, 'user' => 'nate', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:18:23', 'updated' => '2007-03-17 01:20:31'
				),
				'ArticleFeatured' => array()
			),
			array(
				'User' => array(
					'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31'
				),
				'ArticleFeatured' => array(
					array(
						'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
						'Featured' => array(
							'id' => 2, 'article_featured_id' => 2, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
							'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
							'Category' => array(
								'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
								'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31'
							)
						),
						'Comment' => array(
							array(
								'id' => 5, 'article_id' => 2, 'user_id' => 1, 'comment' => 'First Comment for Second Article',
								'published' => 'Y', 'created' => '2007-03-18 10:53:23', 'updated' => '2007-03-18 10:55:31',
								'Article' => array(
									'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
									'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31'
								),
								'Attachment' => array(
									'id' => 1, 'comment_id' => 5, 'attachment' => 'attachment.zip',
									'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31'
								)
							),
							array(
								'id' => 6, 'article_id' => 2, 'user_id' => 2, 'comment' => 'Second Comment for Second Article',
								'published' => 'Y', 'created' => '2007-03-18 10:55:23', 'updated' => '2007-03-18 10:57:31',
								'Article' => array(
									'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
									'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31'
								),
								'Attachment' => array()
							)
						)
					)
				)
			),
			array(
				'User' => array(
					'id' => 4, 'user' => 'garrett', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:22:23', 'updated' => '2007-03-17 01:24:31'
				),
				'ArticleFeatured' => array()
			)
		);
		$this->assertEquals($expected, $result);

		$this->User->contain(array('ArticleFeatured' => array('Featured' => 'Category', 'Comment' => 'Attachment'), 'Article'));
		$result = $this->User->find('all', array('recursive' => 3));
		$expected = array(
			array(
				'User' => array(
					'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'
				),
				'Article' => array(
					array(
						'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
					),
					array(
						'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'
					)
				),
				'ArticleFeatured' => array(
					array(
						'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
						'Featured' => array(
							'id' => 1, 'article_featured_id' => 1, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
							'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
							'Category' => array(
								'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
								'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31'
							)
						),
						'Comment' => array(
							array(
								'id' => 1, 'article_id' => 1, 'user_id' => 2, 'comment' => 'First Comment for First Article',
								'published' => 'Y', 'created' => '2007-03-18 10:45:23', 'updated' => '2007-03-18 10:47:31',
								'Attachment' => array()
							),
							array(
								'id' => 2, 'article_id' => 1, 'user_id' => 4, 'comment' => 'Second Comment for First Article',
								'published' => 'Y', 'created' => '2007-03-18 10:47:23', 'updated' => '2007-03-18 10:49:31',
								'Attachment' => array()
							),
							array(
								'id' => 3, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Third Comment for First Article',
								'published' => 'Y', 'created' => '2007-03-18 10:49:23', 'updated' => '2007-03-18 10:51:31',
								'Attachment' => array()
							),
							array(
								'id' => 4, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Fourth Comment for First Article',
								'published' => 'N', 'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31',
								'Attachment' => array()
							)
						)
					),
					array(
						'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
						'Featured' => array(),
						'Comment' => array()
					)
				)
			),
			array(
				'User' => array(
					'id' => 2, 'user' => 'nate', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:18:23', 'updated' => '2007-03-17 01:20:31'
				),
				'Article' => array(),
				'ArticleFeatured' => array()
			),
			array(
				'User' => array(
					'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31'
				),
				'Article' => array(
					array(
						'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31'
					)
				),
				'ArticleFeatured' => array(
					array(
						'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
						'Featured' => array(
							'id' => 2, 'article_featured_id' => 2, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
							'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
							'Category' => array(
								'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
								'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31'
							)
						),
						'Comment' => array(
							array(
								'id' => 5, 'article_id' => 2, 'user_id' => 1, 'comment' => 'First Comment for Second Article',
								'published' => 'Y', 'created' => '2007-03-18 10:53:23', 'updated' => '2007-03-18 10:55:31',
								'Attachment' => array(
									'id' => 1, 'comment_id' => 5, 'attachment' => 'attachment.zip',
									'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31'
								)
							),
							array(
								'id' => 6, 'article_id' => 2, 'user_id' => 2, 'comment' => 'Second Comment for Second Article',
								'published' => 'Y', 'created' => '2007-03-18 10:55:23', 'updated' => '2007-03-18 10:57:31',
								'Attachment' => array()
							)
						)
					)
				)
			),
			array(
				'User' => array(
					'id' => 4, 'user' => 'garrett', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:22:23', 'updated' => '2007-03-17 01:24:31'
				),
				'Article' => array(),
				'ArticleFeatured' => array()
			)
		);
		$this->assertEquals($expected, $result);
	}

/**
 * testFindEmbeddedThirdLevel method
 *
 * @return void
 */
	public function testFindEmbeddedThirdLevel() {
		$result = $this->User->find('all', array('contain' => array('ArticleFeatured' => array('Featured' => 'Category'))));
		$expected = array(
			array(
				'User' => array(
					'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'
				),
				'ArticleFeatured' => array(
					array(
						'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
						'Featured' => array(
							'id' => 1, 'article_featured_id' => 1, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
							'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
							'Category' => array(
								'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
								'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31'
							)
						)
					),
					array(
						'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
						'Featured' => array()
					)
				)
			),
			array(
				'User' => array(
					'id' => 2, 'user' => 'nate', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:18:23', 'updated' => '2007-03-17 01:20:31'
				),
				'ArticleFeatured' => array()
			),
			array(
				'User' => array(
					'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31'
				),
				'ArticleFeatured' => array(
					array(
						'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
						'Featured' => array(
							'id' => 2, 'article_featured_id' => 2, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
							'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
							'Category' => array(
								'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
								'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31'
							)
						)
					)
				)
			),
			array(
				'User' => array(
					'id' => 4, 'user' => 'garrett', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:22:23', 'updated' => '2007-03-17 01:24:31'
				),
				'ArticleFeatured' => array()
			)
		);
		$this->assertEquals($expected, $result);

		$result = $this->User->find('all', array('contain' => array('ArticleFeatured' => array('Featured' => 'Category', 'Comment' => array('Article', 'Attachment')))));
		$expected = array(
			array(
				'User' => array(
					'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'
				),
				'ArticleFeatured' => array(
					array(
						'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
						'Featured' => array(
							'id' => 1, 'article_featured_id' => 1, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
							'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
							'Category' => array(
								'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
								'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31'
							)
						),
						'Comment' => array(
							array(
								'id' => 1, 'article_id' => 1, 'user_id' => 2, 'comment' => 'First Comment for First Article',
								'published' => 'Y', 'created' => '2007-03-18 10:45:23', 'updated' => '2007-03-18 10:47:31',
								'Article' => array(
									'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
									'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
								),
								'Attachment' => array()
							),
							array(
								'id' => 2, 'article_id' => 1, 'user_id' => 4, 'comment' => 'Second Comment for First Article',
								'published' => 'Y', 'created' => '2007-03-18 10:47:23', 'updated' => '2007-03-18 10:49:31',
								'Article' => array(
									'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
									'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
								),
								'Attachment' => array()
							),
							array(
								'id' => 3, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Third Comment for First Article',
								'published' => 'Y', 'created' => '2007-03-18 10:49:23', 'updated' => '2007-03-18 10:51:31',
								'Article' => array(
									'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
									'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
								),
								'Attachment' => array()
							),
							array(
								'id' => 4, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Fourth Comment for First Article',
								'published' => 'N', 'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31',
								'Article' => array(
									'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
									'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
								),
								'Attachment' => array()
							)
						)
					),
					array(
						'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
						'Featured' => array(),
						'Comment' => array()
					)
				)
			),
			array(
				'User' => array(
					'id' => 2, 'user' => 'nate', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:18:23', 'updated' => '2007-03-17 01:20:31'
				),
				'ArticleFeatured' => array()
			),
			array(
				'User' => array(
					'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31'
				),
				'ArticleFeatured' => array(
					array(
						'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
						'Featured' => array(
							'id' => 2, 'article_featured_id' => 2, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
							'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
							'Category' => array(
								'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
								'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31'
							)
						),
						'Comment' => array(
							array(
								'id' => 5, 'article_id' => 2, 'user_id' => 1, 'comment' => 'First Comment for Second Article',
								'published' => 'Y', 'created' => '2007-03-18 10:53:23', 'updated' => '2007-03-18 10:55:31',
								'Article' => array(
									'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
									'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31'
								),
								'Attachment' => array(
									'id' => 1, 'comment_id' => 5, 'attachment' => 'attachment.zip',
									'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31'
								)
							),
							array(
								'id' => 6, 'article_id' => 2, 'user_id' => 2, 'comment' => 'Second Comment for Second Article',
								'published' => 'Y', 'created' => '2007-03-18 10:55:23', 'updated' => '2007-03-18 10:57:31',
								'Article' => array(
									'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
									'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31'
								),
								'Attachment' => array()
							)
						)
					)
				)
			),
			array(
				'User' => array(
					'id' => 4, 'user' => 'garrett', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:22:23', 'updated' => '2007-03-17 01:24:31'
				),
				'ArticleFeatured' => array()
			)
		);
		$this->assertEquals($expected, $result);

		$result = $this->User->find('all', array('contain' => array('ArticleFeatured' => array('Featured' => 'Category', 'Comment' => 'Attachment'), 'Article')));
		$expected = array(
			array(
				'User' => array(
					'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'
				),
				'Article' => array(
					array(
						'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
					),
					array(
						'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'
					)
				),
				'ArticleFeatured' => array(
					array(
						'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
						'Featured' => array(
							'id' => 1, 'article_featured_id' => 1, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
							'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
							'Category' => array(
								'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
								'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31'
							)
						),
						'Comment' => array(
							array(
								'id' => 1, 'article_id' => 1, 'user_id' => 2, 'comment' => 'First Comment for First Article',
								'published' => 'Y', 'created' => '2007-03-18 10:45:23', 'updated' => '2007-03-18 10:47:31',
								'Attachment' => array()
							),
							array(
								'id' => 2, 'article_id' => 1, 'user_id' => 4, 'comment' => 'Second Comment for First Article',
								'published' => 'Y', 'created' => '2007-03-18 10:47:23', 'updated' => '2007-03-18 10:49:31',
								'Attachment' => array()
							),
							array(
								'id' => 3, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Third Comment for First Article',
								'published' => 'Y', 'created' => '2007-03-18 10:49:23', 'updated' => '2007-03-18 10:51:31',
								'Attachment' => array()
							),
							array(
								'id' => 4, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Fourth Comment for First Article',
								'published' => 'N', 'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31',
								'Attachment' => array()
							)
						)
					),
					array(
						'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
						'Featured' => array(),
						'Comment' => array()
					)
				)
			),
			array(
				'User' => array(
					'id' => 2, 'user' => 'nate', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:18:23', 'updated' => '2007-03-17 01:20:31'
				),
				'Article' => array(),
				'ArticleFeatured' => array()
			),
			array(
				'User' => array(
					'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31'
				),
				'Article' => array(
					array(
						'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31'
					)
				),
				'ArticleFeatured' => array(
					array(
						'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
						'Featured' => array(
							'id' => 2, 'article_featured_id' => 2, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
							'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
							'Category' => array(
								'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
								'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31'
							)
						),
						'Comment' => array(
							array(
								'id' => 5, 'article_id' => 2, 'user_id' => 1, 'comment' => 'First Comment for Second Article',
								'published' => 'Y', 'created' => '2007-03-18 10:53:23', 'updated' => '2007-03-18 10:55:31',
								'Attachment' => array(
									'id' => 1, 'comment_id' => 5, 'attachment' => 'attachment.zip',
									'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31'
								)
							),
							array(
								'id' => 6, 'article_id' => 2, 'user_id' => 2, 'comment' => 'Second Comment for Second Article',
								'published' => 'Y', 'created' => '2007-03-18 10:55:23', 'updated' => '2007-03-18 10:57:31',
								'Attachment' => array()
							)
						)
					)
				)
			),
			array(
				'User' => array(
					'id' => 4, 'user' => 'garrett', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:22:23', 'updated' => '2007-03-17 01:24:31'
				),
				'Article' => array(),
				'ArticleFeatured' => array()
			)
		);
		$this->assertEquals($expected, $result);
	}

/**
 * testSettingsThirdLevel method
 *
 * @return void
 */
	public function testSettingsThirdLevel() {
		$result = $this->User->find('all', array('contain' => array('ArticleFeatured' => array('Featured' => array('Category' => array('id', 'name'))))));
		$expected = array(
			array(
				'User' => array(
					'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'
				),
				'ArticleFeatured' => array(
					array(
						'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
						'Featured' => array(
							'id' => 1, 'article_featured_id' => 1, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
							'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
							'Category' => array(
								'id' => 1, 'name' => 'Category 1'
							)
						)
					),
					array(
						'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
						'Featured' => array()
					)
				)
			),
			array(
				'User' => array(
					'id' => 2, 'user' => 'nate', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:18:23', 'updated' => '2007-03-17 01:20:31'
				),
				'ArticleFeatured' => array()
			),
			array(
				'User' => array(
					'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31'
				),
				'ArticleFeatured' => array(
					array(
						'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
						'Featured' => array(
							'id' => 2, 'article_featured_id' => 2, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
							'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
							'Category' => array(
								'id' => 1, 'name' => 'Category 1'
							)
						)
					)
				)
			),
			array(
				'User' => array(
					'id' => 4, 'user' => 'garrett', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:22:23', 'updated' => '2007-03-17 01:24:31'
				),
				'ArticleFeatured' => array()
			)
		);
		$this->assertEquals($expected, $result);

		$r = $this->User->find('all', array('contain' => array(
			'ArticleFeatured' => array(
				'id', 'title',
				'Featured' => array(
					'id', 'category_id',
					'Category' => array('id', 'name')
				)
			)
		)));

		$this->assertTrue(Set::matches('/User[id=1]', $r));
		$this->assertFalse(Set::matches('/Article', $r) || Set::matches('/Comment', $r));
		$this->assertTrue(Set::matches('/ArticleFeatured', $r));
		$this->assertFalse(Set::matches('/ArticleFeatured/User', $r) || Set::matches('/ArticleFeatured/Comment', $r) || Set::matches('/ArticleFeatured/Tag', $r));
		$this->assertTrue(Set::matches('/ArticleFeatured/Featured', $r));
		$this->assertFalse(Set::matches('/ArticleFeatured/Featured/ArticleFeatured', $r));
		$this->assertTrue(Set::matches('/ArticleFeatured/Featured/Category', $r));
		$this->assertTrue(Set::matches('/ArticleFeatured/Featured[id=1]', $r));
		$this->assertTrue(Set::matches('/ArticleFeatured/Featured[id=1]/Category[id=1]', $r));
		$this->assertTrue(Set::matches('/ArticleFeatured/Featured[id=1]/Category[name=Category 1]', $r));

		$r = $this->User->find('all', array('contain' => array(
			'ArticleFeatured' => array(
				'title',
				'Featured' => array(
					'id',
					'Category' => 'name'
				)
			)
		)));

		$this->assertTrue(Set::matches('/User[id=1]', $r));
		$this->assertFalse(Set::matches('/Article', $r) || Set::matches('/Comment', $r));
		$this->assertTrue(Set::matches('/ArticleFeatured', $r));
		$this->assertFalse(Set::matches('/ArticleFeatured/User', $r) || Set::matches('/ArticleFeatured/Comment', $r) || Set::matches('/ArticleFeatured/Tag', $r));
		$this->assertTrue(Set::matches('/ArticleFeatured/Featured', $r));
		$this->assertFalse(Set::matches('/ArticleFeatured/Featured/ArticleFeatured', $r));
		$this->assertTrue(Set::matches('/ArticleFeatured/Featured/Category', $r));
		$this->assertTrue(Set::matches('/ArticleFeatured/Featured[id=1]', $r));
		$this->assertTrue(Set::matches('/ArticleFeatured/Featured[id=1]/Category[name=Category 1]', $r));

		$result = $this->User->find('all', array('contain' => array(
			'ArticleFeatured' => array(
				'title',
				'Featured' => array(
					'category_id',
					'Category' => 'name'
				)
			)
		)));
		$expected = array(
			array(
				'User' => array(
					'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'
				),
				'ArticleFeatured' => array(
					array(
						'title' => 'First Article', 'id' => 1, 'user_id' => 1,
						'Featured' => array(
							'category_id' => 1, 'id' => 1,
							'Category' => array(
								'name' => 'Category 1'
							)
						)
					),
					array(
						'title' => 'Third Article', 'id' => 3, 'user_id' => 1,
						'Featured' => array()
					)
				)
			),
			array(
				'User' => array(
					'id' => 2, 'user' => 'nate', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:18:23', 'updated' => '2007-03-17 01:20:31'
				),
				'ArticleFeatured' => array()
			),
			array(
				'User' => array(
					'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31'
				),
				'ArticleFeatured' => array(
					array(
						'title' => 'Second Article', 'id' => 2, 'user_id' => 3,
						'Featured' => array(
							'category_id' => 1, 'id' => 2,
							'Category' => array(
								'name' => 'Category 1'
							)
						)
					)
				)
			),
			array(
				'User' => array(
					'id' => 4, 'user' => 'garrett', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:22:23', 'updated' => '2007-03-17 01:24:31'
				),
				'ArticleFeatured' => array()
			)
		);
		$this->assertEquals($expected, $result);

		$orders = array(
			'title DESC', 'title DESC, published DESC',
			array('title' => 'DESC'), array('title' => 'DESC', 'published' => 'DESC'),
		);
		foreach ($orders as $order) {
			$result = $this->User->find('all', array('contain' => array(
				'ArticleFeatured' => array(
					'title', 'order' => $order,
					'Featured' => array(
						'category_id',
						'Category' => 'name'
					)
				)
			)));
			$expected = array(
				array(
					'User' => array(
						'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
						'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'
					),
					'ArticleFeatured' => array(
						array(
							'title' => 'Third Article', 'id' => 3, 'user_id' => 1,
							'Featured' => array()
						),
						array(
							'title' => 'First Article', 'id' => 1, 'user_id' => 1,
							'Featured' => array(
								'category_id' => 1, 'id' => 1,
								'Category' => array(
									'name' => 'Category 1'
								)
							)
						)
					)
				),
				array(
					'User' => array(
						'id' => 2, 'user' => 'nate', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
						'created' => '2007-03-17 01:18:23', 'updated' => '2007-03-17 01:20:31'
					),
					'ArticleFeatured' => array()
				),
				array(
					'User' => array(
						'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
						'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31'
					),
					'ArticleFeatured' => array(
						array(
							'title' => 'Second Article', 'id' => 2, 'user_id' => 3,
							'Featured' => array(
								'category_id' => 1, 'id' => 2,
								'Category' => array(
									'name' => 'Category 1'
								)
							)
						)
					)
				),
				array(
					'User' => array(
						'id' => 4, 'user' => 'garrett', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
						'created' => '2007-03-17 01:22:23', 'updated' => '2007-03-17 01:24:31'
					),
					'ArticleFeatured' => array()
				)
			);
			$this->assertEquals($expected, $result);
		}
	}

/**
 * testFindThirdLevelNonReset method
 *
 * @return void
 */
	public function testFindThirdLevelNonReset() {
		$this->User->contain(false, array('ArticleFeatured' => array('Featured' => 'Category')));
		$result = $this->User->find('all', array('recursive' => 3));
		$expected = array(
			array(
				'User' => array(
					'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'
				),
				'ArticleFeatured' => array(
					array(
						'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
						'Featured' => array(
							'id' => 1, 'article_featured_id' => 1, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
							'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
							'Category' => array(
								'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
								'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31'
							)
						)
					),
					array(
						'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
						'Featured' => array()
					)
				)
			),
			array(
				'User' => array(
					'id' => 2, 'user' => 'nate', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:18:23', 'updated' => '2007-03-17 01:20:31'
				),
				'ArticleFeatured' => array()
			),
			array(
				'User' => array(
					'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31'
				),
				'ArticleFeatured' => array(
					array(
						'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
						'Featured' => array(
							'id' => 2, 'article_featured_id' => 2, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
							'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
							'Category' => array(
								'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
								'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31'
							)
						)
					)
				)
			),
			array(
				'User' => array(
					'id' => 4, 'user' => 'garrett', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:22:23', 'updated' => '2007-03-17 01:24:31'
				),
				'ArticleFeatured' => array()
			)
		);
		$this->assertEquals($expected, $result);

		$this->User->resetBindings();

		$this->User->contain(false, array('ArticleFeatured' => array('Featured' => 'Category', 'Comment' => array('Article', 'Attachment'))));
		$result = $this->User->find('all', array('recursive' => 3));
		$expected = array(
			array(
				'User' => array(
					'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'
				),
				'ArticleFeatured' => array(
					array(
						'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
						'Featured' => array(
							'id' => 1, 'article_featured_id' => 1, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
							'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
							'Category' => array(
								'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
								'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31'
							)
						),
						'Comment' => array(
							array(
								'id' => 1, 'article_id' => 1, 'user_id' => 2, 'comment' => 'First Comment for First Article',
								'published' => 'Y', 'created' => '2007-03-18 10:45:23', 'updated' => '2007-03-18 10:47:31',
								'Article' => array(
									'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
									'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
								),
								'Attachment' => array()
							),
							array(
								'id' => 2, 'article_id' => 1, 'user_id' => 4, 'comment' => 'Second Comment for First Article',
								'published' => 'Y', 'created' => '2007-03-18 10:47:23', 'updated' => '2007-03-18 10:49:31',
								'Article' => array(
									'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
									'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
								),
								'Attachment' => array()
							),
							array(
								'id' => 3, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Third Comment for First Article',
								'published' => 'Y', 'created' => '2007-03-18 10:49:23', 'updated' => '2007-03-18 10:51:31',
								'Article' => array(
									'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
									'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
								),
								'Attachment' => array()
							),
							array(
								'id' => 4, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Fourth Comment for First Article',
								'published' => 'N', 'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31',
								'Article' => array(
									'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
									'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
								),
								'Attachment' => array()
							)
						)
					),
					array(
						'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
						'Featured' => array(),
						'Comment' => array()
					)
				)
			),
			array(
				'User' => array(
					'id' => 2, 'user' => 'nate', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:18:23', 'updated' => '2007-03-17 01:20:31'
				),
				'ArticleFeatured' => array()
			),
			array(
				'User' => array(
					'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31'
				),
				'ArticleFeatured' => array(
					array(
						'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
						'Featured' => array(
							'id' => 2, 'article_featured_id' => 2, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
							'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
							'Category' => array(
								'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
								'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31'
							)
						),
						'Comment' => array(
							array(
								'id' => 5, 'article_id' => 2, 'user_id' => 1, 'comment' => 'First Comment for Second Article',
								'published' => 'Y', 'created' => '2007-03-18 10:53:23', 'updated' => '2007-03-18 10:55:31',
								'Article' => array(
									'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
									'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31'
								),
								'Attachment' => array(
									'id' => 1, 'comment_id' => 5, 'attachment' => 'attachment.zip',
									'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31'
								)
							),
							array(
								'id' => 6, 'article_id' => 2, 'user_id' => 2, 'comment' => 'Second Comment for Second Article',
								'published' => 'Y', 'created' => '2007-03-18 10:55:23', 'updated' => '2007-03-18 10:57:31',
								'Article' => array(
									'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
									'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31'
								),
								'Attachment' => array()
							)
						)
					)
				)
			),
			array(
				'User' => array(
					'id' => 4, 'user' => 'garrett', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:22:23', 'updated' => '2007-03-17 01:24:31'
				),
				'ArticleFeatured' => array()
			)
		);
		$this->assertEquals($expected, $result);

		$this->User->resetBindings();

		$this->User->contain(false, array('ArticleFeatured' => array('Featured' => 'Category', 'Comment' => 'Attachment'), 'Article'));
		$result = $this->User->find('all', array('recursive' => 3));
		$expected = array(
			array(
				'User' => array(
					'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'
				),
				'Article' => array(
					array(
						'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
					),
					array(
						'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31'
					)
				),
				'ArticleFeatured' => array(
					array(
						'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
						'Featured' => array(
							'id' => 1, 'article_featured_id' => 1, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
							'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
							'Category' => array(
								'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
								'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31'
							)
						),
						'Comment' => array(
							array(
								'id' => 1, 'article_id' => 1, 'user_id' => 2, 'comment' => 'First Comment for First Article',
								'published' => 'Y', 'created' => '2007-03-18 10:45:23', 'updated' => '2007-03-18 10:47:31',
								'Attachment' => array()
							),
							array(
								'id' => 2, 'article_id' => 1, 'user_id' => 4, 'comment' => 'Second Comment for First Article',
								'published' => 'Y', 'created' => '2007-03-18 10:47:23', 'updated' => '2007-03-18 10:49:31',
								'Attachment' => array()
							),
							array(
								'id' => 3, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Third Comment for First Article',
								'published' => 'Y', 'created' => '2007-03-18 10:49:23', 'updated' => '2007-03-18 10:51:31',
								'Attachment' => array()
							),
							array(
								'id' => 4, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Fourth Comment for First Article',
								'published' => 'N', 'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31',
								'Attachment' => array()
							)
						)
					),
					array(
						'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
						'Featured' => array(),
						'Comment' => array()
					)
				)
			),
			array(
				'User' => array(
					'id' => 2, 'user' => 'nate', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:18:23', 'updated' => '2007-03-17 01:20:31'
				),
				'Article' => array(),
				'ArticleFeatured' => array()
			),
			array(
				'User' => array(
					'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31'
				),
				'Article' => array(
					array(
						'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31'
					)
				),
				'ArticleFeatured' => array(
					array(
						'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
						'Featured' => array(
							'id' => 2, 'article_featured_id' => 2, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
							'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
							'Category' => array(
								'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
								'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31'
							)
						),
						'Comment' => array(
							array(
								'id' => 5, 'article_id' => 2, 'user_id' => 1, 'comment' => 'First Comment for Second Article',
								'published' => 'Y', 'created' => '2007-03-18 10:53:23', 'updated' => '2007-03-18 10:55:31',
								'Attachment' => array(
									'id' => 1, 'comment_id' => 5, 'attachment' => 'attachment.zip',
									'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31'
								)
							),
							array(
								'id' => 6, 'article_id' => 2, 'user_id' => 2, 'comment' => 'Second Comment for Second Article',
								'published' => 'Y', 'created' => '2007-03-18 10:55:23', 'updated' => '2007-03-18 10:57:31',
								'Attachment' => array()
							)
						)
					)
				)
			),
			array(
				'User' => array(
					'id' => 4, 'user' => 'garrett', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:22:23', 'updated' => '2007-03-17 01:24:31'
				),
				'Article' => array(),
				'ArticleFeatured' => array()
			)
		);
		$this->assertEquals($expected, $result);
	}

/**
 * testFindEmbeddedThirdLevelNonReset method
 *
 * @return void
 */
	public function testFindEmbeddedThirdLevelNonReset() {
		$result = $this->User->find('all', array('reset' => false, 'contain' => array('ArticleFeatured' => array('Featured' => 'Category'))));
		$expected = array(
			array(
				'User' => array(
					'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'
				),
				'ArticleFeatured' => array(
					array(
						'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
						'Featured' => array(
							'id' => 1, 'article_featured_id' => 1, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
							'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
							'Category' => array(
								'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
								'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31'
							)
						)
					),
					array(
						'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
						'Featured' => array()
					)
				)
			),
			array(
				'User' => array(
					'id' => 2, 'user' => 'nate', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:18:23', 'updated' => '2007-03-17 01:20:31'
				),
				'ArticleFeatured' => array()
			),
			array(
				'User' => array(
					'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31'
				),
				'ArticleFeatured' => array(
					array(
						'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
						'Featured' => array(
							'id' => 2, 'article_featured_id' => 2, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
							'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
							'Category' => array(
								'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
								'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31'
							)
						)
					)
				)
			),
			array(
				'User' => array(
					'id' => 4, 'user' => 'garrett', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:22:23', 'updated' => '2007-03-17 01:24:31'
				),
				'ArticleFeatured' => array()
			)
		);
		$this->assertEquals($expected, $result);

		$this->_assertBindings($this->User, array('hasMany' => array('ArticleFeatured')));
		$this->_assertBindings($this->User->ArticleFeatured, array('hasOne' => array('Featured')));
		$this->_assertBindings($this->User->ArticleFeatured->Featured, array('belongsTo' => array('Category')));

		$this->User->resetBindings();

		$this->_assertBindings($this->User, array('hasMany' => array('Article', 'ArticleFeatured', 'Comment')));
		$this->_assertBindings($this->User->ArticleFeatured, array('belongsTo' => array('User'), 'hasOne' => array('Featured'), 'hasMany' => array('Comment'), 'hasAndBelongsToMany' => array('Tag')));
		$this->_assertBindings($this->User->ArticleFeatured->Featured, array('belongsTo' => array('ArticleFeatured', 'Category')));
		$this->_assertBindings($this->User->ArticleFeatured->Comment, array('belongsTo' => array('Article', 'User'), 'hasOne' => array('Attachment')));

		$result = $this->User->find('all', array('reset' => false, 'contain' => array('ArticleFeatured' => array('Featured' => 'Category', 'Comment' => array('Article', 'Attachment')))));
		$expected = array(
			array(
				'User' => array(
					'id' => 1, 'user' => 'mariano', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:16:23', 'updated' => '2007-03-17 01:18:31'
				),
				'ArticleFeatured' => array(
					array(
						'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
						'Featured' => array(
							'id' => 1, 'article_featured_id' => 1, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
							'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
							'Category' => array(
								'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
								'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31'
							)
						),
						'Comment' => array(
							array(
								'id' => 1, 'article_id' => 1, 'user_id' => 2, 'comment' => 'First Comment for First Article',
								'published' => 'Y', 'created' => '2007-03-18 10:45:23', 'updated' => '2007-03-18 10:47:31',
								'Article' => array(
									'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
									'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
								),
								'Attachment' => array()
							),
							array(
								'id' => 2, 'article_id' => 1, 'user_id' => 4, 'comment' => 'Second Comment for First Article',
								'published' => 'Y', 'created' => '2007-03-18 10:47:23', 'updated' => '2007-03-18 10:49:31',
								'Article' => array(
									'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
									'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
								),
								'Attachment' => array()
							),
							array(
								'id' => 3, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Third Comment for First Article',
								'published' => 'Y', 'created' => '2007-03-18 10:49:23', 'updated' => '2007-03-18 10:51:31',
								'Article' => array(
									'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
									'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
								),
								'Attachment' => array()
							),
							array(
								'id' => 4, 'article_id' => 1, 'user_id' => 1, 'comment' => 'Fourth Comment for First Article',
								'published' => 'N', 'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31',
								'Article' => array(
									'id' => 1, 'user_id' => 1, 'title' => 'First Article', 'body' => 'First Article Body',
									'published' => 'Y', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31'
								),
								'Attachment' => array()
							)
						)
					),
					array(
						'id' => 3, 'user_id' => 1, 'title' => 'Third Article', 'body' => 'Third Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:43:23', 'updated' => '2007-03-18 10:45:31',
						'Featured' => array(),
						'Comment' => array()
					)
				)
			),
			array(
				'User' => array(
					'id' => 2, 'user' => 'nate', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:18:23', 'updated' => '2007-03-17 01:20:31'
				),
				'ArticleFeatured' => array()
			),
			array(
				'User' => array(
					'id' => 3, 'user' => 'larry', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:20:23', 'updated' => '2007-03-17 01:22:31'
				),
				'ArticleFeatured' => array(
					array(
						'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
						'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31',
						'Featured' => array(
							'id' => 2, 'article_featured_id' => 2, 'category_id' => 1, 'published_date' => '2007-03-31 10:39:23',
							'end_date' => '2007-05-15 10:39:23', 'created' => '2007-03-18 10:39:23', 'updated' => '2007-03-18 10:41:31',
							'Category' => array(
								'id' => 1, 'parent_id' => 0, 'name' => 'Category 1',
								'created' => '2007-03-18 15:30:23', 'updated' => '2007-03-18 15:32:31'
							)
						),
						'Comment' => array(
							array(
								'id' => 5, 'article_id' => 2, 'user_id' => 1, 'comment' => 'First Comment for Second Article',
								'published' => 'Y', 'created' => '2007-03-18 10:53:23', 'updated' => '2007-03-18 10:55:31',
								'Article' => array(
									'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
									'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31'
								),
								'Attachment' => array(
									'id' => 1, 'comment_id' => 5, 'attachment' => 'attachment.zip',
									'created' => '2007-03-18 10:51:23', 'updated' => '2007-03-18 10:53:31'
								)
							),
							array(
								'id' => 6, 'article_id' => 2, 'user_id' => 2, 'comment' => 'Second Comment for Second Article',
								'published' => 'Y', 'created' => '2007-03-18 10:55:23', 'updated' => '2007-03-18 10:57:31',
								'Article' => array(
									'id' => 2, 'user_id' => 3, 'title' => 'Second Article', 'body' => 'Second Article Body',
									'published' => 'Y', 'created' => '2007-03-18 10:41:23', 'updated' => '2007-03-18 10:43:31'
								),
								'Attachment' => array()
							)
						)
					)
				)
			),
			array(
				'User' => array(
					'id' => 4, 'user' => 'garrett', 'password' => '5f4dcc3b5aa765d61d8327deb882cf99',
					'created' => '2007-03-17 01:22:23', 'updated' => '2007-03-17 01:24:31'
				),
				'ArticleFeatured' => array()
			)
		);
		$this->assertEquals($expected, $result);

		$this->_assertBindings($this->User, array('hasMany' => array('ArticleFeatured')));
		$this->_assertBindings($this->User->ArticleFeatured, array('hasOne' => array('Featured'), 'hasMany' => array('Comment')));
		$this->_assertBindings($this->User->ArticleFeatured->Featured, array('belongsTo' => array('Category')));
		$this->_assertBindings($this->User->ArticleFeatured->Comment, array('belongsTo' => array('Article'), 'hasOne' => array('Attachment')));

		$this->User->resetBindings();
		$this->_assertBindings($this->User, array('hasMany' => array('Article', 'ArticleFeatured', 'Comment')));
		$this->_assertBindings($this->User->ArticleFeatured, array('belongsTo' => array('User'), 'hasOne' => array('Featured'), 'hasMany' => array('Comment'),