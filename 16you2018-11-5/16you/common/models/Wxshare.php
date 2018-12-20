<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%wxshare}}".
 *
 * @property integer $id
 * @property integer $gid
 * @property string $gamename
 * @property string $title
 * @property string $desc
 * @property string $link
 * @property integer $createtime
 */
class Wxshare extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%wxshare}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gid'], 'required'],
            [['gid', 'createtime'], 'integer'],
            [['link'], 'string'],
            [['gamename'], 'string', 'max' => 100],
            [['title', 'desc'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '微信分享',
            'gid' => '游戏id',
            'gamename' => '游戏名称',
            'title' => '分享的标题',
            'desc' => '分享的描述',
            'link' => '分享的链接',
            'createtime' => 'Createtime',
        ];
    }
}
