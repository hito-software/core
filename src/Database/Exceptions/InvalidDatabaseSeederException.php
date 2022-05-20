<?php

namespace Hito\Core\Database\Exceptions;

use Exception;

class InvalidDatabaseSeederException extends Exception
{
    protected $message = 'The database seeder is invalid';
}
