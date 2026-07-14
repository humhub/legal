<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2026 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace legal\functional;

use humhub\modules\legal\models\Page;
use legal\FunctionalTester;
use Yii;

/**
 * Verifies the interception behavior of the LegalGate (see core docs/develop/user-gates.md).
 */
class LegalGateCest
{
    public function _after(FunctionalTester $I)
    {
        Page::deleteAll(['page_key' => Page::PAGE_KEY_TERMS]);
        Yii::$app->getModule('legal')->settings->delete('enabledPages');
        Yii::$app->gateManager->invalidate();
    }

    private function publishTerms(): void
    {
        Yii::$app->getModule('legal')->settings->set('enabledPages', Page::PAGE_KEY_TERMS);

        $page = new Page([
            'page_key' => Page::PAGE_KEY_TERMS,
            'language' => Yii::$app->language,
            'title' => 'Terms and Conditions',
            'content' => 'Gate test terms content',
        ]);
        $page->save(false);

        // Same call the admin controllers perform after saving legal pages
        Yii::$app->gateManager->invalidate();
    }

    public function testTermsPublishedMidSessionIntercept(FunctionalTester $I)
    {
        $I->wantTo('ensure that terms published mid-session intercept already running sessions');

        $I->amUser1();
        $this->publishTerms();

        $I->amOnRoute('/dashboard/dashboard');

        $I->see('I have read and agree to the Terms and Conditions');
    }

    public function testAjaxRequestsAreNotIntercepted(FunctionalTester $I)
    {
        $I->wantTo('ensure that AJAX requests pass while a legal check is open');

        $I->amUser1();
        $this->publishTerms();

        $I->sendAjaxGetRequest('/index-test.php?r=dashboard%2Fdashboard');

        $I->seeResponseCodeIs(200);
    }

    public function testAcceptingTermsClosesTheGate(FunctionalTester $I)
    {
        $I->wantTo('ensure that accepting the terms lets the user continue');

        $I->amUser1();
        $this->publishTerms();

        $I->amOnRoute('/dashboard/dashboard');
        $I->see('I have read and agree to the Terms and Conditions');

        $I->submitForm('#legal-check-form, form', [
            'RegistrationChecks[termsCheck]' => 1,
        ]);

        $I->amOnRoute('/dashboard/dashboard');
        $I->see('Dashboard');
    }

    public function testLogoutStaysReachable(FunctionalTester $I)
    {
        $I->wantTo('ensure that logout works while a legal check is open');

        $I->amUser1();
        $this->publishTerms();

        $I->sendAjaxPostRequest('/index-test.php?r=user%2Fauth%2Flogout');

        $I->amOnRoute('/dashboard/dashboard');
        $I->see('Sign in');
    }
}
