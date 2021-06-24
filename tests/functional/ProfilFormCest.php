<?php
use yii\helpers\Url;

class ProfilFormCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->amLoggedInAs(\app\models\User::findByUsername('2016010536'));
        $I->amOnRoute('app/user/profile');
        
    }

    public function openProfilePage(\FunctionalTester $I)
    {
        
        $I->see('Profile','h3');
        $I->see('SUBBAG PERENCANAAN PROGRAM (SIRS)');
        
    }

    

}