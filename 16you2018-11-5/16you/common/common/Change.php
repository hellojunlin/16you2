<?php
namespace common\common;

use Yii;
use yii\db\ActiveRecord;

class Change extends ActiveRecord
{
	/**
     * 对income、sex、constellation、job数字进行转化
     * @param  $model  arr or Object
     * @return [arr]        [$model]
     */
    public function change($model){
        
        foreach ($model as $k => $v) {
            switch ($k) {
                case 'sex':
                    if($v == 1){
                        $v = '男';
                    }else{
                        $v = '女';
                    }
                    break;
                case 'income':
                    switch ($v) {
                        case '0':
                            $v = '4K以下';
                            break;
                        case '1':
                            $v = '4K~8k';
                            break;
                        case '2':
                            $v = '8K~10k';
                            break;
                        case '3':
                            $v = '10k~15K';
                            break;
                        case '4':
                            $v = '15K以上';
                            break;
                    }
                    break;
                case 'job':
                    switch ($v) {
                    	case '0':
                    		$v = '自由行业';
                    		break;
                        case '1':
                            $v = '互联网行业';
                            break;
                        case '2':
                            $v = '餐饮行业';
                            break;
                        case '3':
                            $v = '服装行业';
                            break;
                        case '4':
                            $v = '制造业';
                            break;
                        case '5':
                            $v = '金融业';
                            break;
                        case '6':
                            $v = '建筑业';
                            break;
                        case '7':
                            $v = '房地产业';
                            break;
                        case '8':
                            $v = '教育行业';
                            break;
                        case '9':
                            $v = '工艺行业';
                            break;
                        case '10':
                            $v = '机械行业';
                            break;
                        case '11':
                            $v = '其他行业';
                            break;
                    }
                    break;
                case 'constellation':
                    switch ($v) {
                        case '0':
                            $v = '白羊座';
                            break;
                        case '1':
                            $v = '金牛座';
                            break;
                        case '2':
                            $v = '双子座';
                            break;
                        case '3':
                            $v = '巨蟹座';
                            break;
                        case '4':
                            $v = '狮子座';
                            break;
                        case '5':
                            $v = '处女座';
                            break;
                        case '6':
                            $v = '天枰座';
                            break;
                        case '7':
                            $v = '天蝎座';
                            break;
                        case '8':
                            $v = '射手座';
                            break;
                        case '9':
                            $v = '摩羯座';
                            break;
                        case '10':
                            $v = '水瓶座';
                            break;
                        case '11':
                            $v = '双鱼座';
                            break;
                    }
                    break;    
            }
            //判断是否为数组
            if(!is_array($model)){
                $model->$k = $v;
            }else{
                $model[$k] = $v;
            }
        }
        return $model;
    }
}