<?php


namespace App\Contracts;


interface SendResponse
{
    /**
     * Send success response
     *
     * @param string $message
     * @param array $data
     * @return mixed
     */
    public function success($message, $data);

    /**
     * Send error response
     *
     * @param string $message
     * @param array $errors
     * @param int $status
     * @return mixed
     */
    public function error($message, $errors, $status);
}
