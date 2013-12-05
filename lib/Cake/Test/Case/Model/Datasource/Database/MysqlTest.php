<?php
/**
 * DboMysqlTest file
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.Test.Case.Model.Datasource.Database
 * @since         CakePHP(tm) v 1.2.0
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('Model', 'Model');
App::uses('AppModel', 'Model');
App::uses('Mysql', 'Model/Datasource/Database');
App::uses('CakeSchema', 'Model');

require_once dirname(dirname(dirname(__FILE__))) . DS . 'models.php';

/**
 * DboMysqlTest class
 *
 * @package       Cake.Test.Case.Model.Datasource.Database
 */
class MysqlTest extends CakeTestCase {

/**
 * autoFixtures property
 *
 * @var bool false
 */
	public $autoFixtures = false;

/**
 * fixtures property
 *
 * @var array
 */
	public $fixtures = array(
		'core.apple', 'core.article', 'core.articles_tag', 'core.attachment', 'core.comment',
		'core.sample', 'core.tag', 'core.user', 'core.post', 'core.author', 'core.data_test',
		'core.binary_test', 'core.inno'
	);

/**
 * The Dbo instance to be tested
 *
 * @var DboSource
 */
	public $Dbo = null;

/**
 * Sets up a Dbo class instance for testing
 *
 */
	public function setUp() {
		$this->Dbo = ConnectionManager::getDataSource('test');
		if (!($this->Dbo instanceof Mysql)) {
			$this->markTestSkipped('The MySQL extension is not available.');
		}
		$this->_debug = Configure::read('debug');
		Configure::write('debug', 1);
		$this->model = ClassRegistry::init('MysqlTestModel');
	}

/**
 * Sets up a Dbo class instance for testing
 *
 */
	public function tearDown() {
		unset($this->model);
		ClassRegistry::flush();
		Configure::write('debug', $this->_debug);
	}

/**
 * Test Dbo value method
 *
 * @group quoting
 */
	public function testQuoting() {
		$result = $this->Dbo->fields($this->model);
		$expected = array(
			'`MysqlTestModel`.`id`',
			'`MysqlTestModel`.`client_id`',
			'`MysqlTestModel`.`name`',
			'`MysqlTestModel`.`login`',
			'`MysqlTestModel`.`passwd`',
			'`MysqlTestModel`.`addr_1`',
			'`MysqlTestModel`.`addr_2`',
			'`MysqlTestModel`.`zip_code`',
			'`MysqlTestModel`.`city`',
			'`MysqlTestModel`.`country`',
			'`MysqlTestModel`.`phone`',
			'`MysqlTestModel`.`fax`',
			'`MysqlTestModel`.`url`',
			'`MysqlTestModel`.`email`',
			'`MysqlTestModel`.`comments`',
			'`MysqlTestModel`.`last_login`',
			'`MysqlTestModel`.`created`',
			'`MysqlTestModel`.`updated`'
		);
		$this->assertEquals($expected, $result);

		$expected = 1.2;
		$result = $this->Dbo->value(1.2, 'float');
		$this->assertEquals($expected, $result);

		$expected = "'1,2'";
		$result = $this->Dbo->value('1,2', 'float');
		$this->assertEquals($expected, $result);

		$expected = "'4713e29446'";
		$result = $this->Dbo->value('4713e29446');

		$this->assertEquals($expected, $result);

		$expected = 'NULL';
		$result = $this->Dbo->value('', 'integer');
		$this->assertEquals($expected, $result);

		$expected = "'0'";
		$result = $this->Dbo->value('', 'boolean');
		$this->assertEquals($expected, $result);

		$expected = 10010001;
		$result = $this->Dbo->value(10010001);
		$this->assertEquals($expected, $result);

		$expected = "'00010010001'";
		$result = $this->Dbo->value('00010010001');
		$this->assertEquals($expected, $result);
	}

/**
 * test that localized floats don't cause trouble.
 *
 * @group quoting
 * @return void
 */
	public function testLocalizedFloats() {
		$this->skipIf(DS === '\\', 'The locale is not supported in Windows and affect the others tests.');

		$restore = setlocale(LC_NUMERIC, 0);
		setlocale(LC_NUMERIC, 'de_DE');

		$result = $this->Dbo->value(3.141593);
		$this->assertEquals('3.141593', $result);

		$result = $this->db->value(3.141593, 'float');
		$this->assertEquals('3.141593', $result);

		$result = $this->db->value(1234567.11, 'float');
		$this->assertEquals('1234567.11', $result);

		$result = $this->db->value(123456.45464748, 'float');
		$this->assertContains('123456.454647', $result);

		$result = $this->db->value(0.987654321, 'float');
		$this->assertEquals('0.987654321', (string)$result);

		$result = $this->db->value(2.2E-54, 'float');
		$this->assertEquals('2.2E-54', (string)$result);

		$result = $this->db->value(2.2E-54);
		$this->assertEquals('2.2E-54', (string)$result);

		setlocale(LC_NUMERIC, $restore);
	}

/**
 * test that scientific notations are working correctly
 *
 * @return void
 */
	public function testScientificNotation() {
		$result = $this->db->value(2.2E-54, 'float');
		$this->assertEquals('2.2E-54', (string)$result);

		$result = $this->db->value(2.2E-54);
		$this->assertEquals('2.2E-54', (string)$result);
	}

/**
 * testTinyintCasting method
 *
 *
 * @return void
 */
	public function testTinyintCasting() {
		$this->Dbo->cacheSources = false;
		$tableName = 'tinyint_' . uniqid();
		$this->Dbo->rawQuery('CREATE TABLE ' . $this->Dbo->fullTableName($tableName) . ' (id int(11) AUTO_INCREMENT, bool tinyint(1), small_int tinyint(2), primary key(id));');

		$this->model = new CakeTestModel(array(
			'name' => 'Tinyint', 'table' => $tableName, 'ds' => 'test'
		));

		$result = $this->model->schema();
		$this->assertEquals('boolean', $result['bool']['type']);
		$this->assertEquals('integer', $result['small_int']['type']);

		$this->assertTrue((bool)$this->model->save(array('bool' => 5, 'small_int' => 5)));
		$result = $this->model->find('first');
		$this->assertSame($result['Tinyint']['bool'], true);
		$this->assertSame($result['Tinyint']['small_int'], '5');
		$this->model->deleteAll(true);

		$this->assertTrue((bool)$this->model->save(array('bool' => 0, 'small_int' => 100)));
		$result = $this->model->find('first');
		$this->assertSame($result['Tinyint']['bool'], false);
		$this->assertSame($result['Tinyint']['small_int'], '100');
		$this->model->deleteAll(true);

		$this->assertTrue((bool)$this->model->save(array('bool' => true, 'small_int' => 0)));
		$result = $this->model->find('first');
		$this->assertSame($result['Tinyint']['bool'], true);
		$this->assertSame($result['Tinyint']['small_int'], '0');
		$this->model->deleteAll(true);

		$this->Dbo->rawQuery('DROP TABLE ' . $this->Dbo->fullTableName($tableName));
	}

/**
 * testLastAffected method
 *
 *
 * @return void
 */
	public function testLastAffected() {
		$this->Dbo->cacheSources = false;
		$tableName = 'tinyint_' . uniqid();
		$this->Dbo->rawQuery('CREATE TABLE ' . $this->Dbo->fullTableName($tableName) . ' (id int(11) AUTO_INCREMENT, bool tinyint(1), small_int tinyint(2), primary key(id));');

		$this->model = new CakeTestModel(array(
			'name' => 'Tinyint', 'table' => $tableName, 'ds' => 'test'
		));

		$this->assertTrue((bool)$this->model->save(array('bool' => 5, 'small_int' => 5)));
		$this->assertEquals(1, $this->model->find('count'));
		$this->model->deleteAll(true);
		$result = $this->Dbo->lastAffected();
		$this->assertEquals(1, $result);
		$this->assertEquals(0, $this->model->find('count'));

		$this->Dbo->rawQuery('DROP TABLE ' . $this->Dbo->fullTableName($tableName));
	}

/**
 * testIndexDetection method
 *
 * @group indices
 * @return void
 */
	public function testIndexDetection() {
		$this->Dbo->cacheSources = false;

		$name = $this->Dbo->fullTableName('simple');
		$this->Dbo->rawQuery('CREATE TABLE ' . $name . ' (id int(11) AUTO_INCREMENT, bool tinyint(1), small_int tinyint(2), primary key(id));');
		$expected = array('PRIMARY' => array('column' => 'id', 'unique' => 1));
		$result = $this->Dbo->index('simple', false);
		$this->Dbo->rawQuery('DROP TABLE ' . $name);
		$this->assertEquals($expected, $result);

		$name = $this->Dbo->fullTableName('bigint');
		$this->Dbo->rawQuery('CREATE TABLE ' . $name . ' (id bigint(20) AUTO_INCREMENT, bool tinyint(1), small_int tinyint(2), primary key(id));');
		$expected = array('PRIMARY' => array('column' => 'id', 'unique' => 1));
		$result = $this->Dbo->index('bigint', false);
		$this->Dbo->rawQuery('DROP TABLE ' . $name);
		$this->assertEquals($expected, $result);

		$name = $this->Dbo->fullTableName('with_a_key');
		$this->Dbo->rawQuery('CREATE TABLE ' . $name . ' (id int(11) AUTO_INCREMENT, bool tinyint(1), small_int tinyint(2), primary key(id), KEY `pointless_bool` ( `bool` ));');
		$expected = array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'pointless_bool' => array('column' => 'bool', 'unique' => 0),
		);
		$result = $this->Dbo->index('with_a_key', false);
		$this->Dbo->rawQuery('DROP TABLE ' . $name);
		$this->assertEquals($expected, $result);

		$name = $this->Dbo->fullTableName('with_two_keys');
		$this->Dbo->rawQuery('CREATE TABLE ' . $name . ' (id int(11) AUTO_INCREMENT, bool tinyint(1), small_int tinyint(2), primary key(id), KEY `pointless_bool` ( `bool` ), KEY `pointless_small_int` ( `small_int` ));');
		$expected = array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'pointless_bool' => array('column' => 'bool', 'unique' => 0),
			'pointless_small_int' => array('column' => 'small_int', 'unique' => 0),
		);
		$result = $this->Dbo->index('with_two_keys', false);
		$this->Dbo->rawQuery('DROP TABLE ' . $name);
		$this->assertEquals($expected, $result);

		$name = $this->Dbo->fullTableName('with_compound_keys');
		$this->Dbo->rawQuery('CREATE TABLE ' . $name . ' (id int(11) AUTO_INCREMENT, bool tinyint(1), small_int tinyint(2), primary key(id), KEY `pointless_bool` ( `bool` ), KEY `pointless_small_int` ( `small_int` ), KEY `one_way` ( `bool`, `small_int` ));');
		$expected = array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'pointless_bool' => array('column' => 'bool', 'unique' => 0),
			'pointless_small_int' => array('column' => 'small_int', 'unique' => 0),
			'one_way' => array('column' => array('bool', 'small_int'), 'unique' => 0),
		);
		$result = $this->Dbo->index('with_compound_keys', false);
		$this->Dbo->rawQuery('DROP TABLE ' . $name);
		$this->assertEquals($expected, $result);

		$name = $this->Dbo->fullTableName('with_multiple_compound_keys');
		$this->Dbo->rawQuery('CREATE TABLE ' . $name . ' (id int(11) AUTO_INCREMENT, bool tinyint(1), small_int tinyint(2), primary key(id), KEY `pointless_bool` ( `bool` ), KEY `pointless_small_int` ( `small_int` ), KEY `one_way` ( `bool`, `small_int` ), KEY `other_way` ( `small_int`, `bool` ));');
		$expected = array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'pointless_bool' => array('column' => 'bool', 'unique' => 0),
			'pointless_small_int' => array('column' => 'small_int', 'unique' => 0),
			'one_way' => array('column' => array('bool', 'small_int'), 'unique' => 0),
			'other_way' => array('column' => array('small_int', 'bool'), 'unique' => 0),
		);
		$result = $this->Dbo->index('with_multiple_compound_keys', false);
		$this->Dbo->rawQuery('DROP TABLE ' . $name);
		$this->assertEquals($expected, $result);

		$name = $this->Dbo->fullTableName('with_fulltext');
		$this->Dbo->rawQuery('CREATE TABLE ' . $name . ' (id int(11) AUTO_INCREMENT, name varchar(255), description text, primary key(id), FULLTEXT KEY `MyFtIndex` ( `name`, `description` )) ENGINE=MyISAM;');
		$expected = array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'MyFtIndex' => array('column' => array('name', 'description'), 'type' => 'fulltext')
		);
		$result = $this->Dbo->index('with_fulltext', false);
		$this->Dbo->rawQuery('DROP TABLE ' . $name);
		$this->assertEquals($expected, $result);

		$name = $this->Dbo->fullTableName('with_text_index');
		$this->Dbo->rawQuery('CREATE TABLE ' . $name . ' (id int(11) AUTO_INCREMENT, text_field text, primary key(id), KEY `text_index` ( `text_field`(20) ));');
		$expected = array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'text_index' => array('column' => 'text_field', 'unique' => 0, 'length' => array('text_field' => 20)),
		);
		$result = $this->Dbo->index('with_text_index', false);
		$this->Dbo->rawQuery('DROP TABLE ' . $name);
		$this->assertEquals($expected, $result);

		$name = $this->Dbo->fullTableName('with_compound_text_index');
		$this->Dbo->rawQuery('CREATE TABLE ' . $name . ' (id int(11) AUTO_INCREMENT, text_field1 text, text_field2 text, primary key(id), KEY `text_index` ( `text_field1`(20), `text_field2`(20) ));');
		$expected = array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'text_index' => array('column' => array('text_field1', 'text_field2'), 'unique' => 0, 'length' => array('text_field1' => 20, 'text_field2' => 20)),
		);
		$result = $this->Dbo->index('with_compound_text_index', false);
		$this->Dbo->rawQuery('DROP TABLE ' . $name);
		$this->assertEquals($expected, $result);
	}

/**
 * testBuildColumn method
 *
 * @return void
 */
	public function testBuildColumn() {
		$restore = $this->Dbo->columns;
		$this->Dbo->columns = array('varchar(255)' => 1);
		$data = array(
			'name' => 'testName',
			'type' => 'varchar(255)',
			'default',
			'null' => true,
			'key',
			'comment' => 'test'
		);
		$result = $this->Dbo->buildColumn($data);
		$expected = '`testName`  DEFAULT NULL COMMENT \'test\'';
		$this->assertEquals($expected, $result);

		$data = array(
			'name' => 'testName',
			'type' => 'varchar(255)',
			'default',
			'null' => true,
			'key',
			'charset' => 'utf8',
			'collate' => 'utf8_unicode_ci'
		);
		$result = $this->Dbo->buildColumn($data);
		$expected = '`testName`  CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL';
		$this->assertEquals($expected, $result);
		$this->Dbo->columns = $restore;
	}

/**
 * MySQL 4.x returns index data in a different format,
 * Using a mock ensure that MySQL 4.x output is properly parsed.
 *
 * @group indices
 * @return void
 */
	public function testIndexOnMySQL4Output() {
		$name = $this->Dbo->fullTableName('simple');

		$mockDbo = $this->getMock('Mysql', array('connect', '_execute', 'getVersion'));
		$columnData = array(
			array('0' => array(
				'Table' => 'with_compound_keys',
				'Non_unique' => '0',
				'Key_name' => 'PRIMARY',
				'Seq_in_index' => '1',
				'Column_name' => 'id',
				'Collation' => 'A',
				'Cardinality' => '0',
				'Sub_part' => null,
				'Packed' => null,
				'Null' => '',
				'Index_type' => 'BTREE',
				'Comment' => ''
			)),
			array('0' => array(
				'Table' => 'with_compound_keys',
				'Non_unique' => '1',
				'Key_name' => 'pointless_bool',
				'Seq_in_index' => '1',
				'Column_name' => 'bool',
				'Collation' => 'A',
				'Cardinality' => null,
				'Sub_part' => null,
				'Packed' => null,
				'Null' => 'YES',
				'Index_type' => 'BTREE',
				'Comment' => ''
			)),
			array('0' => array(
				'Table' => 'with_compound_keys',
				'Non_unique' => '1',
				'Key_name' => 'pointless_small_int',
				'Seq_in_index' => '1',
				'Column_name' => 'small_int',
				'Collation' => 'A',
				'Cardinality' => null,
				'Sub_part' => null,
				'Packed' => null,
				'Null' => 'YES',
				'Index_type' => 'BTREE',
				'Comment' => ''
			)),
			array('0' => array(
				'Table' => 'with_compound_keys',
				'Non_unique' => '1',
				'Key_name' => 'one_way',
				'Seq_in_index' => '1',
				'Column_name' => 'bool',
				'Collation' => 'A',
				'Cardinality' => null,
				'Sub_part' => null,
				'Packed' => null,
				'Null' => 'YES',
				'Index_type' => 'BTREE',
				'Comment' => ''
			)),
			array('0' => array(
				'Table' => 'with_compound_keys',
				'Non_unique' => '1',
				'Key_name' => 'one_way',
				'Seq_in_index' => '2',
				'Column_name' => 'small_int',
				'Collation' => 'A',
				'Cardinality' => null,
				'Sub_part' => null,
				'Packed' => null,
				'Null' => 'YES',
				'Index_type' => 'BTREE',
				'Comment' => ''
			))
		);

		$mockDbo->expects($this->once())->method('getVersion')->will($this->returnValue('4.1'));
		$resultMock = $this->getMock('PDOStatement', array('fetch'));
		$mockDbo->expects($this->once())
			->method('_execute')
			->with('SHOW INDEX FROM ' . $name)
			->will($this->returnValue($resultMock));

		foreach ($columnData as $i => $data) {
			$resultMock->expects($this->at($i))->method('fetch')->will($this->returnValue((object)$data));
		}

		$result = $mockDbo->index($name, false);
		$expected = array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1),
			'pointless_bool' => array('column' => 'bool', 'unique' => 0),
			'pointless_small_int' => array('column' => 'small_int', 'unique' => 0),
			'one_way' => array('column' => array('bool', 'small_int'), 'unique' => 0),
		);
		$this->assertEquals($expected, $result);
	}

/**
 * testColumn method
 *
 * @return void
 */
	public function testColumn() {
		$result = $this->Dbo->column('varchar(50)');
		$expected = 'string';
		$this->assertEquals($expected, $result);

		$result = $this->Dbo->column('text');
		$expected = 'text';
		$this->assertEquals($expected, $result);

		$result = $this->Dbo->column('int(11)');
		$expected = 'integer';
		$this->assertEquals($expected, $result);

		$result = $this->Dbo->column('int(11) unsigned');
		$expected = 'integer';
		$this->assertEquals($expected, $result);

		$result = $this->Dbo->column('bigint(20)');
		$expected = 'biginteger';
		$this->assertEquals($expected, $result);

		$result = $this->Dbo->column('tinyint(1)');
		$expected = 'boolean';
		$this->assertEquals($expected, $result);

		$result = $this->Dbo->column('boolean');
		$expected = 'boolean';
		$this->assertEquals($expected, $result);

		$result = $this->Dbo->column('float');
		$expected = 'float';
		$this->assertEquals($expected, $result);

		$result = $this->Dbo->column('float unsigned');
		$expected = 'float';
		$this->assertEquals($expected, $result);

		$result = $this->Dbo->column('double unsigned');
		$expected = 'float';
		$this->assertEquals($expected, $result);

		$result = $this->Dbo->column('decimal(14,7) unsigned');
		$expected = 'float';
		$this->assertEquals($expected, $result);
	}

/**
 * testAlterSchemaIndexes method
 *
 * @group indices
 * @return void
 */
	public function testAlterSchemaIndexes() {
		$this->Dbo->cacheSources = $this->Dbo->testing = false;
		$table = $this->Dbo->fullTableName('altertest');

		$schemaA = new CakeSchema(array(
			'name' => 'AlterTest1',
			'connection' => 'test',
			'altertest' => array(
				'id' => array('type' => 'integer', 'null' => false, 'default' => 0),
				'name' => array('type' => 'string', 'null' => false, 'length' => 50),
				'group1' => array('type' => 'integer', 'null' => true),
				'group2' => array('type' => 'integer', 'null' => true)
		)));
		$result = $this->Dbo->createSchema($schemaA);
		$this->assertContains('`id` int(11) DEFAULT 0 NOT NULL,', $result);
		$this->assertContains('`name` varchar(50) NOT NULL,', $result);
		$this->assertContains('`group1` int(11) DEFAULT NULL', $result);
		$this->assertContains('`group2` int(11) DEFAULT NULL', $result);

		//Test that the string is syntactically correct
		$query = $this->Dbo->getConnection()->prepare($result);
		$this->assertEquals($query->queryString, $result);

		$schemaB = new CakeSchema(array(
			'name' => 'AlterTest2',
			'connection' => 'test',
			'altertest' => array(
				'id' => array('type' => 'integer', 'null' => false, 'default' => 0),
				'name' => array('type' => 'string', 'null' => false, 'length' => 50),
				'group1' => array('type' => 'integer', 'null' => true),
				'group2' => array('type' => 'integer', 'null' => true),
				'indexes' => array(
					'name_idx' => array('column' => 'name', 'unique' => 0),
					'group_idx' => array('column' => 'group1', 'unique' => 0),
					'compound_idx' => array('column' => array('group1', 'group2'), 'unique' => 0),
					'PRIMARY' => array('column' => 'id', 'unique' => 1))
		)));

		$result = $this->Dbo->alterSchema($schemaB->compare($schemaA));
		$this->assertContains("ALTER TABLE $table", $result);
		$this->assertContains('ADD KEY `name_idx` (`name`),', $result);
		$this->assertContains('ADD KEY `group_idx` (`group1`),', $result);
		$this->assertContains('ADD KEY `compound_idx` (`group1`, `group2`),', $result);
		$this->assertContains('ADD PRIMARY KEY  (`id`);', $result);

		//Test that the string is syntactically correct
		$query = $this->Dbo->getConnection()->prepare($result);
		$this->assertEquals($query->queryString, $result);

		// Change three indexes, delete one and add another one
		$schemaC = new CakeSchema(array(
			'name' => 'AlterTest3',
			'connection' => 'test',
			'altertest' => array(
				'id' => array('type' => 'integer', 'null' => false, 'default' => 0),
				'name' => array('type' => 'string', 'null' => false, 'length' => 50),
				'group1' => array('type' => 'integer', 'null' => true),
				'group2' => array('type' => 'integer', 'null' => true),
				'indexes' => array(
					'name_idx' => array('column' => 'name', 'unique' => 1),
					'group_idx' => array('column' => 'group2', 'unique' => 0),
					'compound_idx' => array('column' => array('group2', 'group1'), 'unique' => 0),
					'id_name_idx' => array('column' => array('id', 'name'), 'unique' => 0))
		)));

		$result = $this->Dbo->alterSchema($schemaC->compare($schemaB));
		$this->assertContains("ALTER TABLE $table", $result);
		$this->assertContains('DROP PRIMARY KEY,', $result);
		$this->assertContains('DROP KEY `name_idx`,', $result);
		$this->assertContains('DROP KEY `group_idx`,', $result);
		$this->assertContains('DROP KEY `compound_idx`,', $result);
		$this->assertContains('ADD KEY `id_name_idx` (`id`, `name`),', $result);
		$this->assertContains('ADD UNIQUE KEY `name_idx` (`name`),', $result);
		$this->assertContains('ADD KEY `group_idx` (`group2`),', $result);
		$this->assertContains('ADD KEY `compound_idx` (`group2`, `group1`);', $result);

		$query = $this->Dbo->getConnection()->prepare($result);
		$this->assertEquals($query->queryString, $result);

		// Compare us to ourself.
		$this->assertEquals(array(), $schemaC->compare($schemaC));

		// Drop the indexes
		$result = $this->Dbo->alterSchema($schemaA->compare($schemaC));

		$this->assertContains("ALTER TABLE $table", $result);
		$this->assertContains('DROP KEY `name_idx`,', $result);
		$this->assertContains('DROP KEY `group_idx`,', $result);
		$this->assertContains('DROP KEY `compound_idx`,', $result);
		$this->assertContains('DROP KEY `id_name_idx`;', $result);

		$query = $this->Dbo->getConnection()->prepare($result);
		$this->assertEquals($query->queryString, $result);
	}

/**
 * test saving and retrieval of blobs
 *
 * @return void
 */
	public function testBlobSaving() {
		$this->loadFixtures('BinaryTest');
		$this->Dbo->cacheSources = false;
		$data = file_get_contents(CAKE . 'Test' . DS . 'test_app' . DS . 'webroot' . DS . 'img' . DS . 'cake.power.gif');

		$model = new CakeTestModel(array('name' => 'BinaryTest', 'ds' => 'test'));
		$model->save(compact('data'));

		$result = $model->find('first');
		$this->assertEquals($data, $result['BinaryTest']['data']);
	}

/**
 * test altering the table settings with schema.
 *
 * @return void
 */
	public function testAlteringTableParameters() {
		$this->Dbo->cacheSources = $this->Dbo->testing = false;

		$schemaA = new CakeSchema(array(
			'name' => 'AlterTest1',
			'connection' => 'test',
			'altertest' => array(
				'id' => array('type' => 'integer', 'null' => false, 'default' => 0),
				'name' => array('type' => 'string', 'null' => false, 'length' => 50),
				'tableParameters' => array(
					'charset' => 'latin1',
					'collate' => 'latin1_general_ci',
					'engine' => 'MyISAM'
				)
			)
		));
		$this->Dbo->rawQuery($this->Dbo->createSchema($schemaA));
		$schemaB = new CakeSchema(array(
			'name' => 'AlterTest1',
			'connection' => 'test',
			'altertest' => array(
				'id' => array('type' => 'integer', 'null' => false, 'default' => 0),
				'name' => array('type' => 'string', 'null' => false, 'length' => 50),
				'tableParameters' => array(
					'charset' => 'utf8',
					'collate' => 'utf8_general_ci',
					'engine' => 'InnoDB'
				)
			)
		));
		$result = $this->Dbo->alterSchema($schemaB->compare($schemaA));
		$this->assertContains('DEFAULT CHARSET=utf8', $result);
		$this->assertContains('ENGINE=InnoDB', $result);
		$this->assertContains('COLLATE=utf8_general_ci', $result);

		$this->Dbo->rawQuery($result);
		$result = $this->Dbo->listDetailedSources($this->Dbo->fullTableName('altertest', false, false));
		$this->assertEquals('utf8_general_ci', $result['Collation']);
		$this->assertEquals('InnoDB', $result['Engine']);
		$this->assertEquals('utf8', $result['charset']);

		$this->Dbo->rawQuery($this->Dbo->dropSchema($schemaA));
	}

/**
 * test alterSchema on two tables.
 *
 * @return void
 */
	public function testAlteringTwoTables() {
		$schema1 = new CakeSchema(array(
			'name' => 'AlterTest1',
			'connection' => 'test',
			'altertest' => array(
				'id' => array('type' => 'integer', 'null' => false, 'default' => 0),
				'name' => array('type' => 'string', 'null' => false, 'length' => 50),
			),
			'other_table' => array(
				'id' => array('type' => 'integer', 'null' => false, 'default' => 0),
				'name' => array('type' => 'string', 'null' => false, 'length' => 50),
			)
		));
		$schema2 = new CakeSchema(array(
			'name' => 'AlterTest1',
			'connection' => 'test',
			'altertest' => array(
				'id' => array('type' => 'integer', 'null' => false, 'default' => 0),
				'field_two' => array('type' => 'string', 'null' => false, 'length' => 50),
			),
			'other_table' => array(
				'id' => array('type' => 'integer', 'null' => false, 'default' => 0),
				'field_two' => array('type' => 'string', 'null' => false, 'length' => 50),
			)
		));
		$result = $this->Dbo->alterSchema($schema2->compare($schema1));
		$this->assertEquals(2, substr_count($result, 'field_two'), 'Too many fields');
	}

/**
 * testReadTableParameters method
 *
 * @return void
 */
	public function testReadTableParameters() {
		$this->Dbo->cacheSources = $this->Dbo->testing = false;
		$tableName = 'tinyint_' . uniqid();
		$table = $this->Dbo->fullTableName($tableName);
		$this->Dbo->rawQuery('CREATE TABLE ' . $table . ' (id int(11) AUTO_INCREMENT, bool tinyint(1), small_int tinyint(2), primary key(id)) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;');
		$result = $this->Dbo->readTableParameters($this->Dbo->fullTableName($tableName, false, false));
		$this->Dbo->rawQuery('DROP TABLE ' . $table);
		$expected = array(
			'charset' => 'utf8',
			'collate' => 'utf8_unicode_ci',
			'engine' => 'InnoDB');
		$this->assertEquals($expected, $result);

		$table = $this->Dbo->fullTableName($tableName);
		$this->Dbo->rawQuery('CREATE TABLE ' . $table . ' (id int(11) AUTO_INCREMENT, bool tinyint(1), small_int tinyint(2), primary key(id)) ENGINE=MyISAM DEFAULT CHARSET=cp1250 COLLATE=cp1250_general_ci;');
		$result = $this->Dbo->readTableParameters($this->Dbo->fullTableName($tableName, false, false));
		$this->Dbo->rawQuery('DROP TABLE ' . $table);
		$expected = array(
			'charset' => 'cp1250',
			'collate' => 'cp1250_general_ci',
			'engine' => 'MyISAM');
		$this->assertEquals($expected, $result);
	}

/**
 * testBuildTableParameters method
 *
 * @return void
 */
	public function testBuildTableParameters() {
		$this->Dbo->cacheSources = $this->Dbo->testing = false;
		$data = array(
			'charset' => 'utf8',
			'collate' => 'utf8_unicode_ci',
			'engine' => 'InnoDB');
		$result = $this->Dbo->buildTableParameters($data);
		$expected = array(
			'DEFAULT CHARSET=utf8',
			'COLLATE=utf8_unicode_ci',
			'ENGINE=InnoDB');
		$this->assertEquals($expected, $result);
	}

/**
 * testGetCharsetName method
 *
 * @return void
 */
	public function testGetCharsetName() {
		$this->Dbo->cacheSources = $this->Dbo->testing = false;
		$result = $this->Dbo->getCharsetName('utf8_unicode_ci');
		$this->assertEquals('utf8', $result);
		$result = $this->Dbo->getCharsetName('cp1250_general_ci');
		$this->assertEquals('cp1250', $result);
	}

/**
 * testGetCharsetNameCaching method
 *
 * @return void
 */
	public function testGetCharsetNameCaching() {
		$db = $this->getMock('Mysql', array('connect', '_execute', 'getVersion'));
		$queryResult = $this->getMock('PDOStatement');

		$db->expects($this->exactly(2))->method('getVersion')->will($this->returnValue('5.1'));

		$db->expects($this->exactly(1))
			->method('_execute')
			->with('SELECT CHARACTER_SET_NAME FROM INFORMATION_SCHEMA.COLLATIONS WHERE COLLATION_NAME = ?', array('utf8_unicode_ci'))
			->will($this->returnValue($queryResult));

		$queryResult->expects($this->once())
			->method('fetch')
			->with(PDO::FETCH_ASSOC)
			->will($this->returnValue(array('CHARACTER_SET_NAME' => 'utf8')));

		$result = $db->getCharsetName('utf8_unicode_ci');
		$this->assertEquals('utf8', $result);

		$result = $db->getCharsetName('utf8_unicode_ci');
		$this->assertEquals('utf8', $result);
	}

/**
 * test that changing the virtualFieldSeparator allows for __ fields.
 *
 * @return void
 */
	public function testVirtualFieldSeparators() {
		$this->loadFixtures('BinaryTest');
		$model = new CakeTestModel(array('table' => 'binary_tests', 'ds' => 'test', 'name' => 'BinaryTest'));
		$model->virtualFields = array(
			'other__field' => 'SUM(id)'
		);

		$this->Dbo->virtualFieldSeparator = '_$_';
		$result = $this->Dbo->fields($model, null, array('data', 'other__field'));

		$expected = array('`BinaryTest`.`data`', '(SUM(id)) AS  `BinaryTest_$_other__field`');
		$this->assertEquals($expected, $result);
	}

/**
 * Test describe() on a fixture.
 *
 * @return void
 */
	public function testDescribe() {
		$this->loadFixtures('Apple');

		$model = new Apple();
		$result = $this->Dbo->describe($model);

		$this->assertTrue(isset($result['id']));
		$this->assertTrue(isset($result['color']));

		$result = $this->Dbo->describe($model->useTable);

		$this->assertTrue(isset($result['id']));
		$this->assertTrue(isset($result['color']));
	}

/**
 * test that a describe() gets additional fieldParameters
 *
 * @return void
 */
	public function testDescribeGettingFieldParameters() {
		$schema = new CakeSchema(array(
			'connection' => 'test',
			'testdescribes' => array(
				'id' => array('type' => 'integer', 'key' => 'primary'),
				'stringy' => array(
					'type' => 'string',
					'null' => true,
					'charset' => 'cp1250',
					'collate' => 'cp1250_general_ci',
				),
				'other_col' => array(
					'type' => 'string',
					'null' => false,
					'charset' => 'latin1',
					'comment' => 'Test Comment'
				)
			)
		));

		$this->Dbo->execute($this->Dbo->createSchema($schema));
		$model = new CakeTestModel(array('table' => 'testdescribes', 'name' => 'Testdescribes'));
		$result = $model->getDataSource()->describe($model);
		$this->Dbo->execute($this->Dbo->dropSchema($schema));

		$this->assertEquals('cp1250_general_ci', $result['stringy']['collate']);
		$this->assertEquals('cp1250', $result['stringy']['charset']);
		$this->assertEquals('Test Comment', $result['other_col']['comment']);
	}

/**
 * Test that two columns with key => primary doesn't create invalid sql.
 *
 * @return void
 */
	public function testTwoColumnsWithPrimaryKey() {
		$schema = new CakeSchema(array(
			'connection' => 'test',
			'roles_users' => array(
				'role_id' => array(
					'type' => 'integer',
					'null' => false,
					'default' => null,
					'key' => 'primary'
				),
				'user_id' => array(
					'type' => 'integer',
					'null' => false,
					'default' => null,
					'key' => 'primary'
				),
				'indexes' => array(
					'user_role_index' => array(
						'column' => array('role_id', 'user_id'),
						'unique' => 1
					),
					'user_index' => array(
						'column' => 'user_id',
						'unique' => 0
					)
				),
			)
		));

		$result = $this->Dbo->createSchema($schema);
		$this->assertContains('`role_id` int(11) NOT NULL,', $result);
		$this->assertContains('`user_id` int(11) NOT NULL,', $result);
	}

/**
 * Test that the primary flag is handled correctly.
 *
 * @return void
 */
	public function testCreateSchemaAutoPrimaryKey() {
		$schema = new CakeSchema();
		$schema->tables = array(
			'no_indexes' => array(
				'id' => array('type' => 'integer', 'null' => false, 'key' => 'primary'),
				'data' => array('type' => 'integer', 'null' => false),
				'indexes' => array(),
			)
		);
		$result = $this->Dbo->createSchema($schema, 'no_indexes');
		$this->assertContains('PRIMARY KEY  (`id`)', $result);
		$this->assertNotContains('UNIQUE KEY', $result);

		$schema->tables = array(
			'primary_index' => array(
				'id' => array('type' => 'integer', 'null' => false),
				'data' => array('type' => 'integer', 'null' => false),
				'indexes' => array(
					'PRIMARY' => array('column' => 'id', 'unique' => 1),
					'some_index' => array('column' => 'data', 'unique' => 1)
				),
			)
		);
		$result = $this->Dbo->createSchema($schema, 'primary_index');
		$this->assertContains('PRIMARY KEY  (`id`)', $result);
		$this->assertContains('UNIQUE KEY `some_index` (`data`)', $result);

		$schema->tables = array(
			'primary_flag_has_index' => array(
				'id' => array('type' => 'integer', 'null' => false, 'key' => 'primary'),
				'data' => array('type' => 'integer', 'null' => false),
				'indexes' => array (
					'some_index' => array('column' => 'data', 'unique' => 1)
				),
			)
		);
		$result = $this->Dbo->createSchema($schema, 'primary_flag_has_index');
		$this->assertContains('PRIMARY KEY  (`id`)', $result);
		$this->assertContains('UNIQUE KEY `some_index` (`data`)', $result);
	}

/**
 * Tests that listSources method sends the correct query and parses the result accordingly
 * @return void
 */
	public function testListSources() {
		$db = $this->getMock('Mysql', array('connect', '_execute'));
		$queryResult = $this->getMock('PDOStatement');
		$db->expects($this->once())
			->method('_execute')
			->with('SHOW TABLES FROM `cake`')
			->will($this->returnValue($queryResult));
		$queryResult->expects($this->at(0))
			->method('fetch')
			->will($this->returnValue(array('cake_table')));
		$queryResult->expects($this->at(1))
			->method('fetch')
			->will($this->returnValue(array('another_table')));
		$queryResult->expects($this->at(2))
			->method('fetch')
			->will($this->returnValue(null));

		$tables = $db->listSources();
		$this->assertEquals(array('cake_table', 'another_table'), $tables);
	}

/**
 * test that listDetailedSources with a named table that doesn't exist.
 *
 * @return void
 */
	public function testListDetailedSourcesNamed() {
		$this->loadFixtures('Apple');

		$result = $this->Dbo->listDetailedSources('imaginary');
		$this->assertEquals(array(), $result, 'Should be empty when table does not exist.');

		$result = $this->Dbo->listDetailedSources();
		$tableName = $this->Dbo->fullTableName('apples', false, false);
		$this->assertTrue(isset($result[$tableName]), 'Key should exist');
	}

/**
 * Tests that getVersion method sends the correct query for getting the mysql version
 * @return void
 */
	public function testGetVersion() {
		$version = $this->Dbo->getVersion();
		$this->assertTrue(is_string($version));
	}

/**
 * Tests that getVersion method sends the correct query for getting the client encoding
 * @return void
 */
	public function testGetEncoding() {
		$db = $this->getMock('Mysql', array('connect', '_execute'));
		$queryResult = $this->getMock('PDOStatement');

		$db->expects($this->once())
			->method('_execute')
			->with('SHOW VARIABLES LIKE ?', array('character_set_client'))
			->will($this->returnValue($queryResult));
		$result = new StdClass;
		$result->Value = 'utf-8';
		$queryResult->expects($this->once())
			->method('fetchObject')
			->will($this->returnValue($result));

		$encoding = $db->getEncoding();
		$this->assertEquals('utf-8', $encoding);
	}

/**
 * testFieldDoubleEscaping method
 *
 * @return void
 */
	public function testFieldDoubleEscaping() {
		$db = $this->Dbo->config['database'];
		$test = $this->getMock('Mysql', array('connect', '_execute', 'execute'));
		$test->config['database'] = $db;

		$this->Model = $this->getMock('Article2', array('getDataSource'));
		$this->Model->alias = 'Article';
		$this->Model->expects($this->any())
			->method('getDataSource')
			->will($this->returnValue($test));

		$this->assertEquals('`Article`.`id`', $this->Model->escapeField());
		$result = $test->fields($this->Model, null, $this->Model->escapeField());
		$this->assertEquals(array('`Article`.`id`'), $result);

		$test->expects($this->at(0))->method('execute')
			->with('SELECT `Article`.`id` FROM ' . $test->fullTableName('articles') . ' AS `Article`   WHERE 1 = 1');

		$result = $test->read($this->Model, array(
			'fields' => $this->Model->escapeField(),
			'conditions' => null,
			'recursive' => -1
		));

		$test->startQuote = '[';
		$test->endQuote = ']';
		$this->assertEquals('[Article].[id]', $this->Model->escapeField());

		$result = $test->fields($this->Model, null, $this->Model->escapeField());
		$this->assertEquals(array('[Article].[id]'), $result);

		$test->expects($this->at(0))->method('execute')
			->with('SELECT [Article].[id] FROM ' . $test->fullTableName('articles') . ' AS [Article]   WHERE 1 = 1');
		$result = $test->read($this->Model, array(
			'fields' => $this->Model->escapeField(),
			'conditions' => null,
			'recursive' => -1
		));
	}

/**
 * testGenerateAssociationQuerySelfJoin method
 *
 * @return void
 */
	public function testGenerateAssociationQuerySelfJoin() {
		$this->Dbo = $this->getMock('Mysql', array('connect', '_execute', 'execute'));
		$this->startTime = microtime(true);
		$this->Model = new Article2();
		$this->_buildRelatedModels($this->Model);
		$this->_buildRelatedModels($this->Model->Category2);
		$this->Model->Category2->ChildCat = new Category2();
		$this->Model->Category2->ParentCat = new Category2();

		$queryData = array();

		foreach ($this->Model->Category2->associations() as $type) {
			foreach ($this->Model->Category2->{$type} as $assoc => $assocData) {
				$linkModel = $this->Model->Category2->{$assoc};
				$external = isset($assocData['external']);

				if ($this->Model->Category2->alias == $linkModel->alias && $type != 'hasAndBelongsToMany' && $type != 'hasMany') {
					$result = $this->Dbo->generateAssociationQuery($this->Model->Category2, $linkModel, $type, $assoc, $assocData, $queryData, $external, $null);
					$this->assertFalse(empty($result));
				} else {
					if ($this->Model->Category2->useDbConfig == $linkModel->useDbConfig) {
						$result = $this->Dbo->generateAssociationQuery($this->Model->Category2, $linkModel, $type, $assoc, $assocData, $queryData, $external, $null);
						$this->assertFalse(empty($result));
					}
				}
			}
		}

		$query = $this->Dbo->generateAssociationQuery($this->Model->Category2, $null, null, null, null, $queryData, false, $null);
		$this->assertRegExp('/^SELECT\s+(.+)FROM(.+)`Category2`\.`group_id`\s+=\s+`Group`\.`id`\)\s+LEFT JOIN(.+)WHERE\s+1 = 1\s*$/', $query);

		$this->Model = new TestModel4();
		$this->Model->schema();
		$this->_buildRelatedModels($this->Model);

		$binding = array('type' => 'belongsTo', 'model' => 'TestModel4Parent');
		$queryData = array();
		$resultSet = null;
		$null = null;

		$params = &$this->_prepareAssociationQuery($this->Model, $queryData, $binding);

		$_queryData = $queryData;
		$result = $this->Dbo->generateAssociationQuery($this->Model, $params['linkModel'], $params['type'], $params['assoc'], $params['assocData'], $queryData, $params['external'], $resultSet);
		$this->assertTrue($result);

		$expected = array(
			'conditions' => array(),
			'fields' => array(
				'`TestModel4`.`id`',
				'`TestModel4`.`name`',
				'`TestModel4`.`created`',
				'`TestModel4`.`updated`',
				'`TestModel4Parent`.`id`',
				'`TestModel4Parent`.`name`',
				'`TestModel4Parent`.`created`',
				'`TestModel4Parent`.`updated`'
			),
			'joins' => array(
				array(
					'table' => $this->Dbo->fullTableName($this->Model),
					'alias' => 'TestModel4Parent',
					'type' => 'LEFT',
					'conditions' => '`TestModel4`.`parent_id` = `TestModel4Parent`.`id`'
				)
			),
			'order' => array(),
			'limit' => array(),
			'offset' => array(),
			'group' => array(),
			'callbacks' => null
		);
		$queryData['joins'][0]['table'] = $this->Dbo->fullTableName($queryData['joins'][0]['table']);
		$this->assertEquals($expected, $queryData);

		$result = $this->Dbo->generateAssociationQuery($this->Model, $null, null, null, null, $queryData, false, $null);
		$this->assertRegExp('/^SELECT\s+`TestModel4`\.`id`, `TestModel4`\.`name`, `TestModel4`\.`created`, `TestModel4`\.`updated`, `TestModel4Parent`\.`id`, `TestModel4Parent`\.`name`, `TestModel4Parent`\.`created`, `TestModel4Parent`\.`updated`\s+/', $result);
		$this->assertRegExp('/FROM\s+\S+`test_model4` AS `TestModel4`\s+LEFT JOIN\s+\S+`test_model4` AS `TestModel4Parent`/', $result);
		$this->assertRegExp('/\s+ON\s+\(`TestModel4`.`parent_id` = `TestModel4Parent`.`id`\)\s+WHERE/', $result);
		$this->assertRegExp('/\s+WHERE\s+1 = 1\s+$/', $result);

		$params['assocData']['type'] = 'INNER';
		$this->Model->belongsTo['TestModel4Parent']['type'] = 'INNER';
		$result = $this->Dbo->generateAssociationQuery($this->Model, $params['linkModel'], $params['type'], $params['assoc'], $params['assocData'], $_queryData, $params['external'], $resultSet);
		$this->assertTrue($result);
		$this->assertEquals('INNER', $_queryData['joins'][0]['type']);
	}

/**
 * buildRelatedModels method
 *
 * @param Model $model
 * @return void
 */
	protected function _buildRelatedModels(Model $model) {
		foreach ($model->associations() as $type) {
			foreach ($model->{$type} as $assocData) {
				if (is_string($assocData)) {
					$className = $assocData;
				} elseif (isset($assocData['className'])) {
					$className = $assocData['className'];
				}
				$model->$className = new $className();
				$model->$className->schema();
			}
		}
	}

/**
 * &_prepareAssociationQuery method
 *
 * @param Model $model
 * @param array $queryData
 * @param array $binding
 * @return void
 */
	protected function &_prepareAssociationQuery(Model $model, &$queryData, $binding) {
		$type = $binding['type'];
		$assoc = $binding['model'];
		$assocData = $model->{$type}[$assoc];
		$className = $assocData['className'];

		$linkModel = $model->{$className};
		$external = isset($assocData['external']);
		$queryData = $this->_scrubQueryData($queryData);

		$result = array_merge(array('linkModel' => &$linkModel), compact('type', 'assoc', 'assocData', 'external'));
		return $result;
	}

/**
 * Helper method copied from DboSource::_scrubQueryData()
 *
 * @param array $data
 * @return array
 */
	protected function _scrubQueryData($data) {
		static $base = null;
		if ($base === null) {
			$base = array_fill_keys(array('conditions', 'fields', 'joins', 'order', 'limit', 'offset', 'group'), array());
			$base['callbacks'] = null;
		}
		return (array)$data + $base;
	}

/**
 * testGenerateInnerJoinAssociationQuery method
 *
 * @return void
 */
	public function testGenerateInnerJoinAssociationQuery() {
		$db = $this->Dbo->config['database'];
		$test = $this->getMock('Mysql', array('connect', '_execute', 'execute'));
		$test->config['database'] = $db;

		$this->Model = $this->getMock('TestModel9', array('getDataSource'));
		$this->Model->expects($this->any())
			->method('getDataSource')
			->will($this->returnValue($test));

		$this->Model->TestModel8 = $this->getMock('TestModel8', array('getDataSource'));
		$this->Model->TestModel8->expects($this->any())
			->method('getDataSource')
			->will($this->returnValue($test));

		$testModel8Table = $this->Model->TestModel8->getDataSource()->fullTableName($this->Model->TestModel8);

		$test->expects($this->at(0))->method('execute')
			->with($this->stringContains('`TestModel9` LEFT JOIN ' . $testModel8Table));

		$test->expects($this->at(1))->method('execute')
			->with($this->stringContains('TestModel9` INNER JOIN ' . $testModel8Table));

		$test->read($this->Model, array('recursive' => 1));
		$this->Model->belongsTo['TestModel8']['type'] = 'INNER';
		$test->read($this->Model, array('recursive' => 1));
	}

/**
 * testGenerateAssociationQuerySelfJoinWithConditionsInHasOneBinding method
 *
 * @return void
 */
	public function testGenerateAssociationQuerySelfJoinWithConditionsInHasOneBinding() {
		$this->Model = new TestModel8();
		$this->Model->schema();
		$this->_buildRelatedModels($this->Model);

		$binding = array('type' => 'hasOne', 'model' => 'TestModel9');
		$queryData = array();
		$resultSet = null;
		$null = null;

		$params = &$this->_prepareAssociationQuery($this->Model, $queryData, $binding);
		$result = $this->Dbo->generateAssociationQuery($this->Model, $params['linkModel'], $params['type'], $params['assoc'], $params['assocData'], $queryData, $params['external'], $resultSet);
		$this->assertTrue($result);

		$result = $this->Dbo->generateAssociationQuery($this->Model, $null, null, null, null, $queryData, false, $null);
		$this->assertRegExp('/^SELECT\s+`TestModel8`\.`id`, `TestModel8`\.`test_model9_id`, `TestModel8`\.`name`, `TestModel8`\.`created`, `TestModel8`\.`updated`, `TestModel9`\.`id`, `TestModel9`\.`test_model8_id`, `TestModel9`\.`name`, `TestModel9`\.`created`, `TestModel9`\.`updated`\s+/', $result);
		$this->assertRegExp('/FROM\s+\S+`test_model8` AS `TestModel8`\s+LEFT JOIN\s+\S+`test_model9` AS `TestModel9`/', $result);
		$this->assertRegExp('/\s+ON\s+\(`TestModel9`\.`name` != \'mariano\'\s+AND\s+`TestModel9`.`test_model8_id` = `TestModel8`.`id`\)\s+WHERE/', $result);
		$this->assertRegExp('/\s+WHERE\s+(?:\()?1\s+=\s+1(?:\))?\s*$/', $result);
	}

/**
 * testGenerateAssociationQuerySelfJoinWithConditionsInBelongsToBinding method
 *
 * @return void
 */
	public function testGenerateAssociationQuerySelfJoinWithConditionsInBelongsToBinding() {
		$this->Model = new TestModel9();
		$this->Model->schema();
		$this->_buildRelatedModels($this->Model);

		$binding = array('type' => 'belongsTo', 'model' => 'TestModel8');
		$queryData = array();
		$resultSet = null;
		$null = null;

		$params = &$this->_prepareAssociationQuery($this->Model, $queryData, $binding);
		$result = $this->Dbo->generateAssociationQuery($this->Model, $params['linkModel'], $params['type'], $params['assoc'], $params['assocData'], $queryData, $params['external'], $resultSet);
		$this->assertTrue($result);

		$result = $this->Dbo->generateAssociationQuery($this->Model, $null, null, null, null, $queryData, false, $null);
		$this->assertRegExp('/^SELECT\s+`TestModel9`\.`id`, `TestModel9`\.`test_model8_id`, `TestModel9`\.`name`, `TestModel9`\.`created`, `TestModel9`\.`updated`, `TestModel8`\.`id`, `TestModel8`\.`test_model9_id`, `TestModel8`\.`name`, `TestModel8`\.`created`, `TestModel8`\.`updated`\s+/', $result);
		$this->assertRegExp('/FROM\s+\S+`test_model9` AS `TestModel9`\s+LEFT JOIN\s+\S+`test_model8` AS `TestModel8`/', $result);
		$this->assertRegExp('/\s+ON\s+\(`TestModel8`\.`name` != \'larry\'\s+AND\s+`TestModel9`.`test_model8_id` = `TestModel8`.`id`\)\s+WHERE/', $result);
		$this->assertRegExp('/\s+WHERE\s+(?:\()?1\s+=\s+1(?:\))?\s*$/', $result);
	}

/**
 * testGenerateAssociationQuerySelfJoinWithConditions method
 *
 * @return void
 */
	public function testGenerateAssociationQuerySelfJoinWithConditions() {
		$this->Model = new TestModel4();
		$this->Model->schema();
		$this->_buildRelatedModels($this->Model);

		$binding = array('type' => 'belongsTo', 'model' => 'TestModel4Parent');
		$queryData = array('conditions' => array('TestModel4Parent.name !=' => 'mariano'));
		$resultSet = null;
		$null = null;

		$params = &$this->_prepareAssociationQuery($this->Model, $queryData, $binding);

		$result = $this->Dbo->generateAssociationQuery($this->Model, $params['linkModel'], $params['type'], $params['assoc'], $params['assocData'], $queryData, $params['external'], $resultSet);
		$this->assertTrue($result);

		$result = $this->Dbo->generateAssociationQuery($this->Model, $null, null, null, null, $queryData, false, $null);
		$this->assertRegExp('/^SELECT\s+`TestModel4`\.`id`, `TestModel4`\.`name`, `TestModel4`\.`created`, `TestModel4`\.`updated`, `TestModel4Parent`\.`id`, `TestModel4Parent`\.`name`, `TestModel4Parent`\.`created`, `TestModel4Parent`\.`updated`\s+/', $result);
		$this->assertRegExp('/FROM\s+\S+`test_model4` AS `TestModel4`\s+LEFT JOIN\s+\S+`test_model4` AS `TestModel4Parent`/', $result);
		$this->assertRegExp('/\s+ON\s+\(`TestModel4`.`parent_id` = `TestModel4Parent`.`id`\)\s+WHERE/', $result);
		$this->assertRegExp('/\s+WHERE\s+(?:\()?`TestModel4Parent`.`name`\s+!=\s+\'mariano\'(?:\))?\s*$/', $result);

		$this->Featured2 = new Featured2();
		$this->Featured2->schema();

		$this->Featured2->bindModel(array(
			'belongsTo' => array(
				'ArticleFeatured2' => array(
					'conditions' => 'ArticleFeatured2.published = \'Y\'',
					'fields' => 'id, title, user_id, published'
				)
			)
		));

		$this->_buildRelatedModels($this->Featured2);

		$binding = array('type' => 'belongsTo', 'model' => 'ArticleFeatured2');
		$queryData = array('conditions' => array());
		$resultSet = null;
		$null = null;

		$params = &$this->_prepareAssociationQuery($this->Featured2, $queryData, $binding);

		$result = $this->Dbo->generateAssociationQuery($this->Featured2, $params['linkModel'], $params['type'], $params['assoc'], $params['assocData'], $queryData, $params['external'], $resultSet);
		$this->assertTrue($result);
		$result = $this->Dbo->generateAssociationQuery($this->Featured2, $null, null, null, null, $queryData, false, $null);

		$this->assertRegExp(
			'/^SELECT\s+`Featured2`\.`id`, `Featured2`\.`article_id`, `Featured2`\.`category_id`, `Featured2`\.`name`,\s+' .
			'`ArticleFeatured2`\.`id`, `ArticleFeatured2`\.`title`, `ArticleFeatured2`\.`user_id`, `ArticleFeatured2`\.`published`\s+' .
			'FROM\s+\S+`featured2` AS `Featured2`\s+LEFT JOIN\s+\S+`article_featured` AS `ArticleFeatured2`' .
			'\s+ON\s+\(`ArticleFeatured2`.`published` = \'Y\'\s+AND\s+`Featured2`\.`article_featured2_id` = `ArticleFeatured2`\.`id`\)' .
			'\s+WHERE\s+1\s+=\s+1\s*$/',
			$result
		);
	}

/**
 * testGenerateAssociationQueryHasOne method
 *
 * @return void
 */
	public function testGenerateAssociationQueryHasOne() {
		$this->Model = new TestModel4();
		$this->Model->schema();
		$this->_buildRelatedModels($this->Model);

		$binding = array('type' => 'hasOne', 'model' => 'TestModel5');

		$queryData = array();
		$resultSet = null;
		$null = null;

		$params = &$this->_prepareAssociationQuery($this->Model, $queryData, $binding);

		$result = $this->Dbo->generateAssociationQuery($this->Model, $params['linkModel'], $params['type'], $params['assoc'], $params['assocData'], $queryData, $params['external'], $resultSet);
		$this->assertTrue($result);

		$testModel5Table = $this->Dbo->fullTableName($this->Model->TestModel5);
		$result = $this->Dbo->buildJoinStatement($queryData['joins'][0]);
		$expected = ' LEFT JOIN ' . $testModel5Table . ' AS `TestModel5` ON (`TestModel5`.`test_model4_id` = `TestModel4`.`id`)';
		$this->assertEquals(trim($expected), trim($result));

		$result = $this->Dbo->generateAssociationQuery($this->Model, $null, null, null, null, $queryData, false, $null);
		$this->assertRegExp('/^SELECT\s+`TestModel4`\.`id`, `TestModel4`\.`name`, `TestModel4`\.`created`, `TestModel4`\.`updated`, `TestModel5`\.`id`, `TestModel5`\.`test_model4_id`, `TestModel5`\.`name`, `TestModel5`\.`created`, `TestModel5`\.`updated`\s+/', $result);
		$this->assertRegExp('/\s+FROM\s+\S+`test_model4` AS `TestModel4`\s+LEFT JOIN\s+/', $result);
		$this->assertRegExp('/`test_model5` AS `TestModel5`\s+ON\s+\(`TestModel5`.`test_model4_id` = `TestModel4`.`id`\)\s+WHERE/', $result);
		$this->assertRegExp('/\s+WHERE\s+(?:\()?\s*1 = 1\s*(?:\))?\s*$/', $result);
	}

/**
 * testGenerateAssociationQueryHasOneWithConditions method
 *
 * @return void
 */
	public function testGenerateAssociationQueryHasOneWithConditions() {
		$this->Model = new TestModel4();
		$this->Model->schema();
		$this->_buildRelatedModels($this->Model);

		$binding = array('type' => 'hasOne', 'model' => 'TestModel5');

		$queryData = array('conditions' => array('TestModel5.name !=' => 'mariano'));
		$resultSet = null;
		$null = null;

		$params = &$this->_prepareAssociationQuery($this->Model, $queryData, $binding);

		$result = $this->Dbo->generateAssociationQuery($this->Model, $params['linkModel'], $params['type'], $params['assoc'], $params['assocData'], $queryData, $params['external'], $resultSet);
		$this->assertTrue($result);

		$result = $this->Dbo->generateAssociationQuery($this->Model, $null, null, null, null, $queryData, false, $null);

		$this->assertRegExp('/^SELECT\s+`TestModel4`\.`id`, `TestModel4`\.`name`, `TestModel4`\.`created`, `TestModel4`\.`updated`, `TestModel5`\.`id`, `TestModel5`\.`test_model4_id`, `TestModel5`\.`name`, `TestModel5`\.`created`, `TestModel5`\.`updated`\s+/', $result);
		$this->assertRegExp('/\s+FROM\s+\S+`test_model4` AS `TestModel4`\s+LEFT JOIN\s+\S+`test_model5` AS `TestModel5`/', $result);
		$this->assertRegExp('/\s+ON\s+\(`TestModel5`.`test_model4_id`\s+=\s+`TestModel4`.`id`\)\s+WHERE/', $result);
		$this->assertRegExp('/\s+WHERE\s+(?:\()?\s*`TestModel5`.`name`\s+!=\s+\'mariano\'\s*(?:\))?\s*$/', $result);
	}

/**
 * testGenerateAssociationQueryBelongsTo method
 *
 * @return void
 */
	public function testGenerateAssociationQueryBelongsTo() {
		$this->Model = new TestModel5();
		$this->Model->schema();
		$this->_buildRelatedModels($this->Model);

		$binding = array('type' => 'belongsTo', 'model' => 'TestModel4');
		$queryData = array();
		$resultSet = null;
		$null = null;

		$params = &$this->_prepareAssociationQuery($this->Model, $queryData, $binding);

		$result = $this->Dbo->generateAssociationQuery($this->Model, $params['linkModel'], $params['type'], $params['assoc'], $params['assocData'], $queryData, $params['external'], $resultSet);
		$this->assertTrue($result);

		$testModel4Table = $this->Dbo->fullTableName($this->Model->TestModel4, true, true);
		$result = $this->Dbo->buildJoinStatement($queryData['joins'][0]);
		$expected = ' LEFT JOIN ' . $testModel4Table . ' AS `TestModel4` ON (`TestModel5`.`test_model4_id` = `TestModel4`.`id`)';
		$this->assertEquals(trim($expected), trim($result));

		$result = $this->Dbo->generateAssociationQuery($this->Model, $null, null, null, null, $queryData, false, $null);
		$this->assertRegExp('/^SELECT\s+`TestModel5`\.`id`, `TestModel5`\.`test_model4_id`, `TestModel5`\.`name`, `TestModel5`\.`created`, `TestModel5`\.`updated`, `TestModel4`\.`id`, `TestModel4`\.`name`, `TestModel4`\.`created`, `TestModel4`\.`updated`\s+/', $result);
		$this->assertRegExp('/\s+FROM\s+\S+`test_model5` AS `TestModel5`\s+LEFT JOIN\s+\S+`test_model4` AS `TestModel4`/', $result);
		$this->assertRegExp('/\s+ON\s+\(`TestModel5`.`test_model4_id` = `TestModel4`.`id`\)\s+WHERE\s+/', $result);
		$this->assertRegExp('/\s+WHERE\s+(?:\()?\s*1 = 1\s*(?:\))?\s*$/', $result);
	}

/**
 * testGenerateAssociationQueryBelongsToWithConditions method
 *
 * @return void
 */
	public function testGenerateAssociationQueryBelongsToWithConditions() {
		$this->Model = new TestModel5();
		$this->Model->schema();
		$this->_buildRelatedModels($this->Model);

		$binding = array('type' => 'belongsTo', 'model' => 'TestModel4');
		$queryData = array('conditions' => array('TestModel5.name !=' => 'mariano'));
		$resultSet = null;
		$null = null;

		$params = &$this->_prepareAssociationQuery($this->Model, $queryData, $binding);

		$result = $this->Dbo->generateAssociationQuery($this->Model, $params['linkModel'], $params['type'], $params['assoc'], $params['assocData'], $queryData, $params['external'], $resultSet);
		$this->assertTrue($result);

		$testModel4Table = $this->Dbo->fullTableName($this->Model->TestModel4, true, true);
		$result = $this->Dbo->buildJoinStatement($queryData['joins'][0]);
		$expected = ' LEFT JOIN ' . $testModel4Table . ' AS `TestModel4` ON (`TestModel5`.`test_model4_id` = `TestModel4`.`id`)';
		$this->assertEquals(trim($expected), trim($result));

		$result = $this->Dbo->generateAssociationQuery($this->Model, $null, null, null, null, $queryData, false, $null);
		$this->assertRegExp('/^SELECT\s+`TestModel5`\.`id`, `TestModel5`\.`test_model4_id`, `TestModel5`\.`name`, `TestModel5`\.`created`, `TestModel5`\.`updated`, `TestModel4`\.`id`, `TestModel4`\.`name`, `TestModel4`\.`created`, `TestModel4`\.`updated`\s+/', $result);
		$this->assertRegExp('/\s+FROM\s+\S+`test_model5` AS `TestModel5`\s+LEFT JOIN\s+\S+`test_model4` AS `TestModel4`/', $result);
		$this->assertRegExp('/\s+ON\s+\(`TestModel5`.`test_model4_id` = `TestModel4`.`id`\)\s+WHERE\s+/', $result);
		$this->assertRegExp('/\s+WHERE\s+`TestModel5`.`name` != \'mariano\'\s*$/', $result);
	}

/**
 * testGenerateAssociationQueryHasMany method
 *
 * @return void
 */
	public function testGenerateAssociationQueryHasMany() {
		$this->Model = new TestModel5();
		$this->Model->schema();
		$this->_buildRelatedModels($this->Model);

		$binding = array('type' => 'hasMany', 'model' => 'TestModel6');
		$queryData = array();
		$resultSet = null;
		$null = null;

		$params = &$this->_prepareAssociationQuery($this->Model, $queryData, $binding);

		$result = $this->Dbo->generateAssociationQuery($this->Model, $params['linkModel'], $params['type'], $params['assoc'], $params['assocData'], $queryData, $params['external'], $resultSet);

		$this->assertRegExp('/^SELECT\s+`TestModel6`\.`id`, `TestModel6`\.`test_model5_id`, `TestModel6`\.`name`, `TestModel6`\.`created`, `TestModel6`\.`updated`\s+/', $result);
		$this->assertRegExp('/\s+FROM\s+\S+`test_model6` AS `TestModel6`\s+WHERE/', $result);
		$this->assertRegExp('/\s+WHERE\s+`TestModel6`.`test_model5_id`\s+=\s+\({\$__cakeID__\$}\)/', $result);

		$result = $this->Dbo->generateAssociationQuery($this->Model, $null, null, null, null, $queryData, false, $null);
		$this->assertRegExp('/^SELECT\s+`TestModel5`\.`id`, `TestModel5`\.`test_model4_id`, `TestModel5`\.`name`, `TestModel5`\.`created`, `TestModel5`\.`updated`\s+/', $result);
		$this->assertRegExp('/\s+FROM\s+\S+`test_model5` AS `TestModel5`\s+WHERE\s+/', $result);
		$this->assertRegExp('/\s+WHERE\s+(?:\()?\s*1 = 1\s*(?:\))?\s*$/', $result);
	}

/**
 * testGenerateAssociationQueryHasManyWithLimit method
 *
 * @return void
 */
	public function testGenerateAssociationQueryHasManyWithLimit() {
		$this->Model = new TestModel5();
		$this->Model->schema();
		$this->_buildRelatedModels($this->Model);

		$this->Model->hasMany['TestModel6']['limit'] = 2;

		$binding = array('type' => 'hasMany', 'model' => 'TestModel6');
		$queryData = array();
		$resultSet = null;
		$null = null;

		$params = &$this->_prepareAssociationQuery($this->Model, $queryData, $binding);

		$result = $this->Dbo->generateAssociationQuery($this->Model, $params['linkModel'], $params['type'], $params['assoc'], $params['assocData'], $queryData, $params['external'], $resultSet);
		$this->assertRegExp(
			'/^SELECT\s+' .
			'`TestModel6`\.`id`, `TestModel6`\.`test_model5_id`, `TestModel6`\.`name`, `TestModel6`\.`created`, `TestModel6`\.`updated`\s+' .
			'FROM\s+\S+`test_model6` AS `TestModel6`\s+WHERE\s+' .
			'`TestModel6`.`test_model5_id`\s+=\s+\({\$__cakeID__\$}\)\s*' .
			'LIMIT \d*' .
			'\s*$/', $result
		);

		$result = $this->Dbo->generateAssociationQuery($this->Model, $null, null, null, null, $queryData, false, $null);
		$this->assertRegExp(
			'/^SELECT\s+' .
			'`TestModel5`\.`id`, `TestModel5`\.`test_model4_id`, `TestModel5`\.`name`, `TestModel5`\.`created`, `TestModel5`\.`updated`\s+' .
			'FROM\s+\S+`test_model5` AS `TestModel5`\s+WHERE\s+' .
			'(?:\()?\s*1 = 1\s*(?:\))?' .
			'\s*$/', $result
		);
	}

/**
 * testGenerateAssociationQueryHasManyWithConditions method
 *
 * @return void
 */
	public function testGenerateAssociationQueryHasManyWithConditions() {
		$this->Model = new TestModel5();
		$this->Model->schema();
		$this->_buildRelatedModels($this->Model);

		$binding = array('type' => 'hasMany', 'model' => 'TestModel6');
		$queryData = array('conditions' => array('TestModel5.name !=' => 'mariano'));
		$resultSet = null;
		$null = null;

		$params = &$this->_prepareAssociationQuery($this->Model, $queryData, $binding);

		$result = $this->Dbo->generateAssociationQuery($this->Model, $params['linkModel'], $params['type'], $params['assoc'], $params['assocData'], $queryData, $params['external'], $resultSet);
		$this->assertRegExp('/^SELECT\s+`TestModel6`\.`id`, `TestModel6`\.`test_model5_id`, `TestModel6`\.`name`, `TestModel6`\.`created`, `TestModel6`\.`updated`\s+/', $result);
		$this->assertRegExp('/\s+FROM\s+\S+`test_model6` AS `TestModel6`\s+WHERE\s+/', $result);
		$this->assertRegExp('/WHERE\s+(?:\()?`TestModel6`\.`test_model5_id`\s+=\s+\({\$__cakeID__\$}\)(?:\))?/', $result);

		$result = $this->Dbo->generateAssociationQuery($this->Model, $null, null, null, null, $queryData, false, $null);
		$this->assertRegExp('/^SELECT\s+`TestModel5`\.`id`, `TestModel5`\.`test_model4_id`, `TestModel5`\.`name`, `TestModel5`\.`created`, `TestModel5`\.`updated`\s+/', $result);
		$this->assertRegExp('/\s+FROM\s+\S+`test_model5` AS `TestModel5`\s+WHERE\s+/', $result);
		$this->assertRegExp('/\s+WHERE\s+(?:\()?`TestModel5`.`name`\s+!=\s+\'mariano\'(?:\))?\s*$/', $result);
	}

/**
 * testGenerateAssociationQueryHasManyWithOffsetAndLimit method
 *
 * @return void
 */
	public function testGenerateAssociationQueryHasManyWithOffsetAndLimit() {
		$this->Model = new TestModel5();
		$this->Model->schema();
		$this->_buildRelatedModels($this->Model);

		$backup = $this->Model->hasMany['TestModel6'];

		$this->Model->hasMany['TestModel6']['offset'] = 2;
		$this->Model->hasMany['TestModel6']['limit'] = 5;

		$binding = array('type' => 'hasMany', 'model' => 'TestModel6');
		$queryData = array();
		$resultSet = null;
		$null = null;

		$params = &$this->_prepareAssociationQuery($this->Model, $queryData, $binding);

		$result = $this->Dbo->generateAssociationQuery($this->Model, $params['linkModel'], $params['type'], $params['assoc'], $params['assocData'], $queryData, $params['external'], $resultSet);

		$this->assertRegExp('/^SELECT\s+`TestModel6`\.`id`, `TestModel6`\.`test_model5_id`, `TestModel6`\.`name`, `TestModel6`\.`created`, `TestModel6`\.`updated`\s+/', $result);
		$this->assertRegExp('/\s+FROM\s+\S+`test_model6` AS `TestModel6`\s+WHERE\s+/', $result);
		$this->assertRegExp('/WHERE\s+(?:\()?`TestModel6`\.`test_model5_id`\s+=\s+\({\$__cakeID__\$}\)(?:\))?/', $result);
		$this->assertRegExp('/\s+LIMIT 2,\s*5\s*$/', $result);

		$result = $this->Dbo->generateAssociationQuery($this->Model, $null, null, null, null, $queryData, false, $null);
		$this->assertRegExp('/^SELECT\s+`TestModel5`\.`id`, `TestModel5`\.`test_model4_id`, `TestModel5`\.`name`, `TestModel5`\.`created`, `TestModel5`\.`updated`\s+/', $result);
		$this->assertRegExp('/\s+FROM\s+\S+`test_model5` AS `TestModel5`\s+WHERE\s+/', $result);
		$this->assertRegExp('/\s+WHERE\s+(?:\()?1\s+=\s+1(?:\))?\s*$/', $result);

		$this->Model->hasMany['TestModel6'] = $backup;
	}

/**
 * testGenerateAssociationQueryHasManyWithPageAndLimit method
 *
 * @return void
 */
	public function testGenerateAssociationQueryHasManyWithPageAndLimit() {
		$this->Model = new TestModel5();
		$this->Model->schema();
		$this->_buildRelatedModels($this->Model);

		$backup = $this->Model->hasMany['TestModel6'];

		$this->Model->hasMany['TestModel6']['page'] = 2;
		$this->Model->hasMany['TestModel6']['limit'] = 5;

		$binding = array('type' => 'hasMany', 'model' => 'TestModel6');
		$queryData = array();
		$resultSet = null;
		$null = null;

		$params = &$this->_prepareAssociationQuery($this->Model, $queryData, $binding);

		$result = $this->Dbo->generateAssociationQuery($this->Model, $params['linkModel'], $params['type'], $params['assoc'], $params['assocData'], $queryData, $params['external'], $resultSet);
		$this->assertRegExp('/^SELECT\s+`TestModel6`\.`id`, `TestModel6`\.`test_model5_id`, `TestModel6`\.`name`, `TestModel6`\.`created`, `TestModel6`\.`updated`\s+/', $result);
		$this->assertRegExp('/\s+FROM\s+\S+`test_model6` AS `TestModel6`\s+WHERE\s+/', $result);
		$this->assertRegExp('/WHERE\s+(?:\()?`TestModel6`\.`test_model5_id`\s+=\s+\({\$__cakeID__\$}\)(?:\))?/', $result);
		$this->assertRegExp('/\s+LIMIT 5,\s*5\s*$/', $result);

		$result = $this->Dbo->generateAssociationQuery($this->Model, $null, null, null, null, $queryData, false, $null);
		$this->assertRegExp('/^SELECT\s+`TestModel5`\.`id`, `TestModel5`\.`test_model4_id`, `TestModel5`\.`name`, `TestModel5`\.`created`, `TestModel5`\.`updated`\s+/', $result);
		$this->assertRegExp('/\s+FROM\s+\S+`test_model5` AS `TestModel5`\s+WHERE\s+/', $result);
		$this->assertRegExp('/\s+WHERE\s+(?:\()?1\s+=\s+1(?:\))?\s*$/', $result);

		$this->Model->hasMany['TestModel6'] = $backup;
	}

/**
 * testGenerateAssociationQueryHasManyWithFields method
 *
 * @return void
 */
	public function testGenerateAssociationQueryHasManyWithFields() {
		$this->Model = new TestModel5();
		$this->Model->schema();
		$this->_buildRelatedModels($this->Model);

		$binding = array('type' => 'hasMany', 'model' => 'TestModel6');
		$queryData = array('fields' => array('`TestModel5`.`name`'));
		$resultSet = null;
		$null = null;

		$params = &$this->_prepareAssociationQuery($this->Model, $queryData, $binding);

		$result = $this->Dbo->generateAssociationQuery($this->Model, $params['linkModel'], $params['type'], $params['assoc'], $params['assocData'], $queryData, $params['external'], $resultSet);
		$this->assertRegExp('/^SELECT\s+`TestModel6`\.`id`, `TestModel6`\.`test_model5_id`, `TestModel6`\.`name`, `TestModel6`\.`created`, `TestModel6`\.`updated`\s+/', $result);
		$this->assertRegExp('/\s+FROM\s+\S+`test_model6` AS `TestModel6`\s+WHERE\s+/', $result);
		$this->assertRegExp('/WHERE\s+(?:\()?`TestModel6`\.`test_model5_id`\s+=\s+\({\$__cakeID__\$}\)(?:\))?/', $result);

		$result = $this->Dbo->generateAssociationQuery($this->Model, $null, null, null, null, $queryData, false, $null);
		$this->assertRegExp('/^SELECT\s+`TestModel5`\.`name`, `TestModel5`\.`id`\s+/', $result);
		$this->assertRegExp('/\s+FROM\s+\S+`test_model5` AS `TestModel5`\s+WHERE\s+/', $result);
		$this->assertRegExp('/\s+WHERE\s+(?:\()?1\s+=\s+1(?:\))?\s*$/', $result);

		$binding = array('type' => 'hasMany', 'model' => 'TestModel6');
		$queryData = array('fields' => array('`TestModel5`.`id`, `TestModel5`.`name`'));
		$resultSet = null;
		$null = null;

		$params = &$this->_prepareAssociationQuery($this->Model, $queryData, $binding);

		$result = $this->Dbo->generateAssociationQuery($this->Model, $params['linkModel'], $params['type'], $params['assoc'], $params['assocData'], $queryData, $params['external'], $resultSet);
		$this->assertRegExp('/^SELECT\s+`TestModel6`\.`id`, `TestModel6`\.`test_model5_id`, `TestModel6`\.`name`, `TestModel6`\.`created`, `TestModel6`\.`updated`\s+/', $result);
		$this->assertRegExp('/\s+FROM\s+\S+`test_model6` AS `TestModel6`\s+WHERE\s+/', $result);
		$this->assertRegExp('/WHERE\s+(?:\()?`TestModel6`\.`test_model5_id`\s+=\s+\({\$__cakeID__\$}\)(?:\))?/', $result);

		$result = $this->Dbo->generateAssociationQuery($this->Model, $null, null, null, null, $queryData, false, $null);
		$this->assertRegExp('/^SELECT\s+`TestModel5`\.`id`, `TestModel5`\.`name`\s+/', $result);
		$this->assertRegExp('/\s+FROM\s+\S+`test_model5` AS `TestModel5`\s+WHERE\s+/', $result);
		$this->assertRegExp('/\s+WHERE\s+(?:\()?1\s+=\s+1(?:\))?\s*$/', $result);

		$binding = array('type' => 'hasMany', 'model' => 'TestModel6');
		$queryData = array('fields' => array('`TestModel5`.`name`', '`TestModel5`.`created`'));
		$resultSet = null;
		$null = null;

		$params = &$this->_prepareAssociationQuery($this->Model, $queryData, $binding);

		$result = $this->Dbo->generateAssociationQuery($this->Model, $params['linkModel'], $params['type'], $params['assoc'], $params['assocData'], $queryData, $params['external'], $resultSet);
		$this->assertRegExp('/^SELECT\s+`TestModel6`\.`id`, `TestModel6`\.`test_model5_id`, `TestModel6`\.`name`, `TestModel6`\.`created`, `TestModel6`\.`updated`\s+/', $result);
		$this->assertRegExp('/\s+FROM\s+\S+`test_model6` AS `TestModel6`\s+WHERE\s+/', $result);
		$this->assertRegExp('/WHERE\s+(?:\()?`TestModel6`\.`test_model5_id`\s+=\s+\({\$__cakeID__\$}\)(?:\))?/', $result);

		$result = $this->Dbo->generateAssociationQuery($this->Model, $null, null, null, null, $queryData, false, $null);
		$this->assertRegExp('/^SELECT\s+`TestModel5`\.`name`, `TestModel5`\.`created`, `TestModel5`\.`id`\s+/', $result);
		$this->assertRegExp('/\s+FROM\s+\S+`test_model5` AS `TestModel5`\s+WHERE\s+/', $result);
		$this->assertRegExp('/\s+WHERE\s+(?:\()?1\s+=\s+1(?:\))?\s*$/', $result);

		$this->Model->hasMany['TestModel6']['fields'] = array('name');

		$binding = array('type' => 'hasMany', 'model' => 'TestModel6');
		$queryData = array('fields' => array('`TestModel5`.`id`', '`TestModel5`.`name`'));
		$resultSet = null;
		$null = null;

		$params = &$this->_prepareAssociationQuery($this->Model, $queryData, $binding);

		$result = $this->Dbo->generateAssociationQuery($this->Model, $params['linkModel'], $params['type'], $params['assoc'], $params['assocData'], $queryData, $params['external'], $resultSet);
		$this->assertRegExp('/^SELECT\s+`TestModel6`\.`name`, `TestModel6`\.`test_model5_id`\s+/', $result);
		$this->assertRegExp('/\s+FROM\s+\S+`test_model6` AS `TestModel6`\s+WHERE\s+/', $result);
		$this->assertRegExp('/WHERE\s+(?:\()?`TestModel6`\.`test_model5_id`\s+=\s+\({\$__cakeID__\$}\)(?:\))?/', $result);

		$result = $this->Dbo->generateAssociationQuery($this->Model, $null, null, null, null, $queryData, false, $null);
		$this->assertRegExp('/^SELECT\s+`TestModel5`\.`id`, `TestModel5`\.`name`\s+/', $result);
		$this->assertRegExp('/\s+FROM\s+\S+`test_model5` AS `TestModel5`\s+WHERE\s+/', $result);
		$this->assertRegExp('/\s+WHERE\s+(?:\()?1\s+=\s+1(?:\))?\s*$/', $result);

		unset($this->Model->hasMany['TestModel6']['fields']);

		$this->Model->hasMany['TestModel6']['fields'] = array('id', 'name');

		$binding = array('type' => 'hasMany', 'model' => 'TestModel6');
		$queryData = array('fields' => array('`TestModel5`.`id`', '`TestModel5`.`name`'));
		$resultSet = null;
		$null = null;

		$params = &$this->_prepareAssociationQuery($this->Model, $queryData, $binding);

		$result = $this->Dbo->generateAssociationQuery($this->Model, $params['linkModel'], $params['type'], $params['assoc'], $params['assocData'], $queryData, $params['external'], $resultSet);
		$this->assertRegExp('/^SELECT\s+`TestModel6`\.`id`, `TestModel6`\.`name`, `TestModel6`\.`test_model5_id`\s+/', $result);
		$this->assertRegExp('/\s+FROM\s+\S+`test_model6` AS `TestModel6`\s+WHERE\s+/', $result);
		$this->assertRegExp('/WHERE\s+(?:\()?`TestModel6`\.`test_model5_id`\s+=\s+\({\$__cakeID__\$}\)(?:\))?/', $result);

		$result = $this->Dbo->generateAssociationQuery($this->Model, $null, null, null, null, $queryData, false, $null);
		$this->assertRegExp('/^SELECT\s+`TestModel5`\.`id`, `TestModel5`\.`name`\s+/', $result);
		$this->assertRegExp('/\s+FROM\s+\S+`test_model5` AS `TestModel5`\s+WHERE\s+/', $result);
		$this->assertRegExp('/\s+WHERE\s+(?:\()?1\s+=\s+1(?:\))?\s*$/', $result);

		unset($this->Model->hasMany['TestModel6']['fields']);

		$this->Model->hasMany['TestModel6']['fields'] = array('test_model5_id', 'name');

		$binding = array('type' => 'hasMany', 'model' => 'TestModel6');
		$queryData = array('fields' => array('`TestModel5`.`id`', '`TestModel5`.`name`'));
		$resultSet = null;
		$null = null;

		$params = &$this->_prepareAssociationQuery($this->Model, $queryData, $binding);

		$result = $this->Dbo->generateAssociationQuery($this->Model, $params['linkModel'], $params['type'], $params['assoc'], $params['assocData'], $queryData, $params['external'], $resultSet);
		$this->assertRegExp('/^SELECT\s+`TestModel6`\.`test_model5_id`, `TestModel6`\.`name`\s+/', $result);
		$this->assertRegExp('/\s+FROM\s+\S+`test_model6` AS `TestModel6`\s+WHERE\s+/', $result);
		$this->assertRegExp('/WHERE\s+(?:\()?`TestModel6`\.`test_model5_id`\s+=\s+\({\$__cakeID__\$}\)(?:\))?/', $result);

		$result = $this->Dbo->generateAssociationQuery($this->Model, $null, null, null, null, $queryData, false, $null);
		$this->assertRegExp('/^SELECT\s+`TestModel5`\.`id`, `TestModel5`\.`name`\s+/', $result);
		$this->assertRegExp('/\s+FROM\s+\S+`test_model5` AS `TestModel5`\s+WHERE\s+/', $result);
		$this->assertRegExp('/\s+WHERE\s+(?:\()?1\s+=\s+1(?:\))?\s*$/', $result);

		unset($this->Model->hasMany['TestModel6']['fields']);
	}

/**
 * test generateAssociationQuery with a hasMany and an aggregate function.
 *
 * @return void
 */
	public function testGenerateAssociationQueryHasManyAndAggregateFunction() {
		$this->Model = new TestModel5();
		$this->Model->schema();
		$this->_buildRelatedModels($this->Model);

		$binding = array('type' => 'hasMany', 'model' => 'TestModel6');
		$queryData = array('fields' => array('MIN(`TestModel5`.`test_model4_id`)'));
		$resultSet = null;
		$null = null;

		$params = &$this->_prepareAssociationQuery($this->Model, $queryData, $binding);
		$this->Model->recursive = 0;

		$result = $this->Dbo->generateAssociationQuery($this->Model, $null, $params['type'], $params['assoc'], $params['assocData'], $queryData, false, $resultSet);
		$this->assertRegExp('/^SELECT\s+MIN\(`TestModel5`\.`test_model4_id`\)\s+FROM/', $result);
	}

/**
 * testGenerateAssociationQueryHasAndBelongsToMany method
 *
 * @return void
 */
	public function testGenerateAssociationQueryHasAndBelongsToMany() {
		$this->Model = new TestModel4();
		$this->Model->schema();
		$this->_buildRelatedModels($this->Model);

		$binding = array('type' => 'hasAndBelongsToMany', 'model' => 'TestModel7');
		$queryData = array();
		$resultSet = null;
		$null = null;

		$params = $this->_prepareAssociationQuery($this->Model, $queryData, $binding);

		$result = $this->Dbo->generateAssociationQuery($this->Model, $params['linkModel'], $params['type'], $params['assoc'], $params['assocData'], $queryData, $params['external'], $resultSet);
		$assocTable = $this->Dbo->fullTableName($this->Model->TestModel4TestModel7, true, true);
		$this->assertRegExp('/^SELECT\s+`TestModel7`\.`id`, `TestModel7`\.`name`, `TestModel7`\.`created`, `TestModel7`\.`updated`, `TestModel4TestModel7`\.`test_model4_id`, `TestModel4TestModel7`\.`test_model7_id`\s+/', $result);
		$this->assertRegExp('/\s+FROM\s+\S+`test_model7` AS `TestModel7`\s+JOIN\s+' . $assocTable . '/', $result);
		$this->assertRegExp('/\s+ON\s+\(`TestModel4TestModel7`\.`test_model4_id`\s+=\s+{\$__cakeID__\$}\s+AND/', $result);
		$this->assertRegExp('/\s+AND\s+`TestModel4TestModel7`\.`test_model7_id`\s+=\s+`TestModel7`\.`id`\)/', $result);
		$this->assertRegExp('/WHERE\s+(?:\()?1 = 1(?:\))?\s*$/', $result);

		$result = $this->Dbo->generateAssociationQuery($this->Model, $null, null, null, null, $queryData, false, $null);
		$this->assertRegExp('/^SELECT\s+`TestModel4`\.`id`, `TestModel4`\.`name`, `TestModel4`\.`created`, `TestModel4`\.`updated`\s+/', $result);
		$this->assertRegExp('/\s+FROM\s+\S+`test_model4` AS `TestModel4`\s+WHERE/', $result);
		$this->assertRegExp('/\s+WHERE\s+(?:\()?1 = 1(?:\))?\s*$/', $result);
	}

/**
 * testGenerateAssociationQueryHasAndBelongsToManyWithConditions method
 *
 * @return void
 */
	public function testGenerateAssociationQueryHasAndBelongsToManyWithConditions() {
		$this->Model = new TestModel4();
		$this->Model->schema();
		$this->_buildRelatedModels($this->Model);

		$binding = array('type' => 'hasAndBelongsToMany', 'model' => 'TestModel7');
		$queryData = array('conditions' => array('TestModel4.name !=' => 'mariano'));
		$resultSet = null;
		$null = null;

		$params = $this->_prepareAssociationQuery($this->Model, $queryData, $binding);

		$result = $this->Dbo->generateAssociationQuery($this->Model, $params['linkModel'], $params['type'], $params['assoc'], $params['assocData'], $queryData, $params['external'], $resultSet);
		$this->assertRegExp('/^SELECT\s+`TestModel7`\.`id`, `TestModel7`\.`name`, `TestModel7`\.`created`, `TestModel7`\.`updated`, `TestModel4TestModel7`\.`test_model4_id`, `TestModel4TestModel7`\.`test_model7_id`\s+/', $result);
		$this->assertRegExp('/\s+FROM\s+\S+`test_model7`\s+AS\s+`TestModel7`\s+JOIN\s+\S+`test_model4_test_model7`\s+AS\s+`TestModel4TestModel7`/', $result);
		$this->assertRegExp('/\s+ON\s+\(`TestModel4TestModel7`\.`test_model4_id`\s+=\s+{\$__cakeID__\$}/', $result);
		$this->assertRegExp('/\s+AND\s+`TestModel4TestModel7`\.`test_model7_id`\s+=\s+`TestModel7`\.`id`\)\s+WHERE\s+/', $result);

		$result = $this->Dbo->generateAssociationQuery($this->Model, $null, null, null, null, $queryData, false, $null);
		$this->assertRegExp('/^SELECT\s+`TestModel4`\.`id`, `TestModel4`\.`name`, `TestModel4`\.`created`, `TestModel4`\.`updated`\s+/', $result);
		$this->assertRegExp('/\s+FROM\s+\S+`test_model4` AS `TestModel4`\s+WHERE\s+(?:\()?`TestModel4`.`name`\s+!=\s+\'mariano\'(?:\))?\s*$/', $result);
	}

/**
 * testGenerateAssociationQueryHasAndBelongsToManyWithOffsetAndLimit method
 *
 * @return void
 */
	public function testGenerateAssociationQueryHasAndBelongsToManyWithOffsetAndLimit() {
		$this->Model = new TestModel4();
		$this->Model->schema();
		$this->_buildRelatedModels($this->Model);

		$backup = $this->Model->hasAndBelongsToMany['TestModel7'];

		$this->Model->hasAndBelongsToMany['TestModel7']['offset'] = 2;
		$this->Model->hasAndBelongsToMany['TestModel7']['limit'] = 5;

		$binding = array('type' => 'hasAndBelongsToMany', 'model' => 'TestModel7');
		$queryData = array();
		$resultSet = null;
		$null = null;

		$params = &$this->_prepareAssociationQuery($this->Model, $queryData, $binding);

		$result = $this->Dbo->generateAssociationQuery($this->Model, $params['linkModel'], $params['type'], $params['assoc'], $params['assocData'], $queryData, $params['external'], $resultSet);
		$this->assertRegExp('/^SELECT\s+`TestModel7`\.`id`, `TestModel7`\.`name`, `TestModel7`\.`created`, `TestModel7`\.`updated`, `TestModel4TestModel7`\.`test_model4_id`, `TestModel4TestModel7`\.`test_model7_id`\s+/', $result);
		$this->assertRegExp('/\s+FROM\s+\S+`test_model7`\s+AS\s+`TestModel7`\s+JOIN\s+\S+`test_model4_test_model7`\s+AS\s+`TestModel4TestModel7`/', $result);
		$this->assertRegExp('/\s+ON\s+\(`TestModel4TestModel7`\.`test_model4_id`\s+=\s+{\$__cakeID__\$}\s+/', $result);
		$this->assertRegExp('/\s+AND\s+`TestModel4TestModel7`\.`test_model7_id`\s+=\s+`TestModel7`\.`id`\)\s+WHERE\s+/', $result);
		$this->assertRegExp('/\s+(?:\()?1\s+=\s+1(?:\))?\s*\s+LIMIT 2,\s*5\s*$/', $result);

		$result = $this->Dbo->generateAssociationQuery($this->Model, $null, null, null, null, $queryData, false, $null);
		$this->assertRegExp('/^SELECT\s+`TestModel4`\.`id`, `TestModel4`\.`name`, `TestModel4`\.`created`, `TestModel4`\.`updated`\s+/', $result);
		$this->assertRegExp('/\s+FROM\s+\S+`test_model4` AS `TestModel4`\s+WHERE\s+(?:\()?1\s+=\s+1(?:\))?\s*$/', $result);

		$this->Model->hasAndBelongsToMany['TestModel7'] = $backup;
	}

/**
 * testGenerateAssociationQueryHasAndBelongsToManyWithPageAndLimit method
 *
 * @return void
 */
	public function testGenerateAssociationQueryHasAndBelongsToManyWithPageAndLimit() {
		$this->Model = new TestModel4();
		$this->Model->schema();
		$this->_buildRelatedModels($this->Model);

		$backup = $this->Model->hasAndBelongsToMany['TestModel7'];

		$this->Model->hasAndBelongsToMany['TestModel7']['page'] = 2;
		$this->Model->hasAndBelongsToMany['TestModel7']['limit'] = 5;

		$binding = array('type' => 'hasAndBelongsToMany', 'model' => 'TestModel7');
		$queryData = array();
		$resultSet = null;
		$null = null;

		$params = &$this->_prepareAssociationQuery($this->Model, $queryData, $binding);

		$result = $this->Dbo->generateAssociationQuery($this->Model, $params['linkModel'], $params['type'], $params['assoc'], $params['assocData'], $queryData, $params['external'], $resultSet);
		$this->assertRegExp('/^SELECT\s+`TestModel7`\.`id`, `TestModel7`\.`name`, `TestModel7`\.`created`, `TestModel7`\.`updated`, `TestModel4TestModel7`\.`test_model4_id`, `TestModel4TestModel7`\.`test_model7_id`\s+/', $result);
		$this->assertRegExp('/\s+FROM\s+\S+`test_model7`\s+AS\s+`TestModel7`\s+JOIN\s+\S+`test_model4_test_model7`\s+AS\s+`TestModel4TestModel7`/', $result);
		$this->assertRegExp('/\s+ON\s+\(`TestModel4TestModel7`\.`test_model4_id`\s+=\s+{\$__cakeID__\$}/', $result);
		$this->assertRegExp('/\s+AND\s+`TestModel4TestModel7`\.`test_model7_id`\s+=\s+`TestModel7`\.`id`\)\s+WHERE\s+/', $result);
		$this->assertRegExp('/\s+(?:\()?1\s+=\s+1(?:\))?\s*\s+LIMIT 5,\s*5\s*$/', $result);

		$result = $this->Dbo->generateAssociationQuery($this->Model, $null, null, null, null, $queryData, false, $null);
		$this->assertRegExp('/^SELECT\s+`TestModel4`\.`id`, `TestModel4`\.`name`, `TestModel4`\.`created`, `TestModel4`\.`updated`\s+/', $result);
		$this->assertRegExp('/\s+FROM\s+\S+`test_model4` AS `TestModel4`\s+WHERE\s+(?:\()?1\s+=\s+1(?:\))?\s*$/', $result);

		$this->Model->hasAndBelongsToMany['TestModel7'] = $backup;
	}

/**
 * testSelectDistict method
 *
 * @return void
 */
	public function testSelectDistict() {
		$this->Model = new TestModel4();
		$result = $this->Dbo->fields($this->Model, 'Vendor', "DISTINCT Vendor.id, Vendor.name");
		$expected = array('DISTINCT `Vendor`.`id`', '`Vendor`.`name`');
		$this->assertEquals($expected, $result);
	}

/**
 * testStringConditionsParsing method
 *
 * @return void
 */
	public function testStringConditionsParsing() {
		$result = $this->Dbo->conditions("ProjectBid.project_id = Project.id");
		$expected = " WHERE `ProjectBid`.`project_id` = `Project`.`id`";
		$this->assertEquals($expected, $result);

		$result = $this->Dbo->conditions("Candy.name LIKE 'a' AND HardCandy.name LIKE 'c'");
		$expected = " WHERE `Candy`.`name` LIKE 'a' AND `HardCandy`.`name` LIKE 'c'";
		$this->assertEquals($expected, $result);

		$result = $this->Dbo->conditions("HardCandy.name LIKE 'a' AND Candy.name LIKE 'c'");
		$expected = " WHERE `HardCandy`.`name` LIKE 'a' AND `Candy`.`name` LIKE 'c'";
		$this->assertEquals($expected, $result);

		$result = $this->Dbo->conditions("Post.title = '1.1'");
		$expected = " WHERE `Post`.`title` = '1.1'";
		$this->assertEquals($expected, $result);

		$result = $this->Dbo->conditions("User.id != 0 AND User.user LIKE '%arr%'");
		$expected = " WHERE `User`.`id` != 0 AND `User`.`user` LIKE '%arr%'";
		$this->assertEquals($expected, $result);

		$result = $this->Dbo->conditions("SUM(Post.comments_count) > 500");
		$expected = " WHERE SUM(`Post`.`comments_count`) > 500";
		$this->assertEquals($expected, $result);

		$result = $this->Dbo->conditions("(Post.created < '" . date('Y-m-d H:i') . "') GROUP BY YEAR(Post.created), MONTH(Post.created)");
		$expected = " WHERE (`Post`.`created` < '" . date('Y-m-d H:i') . "') GROUP BY YEAR(`Post`.`created`), MONTH(`Post`.`created`)";
		$this->assertEquals($expected, $result);

		$result = $this->Dbo->conditions("score BETWEEN 90.1 AND 95.7");
		$expected = " WHERE score BETWEEN 90.1 AND 95.7";
		$this->assertEquals($expected, $result);

		$result = $this->Dbo->conditions(array('score' => array(2 => 1, 2, 10)));
		$expected = " WHERE `score` IN (1, 2, 10)";
		$this->assertEquals($expected, $result);

		$result = $this->Dbo->conditions("Aro.rght = Aro.lft + 1.1");
		$expected = " WHERE `Aro`.`rght` = `Aro`.`lft` + 1.1";
		$this->assertEquals($expected, $result);

		$result = $this->Dbo->conditions("(Post.created < '" . date('Y-m-d H:i:s') . "') GROUP BY YEAR(Post.created), MONTH(Post.created)");
		$expected = " WHERE (`Post`.`created` < '" . date('Y-m-d H:i:s') . "') GROUP BY YEAR(`Post`.`created`), MONTH(`Post`.`created`)";
		$this->assertEquals($expected, $result);

		$result = $this->Dbo->conditions('Sportstaette.sportstaette LIKE "%ru%" AND Sportstaette.sportstaettenart_id = 2');
		$expected = ' WHERE `Sportstaette`.`sportstaette` LIKE "%ru%" AND `Sportstaette`.`sportstaettenart_id` = 2';
		$this->assertRegExp('/\s*WHERE\s+`Sportstaette`\.`sportstaette`\s+LIKE\s+"%ru%"\s+AND\s+`Sports/', $result);
		$this->assertEquals($expected, $result);

		$result = $this->Dbo->conditions('Sportstaette.sportstaettenart_id = 2 AND Sportstaette.sportstaette LIKE "%ru%"');
		$expected = ' WHERE `Sportstaette`.`sportstaettenart_id` = 2 AND `Sportstaette`.`sportstaette` LIKE "%ru%"';
		$this->assertEquals($expected, $result);

		$result = $this->Dbo->conditions('SUM(Post.comments_count) > 500 AND NOT Post.title IS NULL AND NOT Post.extended_title IS NULL');
		$expected = ' WHERE SUM(`Post`.`comments_count`) > 500 AND NOT `Post`.`title` IS NULL AND NOT `Post`.`extended_title` IS NULL';
		$this->assertEquals($expected, $result);

		$result = $this->Dbo->conditions('NOT Post.title IS NULL AND NOT Post.extended_title IS NULL AND SUM(Post.comments_count) > 500');
		$expected = ' WHERE NOT `Post`.`title` IS NULL AND NOT `Post`.`extended_title` IS NULL AND SUM(`Post`.`comments_count`) > 500';
		$this->assertEquals($expected, $result);

		$result = $this->Dbo->conditions('NOT Post.extended_title IS NULL AND NOT Post.title IS NULL AND Post.title != "" AND SPOON(SUM(Post.comments_count) + 1.1) > 500');
		$expected = ' WHERE NOT `Post`.`extended_title` IS NULL AND NOT `Post`.`title` IS NULL AND `Post`.`title` != "" AND SPOON(SUM(`Post`.`comments_count`) + 1.1) > 500';
		$this->assertEquals($expected, $result);

		$result = $this->Dbo->conditions('NOT Post.title_extended IS NULL AND NOT Post.title IS NULL AND Post.title_extended != Post.title');
		$expected = ' WHERE NOT `Post`.`title_extended` IS NULL AND NOT `Post`.`title` IS NULL AND `Post`.`title_extended` != `Post`.`title`';
		$this->assertEquals($expected, $result);

		$result = $this->Dbo->conditions("Comment.id = 'a'");
		$expected = " WHERE `Comment`.`id` = 'a'";
		$this->assertEquals($expected, $result);

		$result = $this->Dbo->conditions("lower(Article.title) LIKE 'a%'");
		$expected = " WHERE lower(`Article`.`title`) LIKE 'a%'";
		$this->assertEquals($expected, $result);

		$result = $this->Dbo->conditions('((MATCH(Video.title) AGAINST(\'My Search*\' IN BOOLEAN MODE) * 2) + (MATCH(Video.description) AGAINST(\'My Search*\' IN BOOLEAN MODE) * 0.4) + (MATCH(Video.tags) AGAINST(\'My Search*\' IN BOOLEAN MODE) * 1.5))');
		$expected = ' WHERE ((MATCH(`Video`.`title`) AGAINST(\'My Search*\' IN BOOLEAN MODE) * 2) + (MATCH(`Video`.`description`) AGAINST(\'My Search*\' IN BOOLEAN MODE) * 0.4) + (MATCH(`Video`.`tags`) AGAINST(\'My Search*\' IN BOOLEAN MODE) * 1.5))';
		$this->assertEquals($expected, $result);

		$result = $this->Dbo->conditions('DATEDIFF(NOW(),Article.published) < 1 && Article.live=1');
		$expected = " WHERE DATEDIFF(NOW(),`Article`.`published`) < 1 && `Article`.`live`=1";
		$this->assertEquals($expected, $result);

		$result = $this->Dbo->conditions('file = "index.html"');
		$expected = ' WHERE file = "index.html"';
		$this->assertEquals($expected, $result);

		$result = $this->Dbo->conditions("file = 'index.html'");
		$expected = " WHERE file = 'index.html'";
		$this->assertEquals($expected, $result);

		$letter = $letter = 'd.a';
		$conditions = array('Company.name like ' => $letter . '%');
		$result = $this->Dbo->conditions($conditions);
		$expected = " WHERE `Company`.`name` like 'd.a%'";
		$this->assertEquals($expected, $result);

		$conditions = array('Artist.name' => 'JUDY and MARY');
		$result = $this->Dbo->conditions($conditions);
		$expected = " WHERE `Artist`.`name` = 'JUDY and MARY'";
		$this->assertEquals($expected, $result);

		$conditions = array('Artist.name' => 'JUDY AND MARY');
		$result = $this->Dbo->conditions($conditions);
		$expected = " WHERE `Artist`.`name` = 'JUDY AND MARY'";
		$this->assertEquals($expected, $result);

