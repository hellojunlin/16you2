<?php

namespace backend\controllers;

use Yii;
use common\models\GiftReceive;
use common\models\Game;
use backend\controllers\BaseController;
use yii\data\Pagination;
use common\common\Helper;
use common\common\Phpexcelr;

/**
 * 礼包领取类
 */
class GiftreceiveController extends BaseController{
      /**
     * 进入礼包领取记录页.
     * @return mixed
     */
    public function actionIndex() { 
        //分页
        $curPage = Yii:: $app->request->get( 'page',1);
        $pageSize = yii::$app->params['pagenum'];
        //搜索
        $value = Helper::filtdata(Yii:: $app->request->get('keyword',''));
        $select = Yii:: $app->request->get('selectval','');
        $search = ($value)?['like',$select,$value]: '';
        $query = (new \yii\db\Query())
            ->select('gift_name,game_name,username,G.createtime')
            ->from('g_gift AS G')
            ->leftJoin('g_user AS U','U.id=G.uid')
            ->where(['state'=>1])
            ->orderBy('G.createtime DESC');
        $data = Helper::getPages($query,$curPage,$pageSize,$search);
        $data['data'] =  ($data['data'])?$data['data']->all():'';
        $pages = new Pagination([ 'totalCount' =>$data[ 'count'], 'pageSize' => $pageSize]);
        //菜单定位
        unset(yii::$app->session['localfirsturl']);
        yii::$app->session['localsecondurl'] = yii::$app->params['backend'].'/giftreceive/index.html';
        return $this->render('index', [
                'data' => $data, 
                'pages' => $pages,
                'value' => $value,
                'select'=>$select,
            ]);
    } 
}