<?php

namespace app\components;


use yii\base\Component;

class ContentLanguage extends Component
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
            'EN' => 'EN',
            'UA' => 'UA',
            'RU' => 'RU',
        ];
    }
}