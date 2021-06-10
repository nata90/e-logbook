<?php
use yii\helpers\Url;

class ProfilFormCest
{
    public function _before(\FunctionalTester $I)
    {
        $I->amLoggedInAs(\app\models\User::findByUsername('2016010536'));
        $I->amOnPage(Url::to('/app/user/profile'));
        
    }

    public function openProfilePage(\FunctionalTester $I)
    {
        
        $I->see('Profile');
        $I->see('SUBBAG PERENCANAAN PROGRAM (SIRS)');
        
    }

    public function saveProfilePage(\FunctionalTester $I)
    {
        $I->see('Profile');
        $I->submitForm('#form-profile', []);

        $I->expectTo('see success message');
        $I->see('Data pegawai Rahanata, S.Kom berhasil diupdate');
        
    }
    

}