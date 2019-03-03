<?php
/**
 * Created by PhpStorm.
 * User: Raidkon
 * Date: 28.02.2019
 * Time: 20:47
 */

namespace raidkon\yii2\sbergateway;


use RuntimeException;
use Yii;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

/**
 * Class Response
 * @package raidkon\yii2\sbergateway
 *
 * @property array $error
 * @property array $data
 * @property-read string $url
 * @property-read bool $isSuccess
 * @property-read bool $isError
 */
class Response extends BaseObject
{
    const ERROR_CURL = 'curl';
    const ERROR_JSON = 'json';
    const ERROR_EXCEPTION = 'exception';
    
    protected $sendData;
    protected $error;
    protected $data = [];
    protected $url;
    protected $info;
    
    /**
     * @return mixed
     */
    public function getSendData()
    {
        return $this->sendData;
    }
    
    /**
     * @param mixed $sendData
     */
    public function setSendData($sendData): void
    {
        if ($this->sendData) {
            throw new RuntimeException("Отправленные данные уже установлена");
        }
        $this->sendData = $sendData;
    }
    
    /**
     * @return mixed
     */
    public function getInfo()
    {
        return $this->info;
    }
    
    /**
     * @param mixed $info
     */
    public function setInfo($info): void
    {
        if ($this->info) {
            throw new RuntimeException("Информация уже установлена");
        }
        $this->info = $info;
    }
    
    /**
     * @return mixed
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }
    
    /**
     * @param mixed $url
     */
    public function setUrl($url): void
    {
        if (!$this->url) {
            $this->url = $url;
        }
    }
    
    /**
     * @return mixed
     */
    public function getError(): ?array
    {
        return $this->errors;
    }
    
    /**
     * @param mixed $errors
     */
    public function setError(array $error): void
    {
        if ($this->error) {
            throw new RuntimeException("Ошибка уже установлена");
        }
        if (!key_exists("text",$error) || !key_exists("code",$error)) {
            throw new RuntimeException("Отсутствуют обязательные ключи text или code");
        }
        $this->error = $error;
    }
    
    /**
     * @param string|null $key
     * @param string|null $default
     * @return array|string|null
     */
    public function getData(?string $key = null, ?string $default = null)
    {
        if ($key === null) {
            return $this->data;
        }
        return ArrayHelper::getValue($this->data, $key, $default);
    }
    
    /**
     * @param mixed $data
     */
    public function setData($data): void
    {
        if ($this->data) {
            throw new RuntimeException("Данные уже установлены");
        }
        $this->data = $data;
    }
    
    public function getIsSuccess(): bool
    {
        return !$this->error;
    }
    
    public function getIsError(): bool
    {
        return !$this->isSuccess;
    }
    
    public function print()
    {
        if (php_sapi_name() != "cli") {
            echo '<pre>';
        }
        
        echo $this->isSuccess?'Respone success:':'Response error:';
        echo PHP_EOL;
        
        if ($this->isError) {
            print_r($this->error);
        } else {
            print_r($this->data);
        }
        
        if (php_sapi_name() != "cli") {
            echo '</pre>';
        }
    }
    
    public function printInfo()
    {
        if (php_sapi_name() != "cli") {
            echo '<pre>';
        }
    
        echo $this->isSuccess?'Respone success:':'Response error:';
        echo PHP_EOL;
    
        print_r($this->info);
    
        if (php_sapi_name() != "cli") {
            echo '</pre>';
        }
    }
    
    public function printSendData()
    {
        if (php_sapi_name() != "cli") {
            echo '<pre>';
        }
    
        echo $this->isSuccess?'Respone success:':'Response error:';
        echo PHP_EOL;
    
        print_r($this->sendData);
    
        if (php_sapi_name() != "cli") {
            echo '</pre>';
        }
    }
}
