<?php
/**
 * Created by PhpStorm.
 * User: laugusto
 * Date: 24/10/18
 * Time: 20:00
 */

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require './vendor/autoload.php';

/**
 * Container Resources do Slim
 * Aqui dentro dele vamos carregar todos as dependencias
 * da nossa aplicação que vão ser consumidas durante a execução
 * da nossa API
 */
$container = new \Slim\Container;

$isDevMode = true;

/**
 * Diretorio de Entidade e Metadados do Doctrine
 */
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/src/Models/Entity"), $isDevMode);

/**
 * Array de configuração da nossa conexão
 * com o banco de dados
 */
//$conn = array(
//    'driver' => 'mysql',
//    'host' => '127.0.0.1',
//    'user' => 'api_slim',
//    'pass' => 'api_slim',
//    'dbname' => 'api_slim'
//);
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

$app = new \Slim\App($container);