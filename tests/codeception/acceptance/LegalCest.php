<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2022 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\legal\tests\codeception\acceptance;

use humhub\modules\legal\models\Page;
use legal\AcceptanceTester;

class LegalCest
{
    public function testImprint(AcceptanceTester $I)
    {
        $I->wantTo('test imprint');
        $page = Page::PAGE_KEY_IMPRINT;
        $title = 'Test Imprint title';
        $content = 'Test Imprint content';

        $I->amAdmin();

        $I->amGoingTo('enable page "' . $page . '"');
        $I->enablePage($page);
        $I->fillPage($page, $title, $content);

        $I->waitForText($title, 10, '.footer-nav-default');
        $I->click($title, '.footer-nav-default');
        $I->waitForText($content);
        $I->waitForText('Edit this page', 10, '.panel-heading');
        $I->click('Edit this page');
        $I->waitForText('Legal module - administration');
        $I->waitForText('Imprint', 10, '.tab-menu .active');
    }

    public function testTermsAndConditions(AcceptanceTester $I)
    {
        $I->wantTo('test terms and conditions');
        $I->testPage(Page::PAGE_KEY_TERMS,
            'Test Terms and Conditions title',
            'Test Terms and Conditions content',
            'termscheck');
    }

    public function testPrivacyPolicy(AcceptanceTester $I)
    {
        $I->wantTo('test privacy policy');
        $I->testPage(Page::PAGE_KEY_PRIVACY_PROTECTION,
            'Test Privacy Policy title',
            'Test Privacy Policy content',
            'dataprivacycheck');
    }

    public function testCookie(AcceptanceTester $I)
    {
        $I->wantTo('test cookie notification');
        $page = Page::PAGE_KEY_COOKIE_NOTICE;
        $content = 'Test Cookie Notification content';
        $button = 'Accept cookie';

        $I->amAdmin();

        $I->amGoingTo('enable page "' . $page . '"');
        $I->enablePage($page);
        $I->fillPage($page, $button, $content, 'Accept button label');

        $I->waitForElementVisible('[aria-label=cookieconsent]');
        $I->waitForText($content, 10, '#cookieconsent:desc');
        $I->see($button, '.cc-compliance');
        $I->click($button, '.cc-compliance');
        $I->waitForElementNotVisible('[aria-label=cookieconsent]');
    }

    public function testUpdate(AcceptanceTester $I)
    {
        $I->wantTo('test imprint');
        $pages = [
            Page::PAGE_KEY_TERMS => [
                'title' => 'Test Terms and Conditions title',
                'content' => 'Test Terms and Conditions content'
            ],
            Page::PAGE_KEY_LEGAL_UPDATE => [
                'title' => 'Test Legal Update title',
                'content' => 'Test Legal Update content'
            ]
        ];

        $I->amAdmin();

        $I->amGoingTo('enable page "Legal Update"');
        $I->enablePage(array_keys($pages));
        foreach ($pages as $pageKey => $page) {
            $I->fillPage($pageKey, $page['title'], $page['content']);
        }

        $page = Page::PAGE_KEY_LEGAL_UPDATE;
        $updatePage = $pages[$page];
        $I->amGoingTo('log in as user without accepted ' . $page);
        $I->amUserWithNotAcceptedPage($updatePage['title'], $updatePage['content']);
        $I->see('Delete my account including my personal data');
        $I->acceptPage('termscheck');

        $I->amGoingTo('log in as user with already accepted ' . $page);
        $I->amUser1(true);
        $I->dontSee($updatePage['title'], '.panel-heading');
    }

    public function testAgeVerification(AcceptanceTester $I)
    {
        $I->wantTo('test age verification');
        $minimumAge = 18;

        $I->amAdmin();

        $I->amGoingTo('enable age verification');
        $I->enableAgeVerification($minimumAge);

        $I->amGoingTo('enable page "Legal Update"');
        $page = Page::PAGE_KEY_LEGAL_UPDATE;
        $title = 'Test Legal Update title';
        $content = 'Test Legal Update content';
        $I->enablePage($page);
        $I->fillPage($page, $title, $content);

        $I->amGoingTo('log in as user without accepted age verification');
        $I->amUserWithNotAcceptedPage($title, $content);
        $I->see('I am older than ' . $minimumAge . ' years');
        $I->acceptPage('agecheck');

        $I->amGoingTo('log in as user with already accepted age verification');
        $I->amUser1(true);
        $I->dontSee($title, '.panel-heading');
    }
}