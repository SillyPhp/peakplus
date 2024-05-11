<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "policies".
 *
 * @property int $id
 * @property string $plan_name
 *
 * @property Plans[] $plans
 */
class Policies extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'policies';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['plan_name'], 'required'],
            [['plan_name'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'plan_name' => 'Plan Name',
        ];
    }

    /**
     * Gets query for [[Plans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlans()
    {
        return $this->hasMany(Plans::class, ['policy_id' => 'id']);
    }
}
