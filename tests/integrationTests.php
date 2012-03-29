<?php
/**
 * Acceptance testing for NeatlineThin theme
 *
 * This file requires the PHPUnit_Extensions_SeleniumTestCase be installed
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at http://www.apache.org/licenses/LICENSE-2.0 Unless required by
 * applicable law or agreed to in writing, software distributed under the
 * License is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS
 * OF ANY KIND, either express or implied. See the License for the specific
 * language governing permissions and limitations under the License.
 *
 * @package   omeka
 * @author    "Scholars Lab"
 * @copyright 2010 The Board and Visitors of the University of Virginia
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache 2.0
 * @version   Release: 0.0.1-pre
 * @link      http://www.scholarslab.org
 *
 * PHP version 5
 *
 */
?>

<?php
require_once 'PHPUnit/Extensions/SeleniumTestCase.php';

class NeatlineThemeTest extends PHPUnit_Extensions_SeleniumTestCase
{
    const SERVER_URL = 'http://localhost:8888/';
    const SCREENSHOT_DIR = '/Applications/MAMP/htdocs/screenshots';
    const SCREENSHOT_URL = 'http://localhost:8888/screenshots';

    protected $captureScreenshotOnFailure = true;
    protected $screenshotPath = self::SCREENSHOT_DIR;
    protected $screenshotUrl = self::SCREENSHOT_URL;

    public static $browsers = array(
        array(
          'name' => "Firefox on OS X",
          'browser' => 'custom /Applications/Firefox.app/Contents/MacOS/firefox-bin',
          'host' => 'localhost',
          'port' => 4444,
          'timeout' => 3000,
        ),
//        array(
//          'name' => "Safari",
//          'browser' => '*safari',
//          'host' => 'localhost',
//          'port' => 4444,
//          'timeout' => 3000,
//        ),
//
//
//        array(
//          'name' => "Google Chrome",
//          //'browser' => '*googlechrome',
//          'browser' => 'custom /Applications/Google\ Chrome.app/Contents/MacOS/Google\ Chrome',
//          'host' => 'localhost',
//          'port' => 4444,
//          'timeout' => 3000,
//        ),
    );

    /**
     * Set up the tests to use different browsers
     */
    protected function setup()
    {
        /**
         * '*firefox' => Firefox 1, 2, or 3
         * '*iexplore' => Internet Explorer (all)
         * '*custom /path/to/browser/binary => Other browsers (incl. Firefox on Linux)
         * '*iehta' => Experimental Embedded IE
         * '*chrome' => Experimental Firefox profile
         */
        //$this->setBrowser("*chrome");
        $this->setBrowserUrl(self::SERVER_URL);

    }

    /**
     * This is an HTML5 doc type with "fixes" for IE versions
     */
    public function testDocType()
    {

    }

    /**
     * Make sure the metatags are in the head
     */
    public function testMetaTags()
    {
        $this->open('omeka_trunk');
        $this->assertElementPresent('//head/meta');

        $this->assertElementPresent('xpath=//meta[@charset="utf-8"]', 'Check meta to ensure proper charset encoding');
        $this->assertElementPresent('xpath=//meta[@name]', "Check meta for existance of name");
        $this->assertElementPresent('xpath=//meta[@autor]', "Check meta for existance of author");
        $this->assertElementPresent('xpath=//meta[@name="viewport"]', "Check meta for existance of viewport");

    }

    /**
     * Ensure the javascript is in the footer
     */
    public function testJavaScript()
    {
        $this->open('omeka_trunk');
    }

    public function testLoginFormExists()
    {
        $this->open('omeka_trunk/admin/');

        $this->assertElementPresent('id=login-form', "Ensure the login div id is present");
        $this->assertElementPresent("dom=document.forms['login-form'].username", 'Ensure there is a username field');
        $this->assertElementPresent("dom=document.forms['login-form'].password", 'Ensure there is a password field');
        $this->assertElementPresent("xpath=//form[@id='login-form']/input[@type='submit']", 'Ensure there is a submit button');

    }

    /**
     * Test an invalid login
     */
    public function testInvalidLogin()
    {
         $this->open("/omeka_trunk/admin/users/login");

        // fill out the form
        $this->type("username", "mal");
        $this->type("password", "serenity");

        // submit the form
        $this->click("submit");
        $this->waitForPageToLoad("30000");

        // verify login was unsuccessful
        $this->assertTextNotPresent('regexp=Welcome, [a-zA-Z0-9]');

        // and that an error exists
        $this->assertTextPresent('*Error*');
    }
}


?>
