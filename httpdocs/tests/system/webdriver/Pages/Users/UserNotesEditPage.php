<?php

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
class UserNotesEditPage extends AdminEditPage
{
	protected $waitForXpath = "//form[@id='note-form']";
	protected $url = 'administrator/index.php?option=com_users&view=note&layout=edit';

	/**
	 * Associative array of expected input fields for the Account Details and Basic Settings tabs
	 * Assigned User UserNotess tab is omitted because that depends on the groups set up in the sample data
	 * @var unknown_type
	 */
	public $inputFields = array (
			array('label' => 'Subject', 'id' => 'jform_subject', 'type' => 'input', 'tab' => 'none'),
			array('label' => 'ID', 'id' => 'jform_user_id_id', 'type' => 'input', 'tab' => 'none'),
			array('label' => 'Category', 'id' => 'jform_catid', 'type' => 'select', 'tab' => 'none'),
			array('label' => 'Status', 'id' => 'jform_state', 'type' => 'select', 'tab' => 'none'),
			array('label' => 'Review time', 'id' => 'jform_review_time', 'type' => 'input', 'tab' => 'none'),
			array('label' => 'Note', 'id' => 'jform_body', 'type' => 'textarea', 'tab' => 'none'),
	);

	public function getAllInputFields($tabIds = array())
	{
		$return = array();
		$labels = $this->driver->findElements(By::xPath("//fieldset/div[@class='control-group']/div/label"));
		$tabId = 'none';
		foreach ($labels as $label)
		{
			$labelText = $label->getText();
			if (($inputField = $this->getInputField($tabId, $label)) !== false)
			{
				$return[] = $inputField;
			}
			elseif ($labelText == 'ID *')
			{
				$object = new stdClass();
				$object->tab = $tabId;
				$object->tag = 'input';
				$object->labelText = 'ID';
				$object->id = 'jform_user_id_id';
				$object->labelId = $label->getAttribute('id');
				$object->type = 'text';
				$object->element = $this->driver->findElement(By::id('jform_user_id_id'));
				$return[] = $object;
			}
		}
		return $return;
	}

	public function setUser($userName)
	{
		$linkXpath = "//a[contains(@href, 'view=users&layout=modal')]";
		$frameXpath = "//iframe[contains(@src, 'view=users&layout=modal')]";
		$this->driver->findElement(By::xPath($linkXpath))->click();
		$this->driver->waitForElementUntilIsPresent(By::xPath($frameXpath));
		$el = $this->driver->findElement(By::xPath($frameXpath));
		$el = $this->driver->switchTo()->getFrameByWebElement($el);
		$el = $this->driver->waitForElementUntilIsPresent(By::id('filter_search'));
		$el->clear();
		$el->sendKeys($userName);
		$this->driver->findElement(By::xPath("//button[@title='Search']"))->click();
		$this->driver->waitForElementUntilIsPresent(By::id('filter_search'));
		$this->driver->findElement(By::xPath("//a[contains(@onclick, '" . $userName . "')]"))->click();
		$this->driver->waitForElementUntilIsNotPresent(By::xPath($frameXpath));
		$this->driver->switchTo()->getDefaultFrame();
	}

	public function setFieldValues(array $fields)
	{
		foreach ($fields as $label => $value)
		{
			if ($label == 'ID')
			{
				$this->setUser($value);
			}
			else
			{
				$this->setFieldValue($label, $value);
			}
		}
	}

	public function getFieldValue($label)
	{
		$result = false;
		if ($label == 'ID')
		{
			$result = $this->driver->findElement(By::id('jform_user_id_name'))->getAttribute('value');
		}
		else
		{
			$result = parent::getFieldValue($label);
		}
		return $result;
	}
}