<?php

namespace App\Exceptions;

/**
 * Class RandomUserRequestException
 * @package App\Exceptions
 * @author  Mark Joshua Fajardo <mjt.fajardo@gmail.com>
 * @since   2023.05.25
 */
class RandomUserRequestException extends BaseException
{
    /**
     * RandomUserRequestException constructor.
     * @param string $message
     * @param int    $code
     */
    public function __construct(int $code = 422, string $message = '')
    {
        parent::__construct($code, $message);
    }
}
