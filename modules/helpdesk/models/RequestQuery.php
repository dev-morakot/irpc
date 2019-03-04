<?php

namespace app\modules\helpdesk\models;

/**
 * This is the ActiveQuery class for [[RequestQuery]].
 *
 * @see RequestQuery
 */
class RequestQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return RequestQuery[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return RequestQuery|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}