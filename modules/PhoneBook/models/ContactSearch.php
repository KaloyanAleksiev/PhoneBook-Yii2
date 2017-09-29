<?php

namespace app\modules\PhoneBook\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\PhoneBook\models\Contact;
use app\modules\PhoneBook\models\Phone;

/**
 * ContactSearch represents the model behind the search form about `app\modules\PhoneBook\models\Contact`.
 */
class ContactSearch extends Contact {

    public $phonesCount;
    public $fullName;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id'], 'integer'],
            [['created_at', 'updated_at', 'phonesCount', 'fullName'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = Contact::find();

        $subQuery = Phone::find()->select('contact_id, COUNT(contact_id) as contact_count')->groupBy('contact_id');
        $query->leftJoin(['phoneSum' => $subQuery], 'phoneSum.contact_id = id');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['defaultPageSize' => 10]
        ]);
        $dataProvider->setSort([
            'attributes' => [
                'id',
                'fullName' => [
                    'asc' => ['name' => SORT_ASC, 'fname' => SORT_ASC],
                    'desc' => ['name' => SORT_DESC, 'fname' => SORT_DESC],
                    'label' => 'Full Name',
                    'default' => SORT_ASC
                ],
                'phonesCount' => [
                    'asc' => ['phoneSum.contact_count' => SORT_ASC],
                    'desc' => ['phoneSum.contact_count' => SORT_DESC],
                    'label' => 'Number of Phones'
                ],
                'created_at',
                'updated_at',
            ]
        ]);
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'phoneSum.contact_count' => $this->phonesCount
        ]);

        $query->andFilterWhere(['like', 'name', $this->fullName])
                ->orFilterWhere(['like', 'fname', $this->fullName]);

        return $dataProvider;
    }

}
