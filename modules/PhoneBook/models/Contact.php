<?php

namespace app\modules\PhoneBook\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "contact".
 *
 * @property integer $id
 * @property string $name
 * @property string $fname
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Phone[] $phones
 */
class Contact extends ActiveRecord {

    public function behaviors() {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'contact';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['name', 'fname'], 'required'],
            [['name', 'fname'], 'string', 'min' => 3, 'max' => 64, 'tooShort' => '{attribute} should be at least 3 symbols', 'message' => '{attribute} should be at least 3 symbols'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'fname' => 'Family Name',
            'fullName' => Yii::t('app', 'Full Name'),
            'phonesCount' => Yii::t('app', 'Number of phones'),
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhones() {
        return $this->hasMany(Phone::className(), ['contact_id' => 'id']);
    }

    public function getPhonesCount() {
        return $this->getPhones()->count();
    }

    public function getFullName() {
        return $this->name . ' ' . $this->fname;
    }

}
