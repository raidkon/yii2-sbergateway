<?php
/**
 * Created by PhpStorm.
 * User: Raidkon
 * Date: 28.02.2019
 * Time: 20:41
 */

namespace raidkon\yii2\sbergateway;


use yii\base\BaseObject;
use yii\base\InvalidConfigException;

/**
 * Class Request
 * @package raidkon\yii2\sbergateway
 *
 * @property string $method
 * @property array $data
 */
class Request extends BaseObject
{
    public const METHOD_REGISTER_DO = 'rest/register.do';
    public const METHOD_ORDER_STATUS_EXTENDED = 'rest/getOrderStatusExtended.do';
    public const METHOD_ORDER_BINDING = 'rest/paymentOrderBinding.do';
    public const METHOD_APPLE_PAYMENT_DO = 'applepay/payment.do';
    public const METHOD_GOOGLE_PAYMENT_DO = 'google/payment.do';
    public const METHOD_SAMSUNG_PAYMENT_DO = 'samsung/payment.do';

    protected $method = null;
    protected $data = [];

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(string $name, string $value)
    {
        $this->data[$name] = $value;
    }

    public static function getMethods(): array
    {
        return [
            self::METHOD_REGISTER_DO => 'Запрос регистрации заказа',
            self::METHOD_ORDER_STATUS_EXTENDED => 'Расширенный запрос состояния заказа',
            self::METHOD_ORDER_BINDING => 'Запрос проведения платежа по связкам',
            self::METHOD_APPLE_PAYMENT_DO => 'Запрос оплаты через Apple Pay',
        ];
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setMethod(string $method): void
    {
        if (!key_exists($method, static::getMethods())) {
            throw new InvalidConfigException();
        }
        $this->method = $method;
    }
}
