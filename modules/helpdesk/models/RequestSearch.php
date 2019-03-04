<?php 

namespace app\modules\helpdesk\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\helpdesk\models\Request;

/**
 * RequestSearch represents the model behind the search form about `app\modules\helpdesk\models\Request`.
 */
 class RequestSearch extends Request {

     public function rules() {

        return [
             [['id','requested_by','repair_id', 'create_uid', 'write_uid'], 'integer'],
             [['name','state','date_create','origin','other','problem','sn_number','brand','description', 'date_repair', 'create_date', 'write_date'], 'safe'],
        ];
     }

     /**
     * @inheritdoc
     */
    public function scenarios(){
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
         $query = Request::find();

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
            'name' => $this->name,
            'sn_number' =>$this->sn_number,
            'date_create' =>$this->date_create,
            'problem' => $this->problem,
            'description' => $this->description, 
            'brand' => $this->brand,
            'date_repair' => $this->date_repair,
            'create_uid' => $this->create_uid,
            'create_date' => $this->create_date,
            'write_uid' => $this->write_uid,
            'write_date' => $this->write_date,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'state', $this->state])
            ->andFilterWhere(['like', 'origin', $this->origin]);

        return $dataProvider;
     }
 }
?>