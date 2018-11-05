<?php
/**
 * Created by PhpStorm.
 * User: laugusto
 * Date: 27/10/18
 * Time: 07:02
 */
namespace App\v1\Controllers;

use \Psr\Http\Message\ResponseInterface as Response;
use \Psr\Http\Message\RequestInterface as Request;
use App\Models\Entity\Book;

/**
 * Class BookController
 * @package App\v1\Controllers
 */
class BookController
{
    /**
     * Container Class
     * @var [object]
     */
    protected $container;

    /**
     * Undocumented function
     * @param [object] $container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * Listagem de Livros
     * @param [type] $request
     * @param [type] $response
     * @param [type] $args
     * @return Response
     */
    public function listBook (Request $request, Response $response, $args) {
        $entityManager = $this->container->get('em');
        $booksRepository = $entityManager->getRepository('App\Models\Entity\Book');
        $books = $booksRepository->findAll();
        $return = $response->withJson($books, 200);

        return $return;
    }

    /**
     * Cria um livro
     * @param [type] $request
     * @param [type] $response
     * @params [type] $args
     * @return Response
     */
    public function createBook (Request $request, Response $response, $args) {
        $params = (object) $request->getParams();

        /**
         * Instância da nossa Entidade preenchida com
         * nossos paramentros do post
         */
        $book = (new Book())->setAuthor($params->author)->setName($params->name);

        /**
         * Registra a criação do livro
         */
        $logger = $this->container->get('logger');
        $logger->info('Book Created!', $book);

        /**
         * Pega a EntityManager do nosso
         * container
         */
        $entityManager = $this->container->get('em');
        /**
         * Persiste a entidade no banco de dados
         */
        $entityManager->persist($book);
        $entityManager->flush();

        $return = $response->withJson($book, 201);

        return $return;
    }

    /**
     * Exibe as informações de um livro
     * @param [type] $request
     * @param [type] $response
     * @param [type] $args
     * @return Response
     */
    public function viewBook (Request $request, Response $response, $args) {
        $id = (int) $args['id'];

        $entityManager = $this->container->get('em');
        $booksRespository = $entityManager->getRepository('App\Models\Entity\Book');
        $book = $booksRespository->find($id);

        /**
         * Verificar se existe um livro com
         * o ID informando
         */
        if (!$book) {
            $logger = $this->container->get('logger');
            $logger->warning("Book {$id} not found", $book);
            throw new \Exception('Book not Found', 404);
        }

        $return = $response->withJson($book, 200);

        return $return;
    }

    /**
     * Atualizar um Livro
     * @param [type] $request
     * @param [type] $response
     * @param [type] $args
     * @return Response
     */
    public function updateBook (Request $request, Response $response, $args) {
        $id = (int) $args['id'];

        /**
         * Encontra o livro no banco
         */
        $entityManager = $this->container-get('em');
        $booksRepository = $entityManager->getRepository('App\Models\Entity\Book');
        $book = $booksRepository->find($id);

        /**
         * Verificar ser o livro existe
         */
        if (!$book) {
            $logger = $this->container->get('logger');
            $logger->warning("Book {$id} not Found", $book);
            throw new \Exception('Book not Found', 404);
        }

        /**
         * Atualizar e Persiste o Livro com
         * as informações passadas por paramentro
         */
        $book->setName($request->getParams('name'))->setAuthor($request->getParams('author'));

        /**
         * Persiste a entidade no banco de dados
         */
        $entityManager->persist($book);
        $entityManager->flush();

        $return = $response->withJson($book, 200);

        return $return;
    }

    /**
     * Delete um livro
     * @param [type] $request
     * @param [type] $response
     * @param [type] $args
     * @return Response
     */
    public function deleteBook (Request $request, Response $response, $args) {
        $id = (int) $args['id'];

        /**
         * Encontra o livro no banco
         */
        $entityManager = $this->container->get('em');
        $booksRepository = $entityManager->getRepository('App\Models\Entity\Book');
        $book = $booksRepository->find($id);

        /**
         * Verificar ser o livro existe
         */
        if (!$book) {
            $logger = $this->container->get('logger');
            $logger->warning("Book {$id} not Found", $book);
            throw new \Exception('Book not found', 404);
        }

        /**
         * Remove o livro do banco de dados
         */
        $entityManager->remove($book);
        $entityManager->flush();

        $return = $response->withJson(['msg' => "Removido o livro {$id}"], 200);

        return $return;
    }
}
