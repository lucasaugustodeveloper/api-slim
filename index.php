<?php
/**
 * Created by PhpStorm.
 * User: laugusto
 * Date: 24/10/18
 * Time: 19:24
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use App\Models\Entity\Book;

require 'bootstrap.php';

/**
 * Inicio do bang ;)
 * @var string
 */
$app->get('/', function (Request $request, Response $response) use ($app) {
    $response->getBody()->write('Welcome this api Slim Framework');
    return $response;
});

/**
 * Lista de todos os livros
 */
$app->get('/book', function (Request $request, Response $response) use ($app) {
    $entityManager = $this->get('em');
    $booksRepository = $entityManager->getRepository('App\Models\Entity\Book');
    $books = $booksRepository->findAll();

    $return = $response->withJson($books, 200);

    return $return;
});

/**
 * Retornando mas informações do livro informado pelo id
 * @request curl -X GET http://localhost:8000/book/1
 */
$app->get('/book/{id}', function (Request $request, Response $response) use ($app) {
    $route = $request->getAttribute('route');
    $id = $route->getArgument('id');

    $entityManager = $this->get('em');
    $booksRepository = $entityManager->getRepository('App\Models\Entity\Book');
    $book = $booksRepository->find($id);

    /**
     * Verificar ser o livro existe
     */
    if (!book) {
        throw new Exception("Book not found", 404);
    }

    $return = $response->withJson($book, 200);

    return $return;
});

/**
 * Cadastra um novo livro <Livro></Livro>
 * @request curl -x POST http://localhost:8000/book -H "Content-type: application/json"
 *  -d { "name": "Aplicações web real-time com Node.js", "author": "Caio Ribeiro Pereira" }
 */
$app->post('/book', function (Request $request, Response $response) use ($app) {
    $params = (object) $request->getParams();

    /**
     * Pega o Entity do nosso Container
     */
    $entityManager = $this->get('em');

    /**
     * Instância da nossa Entidade
     * preenchida com nossos paramentros
     * do post
     */
    $book = (new Book())->setName($params->name)
        ->setAuthor($params->author);

    /**
     * Persiste a entidade no
     * banco de dados
     */
    $entityManager->persist($book);
    $entityManager->flush();

    $return = $response->withJson($book, 201);

    return $return;
});

/**
 * Atualizar os dados do livro
 * @request curl -X PUT http://localhost:8000/book/1 -d '{"name": "Deuses Americanos", "author":"Neil Gaiman"}'
 */
$app->put('/book/{id}', function (Request $request, Response $response) use ($app) {
    /**
     * Pega o ID do livro informado na url
     */
    $route = $request->getAttribute('route');
    $id = $route->getArgument('id');

    /**
     * Encontra o livro no Banco
     */
    $entityManager = $this->get('em');
    $booksRepository = $entityManager->getRepository('App\Models\Entity\Book');
    $book = $booksRepository->find($id);
    
    /**
     * Verificar ser o livro existe
     */
    if (!book) {
        throw new Exception("Book not found", 404);
    }

    /**
     * Atualiza e Persiste o Livro
     * com os parâmentros recebidos no request
     */
    $book->setName($request->getParam('name'))
        ->setAuthor($request->getParam('author'));

    $entityManager->persist($book);
    $entityManager->flush();

    $return = $response->withJson($book, 200);

    return $return;
});

/**
 * Deleta o livro informado pelo ID
 * @request curl -X DELETE http://localhost:8000/4
 */
$app->delete('/book/{id}', function (Request $request, Response $response) use ($app) {
    /**
     * Pega o ID do livro informado na URL
     */
    $route = $request->getAttribute('route');
    $id = $route->getArgument('id');

    /**
     * Encontra o Livro no Banco
     */
    $entityManager = $this->get('em');
    $booksRepository = $entityManager->getRepository('App\Models\Entity\Book');
    $book = $booksRepository->find($id);

    /**
     * Verificar ser o livro existe
     */
    if (!book) {
        throw new Exception("Book not found", 404);
    }

    /**
     * Remove a entidade
     */
    $entityManager->remove($book);
    $entityManager->flush();

    $return = $response->withJson(['msg' => "Deletando o livro {$id}"], 204);

    return $return;
});

$app->run();
