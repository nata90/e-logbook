<?php

namespace app\modules\pegawai\controllers;

use yii\web\Controller;

/**
 * Default controller for the `pegawai` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
