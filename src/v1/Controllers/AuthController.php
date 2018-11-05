<?php
/**
 * Created by PhpStorm.
 * User: laugusto
 * Date: 27/10/18
 * Time: 07:02
 */

namespace App\v1\Controllers;

use Psr\Container\ContainerInterface;
use \Psr\Http\Message\RequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Firebase\JWT\JWT;

/**
 * Class AuthController
 * @package App\v1\Controllers
 */
class AuthController
{
    /**
     * Container
     * @var object s
     */
    protected $container;

    /**
     * Undocumented function
     * @param ContainerInterface $container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * Invokable Method
     * @param Request $request
     * @param Response $response
     * @param [type] $args
     * @return void
     */
    public function __invoke(Request $request, Response $response, $args)
    {
        /**
         * JWT Key
         */
        $key = $_ENV['KEY_SECRET'];
        $token = array(
            'user' => '@laugustofrontend',
            'twitter' => 'https://twitter.com/laugustofront',
            'github' => 'https://github.com/laugstofrontend'
        );

        $jwt = JWT::encode($token, $key);

        return $response->withJson(['auth' => $jwt], 200);
    }
}
