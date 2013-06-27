<?php

require_once 'JoomlaWebdriverTestCase.php';

use SeleniumClient\By;
use SeleniumClient\SelectElement;
use SeleniumClient\WebDriver;
use SeleniumClient\WebDriverWait;
use SeleniumClient\DesiredCapabilities;

/**
 * This class tests the  Manager: Add / Edit  Screen
 * @author Mark
 *
 */
class MenuItemsManager0001Test extends JoomlaWebdriverTestCase
{
	/**
	 *
	 * @var MenuItemsManagerPage
	 */
	protected $menuItemsManagerPage = null; // Global configuration page

	public function setUp()
	{
		parent::setUp();
		$cpPage = $this->doAdminLogin();
		$this->menuItemsManagerPage = $cpPage->clickMenu('Main Menu', 'MenuItemsManagerPage');
	}

	public function tearDown()
	{
		$this->doAdminLogout();
		parent::tearDown();
	}

	/**
	 * @test
	 */
	public function constructor_OpenEditScreen_MenuEditOpened()
	{
		$this->menuItemsManagerPage->clickButton('toolbar-new');

		/* @var $menuItemEditPage MenuItemEditPage */
		$menuItemEditPage = $this->getPageObject('MenuItemEditPage');
		$tabIds = $menuItemEditPage->getTabIds();
// 		$menuItemEditPage->printFieldArray($menuItemEditPage->getAllInputFields($tabIds));
		$menuItemEditPage->clickButton('toolbar-cancel');
		$this->menuItemsManagerPage = $this->getPageObject('MenuItemsManagerPage');
	}

	/**
	 * @test
	 */
	public function getAllInputFields_ScreenDisplayed_EqualExpected()
	{
		$this->menuItemsManagerPage->clickButton('toolbar-new');
		$menuItemEditPage = $this->getPageObject('MenuItemEditPage');

		$testElements = $menuItemEditPage->getAllInputFields($menuItemEditPage->getTabIds());
		$actualFields = array();
		foreach ($testElements as $el)
		{
			$el->labelText = (substr($el->labelText, -2) == ' *') ? substr($el->labelText, 0, -2) : $el->labelText;
			$actualFields[] = array('label' => $el->labelText, 'id' => $el->id, 'type' => $el->tag, 'tab' => $el->tab);
		}
		$this->assertEquals($menuItemEditPage->inputFields, $actualFields);
		$menuItemEditPage->clickButton('toolbar-cancel');
		$this->menuItemsManagerPage = $this->getPageObject('menuItemsManagerPage');
	}

	/**
	 * @test
	 */
	public function getMenuItemTypes_ShouldMatchExpected()
	{
		$this->menuItemsManagerPage->clickButton('toolbar-new');
		$menuItemEditPage = $this->getPageObject('MenuItemEditPage');
		$actualMenuItemTypes = $menuItemEditPage->getMenuItemTypes();
// 		foreach ($actualMenuItemTypes as $array)
// 		{
// 			echo "array('group' => '" . $array['group'] . "', 'type' => '" . $array['type'] . "' ),\n";
// 		}
		$count = count($actualMenuItemTypes);
		for ($i = 0; $i < $count; $i++)
		{
			$this->assertEquals($menuItemEditPage->menuItemTypes[$i], $actualMenuItemTypes[$i], 'Menu item type should match expected');
		}
	}

	/**
	 * @test
	 */
	public function addMenuItem_WithFieldDefaults_MenuItemAdded()
	{
		$this->assertFalse($this->menuItemsManagerPage->getRowNumber('Test Menu Item'), 'Test menu should not be present');
		$this->menuItemsManagerPage->addMenuItem();
		$message = $this->menuItemsManagerPage->getAlertMessage();
		$this->assertTrue(strpos($message, 'Menu successfully saved') >= 0, 'Menu save should return success');
		$this->menuItemsManagerPage->setFilter('menutype', 'Main Menu');
		$this->assertTrue($this->menuItemsManagerPage->getRowNumber('Test Menu') > 0, 'Test menu should be in list');
		$this->menuItemsManagerPage->deleteItem('Test Menu');
		$this->assertFalse($this->menuItemsManagerPage->getRowNumber('Test Menu'), 'Test menu should not be present');
	}

	/**
	 * @test
	 */
	public function addMenuItem_SingleContact_MenuAdded()
	{
		$salt = rand();
		$title = 'Menu Item ' . $salt;
		$menuType = 'Single Contact';
		$itemName = 'Bananas';
		$menuLocation = 'About Joomla';
		$this->menuItemsManagerPage->setFilter('menutype', $menuLocation);
		$this->assertFalse($this->menuItemsManagerPage->getRowNumber($title), 'Test menu should not be present');
		$this->menuItemsManagerPage->addMenuItem($title, $menuType, $menuLocation, array('contact' => $itemName));
		$message = $this->menuItemsManagerPage->getAlertMessage();
		$this->assertContains('Menu item successfully saved', $message, 'Menu save should return success', true);
		$this->menuItemsManagerPage->searchFor($title);
		$this->assertTrue($this->menuItemsManagerPage->getRowNumber($title) > 0, 'Test menu should be on the page');
		$this->menuItemsManagerPage->searchFor();
		$actualValues = $this->menuItemsManagerPage->getFieldValues('MenuItemEditPage', $title, array('Menu Title', 'Menu Item Type', 'contact'));
		$expectedValues = array ($title, $menuType, $itemName);
		$this->assertEquals($expectedValues, $actualValues, 'Actual values should match entered values');
		$this->menuItemsManagerPage->deleteItem($title);
		$this->menuItemsManagerPage->searchFor($title);
		$this->assertFalse($this->menuItemsManagerPage->getRowNumber($title), 'Test menu should not be present');
		$this->menuItemsManagerPage->searchFor();
	}

	/**
	 * @test
	 */
	public function addMenuItem_CategoryBlog_MenuAdded()
	{
		$salt = rand();
		$title = 'Menu Item ' . $salt;
		$menuType = 'Category Blog';
		$itemName = '- Joomla!';
		$menuLocation = 'Fruit Shop';
		$this->menuItemsManagerPage->setFilter('menutype', $menuLocation);
		$this->assertFalse($this->menuItemsManagerPage->getRowNumber($title), 'Test menu should not be present');
		$this->menuItemsManagerPage->addMenuItem($title, $menuType, $menuLocation, array('category' => $itemName));
		$message = $this->menuItemsManagerPage->getAlertMessage();
		$this->assertContains('Menu item successfully saved', $message, 'Menu save should return success', true);
		$this->menuItemsManagerPage->searchFor($title);
		$this->assertTrue($this->menuItemsManagerPage->getRowNumber($title) > 0, 'Test menu should be on the page');
		$this->menuItemsManagerPage->searchFor();
		$actualValues = $this->menuItemsManagerPage->getFieldValues('MenuItemEditPage', $title, array('Menu Title', 'Menu Item Type', 'category'));
		$expectedValues = array ($title, $menuType, $itemName);
		$this->assertEquals($expectedValues, $actualValues, 'Actual values should match entered values');
		$this->menuItemsManagerPage->deleteItem($title);
		$this->menuItemsManagerPage->searchFor($title);
		$this->assertFalse($this->menuItemsManagerPage->getRowNumber($title), 'Test menu should not be present');
		$this->menuItemsManagerPage->searchFor();
	}

	/**
	 * @test
	 */
	public function editMenuItem_ChangeFields_FieldsChanged()
	{
		$salt = rand();
		$title = 'Menu Item ' . $salt;
		$menuType = 'Single News Feed';
		$itemName = 'Joomla! Connect';
		$menuLocation = 'Top';
		$this->menuItemsManagerPage->setFilter('menutype', $menuLocation);
		$this->assertFalse($this->menuItemsManagerPage->getRowNumber($title), 'Test menu should not be present');
		$this->menuItemsManagerPage->addMenuItem($title, $menuType, $menuLocation, array('newsfeed' => $itemName));

		$newTitle = 'New Menu Item ' . $salt;
		$newMenuType = 'List News Feeds in a Category';
		$newCategory = 'Uncategorised';
		$newMenuLocation = 'Main Menu';
		$this->menuItemsManagerPage->editMenuItem($title, array('Menu Title' => $newTitle, 'Menu Item Type' => $newMenuType, 'category' => $newCategory, 'Menu Location' => $newMenuLocation));

		$this->menuItemsManagerPage->setFilter('menutype', $newMenuLocation);
		$actualValues = $this->menuItemsManagerPage->getFieldValues('MenuItemEditPage', $newTitle, array('Menu Title', 'Menu Item Type', 'category', 'Menu Location'));
		$expectedValues = array ($newTitle, $newMenuType, $newCategory, $newMenuLocation);
		$this->assertEquals($expectedValues, $actualValues, 'Actual values should match entered values');
		$this->menuItemsManagerPage->deleteItem($newTitle);
		$this->assertFalse($this->menuItemsManagerPage->getRowNumber($newTitle), 'Test menu item should not be present');
	}
}