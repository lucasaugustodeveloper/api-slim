<?php
/**
 * Created by PhpStorm.
 * User: laugusto
 * Date: 24/10/18
 * Time: 20:26
 */

require_once './bootstrap.php';

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);
