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
     * @param string|array $pages
     */
    public function enablePage($pages)
    {
        $this->amOnRoute('/legal/admin');
        $this->waitForText($settingsLabel = 'Enabled pages and features');

        if (!is_array($pages)) {
            $pages = [$pages];
        }
        $checkboxSelectors = [];
        foreach ($pages as $page) {
            $checkboxSelector = 'input[name="ConfigureForm[enabledPages][]"][value="' . $page . '"]';
            $this->dontSeeCheckboxIsChecked($checkboxSelector);
            $this->jsClick($checkboxSelector);
            $checkboxSelectors[] = $checkboxSelector;
        }
        $this->click('Save');
        $this->seeSuccess();

        $this->waitForText($settingsLabel);
        foreach ($checkboxSelectors as $checkboxSelector) {
            $this->seeCheckboxIsChecked($checkboxSelector);
        }
    }
    /**
     * Enable age verification
     *
     * @param int $age
     */
    public function enableAgeVerification(int $newAge)
    {
        $defaultAge = 16;
        $this->amOnRoute('/legal/admin');
        $this->waitForText($settingsLabel = 'Minimum age');
        $this->see('Show age verification ' . $defaultAge);

        $checkboxSelector = 'input[type=checkbox][name="ConfigureForm[showAgeCheck]"]';
        $this->dontSeeCheckboxIsChecked($checkboxSelector);
        $this->jsClick($checkboxSelector);
        $this->fillField('input[name="ConfigureForm[minimumAge]"]', $newAge);
        $this->click('Save');
        $this->seeSuccess();

        $this->waitForText($settingsLabel);
        $this->see('Show age verification ' . $newAge);
        $this->seeCheckboxIsChecked($checkboxSelector);
    }

    /**
     * Fill page with title and content
     *
     * @param string $page
     * @param string $title
     * @param string $content
     */
    public function fillPage(string $page, string $title, string $content, string $checkText = 'Title (en-US)')
    {
        $this->amOnRoute(['/legal/admin/page', 'pageKey' => $page]);
        $this->waitForText($checkText);

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