<?php
/**
 * Created by PhpStorm.
 * User: andkon
 * Date: 22.08.14
 * Time: 18:33
 */

namespace app\common\helpers;

use yii\db\ActiveRecord;

/**
 * Class ArrayHelper
 * Хелпер для работы с массивами
 *
 * @package app\common\helpers
 */
class ArrayHelper extends \yii\helpers\ArrayHelper
{
    /**
     * Преобразует массив моделей в массив вида [id => name]
     *
     * @param ActiveRecord[] $models
     * @param string         $keyField
     * @param string         $valField
     *
     * @return array
     */
    public static function listData($models, $keyField = 'id', $valField = 'name')
    {
        return self::map($models, $keyField, $valField);
    }
}
