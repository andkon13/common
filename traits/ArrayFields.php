<?php
/**
 * Created by PhpStorm.
 * User: andkon
 * Date: 06.05.16
 * Time: 12:36
 */

namespace common\traits;

use yii\helpers\Json;

/**
 * Class ArrayFields
 * Класc преобразовывает текстовое поле в базе в массив при работе с моделью в php
 * Для задания обрабатываемых полей используйте
 * <code>
 *  public function init()
 * {
 *      parent::init();
 *      $this->registryFields(['fieldName0', 'fieldName1', ...]);
 * }
 * </code>
 *
 * @package common\traits
 */
trait ArrayFields
{
    private $arrayFields = [];

    /**
     * Регистрирует поля с которыми необходимо работать как с массивом
     *
     * @param array $fields
     */
    protected function registryFields($fields)
    {
        $this->arrayFields = $fields;
        $this->registryEvents();
    }

    protected function registryEvents()
    {
        $this->on(self::EVENT_AFTER_FIND, [$this, 'triggerExtract']);
        $this->on(self::EVENT_AFTER_INSERT, [$this, 'triggerExtract']);
        $this->on(self::EVENT_AFTER_UPDATE, [$this, 'triggerExtract']);
        $this->on(self::EVENT_AFTER_REFRESH, [$this, 'triggerExtract']);
        $this->on(self::EVENT_AFTER_VALIDATE, [$this, 'triggerExtract']);
        $this->on(self::EVENT_BEFORE_UPDATE, [$this, 'triggerImpact']);
        $this->on(self::EVENT_BEFORE_INSERT, [$this, 'triggerImpact']);
        $this->on(self::EVENT_BEFORE_VALIDATE, [$this, 'triggerImpact']);
    }

    /**
     * Распаковывает Json
     *
     * @return bool
     */
    public function triggerExtract()
    {
        foreach ($this->arrayFields as $field) {
            if (!is_array($this->$field)) {
                $this->$field = Json::decode($this->$field);
                if (!$this->$field) {
                    $this->$field = [];
                }
            }
        }

        return true;
    }

    /**
     * Пакует массив в Json
     *
     * @return bool
     */
    public function triggerImpact()
    {
        foreach ($this->arrayFields as $field) {
            if (is_array($this->$field)) {
                $this->$field = Json::encode($this->$field);
            }
        }

        return true;
    }
}
