<?php

namespace frontend\models;

use common\models\User;
use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property int $parent_id
 * @property int $role_id
 * @property string $username
 * @property string $password_hash
 * @property string $secret_code
 * @property string $fullname
 * @property string $email
 * @property string $phone
 * @property string $bank_account_vietcombank_holder
 * @property string $bank_account_vietcombank_number
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $last_login_at
 * @property string $unblocked_at
 * @property string $block_note
 * @property string $blocked_at
 * @property double $bonus
 * @property int $rate
 * @property int $is_calculated
 * @property string $bank_account_agribank_holder
 * @property string $bank_account_agribank_number
 * @property string $children_id
 * @property string $auth_key
 * @property int $confirmed_at
 * @property string $unconfirmed_email
 * @property string $registration_ip
 * @property int $flags
 *
 * @property Bonus[] $bonuses
 * @property GetHelp[]       $getHelps
 * @property Notification[]  $notifications
 * @property Pin[]           $pins
 * @property Pin[]           $pins0
 * @property PinTransfer[]   $pinTransfers
 * @property PinTransfer[]   $pinTransfers0
 * @property Profile         $profile
 * @property ProvideHelp[]   $provideHelps
 * @property RegisterRule[]  $registerRules
 * @property SocialAccount[] $socialAccounts
 * @property Token[]         $tokens
 * @property Transaction[]   $transactions
 * @property Transaction[]   $transactions0
 * @property Role            $role
 * @property int             customer_id
 */
class Users extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id', 'username', 'password_hash', 'secret_code', 'fullname', 'email', 'phone', 'bank_account_vietcombank_holder', 'bank_account_vietcombank_number', 'bonus'], 'required'],
            [['parent_id', 'role_id', 'status', 'rate', 'is_calculated', 'confirmed_at', 'flags'], 'integer'],
            [['created_at', 'updated_at', 'last_login_at', 'unblocked_at', 'blocked_at'], 'safe'],
            [['block_note', 'children_id'], 'string'],
            [['bonus'], 'number'],
            [['username', 'password_hash', 'secret_code', 'fullname', 'email', 'bank_account_vietcombank_holder', 'bank_account_vietcombank_number', 'bank_account_agribank_holder', 'bank_account_agribank_number', 'unconfirmed_email'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 15],
            [['auth_key'], 'string', 'max' => 32],
            [['registration_ip'], 'string', 'max' => 45],
            [['username'], 'unique'],
//            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Role::className(), 'targetAttribute' => ['role_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent ID',
            'role_id' => 'Role ID',
            'username' => 'Username',
            'password_hash' => 'Password Hash',
            'secret_code' => 'Secret Code',
            'fullname' => 'Fullname',
            'email' => 'Email',
            'phone' => 'Phone',
            'bank_account_vietcombank_holder' => 'Bank Account Vietcombank Holder',
            'bank_account_vietcombank_number' => 'Bank Account Vietcombank Number',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'last_login_at' => 'Last Login At',
            'unblocked_at' => 'Unblocked At',
            'block_note' => 'Block Note',
            'blocked_at' => 'Blocked At',
            'bonus' => 'Bonus',
            'rate' => 'Rate',
            'is_calculated' => 'Is Calculated',
            'bank_account_agribank_holder' => 'Bank Account Agribank Holder',
            'bank_account_agribank_number' => 'Bank Account Agribank Number',
            'children_id' => 'Children ID',
            'auth_key' => 'Auth Key',
            'confirmed_at' => 'Confirmed At',
            'unconfirmed_email' => 'Unconfirmed Email',
            'registration_ip' => 'Registration Ip',
            'flags' => 'Flags',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBonuses()
    {
        return $this->hasMany(Bonus::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGetHelps()
    {
        return $this->hasMany(GetHelp::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotifications()
    {
        return $this->hasMany(Notification::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPins()
    {
        return $this->hasMany(Pin::className(), ['buyer_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPins0()
    {
        return $this->hasMany(Pin::className(), ['owner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPinTransfers()
    {
        return $this->hasMany(PinTransfer::className(), ['new_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPinTransfers0()
    {
        return $this->hasMany(PinTransfer::className(), ['old_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProvideHelps()
    {
        return $this->hasMany(ProvideHelp::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegisterRules()
    {
        return $this->hasMany(RegisterRule::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSocialAccounts()
    {
        return $this->hasMany(SocialAccount::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTokens()
    {
        return $this->hasMany(Token::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::className(), ['from_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions0()
    {
        return $this->hasMany(Transaction::className(), ['to_user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRole()
    {
        return $this->hasOne(Role::className(), ['id' => 'role_id']);
    }

    public static function getUserIdByUserName($username){
    	$user = User::findOne(['username'=> $username]);
	    if($user){
		    return $user->id;
	    }
	    return 1;
    }
}
