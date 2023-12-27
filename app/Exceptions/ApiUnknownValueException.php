<?php

namespace App\Exceptions;

use Exception;
/**
 * Exceção lançada quando um valor obtido pela API Sistemas é desconhecido na aplicação do portal, como
 * o valor de uma situação do aluno é desconhecido pelo Portal das Disciplinas.
 */
class ApiUnknownValueException extends Exception
{
    
}
