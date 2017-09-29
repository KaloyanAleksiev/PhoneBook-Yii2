<?php

namespace app\modules\PhoneBook\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "phone".
 *
 * @property integer $id
 * @property integer $number
 * @property integer $type
 * @property string $created_at
 * @property integer $contact_id
 *
 * @property Contact $contact
 */
class Phone extends ActiveRecord {

    public function behaviors() {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'created_at',
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'phone';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['number'], 'required'],
            [['number', 'type', 'contact_id'], 'integer'],
            [['contact_id'], 'exist', 'skipOnError' => true, 'targetClass' => Contact::className(), 'targetAttribute' => ['contact_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'number' => 'Number',
            'type' => 'Type',
            'created_at' => 'Created At',
            'contact_id' => 'Contact ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContact() {
        return $this->hasOne(Contact::className(), ['id' => 'contact_id']);
    }

    /**
     * Creates and populates a set of models.
     *
     * @param string $modelClass
     * @param array $multipleModels
     * @return array
     */
    public static function createMultiple($modelClass, $multipleModels = []) {
        $model = new $modelClass;
        $formName = $model->formName();
        $post = Yii::$app->request->post($formName);
        $models = [];

        if (!empty($multipleModels)) {
            $keys = array_keys(ArrayHelper::map($multipleModels, 'id', 'id'));
            $multipleModels = array_combine($keys, $multipleModels);
        }

        if ($post && is_array($post)) {
            foreach ($post as $i => $item) {
                if (isset($item['id']) && !empty($item['id']) && isset($multipleModels[$item['id']])) {
                    $models[] = $multipleModels[$item['id']];
                } else {
                    $models[] = new $modelClass;
                }
            }
        }

        unset($model, $formName, $post);

        return $models;
    }

}
