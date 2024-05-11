<?php

namespace common\models;


/**
 * This is the model class for table "plans".
 *
 * @property int $plan_id
 * @property int $policy_id
 * @property int $min_age
 * @property int $max_age
 * @property int $coverage_id
 * @property int $company_id
 * @property float $rate_per
 * @property float $deductible
 * @property int $pre_medical any pre medical 0 no 1 yes
 * @property string $plan_sub_category
 * @property string $created_on
 * @property string|null $updated_on
 *
 * @property Companies $company
 * @property CoverageAmount $coverage
 * @property Policies $policy
 */
class Plans extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'plans';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['policy_id', 'min_age', 'max_age', 'coverage_id', 'company_id', 'rate_per'], 'required'],
            [['policy_id', 'min_age', 'max_age', 'coverage_id', 'company_id', 'pre_medical'], 'integer'],
            [['rate_per', 'deductible'], 'number'],
            [['plan_sub_category'], 'string'],
            [['created_on', 'updated_on'], 'safe'],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Companies::class, 'targetAttribute' => ['company_id' => 'id']],
            [['coverage_id'], 'exist', 'skipOnError' => true, 'targetClass' => CoverageAmount::class, 'targetAttribute' => ['coverage_id' => 'id']],
            [['policy_id'], 'exist', 'skipOnError' => true, 'targetClass' => Policies::class, 'targetAttribute' => ['policy_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'plan_id' => 'Plan ID',
            'policy_id' => 'Policy ID',
            'min_age' => 'Min Age',
            'max_age' => 'Max Age',
            'coverage_id' => 'Coverage ID',
            'company_id' => 'Company ID',
            'rate_per' => 'Rate Per',
            'deductible' => 'Deductible',
            'pre_medical' => 'Pre Medical',
            'plan_sub_category' => 'Plan Sub Category',
            'created_on' => 'Created On',
            'updated_on' => 'Updated On',
        ];
    }

    /**
     * Gets query for [[Company]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Companies::class, ['id' => 'company_id']);
    }

    /**
     * Gets query for [[Coverage]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCoverage()
    {
        return $this->hasOne(CoverageAmount::class, ['id' => 'coverage_id']);
    }

    /**
     * Gets query for [[Policy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPolicy()
    {
        return $this->hasOne(Policies::class, ['id' => 'policy_id']);
    }
}
