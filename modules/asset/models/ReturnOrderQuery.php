<?php

namespace app\modules\asset\models;

/**
 * This is the ActiveQuery class for [[ReturnOrder]].
 *
 * @see ReturnOrder
 */
class ReturnOrderQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ReturnOrder[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ReturnOrder|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
