<?php

namespace app\modules\asset\models;

/**
 * This is the ActiveQuery class for [[AssetOrderLine]].
 *
 * @see AssetOrderLine
 */
class AssetOrderLineQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return AssetOrderLine[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AssetOrderLine|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
