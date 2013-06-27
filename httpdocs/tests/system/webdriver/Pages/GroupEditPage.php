<?php

require_once 'AdminEditPage.php';

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
class GroupEditPage extends AdminEditPage
{
	protected $waitForXpath = "//form[@id='group-form']";
	protected $url = 'administrator/index.php?option=com_users&view=group&layout=edit';

	/**
	 * Array of expected id values for toolbar div elements
	 * @var array
	 */
	public $toolbar = array (
			'Save' => 'toolbar-apply',
			'Save & Close' => 'toolbar-save',
			'Save & New' => 'toolbar-save-new',
			'Cancel' => 'toolbar-cancel',
			'Help' => 'toolbar-help',
	);

	/**
	 * Associative array of expected input fields for the Account Details and Basic Settings tabs
	 * Assigned User Groups tab is omitted because that depends on the groups set up in the sample data
	 * @var unknown_type
	 */
	public $inputFields = array (
			array('label' => 'Group Title', 'id' => 'jform_title', 'type' => 'input', 'tab' => 'none'),
			array('label' => 'Group Parent', 'id' => 'jform_parent_id', 'type' => 'select', 'tab' => 'none'),
	);
}