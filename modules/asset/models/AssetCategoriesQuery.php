<?php

namespace app\modules\asset\models;

/**
 * This is the ActiveQuery class for [[AssetCategories]].
 *
 * @see AssetCategories
 */
class AssetCategoriesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return AssetCategories[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AssetCategories|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
