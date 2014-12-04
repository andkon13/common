<?php
/**
 * Created by PhpStorm.
 * User: andkon
 * Date: 22.08.14
 * Time: 18:55
 */

namespace app\common\helpers;

use yii\base\Model;
use yii\bootstrap\Button;

/**
 * Class ActiveForm
 *
 * @package app\common\helpers
 */
class ActiveForm extends \yii\widgets\ActiveForm
{
    public $model = null;
    public $fieldClass = '\app\common\helpers\ActiveField';
    public $fieldConfig = ['class' => '\app\common\helpers\ActiveField'];

    /**
     * @inheritdoc
     *
     * @param array $config
     * @param mixed $model
     */
    public function __construct($config = [], $model = null)
    {
        if (empty($config) && is_array($model)) {
            parent::__construct($model);
        } else {
            parent::__construct($config);
        }

        if ($model) {
            $this->model = $model;
        }
    }

    /**
     * @inheritdoc
     *
     * @param array|Model $modelsOrOption
     * @param array       $options
     *
     * @return string
     */
    public function errorSummary($modelsOrOption = [], $options = [])
    {
        if ($modelsOrOption instanceof Model) {
            if ($this->model == null) {
                $this->model = $modelsOrOption;
            }

            return parent::errorSummary($modelsOrOption, $options);
        } else {
            return parent::errorSummary($this->model, $modelsOrOption);
        }
    }

    /**
     * Прослойка, обходит необходимость каждый раз отправлять модель
     *
     * @param \yii\base\Model|string $modelOrAttribute
     * @param array|string           $attributeOrOptions
     * @param array                  $options
     *
     * @return \app\common\helpers\ActiveField
     */
    public function field($modelOrAttribute, $attributeOrOptions = [], $options = [])
    {
        if (!$modelOrAttribute instanceof Model) {
            return parent::field($this->model, $modelOrAttribute, $attributeOrOptions);
        } else {
            if ($this->model == null) {
                $this->model = $modelOrAttribute;
            }

            return parent::field($modelOrAttribute, $attributeOrOptions, $options);
        }
    }

    /**
     * Рисует кнопку "Сохранить"
     *
     * @param array $options
     *
     * @return string
     */
    public function button($options = [])
    {
        $options = array_merge(['type' => 'submit', 'class' => 'btn-primary'], $options);

        return Button::widget(
            [
                'label'   => \Yii::t('app', 'Save'),
                'options' => $options
            ]
        );
    }

    /**
     * @inheritdoc
     *
     * @param array      $config
     * @param null|Model $model
     *
     * @return ActiveForm
     */
    public static function begin($config = [], $model = null)
    {
        /** @var \app\common\helpers\ActiveForm $instance */
        $instance        = parent::begin($config);
        $instance->model = $model;

        return $instance;
    }
}
