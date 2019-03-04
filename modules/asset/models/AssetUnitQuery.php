<?php

namespace app\modules\asset\models;

/**
 * This is the ActiveQuery class for [[AssetUnit]].
 *
 * @see AssetUnit
 */
class AssetUnitQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return AssetUnit[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AssetUnit|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
