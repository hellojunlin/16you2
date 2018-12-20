<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%gift}}".
 *
 * @property integer $id
 * @property integer $gid
 * @property string $gift_name
 * @property string $game_image
 * @property string $game_name
 * @property string $content
 * @property string $number
 * @property string $CDKEY
 * @property integer $state
 * @property integer $createtime
 * @property integer $uid
 * @property string $payment
 * @property integer $type
 * @property integer $validtime
 * @property integer $gifttype
 */
class Gift extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%gift}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gid', 'gift_name', 'game_image', 'game_name', 'number', 'CDKEY'], 'required'],
            [['gid', 'state', 'createtime', 'uid', 'type', 'validtime', 'gifttype'], 'integer'],
            [['gift_name', 'game_name'], 'string', 'max' => 100],
            [['game_image', 'number', 'CDKEY'], 'string', 'max' => 50],
            [['content', 'payment'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '游戏礼包表',
            'gid' => '游戏id',
            'gift_name' => '礼包名称',
            'game_image' => '游戏图片',
            'game_name' => '所属游戏名称',
            'content' => '礼包内容',
            'number' => '编号',
            'CDKEY' => '激活码',
            'state' => '状态 1已领取 0未领取',
            'createtime' => 'Createtime',
            'uid' => '领取礼包的用户id',
            'payment' => '领取方式',
            'type' => '用途 1礼包领取 2邮件',
            'validtime' => '有效时间',
            'gifttype' => '礼包类型  0:新手 1：节日 2：活动  3：首发 4：入群',
        ];
    }
}
