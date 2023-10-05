<?php

namespace app\commands;

use Yii;
use yii\console\Controller;


class CommandController extends Controller
{
    

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        echo 'Hello guys';
    }


    
}