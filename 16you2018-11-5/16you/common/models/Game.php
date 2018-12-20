<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%game}}".
 *
 * @property string $id
 * @property string $unique
 * @property string $name
 * @property string $descript
 * @property integer $cid
 * @property integer $state
 * @property string $label
 * @property string $intro
 * @property string $game_url
 * @property integer $type
 * @property string $image
 * @property integer $sort
 * @property integer $game_type
 * @property string $head_img
 * @property string $f_gamelogo
 * @property integer $createtime
 * @property integer $is_newgame
 * @property string $r_company
 * @property string $article
 * @property string $detailimg
 * @property string $remark
 */
class Game extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%game}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['unique', 'name', 'cid', 'state', 'game_url', 'game_type', 'head_img', 'createtime'], 'required'],
            [['descript', 'label', 'intro', 'image'], 'string'],
            [['cid', 'state', 'type', 'sort', 'game_type', 'createtime', 'is_newgame'], 'integer'],
            [['unique'], 'string', 'max' => 200],
            [['name'], 'string', 'max' => 100],
            [['game_url', 'head_img', 'f_gamelogo', 'remark'], 'string', 'max' => 255],
            [['r_company', 'detailimg'], 'string', 'max' => 70],
            [['article'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'unique' => '游戏唯一标识',
            'name' => 'Name',
            'descript' => 'Descript',
            'cid' => '所属的游戏公司',
            'state' => '状态 0：禁用 1：启用  2：下架',
            'label' => '标签',
            'intro' => '简介',
            'game_url' => '游戏链接',
            'type' => '游戏类别：1热门',
            'image' => 'Image',
            'sort' => '排序，数值越大越靠前',
            'game_type' => '游戏类型:0：挂机装置 1：角色扮演  2：经营策略 3：创意休闲',
            'head_img' => '游戏logo',
            'f_gamelogo' => '新版首页游戏logo',
            'createtime' => 'Createtime',
            'is_newgame' => '是否为新游 0：否 1：是',
            'r_company' => '研发公司',
            'article' => '文网游备字',
            'detailimg' => '详情页图片',
            'remark' => '备注',
        ];
    }
}
