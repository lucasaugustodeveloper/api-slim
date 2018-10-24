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
     * @return Book()
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Book()
     */
    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }
}
