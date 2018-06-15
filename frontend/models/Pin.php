<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "pin".
 *
 * @property int $id
 * @property int $buyer_id
 * @property int $owner_id
 * @property string $code
 * @property int $status
 * @property string $used_at
 * @property string $bought_at
 * @property string $activated_at
 *
 * @property User $buyer
 * @property User $owner
 * @property ProvideHelp[] $provideHelps
 */
class Pin extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pin';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['buyer_id', 'owner_id', 'code'], 'required'],
            [['buyer_id', 'owner_id', 'status'], 'integer'],
            [['used_at', 'bought_at', 'activated_at'], 'safe'],
            [['code'], 'string', 'max' => 255],
            [['code'], 'unique'],
            [['buyer_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['buyer_id' => 'id']],
            [['owner_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['owner_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'buyer_id' => 'Buyer ID',
            'owner_id' => 'Owner ID',
            'code' => 'Code',
            'status' => 'Status',
            'used_at' => 'Used At',
            'bought_at' => 'Bought At',
            'activated_at' => 'Activated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBuyer()
    {
        return $this->hasOne(User::className(), ['id' => 'buyer_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOwner()
    {
        return $this->hasOne(User::className(), ['id' => 'owner_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProvideHelps()
    {
        return $this->hasMany(ProvideHelp::className(), ['pin_id' => 'id']);
    }
}
