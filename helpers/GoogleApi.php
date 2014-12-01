<?php
/**
 * Created by PhpStorm.
 * User: andkon
 * Date: 24.09.14
 * Time: 14:09
 */

namespace app\common\helpers;
/**
 * Class GoogleApi
 *
 * @package app\modules\place\helpers
 */
class GoogleApi
{
    // https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=59.903109,30.3259059&radius=500&types=store&sensor=false&key=AIzaSyAoEqyU0QinBnt-9GRsj-BS8q3uvtUPqMk
    const API_KEY = 'AIzaSyAoEqyU0QinBnt-9GRsj-BS8q3uvtUPqMk';
    const  URL    = 'https://maps.googleapis.com/maps/api/place/nearbysearch/json?';
    private $type = 'store';
    private $radius = 500;
    private static $coordinateRound = 4;
    /** @var null|GoogleApi */
    private static $instance = null;
    /** @var bool использовать ли кэш */
    public static $useCache = true;

    /**
     * Возвращает места рядом с координатоми
     *
     * @param int $lat
     * @param int $lng
     *
     * @return bool|array
     */
    public static function getPlaceList($lat, $lng)
    {
        $cacheKey = __CLASS__ . '::' . __FUNCTION__ . '_' . round($lat, self::$coordinateRound) . '#' . round($lng, self::$coordinateRound);
        $cache    = \Yii::$app->getCache();
        $result   = $cache->get($cacheKey);
        if (false === $result && !self::$useCache) {
            $instance = self::getInstance();
            $result   = $instance->request(
                [
                    'location' => $lat . ',' . $lng,
                    'radius'   => $instance->radius,
                    'types'    => $instance->type,
                ]
            );
            $result   = (isset($result['results'])) ? $result['results'] : false;
            if ($result) {
                $cache->set($cacheKey, $result, (60 * 60 * 24));
            }
        }

        return $result;
    }

    /**
     * @return GoogleApi
     */
    protected static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Блокировка функции
     */
    private final function __construct()
    {
    }

    /**
     * Блокировка функции
     */
    private final function __clone()
    {
    }

    /**
     * Делает запрос в гугль
     *
     * @param array $param параметры запроса
     *
     * @return mixed|string
     */
    private function request($param)
    {
        $urlParam = '';
        foreach ($param as $key => $val) {
            $urlParam .= '&' . $key . '=' . $val;
        }

        $url    = self::URL . $urlParam . '&key=' . self::API_KEY;
        $result = @file_get_contents($url);
        if ($result) {
            $result = json_decode($result, true);
        }

        return $result;
    }
}