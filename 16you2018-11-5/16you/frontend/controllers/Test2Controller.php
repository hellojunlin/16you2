<?php 
namespace frontend\controllers;

use yii;
use yii\web\Controller;
    
class TestController extends Controller{
    public $layout=false;
    //test
    public function actionTot(){
        return $this->render('tot');
    }
    
    
    
    
}