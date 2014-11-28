<?php
/**
 * Created by PhpStorm.
 * User: andkon
 * Date: 23.10.14
 * Time: 15:06
 */

namespace app\common\grid;

use andkon\yii2actions\ActiveRecord;
use yii\grid\DataColumn;
use yii\helpers\Html;

/**
 * Class ArrayLinkColumn
 *
 * @package app\common\helpers
 */
class ArrayLinkColumn extends DataColumn
{
    public $url = '#';
    public $separator = ', ';

    /**
     * @inheritdoc
     *
     * @param ActiveRecord $model
     * @param int          $key
     * @param int          $index
     *
     * @return string
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        $content = [];
        foreach ($model->{$this->attribute} as $relationModel) {
            /** @var ActiveRecord $relationModel */
            $content[] = Html::a(
                $relationModel->__toString(),
                \Yii::$app->getUrlManager()->createUrl([$this->url, 'id' => $relationModel->id])
            );
        }

        return implode($this->separator, $content);
    }
}