<?php

namespace Hito\Core\Module\Exceptions;

use Exception;

class ModuleNotFoundException extends Exception
{
    protected $message = 'Module not found';
}
