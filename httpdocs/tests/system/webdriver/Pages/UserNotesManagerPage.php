<?php

require_once 'AdminPage.php';

use SeleniumClient\By;
use SeleniumClient\SelectElement;
use SeleniumClient\WebDriver;
use SeleniumClient\WebDriverWait;
use SeleniumClient\DesiredCapabilities;
use SeleniumClient\WebElement;

/**
 * Class for the back-end control panel screen.
 *
 */
class UserNotesManagerPage extends AdminManagerPage
{
	protected $waitForXpath =  "//ul/li/a[@href='index.php?option=com_users&view=notes']";
	protected $url = 'administrator/index.php?option=com_users&view=notes';

	/**
	 *
	 * @var UserNotesManagerPage
	 */
	public $userNotesManagerPage = null;

	public $toolbar = array (

			'New' => 'toolbar-new',
			'Edit' => 'toolbar-edit',
			'Activate' => 'toolbar-publish',
			'Block' => 'toolbar-unpublish',
			'Archive' => 'toolbar-archive',
			'Check In' => 'toolbar-checkin',
			'Trash' => 'toolbar-trash',
			'Empty Trash' => 'toolbar-delete',
			'Options' => 'toolbar-options',
			'Help' => 'toolbar-help',
	);

	public $submenu = array (
			'option=com_users&view=users',
			'option=com_users&view=groups',
			'option=com_users&view=levels',
			'option=com_users&view=notes',
			'option=com_categories&extension=com_users'
	);

	public $filters = array (
			'Select Status' => 'filter_published',
			'Select Category' => 'filter_category_id',
	);

	public function addUserNotes($name = 'Test User Notes', $otherFields = null)
	{
		$this->clickButton('toolbar-new');
		$editUserNotesPage = $this->test->getPageObject('UserNotesEditPage');
		$editUserNotesPage->setFieldValues(array('Subject' => $name));
		if (is_array($otherFields))
		{
			$editUserNotesPage->setFieldValues($otherFields);
		}
		$editUserNotesPage->clickButton('toolbar-save');
		$this->userNotesManagerPage = $this->test->getPageObject('UserNotesManagerPage');
	}

	public function deleteUserNotes($name)
	{
		$this->searchFor($name);
		$this->userNotesManagerPage->checkAll();
		$this->clickButton('toolbar-trash');
		$this->driver->waitForElementUntilIsPresent(By::xPath($this->waitForXpath));
		$this->searchFor();
		$this->setFilter('Status', 'Trashed');
		$this->userNotesManagerPage->checkAll();
		$this->clickButton('Empty trash');
		$this->driver->waitForElementUntilIsPresent(By::xPath($this->waitForXpath));
		$this->setFilter('Status', 'Select Status');
		$this->driver->waitForElementUntilIsPresent(By::xPath($this->waitForXpath));
	}

	public function editUserNotes($name, $fields)
	{
		$this->clickItem($name);
		$editUserNotesPage = $this->test->getPageObject('UserNotesEditPage');
		$editUserNotesPage->setFieldValues($fields);
		$editUserNotesPage->clickButton('toolbar-save');
		$this->userNotesManagerPage = $this->test->getPageObject('UserNotesManagerPage');
	}
}