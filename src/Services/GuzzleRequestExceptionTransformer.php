<?php

namespace HealiosTrial\Services;

use GuzzleHttp\Exception\RequestException;

class GuzzleRequestExceptionTransformer
{
    /**
     * @param RequestException $exception
     * @return string
     */
    public static function toString(RequestException $exception): string
    {
        $body = (string)$exception->getResponse()->getBody();
        $body = json_decode($body, true);

        if (is_array($body)) {
            if (array_key_exists('errors', $body)) {
                return (string)$body['errors'];
            }

            if (array_key_exists('message', $body)) {
                return (string)$body['message'];
            }
        }

        return $exception->getMessage();
    }
}
