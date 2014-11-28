<?php
/**
 * Created by PhpStorm.
 * User: andkon
 * Date: 02.10.14
 * Time: 19:26
 */

namespace app\common\grid;

use andkon\yii2actions\ActiveRecord;
use yii\grid\DataColumn;
use yii\helpers\Html;

/**
 * Class LinkColumn
 *
 * @package app\common\helpers
 */
class LinkColumn extends DataColumn
{
    /** @var string|array */
    public $url = '#';
    public $externalUrl = false;
    public $title = '';
    public $options = [];

    public function init()
    {
        parent::init();
        $this->options = array_merge(['class' => 'linkColumn', 'title' => $this->title], $this->options);
    }

    /**
     * @inheritdoc
     *
     * @param ActiveRecord|mixed $model
     * @param mixed              $key
     * @param int                $index
     *
     * @return string
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        $content = parent::renderDataCellContent($model, $key, $index);
        if (!$this->externalUrl) {
            $url = '#';
            if ($model instanceof ActiveRecord && is_array($this->url)) {
                $url = $this->url;
                foreach ($url as $key => $attrib_name) {
                    if (!is_int($key)) {
                        $url[$key] = $model->$attrib_name;
                    }
                }

                $url['id'] = $model->id;
            }

            $url = ($url == '#') ? '#' : \Yii::$app->getUrlManager()->createUrl($url);
        } else {
            $url                     = 'http://' . $model->{$this->externalUrl};
            $this->options['target'] = '_blank';
        }

        return Html::a($content, $url, $this->options);
    }
}
