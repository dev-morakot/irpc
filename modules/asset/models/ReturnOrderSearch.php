<?php

namespace app\modules\asset\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\asset\models\ReturnOrder;
use yii\helpers\ArrayHelper;
use app\modules\asset\Asset;
use yii\db\Query;

/**
 * ReturnOrderSearch represents the model behind the search form about `app\models\Asset`.
 */
class ReturnOrderSearch extends ReturnOrder
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
         return [
            [['date_order', 'date_approve', 'date_cancel', 'create_date', 'write_date'], 'safe'],
            [['group_id', 'location_id', 'approver_id', 'cancel_id', 'create_id', 'create_uid', 'write_uid'], 'integer'],
            [['notes'], 'string'],
            [['state'], 'string', 'max' => 50],
            [['name','full_name'], 'string', 'max' => 255],
            [['state'],'in','range'=>  ArrayHelper::getColumn(Asset::asState(), 'id')],// allow only key in poState
            
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
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
    public function search($params)
    {
        $query = ReturnOrder::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'date_order' => $this->date_order,
            'date_approve' => $this->date_approve,
            'date_cancel' => $this->date_cancel,
            'state' => $this->state,
            'name' => $this->name,
            'full_name' => $this->full_name,
            'group_id' => $this->group_id,
            'notes' => $this->notes,
            'location_id' => $this->location_id,
            'approver_id' => $this->approver_id,
            'cancel_id' => $this->cancel_id,
            'create_id' => $this->create_id,
            'create_uid' => $this->create_uid,
            'create_date' => $this->create_date,
            'write_uid' => $this->write_uid,
            'write_date' => $this->write_date,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'full_name', $this->full_name]);

        return $dataProvider;
    }


    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchLine($params)
    {
        $query = (new Query())
        	->select('
        		a.name,
        		a.full_name,
        		a.date_approve,
        		a.date_order,
        		a.state,
        		b.qty,
                c.description,
                g.name as group_name, g.department,l.name as location_name,
                c.name as as_name, categ.name as category_name,u.name as unit_name
        	')
        	->from('return_order a')
        	->leftJoin('return_order_line b','b.order_id = a.id')
        	->leftJoin('asset_asset c','c.id = b.asset_id')
            ->leftJoin('res_group g','g.id = a.group_id')
            ->leftJoin('asset_location l','l.id = a.location_id')
            ->leftJoin("asset_categories categ",'categ.id = c.categories_id')
            ->leftJoin('asset_unit u','u.id = c.unit_id');


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'a.id' => $this->id,
            'a.date_order' => $this->date_order,
            'a.date_approve' => $this->date_approve,
            'a.date_cancel' => $this->date_cancel,
            'a.state' => $this->state,
            'a.name' => $this->name,
            'a.full_name' => $this->full_name,
            'a.group_id' => $this->group_id,
            'a.notes' => $this->notes,
            'a.location_id' => $this->location_id,
            'a.approver_id' => $this->approver_id,
            'a.cancel_id' => $this->cancel_id,
            'a.create_id' => $this->create_id,
            'a.create_uid' => $this->create_uid,
            'a.create_date' => $this->create_date,
            'a.write_uid' => $this->write_uid,
            'a.write_date' => $this->write_date,
        ]);
        $query->andFilterWhere(['a.state' => 'approved']);

        $query->andFilterWhere(['like', 'a.name', $this->name])
            ->andFilterWhere(['like', 'a.full_name', $this->full_name]);
            
        return $dataProvider;
    }



    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchFilter($params)
    {
        $query = (new Query())
        	->select('
        		a.name,
        		a.full_name,
        		a.date_approve,
        		a.date_order,
        		a.state,
        		b.qty,
                c.description,
                g.name as group_name, g.department,l.name as location_name,
                c.name as as_name, categ.name as category_name,u.name as unit_name
        	')
        	->from('return_order a')
        	->leftJoin('return_order_line b','b.order_id = a.id')
        	->leftJoin('asset_asset c','c.id = b.asset_id')
            ->leftJoin('res_group g','g.id = a.group_id')
            ->leftJoin('asset_location l','l.id = a.location_id')
            ->leftJoin("asset_categories categ",'categ.id = c.categories_id')
            ->leftJoin('asset_unit u','u.id = c.unit_id');


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'a.id' => $this->id,
            'a.date_order' => $this->date_order,
            'a.date_approve' => $this->date_approve,
            'a.date_cancel' => $this->date_cancel,
            'a.state' => $this->state,
            'a.name' => $this->name,
            'a.full_name' => $this->full_name,
            'a.group_id' => $this->group_id,
            'a.notes' => $this->notes,
            'a.location_id' => $this->location_id,
            'a.approver_id' => $this->approver_id,
            'a.cancel_id' => $this->cancel_id,
            'a.create_id' => $this->create_id,
            'a.create_uid' => $this->create_uid,
            'a.create_date' => $this->create_date,
            'a.write_uid' => $this->write_uid,
            'a.write_date' => $this->write_date,
        ]);
        $query->andFilterWhere(['a.state' => 'wait']);

        $query->andFilterWhere(['like', 'a.name', $this->name])
            ->andFilterWhere(['like', 'a.full_name', $this->full_name]);
            
        return $dataProvider;
    }
}
