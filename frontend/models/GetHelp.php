<?php

namespace frontend\models;

use common\models\User;
use Yii;

/**
 * This is the model class for table "get_help".
 *
 * @property int $id
 * @property int $user_id
 * @property int $count
 * @property int $type
 * @property int $count_origin
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $note
 * @property string $received_at
 *
 * @property User $user
 * @property TransferHelp[] $transferHelps
 */
class GetHelp extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'get_help';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'count'], 'required'],
            [['user_id', 'count', 'type', 'count_origin', 'status'], 'integer'],
            [['created_at', 'updated_at', 'received_at'], 'safe'],
            [['note'], 'string'],
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
            'count' => 'Count',
            'type' => 'Type',
            'count_origin' => 'Count Origin',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'note' => 'Note',
            'received_at' => 'Received At',
        ];
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
        return $this->hasMany(TransferHelp::className(), ['get_help_id' => 'id']);
    }
}
