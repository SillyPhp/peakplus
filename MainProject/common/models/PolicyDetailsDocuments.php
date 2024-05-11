<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "policy_details_documents".
 *
 * @property int $id
 * @property int $company_id
 * @property string $document_name
 * @property string $url
 *
 * @property Companies $company
 */
class PolicyDetailsDocuments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'policy_details_documents';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['company_id', 'document_name', 'url'], 'required'],
            [['company_id'], 'integer'],
            [['document_name', 'url'], 'string', 'max' => 500],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Companies::class, 'targetAttribute' => ['company_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_id' => 'Company ID',
            'document_name' => 'Document Name',
            'url' => 'Url',
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
}
