<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\legal\tests\codeception\acceptance;

use humhub\modules\user\models\Invite;
use legal\AcceptanceTester;
use Yii;

class RegistrationCest
{
    public function testCheckboxesOnRegistrationForm(AcceptanceTester $I)
    {
        $I->wantTo('see checkboxes on registration form');
        $I->amAdmin();

        $I->amGoingTo('activate checkboxes on registration form');
        $pages = ['terms', 'privacy'];
        $I->enablePage($pages);
        $I->checkOption('#configureform-showagecheck');
        $I->click('Save');
        $I->seeSuccess();
        foreach ($pages as $page) {
            $I->fillPage($page, 'Test ' . $page . ' title', 'Test ' . $page . ' content');
        }

        $I->amGoingTo('start registration by email');
        $I->logout();
        $I->fillField('#register-email', 'legal@module.test');
        $I->click('Register');
        $I->waitForText(version_compare(Yii::$app->version, '1.18', '>=') ? 'Almost there!' : 'Registration successful!');

        $I->amGoingTo('register by email');
        $invite = Invite::findOne(['email' => 'legal@module.test']);
        $I->amOnRoute(['/user/registration', 'token' => $invite->token]);
        $I->waitForText('Account registration');
        $I->see('I have read and agree to the Terms and Conditions');
        $I->see('I have read and agree to the Privacy Policy');
        $I->see('I am older than 16 years');
        $I->fillField('#user-username', 'legal');
        $I->fillField('#password-newpassword', 'PassWord');
        $I->fillField('#password-newpasswordconfirm', 'PassWord');
        $I->fillField('#profile-firstname', 'Legal');
        $I->fillField('#profile-lastname', 'Test');
        $I->checkOption('#registrationchecks-termscheck');
        $I->checkOption('#registrationchecks-dataprivacycheck');
        $I->checkOption('#registrationchecks-agecheck');
        $I->click('Create account');

        $I->amGoingTo('check the user has been registered successfully');
        $I->waitForText('Legal Test', null, '.user-title');
        $I->logout();
        $I->fillField('#login_username', 'legal');
        $I->fillField('#login_password', 'PassWord');
        $I->click('Sign in');
        $I->waitForText('Legal Test', null, '.user-title');
    }
}
