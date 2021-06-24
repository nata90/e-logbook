<?php
use yii\helpers\Url;

class CekDashboardCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->amLoggedInAs(\app\models\User::findByUsername('2016010536'));
        $I->amOnPage(Url::to('site/index'));
        
    }

    public function cekSuperadminDashboardPage(\FunctionalTester $I)
    {
        

       
        $I->expectTo('see dashboard page');
        $I->see('Rahanata, S.Kom','h3');
        $I->see('Subbag Perencanaan Program (SIRS)','p');
        
    }

    

}