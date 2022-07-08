<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2022 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace legal;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
*/
class AcceptanceTester extends \AcceptanceTester
{
    use _generated\AcceptanceTesterActions;

    /**
     * Enable page or feature
     *
     * @param string $page
     */
    public function enablePage(string $page)
    {
        $this->amOnRoute('/legal/admin');
        $this->waitForText($settingsLabel = 'Enabled pages and features');

        $this->dontSeeCheckboxIsChecked($checkboxSelector = 'input[name="ConfigureForm[enabledPages][]"][value="' . $page . '"]');
        $this->jsClick($checkboxSelector);
        $this->click('Save');
        $this->seeSuccess();

        $this->waitForText($settingsLabel);
        $this->seeCheckboxIsChecked($checkboxSelector);
    }

    /**
     * Fill page with title and content
     *
     * @param string $page
     * @param string $title
     * @param string $content
     */
    public function fillPage(string $page, string $title, string $content)
    {
        $this->amOnRoute(['/legal/admin/page', 'pageKey' => $page]);
        $this->waitForText('Title (en-US)');

        $this->fillField('#page-en-us-title', $title);
        $this->fillField('#page-en-us-content .humhub-ui-richtext', $content);
        $this->click('Save');
        $this->seeSuccess();
    }

    /**
     * Relogin as user without accepted legal page
     *
     * @param string $title
     * @param string $content
     * @param bool $logout
     */
    public function amUserWithNotAcceptedPage(string $title, string $content, $logout = true)
    {
        if ($logout) {
            $this->logout();
        }
        $this->login('User1', '123qwe');

        $this->waitForText($title, 10, '.panel-heading');
        $this->waitForText($content, 10, '.panel-body');
    }

    /**
     * Accept current legal page restriction
     *
     * @param string $acceptCheckboxKey
     */
    public function acceptPage(string $acceptCheckboxKey)
    {
        $this->click('[for=registrationchecks-' . $acceptCheckboxKey . ']');
        $this->click('Accept');
        $this->waitForElementVisible('#wallStream', 30);
    }

    /**
     * Test a legal page
     *
     * @param string $page
     * @param string $title
     * @param string $content
     * @param string $acceptCheckboxKey
     */
    public function testPage(string $page, string $title, string $content, string $acceptCheckboxKey)
    {
        $this->amAdmin();

        $this->amGoingTo('enable page "' . $page . '"');
        $this->enablePage($page);
        $this->fillPage($page, $title, $content);

        $this->amGoingTo('log in as user without accepted ' . $page);
        $this->amUserWithNotAcceptedPage($title, $content);
        $this->acceptPage($acceptCheckboxKey);

        $this->amGoingTo('log in as user with already accepted ' . $page);
        $this->amUser1(true);
        $this->dontSee($title, '.panel-heading');
    }
}