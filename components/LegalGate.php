<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2026 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\legal\components;

use humhub\components\gates\RequestClass;
use humhub\components\gates\UserGate;
use humhub\modules\legal\Events;
use humhub\modules\legal\models\Page;
use humhub\modules\legal\models\RegistrationChecks;
use humhub\modules\legal\Module;
use Yii;

/**
 * Routes users with open legal checks (updated terms, pending terms/privacy
 * confirmation) through the legal update/confirm flow before they can use the
 * platform (see core `docs/develop/user-gates.md`).
 *
 * Replaces the module's former `Controller::EVENT_BEFORE_ACTION` interception.
 * The per-session `legalModuleChecked` flag is replaced by the gate dispatcher's
 * all-closed snapshot; publishing or changing legal pages calls
 * `Yii::$app->gateManager->invalidate()`, so updated terms now reach already
 * running sessions immediately (previously only after re-login).
 *
 * @since 1.8
 */
class LegalGate extends UserGate
{
    /**
     * @inheritdoc
     */
    public function getId(): string
    {
        return 'legal';
    }

    /**
     * @inheritdoc
     */
    public function getSortOrder(): int
    {
        return self::SORT_LEGAL;
    }

    /**
     * @inheritdoc
     */
    public function isOpen(): bool
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }

        $checks = new RegistrationChecks(['user' => Yii::$app->user->getIdentity()]);
        if (!$checks->hasOpenCheck()) {
            return false;
        }

        return $this->isLegalUpdatePageApplicable()
            || $checks->showTermsCheck()
            || $checks->showPrivacyCheck();
    }

    /**
     * The "legal update" page presents all open checks at once and takes precedence;
     * freshly registered users (who just accepted everything at signup) get the
     * one-by-one confirm flow instead.
     *
     * @inheritdoc
     */
    public function getRoute(): array
    {
        return $this->isLegalUpdatePageApplicable()
            ? ['/legal/page/update']
            : ['/legal/page/confirm'];
    }

    /**
     * Users must be able to read the legal pages while confirming ('legal'), log
     * in/out, delete their account and download files. 'mail' keeps the messenger
     * usable during an open check (previous behavior). 'twofa/check' is transitional:
     * it protects the check page of twofa versions that still intercept via
     * EVENT_BEFORE_ACTION and can be removed once twofa >= 1.4 (gate based) is common.
     *
     * @inheritdoc
     */
    public function getAllowedRoutes(): array
    {
        return [
            'legal',
            'user/auth',
            'user/account/delete',
            'mail/mail',
            'file/file/download',
            // transitional, see method docblock:
            'twofa/check',
        ];
    }

    /**
     * Legal checks only apply to full page navigation — AJAX, live polling and API
     * requests pass, matching the previous interceptor behavior.
     *
     * @inheritdoc
     */
    public function appliesTo(RequestClass $requestClass): bool
    {
        return $requestClass === RequestClass::FullPage;
    }

    private function isLegalUpdatePageApplicable(): bool
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('legal');

        return empty(Yii::$app->session->get(Events::SESSION_KEY_LEGAL_AFTER_REGISTRATION))
            && $module->isPageEnabled(Page::PAGE_KEY_LEGAL_UPDATE)
            && Page::getPage(Page::PAGE_KEY_LEGAL_UPDATE) !== null;
    }
}
