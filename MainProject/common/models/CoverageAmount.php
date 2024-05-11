<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "coverage_amount".
 *
 * @property int $id
 * @property float $coverage_amount
 *
 * @property Plans[] $plans
 */
class CoverageAmount extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'coverage_amount';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['coverage_amount'], 'required'],
            [['coverage_amount'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'coverage_amount' => 'Coverage Amount',
        ];
    }

    /**
     * Gets query for [[Plans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlans()
    {
        return $this->hasMany(Plans::class, ['coverage_id' => 'id']);
    }
}
