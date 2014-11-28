<?php
/**
 * Created by PhpStorm.
 * User: andkon
 * Date: 11.11.14
 * Time: 16:45
 */

namespace app\common\helpers;

use app\common\widgets\RegionAutoComplete;
use app\models\helpers\Region;
use kartik\widgets\DatePicker;
use kartik\widgets\FileInput;
use kartik\widgets\Select2;
use kartik\widgets\Typeahead;
use yii\helpers\Html;

/**
 * Class ActiveField
 *
 * @inheritdoc
 * @package app\common\helpers
 */
class ActiveField extends \yii\widgets\ActiveField
{
    /**
     * @inheritdoc
     *
     * @param array $items
     * @param array $options
     *
     * @return static
     */
    public function dropDownList($items, $options = [])
    {
        $options = array_merge(['prompt' => '---'], $options);

        return parent::dropDownList($items, $options);
    }

    /**
     * Выводит поле с вкалендарем
     *
     * @param array $options
     *
     * @return $this
     */
    public function dateField($options = [])
    {
        $options = array_merge($this->inputOptions, $options);
        $this->adjustLabelFor($options);
        $this->parts['{input}'] = DatePicker::widget([
            'language'  => 'ru',
            'model'     => $this->model,
            'attribute' => $this->attribute,
        ]);

        return $this;
    }

    /**
     * Возвращает select2
     *
     * @param       $data
     * @param array $options
     *
     * @return $this
     */
    public function autoComplete($data, $options = [])
    {
        $data    = (empty($data)) ? ['---'] : $data;
        $options = array_merge(
            [
                'placeholder' => \Yii::t(
                    'app',
                    'Select {attribute}',
                    ['attribute' => $this->model->getAttributeLabel($this->attribute)]
                )
            ],
            $options
        );
        $options = array_merge($this->inputOptions, $options);
        $this->adjustLabelFor($options);
        $this->parts['{input}'] = Typeahead::widget(
            [
                'model'         => $this->model,
                'attribute'     => $this->attribute,
                'options'       => ['placeholder' => $options['placeholder']],
                'pluginOptions' => ['highlight' => true],
                'dataset'       => [
                    [
                        'local' => $data,
                        'limit' => 10
                    ]
                ]
            ]
        );

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @param array $options
     *
     * @return string
     */
    public function fileInput($options = [], $withLabel = true)
    {
        $html = '';
        if ($withLabel) {
            $html .= Html::activeLabel($this->model, $this->attribute);
        }

        $options = array_merge(['showUpload' => false], $options);
        $html .= FileInput::widget(
            [
                'model'         => $this->model,
                'attribute'     => $this->attribute,
                'pluginOptions' => $options,
            ]
        );

        return $html;
    }

    public function multiSelect($data, $option = [])
    {
        $option = array_merge(['multiple' => true], $option);
        return $this->widget(
            Select2::className(),
            [
                'model'         => $this->model,
                'attribute'     => $this->attribute,
                'data'          => $data,
                'options'       => $option,
                'pluginOptions' => [
                    'allowClear' => true,
                ],
            ]
        );
    }
}
