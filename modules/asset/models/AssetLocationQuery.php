<?php

namespace app\modules\asset\models;

/**
 * This is the ActiveQuery class for [[AssetLocation]].
 *
 * @see AssetLocation
 */
class AssetLocationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return AssetLocation[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AssetLocation|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
