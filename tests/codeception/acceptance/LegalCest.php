<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2022 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\legal\tests\codeception\acceptance;

use legal\AcceptanceTester;

class LegalCest
{
    public function testTermsAndConditions(AcceptanceTester $I)
    {
        $I->wantTo('test terms and conditions');
        $I->testPage('terms',
            'Test Terms and Conditions title',
            'Test Terms and Conditions content',
            'termscheck');
    }

    public function testPrivacyPolicy(AcceptanceTester $I)
    {
        $I->wantTo('test privacy policy');
        $I->testPage('privacy',
            'Test Privacy Policy title',
            'Test Privacy Policy content',
            'dataprivacycheck');
    }
}