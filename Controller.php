<?php
/**
 * Created by PhpStorm.
 * User: andkon
 * Date: 17.09.14
 * Time: 15:11
 */

namespace app\common;

use Yii;
use yii\base\Action;

/**
 * Class Controller
 *
 * @package app\common
 */
class Controller extends \andkon\yii2actions\Controller
{
    /** @var \yii\console\Response|\yii\web\Response|null */
    private $response = null;

    /**
     * Заглушка для запросов с андроида
     * //TODO: выяснить почему вылетает и убрать
     *
     * @param Action $action
     *
     * @return bool|mixed
     */
    public function beforeAction($action)
    {
        return true;
    }

    /**
     * @return \yii\console\Response|\yii\web\Response
     */
    public function getResponse()
    {
        if (null == $this->response) {
            $this->response = Yii::$app->getResponse();
        }

        return $this->response;
    }

    /**
     * Очищает response
     *
     * @return void
     */
    public function clearResponse()
    {
        $this->response->data = [];
    }

    /**
     * Выводит json в поток
     *
     * @param array $result
     * @param bool  $returnCount
     */
    protected function setResponse($result, $returnCount = false)
    {
        $response         = $this->getResponse();
        $response->format = $response::FORMAT_JSON;
        if ($returnCount && !isset($result['errorCode'])) {
            $tmp                  = [];
            $tmp['values']        = $result;
            $tmp['result_length'] = count($result);
            $result               = $tmp;
        }

        $response->data = $result;
    }

    /**
     * Забирает данные из php://input и если есть вставляет в $_POST
     *
     * @param null|string $name
     * @param bool        $returnIsNull
     *
     * @return bool
     */
    public function getPost($name = null, $returnIsNull = false)
    {
        $angularPOST = file_get_contents('php://input');
        $angularPOST = json_decode($angularPOST, true);
        if (null != $angularPOST) {
            $_POST = $angularPOST;
            Yii::$app->getRequest()->setBodyParams($angularPOST);
        }

        return parent::getPost($name, $returnIsNull);
    }
}