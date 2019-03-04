<?php

namespace app\modules\asset\models;

/**
 * This is the ActiveQuery class for [[AssetOrder]].
 *
 * @see AssetOrder
 */
class AssetOrderQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return AssetOrder[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AssetOrder|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
