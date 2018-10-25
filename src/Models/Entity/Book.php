<?php
/**
 * Created by PhpStorm.
 * User: laugusto
 * Date: 24/10/18
 * Time: 20:10
 */

namespace App\Models\Entity;

/**
 * @Entity @Table(name="books")
*/
class Book
{
    /**
     * @var int
     * @Id @Column(type="integer")
     * @GeneratedValue
     */
    public $id;

    /**
     * @var string
     * @Column(type="string")
    */
    public $name;

    /**
     * @var string
     * @Column(type="string")
    */
    public $author;

    /**
     * @return int id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string author
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return App\Models\Entity\Book
     */
    public function setName($name)
    {
        if (!name && is_string($name)) {
            throw new \InvalidArgumentException('Book name is required', 400);
        }

        $this->name = $name;
        return $this;
    }

    /**
     * @return Book()
     */
    public function setAuthor($author)
    {
        if (!author && is_string($author)) {
            throw new \InvalidArgumentException('Author is required', 400);
        }

        $this->author = $author;
        return $this;
    }
}
