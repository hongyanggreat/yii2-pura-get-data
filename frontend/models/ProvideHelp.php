<?php

namespace frontend\models;

use common\models\User;
use Yii;

/**
 * This is the model class for table "provide_help".
 *
 * @property int $id
 * @property int $user_id
 * @property int $pin_id
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 * @property int $count
 * @property int $count_origin
 * @property int $is_completed
 * @property int $type
 * @property string $sent_at
 *
 * @property Bonus[] $bonuses
 * @property Pin $pin
 * @property User $user
 * @property TransferHelp[] $transferHelps
 */
class ProvideHelp extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'provide_help';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'count', 'count_origin'], 'required'],
            [['user_id', 'pin_id', 'status', 'count', 'count_origin', 'is_completed', 'type'], 'integer'],
            [['created_at', 'updated_at', 'sent_at'], 'safe'],
            [['pin_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pin::className(), 'targetAttribute' => ['pin_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'pin_id' => 'Pin ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'count' => 'Count',
            'count_origin' => 'Count Origin',
            'is_completed' => 'Is Completed',
            'type' => 'Type',
            'sent_at' => 'Sent At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBonuses()
    {
        return $this->hasMany(Bonus::className(), ['provide_help_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPin()
    {
        return $this->hasOne(Pin::className(), ['id' => 'pin_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransferHelps()
    {
        return $this->hasMany(TransferHelp::className(), ['provide_help_id' => 'id']);
    }
}
