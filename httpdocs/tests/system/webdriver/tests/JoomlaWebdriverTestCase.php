<?php

require_once '../bootstrap.php';
require_once '../../servers/configdef.php';

use SeleniumClient\By;
use SeleniumClient\SelectElement;
use SeleniumClient\WebDriver;
use SeleniumClient\WebDriverWait;
use SeleniumClient\DesiredCapabilities;
use SeleniumClient\Http\HttpFactory;
use SeleniumClient\Http\HttpClient;
use Pages\AdminLoginPage;
use Pages\ControlPanelPage;

class JoomlaWebdriverTestCase extends PHPUnit_Framework_TestCase
{

	/**
	 * @var SeleniumConfig
	 */
	public $cfg; // configuration so tests can get at the fields

	/**
	 *
	 * @var Webdriver
	 */
	protected $driver = null; // Webdriver

	/**
	 *
	 * @var string
	 */
	protected $testUrl = null; // URL from configuration file

	public function setUp()
	{
		$cfg = new SeleniumConfig();
		$this->cfg = $cfg; // save current configuration
		$this->testUrl = $cfg->host . $cfg->path;
		switch ($cfg->browser)
		{
			case '*chrome':
				$browser = 'firefox';
				break;
			case '*googlechrome':
			default:
				$browser = 'chrome';
				break;
		}

		$desiredCapabilities = new DesiredCapabilities($browser);
		$this->driver = new WebDriver($desiredCapabilities);
		if (isset($this->cfg->windowSize) && is_array($this->cfg->windowSize))
		{
			$this->driver->setCurrentWindowSize($this->cfg->windowSize[0], $this->cfg->windowSize[1]);
		}
		else
		{
			$this->driver->setCurrentWindowSize(1280, 1024);
		}
	}

	public function tearDown()
	{
		if($this->driver != null)
		{
			$this->driver->clearCurrentCookies();
			$this->driver->quit();
		}
	}

	/**
	 *
	 * @param string  $type             Class name for object to create.
	 * @param bool    $checkForNotices  If true, check for notices after page load
	 * @param string  $url              Optional URL to load
	 *
	 * @return AdminPage
	 */
	public function getPageObject($type, $checkForNotices = true, $url = null)
	{
		$pageObject =  new $type($this->driver, $this, $url);
		if ($checkForNotices)
		{
			$this->assertFalse($pageObject->checkForNotices(), 'PHP Notice found on page ' . $pageObject);
		}
		return $pageObject;
	}

	public function doAdminLogin()
	{
		$d = $this->driver;
		$d->clearCurrentCookies();
		$url = $this->cfg->host . $this->cfg->path . 'administrator/index.php';
		$loginPage = $this->getPageObject('AdminLoginPage', true, $url);
		$cpPage = $loginPage->loginValidUser($this->cfg->username, $this->cfg->password);
		$this->assertTrue(is_a($cpPage, 'ControlPanelPage'));
		return $cpPage;
	}

	public function doAdminLogout()
	{
		// Clear cookies to force logout
		$this->driver->clearCurrentCookies();
		$url = $this->cfg->host . $this->cfg->path . 'administrator/index.php';
		$loginPage = $this->getPageObject('AdminloginPage', true, $url);
		$this->assertTrue(is_a($loginPage, 'AdminloginPage'));
		return $loginPage;
	}

	/**
	 * Takes screenshot of current screen, saves it in specified default directory or as specified in parameter
	 * @param String $folder
	 * @throws \Exception
	 * @return string
	 */
	public function helpScreenshot($fileName, $folder = null)
	{
		$this->driver->setCurrentWindowSize(1280, 1024);
		$screenshotsDirectory = null;
		if (isset($folder)) {
			$screenshotsDirectory = $folder;
		}
		else if ($this->driver->getScreenShotsDirectory()) {
			$screenshotsDirectory = $this->driver->getScreenShotsDirectory();
		}
		else { throw new \Exception("Must Specify Screenshot Directory");
		}

		$command = "screenshot";
		$urlHubFormatted = $this->driver->getHubUrl() . "/session/{$this->driver->getSessionId()}/{$command}";

		$httpClient = HttpFactory::getClient($this->driver->getEnvironment());
		$results = $httpClient->setUrl($urlHubFormatted)->setHttpMethod(HttpClient::GET)->execute();

		if (isset($results["value"]) && trim($results["value"]) != "")
		{
			if (!file_exists($screenshotsDirectory)) {
				mkdir($screenshotsDirectory, 0777, true);
			}

			file_put_contents($screenshotsDirectory . "/" .$fileName, base64_decode($results["value"]));

			return $fileName;
		}
	}
}