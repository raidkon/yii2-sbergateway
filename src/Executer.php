<?php
/**
 * Created by PhpStorm.
 * User: Raidkon
 * Date: 28.02.2019
 * Time: 20:48
 */

namespace raidkon\yii2\sbergateway;


use yii\base\BaseObject;
use Exception;
use yii\base\InvalidConfigException;
use yii\debug\models\timeline\Svg;

class Executer extends BaseObject
{
    /** @var Request */
    protected $request;
    
    /** @var GateWay */
    protected $component;
    
    /**
     * Executer constructor.
     * @param array $config
     * @throws InvalidConfigException
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);
        if (!$this->component) {
            throw new InvalidConfigException("Require attr: component");
        }
    }
    
    /**
     * @param GateWay $component
     */
    public function setComponent(GateWay $component): void
    {
        $this->component = $component;
    }
    
    /**
     * @param Request $request
     */
    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }
    
    /**
     * @return Response
     * @throws InvalidConfigException
     */
    public function execute(): Response
    {
        if (!$this->component->authUserName || !$this->component->authPassword) {
            throw new InvalidConfigException("У компонента Sberbank (id: " . $this->component->getComponentId() . ') не установлен параметры авторизации');
        }
        
        try {
    
            $this->request->setData('userName', $this->component->authUserName);
            $this->request->setData('password', $this->component->authPassword);
            
            
            $url = $this->createUrl();
            $sendData = $this->request->data;
    
            $curl = curl_init($url);
            curl_setopt_array($curl, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => http_build_query($sendData),
                CURLOPT_SSL_VERIFYHOST => $this->component->mode === GateWay::MODE_TEST ? 0 : 2
            ]);
            $response = curl_exec($curl);
            $info = curl_getinfo($curl);
            $error = curl_error($curl);
            $errno = curl_errno($curl);
            if (!$response) {
                return new Response([
                    'error' => ['text' => "Ошибка запроса CURL", 'code' => Response::ERROR_CURL, 'curl_error' => $error, 'curl_errno' => $errno],
                    'data' => $response,
                    'info' => $info,
                    'sendData' => $sendData
                ]);
            }
            $json = @json_decode($response, 1);
    
            if (($errno = json_last_error()) !== JSON_ERROR_NONE) {
        
                switch ($errno) {
                    case JSON_ERROR_DEPTH:
                        $error = 'Достигнута максимальная глубина стека';
                        break;
                    case JSON_ERROR_STATE_MISMATCH:
                        $error = 'Некорректные разряды или несоответствие режимов';
                        break;
                    case JSON_ERROR_CTRL_CHAR:
                        $error = 'Некорректный управляющий символ';
                        break;
                    case JSON_ERROR_SYNTAX:
                        $error = 'Синтаксическая ошибка, некорректный JSON';
                        break;
                    case JSON_ERROR_UTF8:
                        $error = 'Некорректные символы UTF-8, возможно неверно закодирован';
                        break;
                    default:
                        $error = 'Неизвестная ошибка';
                        break;
                }
        
                return new Response([
                    'error' => ['text' => "Ошибка парсинга JSON", 'code' => Response::ERROR_JSON, 'json_error' => $error, 'json_errno' => $errno],
                    'data' => $response,
                    'info' => $info,
                    'sendData' => $sendData
                ]);
            }
    
    
            return new Response([
                'data' => $json,
                'info' => $info,
                'sendData' => $sendData
            ]);
        } catch (Exception $e) {
            return new Response([
                'error' => ['text' => "Не предвиденная ошибка", 'code' => Response::ERROR_EXCEPTION, 'json_error' => $e->getMessage(), 'json_errno' => $e->getCode(),'trace' => $e->getTraceAsString()],
            ]);
        }
    }
    
    protected function createUrl()
    {
        return $this->component->getActualUrl() . $this->request->method;
    }
}


