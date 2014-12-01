<?php
/**
 * Created by PhpStorm.
 * User: andkon
 * Date: 28.11.14
 * Time: 13:11
 */

namespace app\common\grid;

use yii\grid\DataColumn;
use yii\helpers\Html;

/**
 * Class StatusColumn
 *
 * @package app\common\grid
 */
class StatusColumn extends DataColumn
{
    public $data = [];

    /**
     * @inheritdoc
     *
     * @param mixed $model
     * @param mixed $key
     * @param int   $index
     *
     * @return string|null
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        $id = $model->{$this->attribute};
        if (array_key_exists($id, $this->data)) {
            return $this->data[$id];
        }

        return null;
    }

    /**
     * @inheritdoc
     * @return string
     */
    protected function renderFilterCellContent()
    {
        return Html::activeDropDownList(
            $this->grid->filterModel,
            $this->attribute,
            $this->data,
            ['class' => 'form-control', 'prompt' => '---']
        );
    }
}
