<?php

namespace app\components;


use yii\base\Component;

class ContentCountry extends Component
{

    /**
     * @param $code
     * @return mixed
     */
    public function findByCode($code)
    {
        $code = strtoupper($code);
        $countryList = $this->getList();
        return $countryList[$code];
    }

    /**
     * @return array
     */
    public function getList()
    {
        return [
            'GB' => \Yii::t('app', 'Великобритания'),
            'US' => \Yii::t('app', 'США'),
            'UA' => \Yii::t('app', 'Украина'),
            'RU' => \Yii::t('app', 'Россия'),
        ];
    }
}