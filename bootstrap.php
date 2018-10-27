<?php
/**
 * Created by PhpStorm.
 * User: laugusto
 * Date: 24/10/18
 * Time: 20:00
 */

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Psr7Middlewares\Middleware\TrailingSlash;
use Monolog\Logger;

require './vendor/autoload.php';

/**
 * Configurações
 */
$configs = [
    'settings' => [
        'displayErrorDetail' => true,
    ]
];

/**
 * Container Resources do Slim
 * Aqui dentro dele vamos carregar todos as dependencias
 * da nossa aplicação que vão ser consumidas durante a execução
 * da nossa API
 */
$container = new \Slim\Container($configs);

/**
 * Converte os Exception dento da aplicação
 * em respostas JSON
*/
$container['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
        $statusCode = $exception->getCode() ? $exception->getCode() : 500;

        return $c['response']->withStatus($statusCode)
            ->withJson(['message' => $exception->getMessage()], $statusCode);
    };
};

/**
 * Converte os Exceptions em Erros 405 - Not Allowed
*/
$container['notAllowedHandler'] = function ($c) {
    return function ($request, $response, $methods) use ($c) {
        return $c['response']
            ->withStatus(405)
            ->withHeader('Allow', implode(', ', $methods))
            ->withHeader('Access-Control-Allow-Methods', implode(',', $methods))
            ->withJson(['message' => 'Method not allowed; Method must be one of: '.implode(', ', $methods)], 405);
    };
};

/**
 * Converte os Exceptions em Erros 405 - Not Allowed
*/
$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $c['response']
            ->withStatus(404)
            ->withJson(['message' => 'Page not Found']);
    };
};

/**
 * Serviço de Logging em Arquivo
*/
$container['logger'] = function ($c) {
    $logger = new Monolog\Logger('books-microservice');
    $logfile = __DIR__ . '/log/books-microservice.log';
    $stream = new Monolog\Handler\StreamHandler($logfile, Monolog\Logger::DEBUG);
    $fingersCrossed = new Monolog\Handler\FingersCrossedHandler(
        $stream,
        Monolog\Logger::INFO
    );
    $logger->pushHandler($fingersCrossed);

    return $logger;
};

$isDevMode = true;

/**
 * Diretorio de Entidade e Metadados do Doctrine
 */
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/src/Models/Entity"), $isDevMode);

/**
 * Array de configuração da nossa conexão
 * com o banco de dados
 */
$conn = array(
    'driver' => 'pdo_sqlite',
    'path' => './db.sqlite'
);

/**
 * Instância do Entity Manager
 */
$entityManager = EntityManager::create($conn, $config);

/**
 * Coloca o Entity manager dentro
 * do container com o nome de em [Entity Manager]
 */
$container['em'] = $entityManager;

/**
 * Application Instance
 */

$app = new \Slim\App($container);

/**
 * @Middleware Tratamento da / do Request
 * true - Adiciona a / no final da URL
 * false - remove a / no final da URL
*/
$app->add(new TrailingSlash(false));
