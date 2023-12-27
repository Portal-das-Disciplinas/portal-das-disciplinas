<?php

namespace App\Exceptions;

use Exception;

/**
 * Exceção lançada quando o limite de requisições por hora à API Sistemas é atingido.
 * Este limite é de 5.000 requisições por hora.
 */
class APISistemasRequestLimitException extends Exception
{
    //
}
