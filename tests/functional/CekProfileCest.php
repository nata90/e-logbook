<?php
use yii\helpers\Url;

class CekProfileCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->amLoggedInAs(\app\models\User::findByUsername('2016010536'));
        $I->amOnPage(['.app/user/profile']);
        
    }

    public function cekSuperadminProfilePage(\FunctionalTester $I)
    {
        
        $I->expectTo('see profile page');
        $I->see('Profile','h3');
        
    }

    

}