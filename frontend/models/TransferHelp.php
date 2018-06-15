<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "transfer_help".
 *
 * @property int $id
 * @property int $provide_help_id
 * @property int $get_help_id
 * @property int $status
 * @property string $note
 * @property string $created_at
 * @property string $updated_at
 * @property string $approved_at
 *
 * @property Transaction[] $transactions
 * @property GetHelp $getHelp
 * @property ProvideHelp $provideHelp
 */
class TransferHelp extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transfer_help';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['provide_help_id', 'get_help_id', 'status'], 'integer'],
            [['note'], 'string'],
            [['created_at', 'updated_at', 'approved_at'], 'safe'],
            [['get_help_id'], 'exist', 'skipOnError' => true, 'targetClass' => GetHelp::className(), 'targetAttribute' => ['get_help_id' => 'id']],
            [['provide_help_id'], 'exist', 'skipOnError' => true, 'targetClass' => ProvideHelp::className(), 'targetAttribute' => ['provide_help_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'provide_help_id' => 'Provide Help ID',
            'get_help_id' => 'Get Help ID',
            'status' => 'Status',
            'note' => 'Note',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'approved_at' => 'Approved At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::className(), ['transfer_help_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGetHelp()
    {
        return $this->hasOne(GetHelp::className(), ['id' => 'get_help_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProvideHelp()
    {
        return $this->hasOne(ProvideHelp::className(), ['id' => 'provide_help_id']);
    }
}
