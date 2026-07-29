<?php

namespace frontend\tests\functional;

use frontend\tests\FunctionalTester;

/* @var $scenario \Codeception\Scenario */

class ContactCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amOnRoute('site/contact');
    }

    public function checkContact(FunctionalTester $I)
    {
        $I->see('Contact', 'h1');
    }

    public function checkContactSubmitNoData(FunctionalTester $I)
    {
        $I->submitForm('#contact-form', []);
        $I->see('Contact', 'h1');
        $I->seeValidationError('Full name cannot be blank');
        $I->seeValidationError('Email cannot be blank');
        $I->seeValidationError('Phone cannot be blank');
        $I->seeValidationError('Subject cannot be blank');
        $I->seeValidationError('Message cannot be blank');
        $I->seeValidationError('The verification code is incorrect');
    }

    public function checkContactSubmitNotCorrectEmail(FunctionalTester $I)
    {
        $I->submitForm('#contact-form', [
            'Contacts[fullname]' => 'tester',
            'Contacts[email]' => 'tester.email',
            'Contacts[phone]' => '+998901234567',
            'Contacts[subject]' => 'test subject',
            'Contacts[message]' => 'test content',
            'Contacts[verifyCode]' => 'testme',
        ]);
        $I->seeValidationError('Email is not a valid email address.');
        $I->dontSeeValidationError('Full name cannot be blank');
        $I->dontSeeValidationError('Phone cannot be blank');
        $I->dontSeeValidationError('Subject cannot be blank');
        $I->dontSeeValidationError('Message cannot be blank');
        $I->dontSeeValidationError('The verification code is incorrect');
    }

    public function checkContactSubmitCorrectData(FunctionalTester $I)
    {
        $I->submitForm('#contact-form', [
            'Contacts[fullname]' => 'tester',
            'Contacts[email]' => 'tester@example.com',
            'Contacts[phone]' => '+998901234567',
            'Contacts[subject]' => 'test subject',
            'Contacts[message]' => 'test content',
            'Contacts[verifyCode]' => 'testme',
        ]);
        $I->see('Thank you for contacting us. We will respond to you as soon as possible.');
    }
}
