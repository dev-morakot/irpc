<?php

namespace app\modules\asset\models;

/**
 * This is the ActiveQuery class for [[ReturnOrderLine]].
 *
 * @see ReturnOrderLine
 */
class ReturnOrderLineQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ReturnOrderLine[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ReturnOrderLine|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
