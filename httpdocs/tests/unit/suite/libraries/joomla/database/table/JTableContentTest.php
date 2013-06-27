<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

require_once JPATH_BASE.'/libraries/joomla/database/table.php';
require_once JPATH_BASE.'/libraries/joomla/database/table/content.php';

/**
 * Test class for JTableContent.
 * Generated by PHPUnit on 2009-10-08 at 22:06:28.
 */
class JTableContentTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @todo Implement testBind().
	 */
	public function testBind()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Method to test JTableContent::check().
	 *
	 * @since	1.6
	 */
	public function testCheck()
	{
		// Mock the database table class.
		$dbStub = $this->getMock( 'JDatabaseMySQL', array('getTableFields'), array(array()));
        $dbStub->expects($this->any())
			->method('getTableFields')
			->will($this->returnValue(array()));

		/*
		$appStub = $this->getMock('JApplication', array('stringURLSafe'));
        $appStub::staticExpects($this->any())
			->method('stringURLSafe')
			->will($this->returnValue(''));
			// We need phpunit 3.5 for this to work.
		*/

		$table	= new JTableContent($dbStub);

		$this->assertThat(
			$table->check(),
			$this->isFalse(),
			'Line: '.__LINE__.' Checking an empty table should fail.'
		);

		/*
		$table->title = 'Test Title';
		$this->assertThat(
			$table->check(),
			$this->isFalse(),
			'Line: '.__LINE__.' Checking the table with just the title should fail.'
		);

		$this->assertThat(
			$table->alias,
			$this->equalTo('Test Title'),
			'Line: '.__LINE__.' An empty alias should assume the value of the title.'
		);

		$table->publish_up = '2010-01-12 00:00:00';
		$table->publish_down = '2010-01-11 00:00:00';
		*/

		$this->markTestIncomplete('More cases to test.');
	}

	/**
	 * @todo Implement testStore().
	 */
	public function testStore()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * @todo Implement testPublish().
	 */
	public function testPublish()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * @todo Implement testToXML().
	 */
	public function testToXML()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
}