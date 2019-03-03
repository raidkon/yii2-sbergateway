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
 * @property string $data
 */
class Request extends BaseObject
{
    public const METHOD_REGISTER_DO = 'register.do';
    public const METHOD_ORDER_STATUS_EXTENDED = 'getOrderStatusExtended.do';
    public const METHOD_ORDER_BINDING = 'paymentOrderBinding.do';
    
    protected $method;
    protected $data = [];
    
    /**
     * @return mixed
     */
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
        ];
    }
    
    /**
     * @return mixed
     */
    public function getMethod(): string
    {
        return $this->method;
    }
    
    /**
     * @param mixed $method
     * @throws InvalidConfigException
     */
    public function setMethod(string $method): void
    {
        if (!key_exists($method, static::getMethods())) {
            throw new InvalidConfigException();
        }
        $this->method = $method;
    }
}
