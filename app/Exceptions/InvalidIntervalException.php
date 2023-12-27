<?php

namespace App\Exceptions;

use Exception;

/**
 * Exceção lançada quando um intervalo de valor inválido é enviado, como uma data final menor que uma data inicial.
 */
class InvalidIntervalException extends Exception
{
    
}
