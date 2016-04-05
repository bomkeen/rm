<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pro_risk_sub_detail".
 *
 * @property integer $pro_risk_sub_detail_id
 * @property integer $pro_risk_id
 * @property integer $pro_risk_detail_id
 * @property integer $pro_risk_sub_detail_key
 * @property string $pro_risk_sub_detail_name
 */
class Prorisksubdetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pro_risk_sub_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pro_risk_id', 'pro_risk_detail_id', 'pro_risk_sub_detail_key', 'pro_risk_sub_detail_name'], 'required'],
            [['pro_risk_id', 'pro_risk_detail_id', 'pro_risk_sub_detail_key'], 'integer'],
            [['pro_risk_sub_detail_name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pro_risk_sub_detail_id' => 'Pro Risk Sub Detail ID',
            'pro_risk_id' => 'Pro Risk ID',
            'pro_risk_detail_id' => 'Pro Risk Detail ID',
            'pro_risk_sub_detail_key' => 'Pro Risk Sub Detail Key',
            'pro_risk_sub_detail_name' => 'Pro Risk Sub Detail Name',
        ];
    }
}
