<?php
/**
 * Created by PhpStorm.
 * User: Raidkon
 * Date: 28.02.2019
 * Time: 20:28
 */

namespace raidkon\yii2\sbergateway;


use Yii;
use yii\base\Application;
use yii\base\BaseObject;
use yii\base\BootstrapInterface;
use yii\base\InvalidConfigException;
use yii\console\Application as ConsoleApp;
use yii\helpers\Inflector;
use yii\web\Application as WebApp;

class GateWay extends BaseObject implements BootstrapInterface
{
    public const MODE_PRODUCTION = 'production';
    public const MODE_TEST = 'test';
    
    public $urlGateway = [
        GateWay::MODE_PRODUCTION => 'https://securepayments.sberbank.ru/payment/rest/',
        GateWay::MODE_TEST => 'https://3dsec.sberbank.ru/payment/rest/',
    ];
    
    public $mode = GateWay::MODE_TEST;
    
    public $commandClass = CommandController::class;
    public $commandOptions = [];
    
    public $authUserName;
    public $authPassword;
    
    public $executeClass = Executer::class;
    
    /**
     * @return string command id
     * @throws
     */
    public function getComponentId(): string
    {
        foreach (Yii::$app->getComponents(false) as $id => $component) {
            if ($component === $this) {
                return Inflector::camel2id($id);
            }
        }
        throw new InvalidConfigException('Queue must be an application component.');
    }
    
    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     * @throws InvalidConfigException
     */
    public function bootstrap($app): void
    {
        if ($app instanceof ConsoleApp) {
            $app->controllerMap[$this->getComponentId()] = [
                    'class' => $this->commandClass,
                    'component' => $this,
                ] + $this->commandOptions;
        }
    }
    
    public function getActualUrl(): string
    {
        return $this->urlGateway[$this->mode];
    }
    
    public function execute(Request $request): Response
    {
        /** @var Executer $executer */
        $executer = new $this->executeClass(['component' => $this]);
        $executer->setRequest($request);
        return $executer->execute();
    }
}
