<?php
App::uses('Flag', 'Model');

/**
 * Flag Test Case
 *
 */
class FlagTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.flag',
		'app.user',
		'app.group',
		'app.gender',
		'app.image',
		'app.image_comment',
		'app.image_comment_flag',
		'app.image_comment_like',
		'app.image_flag',
		'app.image_hit',
		'app.image_like',
		'app.distance',
		'app.strain',
		'app.category',
		'app.category_video',
		'app.inventory',
		'app.portal',
		'app.city',
		'app.state',
		'app.store_type',
		'app.timing',
		'app.checkin',
		'app.coupon',
		'app.repetition_type',
		'app.portal_hit',
		'app.portal_image',
		'app.portal_like',
		'app.portal_video',
		'app.video',
		'app.video_comment',
		'app.video_comment_comment',
		'app.video_comment_flag',
		'app.video_comment_like',
		'app.video_flag',
		'app.video_hit',
		'app.video_like',
		'app.review',
		'app.review_comment',
		'app.review_flag',
		'app.review_like',
		'app.strain_image',
		'app.strain_video',
		'app.forum',
		'app.topic',
		'app.thread',
		'app.post',
		'app.post_flag',
		'app.post_like',
		'app.user_read_post',
		'app.friend',
		'app.news',
		'app.playlist',
		'app.playlist_video',
		'app.user_read_posts',
		'app.subscriber',
		'app.user_flag'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Flag = ClassRegistry::init('Flag');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Flag);

		parent::tearDown();
	}

}
