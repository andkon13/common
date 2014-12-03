<?php
/**
 * Created by PhpStorm.
 * User: andkon
 * Date: 30.10.14
 * Time: 15:59
 */

namespace app\common;

use Yii;

/**
 * Class ActiveRecord
 *
 * @package app\common
 */
class ActiveRecord extends \andkon\yii2actions\ActiveRecord
{
    /**
     * Удаляет записи where $key[0] = $params[0] and $key[1] not in ($params[1])
     *
     * @param array $params
     *
     * @return int
     */
    public static function deleteNotIn($params)
    {
        $fields = array_keys($params);
        $query  = $fields[0] . '=:id';
        if (isset($fields[1])) {
            $query .= (!empty($params[$fields[1]])) ? ' and ' . $fields[1] . ' not in (' . implode(',', $params[$fields[1]]) . ')' : '';
        }

        return self::deleteAll($query,[':id' => $params[$fields[0]]]);
    }
}