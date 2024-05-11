<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "companies".
 *
 * @property int $id
 * @property string $name
 * @property string $logo
 * @property string|null $contact
 * @property string|null $email
 * @property string|null $ref_url
 * @property string $ref_url_2
 *
 * @property Plans[] $plans
 * @property PolicyDetailsDocuments[] $policyDetailsDocuments
 */
class Companies extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'companies';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'logo', 'ref_url_2'], 'required'],
            [['name', 'logo'], 'string', 'max' => 200],
            [['contact'], 'string', 'max' => 50],
            [['email'], 'string', 'max' => 100],
            [['ref_url', 'ref_url_2'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'logo' => 'Logo',
            'contact' => 'Contact',
            'email' => 'Email',
            'ref_url' => 'Ref Url',
            'ref_url_2' => 'Ref Url 2',
        ];
    }

    /**
     * Gets query for [[Plans]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlans()
    {
        return $this->hasMany(Plans::class, ['company_id' => 'id']);
    }

    /**
     * Gets query for [[PolicyDetailsDocuments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPolicyDetailsDocuments()
    {
        return $this->hasMany(PolicyDetailsDocuments::class, ['company_id' => 'id']);
    }
}
