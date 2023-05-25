<?php

namespace App\Exceptions;

use App\Constants\ApiConstants;
use Exception;

/**
 * Class BaseException
 * @package App\Exceptions
 * @author  Mark Joshua Fajardo <mjt.fajardo@gmail.com>
 * @since   2023.05.25
 */
class BaseException extends Exception
{

    /**
     * BaseException constructor.
     * @param int    $code
     * @param string $message
     */
    public function __construct(int $code = 500, string $message = '')
    {
        parent::__construct($message, $code);
    }
}
