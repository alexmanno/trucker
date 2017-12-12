<?php


namespace Trucker\Responses;

/**
 * Provides a common type hint for responses.
 *
 * Classes that implement this interface both utilize the magic __call()
 * to delegate method calls to Guzzle.  Due to this magic, common methods
 * are not outlined in this interface to avoid conflicts/implementation.
 *
 * @method int getStatusCode
 *
 * @codeCoverageIgnore
 *
 * Coverage is ignored here since this class has no functionality by itself.
 */
class BaseResponse
{
    protected $response;

    /**
     * BaseResponse constructor.
     *
     * @param $response
     */
    public function __construct($response = null)
    {
        $this->response = $response;
    }
}
