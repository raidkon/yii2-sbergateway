<?php
/**
 * Created by PhpStorm.
 * User: Raidkon
 * Date: 28.02.2019
 * Time: 20:33
 */

namespace raidkon\yii2\sbergateway;


use Yii;
use yii\console\Controller;

class CommandController extends Controller
{
    /** @var GateWay */
    public $component;
    
    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function actionPay()
    {
        $request = new Request();
        $request->method = Request::METHOD_REGISTER_DO;
        $request->setData('amount', 22200);
        $request->setData('currency', '643');
        $request->setData('language', 'ru');
        $request->setData('orderNumber', 'asdasdasdasdasdasd'.time());
        $request->setData('returnUrl', 'https://3dsec.sberbank.ru/payment/finish.html');
        $request->setData('jsonParams', '{"orderNumber":1234567890}');
        $request->setData('pageView', 'DESKTOP');
        $request->setData('expirationDate', '2020-09-08T14:14:14');
        $request->setData('features', 'FORCE_TDS');
        $request->setData('features', 'AUTO_PAYMENT');
        $request->setData('clientId', '1');
        $response = Yii::$app->payment->execute($request);
        $response->print();
    }
    
    public function actionStatus($orderId)
    {
        $request = new Request();
        $request->method = Request::METHOD_ORDER_STATUS_EXTENDED;
        $request->setData('language', 'ru');
        $request->setData('orderId', $orderId);
        $response = Yii::$app->payment->execute($request);
        $response->print();
    }
    
    public function actionBind($bindId)
    {
    
        $request = new Request();
        $request->method = Request::METHOD_REGISTER_DO;
        $request->setData('amount', 22200);
        $request->setData('currency', '643');
        $request->setData('language', 'ru');
        $request->setData('orderNumber', time());
        $request->setData('returnUrl', 'https://3dsec.sberbank.ru/payment/finish.html');
        $request->setData('jsonParams', '{"orderNumber":1234567890}');
        $request->setData('pageView', 'DESKTOP');
        $request->setData('expirationDate', '2020-09-08T14:14:14');
//        $request->setData('features', 'FORCE_TDS');
        $request->setData('features', 'AUTO_PAYMENT');
        $request->setData('clientId', '1');
        $response = Yii::$app->payment->execute($request);
        
        if ($response->isError) {
            $response->printInfo();
            $response->printSendData();
            $response->print();
            return;
        }
        
        $orderId = $response->getData('orderId');
        
        $response->print();
        
        $request = new Request();
        $request->method = Request::METHOD_ORDER_BINDING;
        $request->setData('mdOrder', $orderId);
        $request->setData('language', 'ru');
        $request->setData('ip','94.138.142.24');
        $response = Yii::$app->payment->execute($request);
        $response->print();
    
        $request = new Request();
        $request->method = Request::METHOD_ORDER_STATUS_EXTENDED;
        $request->setData('language', 'ru');
        $request->setData('orderId', $orderId);
        $response = Yii::$app->payment->execute($request);
        $response->print();
    }
}
