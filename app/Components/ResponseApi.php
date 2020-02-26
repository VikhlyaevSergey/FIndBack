<?php

namespace App\Components;

class ResponseApi
{
    /**
     * headers
     *
     * @var array
     */
    private $headers;

    /**
     * is success
     *
     * @var bool
     */
    private $success;

    /**
     * error number
     *
     * @var int
     */
    private $error;

    /**
     * error message
     *
     * @var string
     */
    private $message;

    /**
     * response data
     *
     * @var array
     */
    private $response;

    /**
     * ApiResponse constructor.
     *
     * @param bool  $success
     * @param int   $error
     * @param string $message
     * @param array $response
     */
    public function __construct(array $response = [], bool $success = true, int $error = NULL, string $message = '')
    {
        $this->setHeaders(
            [
                'Content-type'                 => 'application/json; charset=utf-8',
                'Access-Control-Allow-Origin'  => '*',
                'Access-Control-Allow-Headers' => 'Origin, X-Requested-With, Content-Type, Accept, Authorization',
                'Access-Control-Allow-Methods' => 'POST, GET, OPTIONS, PUT, PATCH, DELETE',
            ]);
        $this->setSuccess($success);
        $this->setError($error);
        $this->setMessage($message);
        $this->setResponse($response);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function get()
    {
        return response()->json(
            [
                'success'  => $this->success,
                'error'    => $this->error,
                'message'  => !$this->message ? NULL : $this->message,
                'response' => !$this->response || !$this->success ? NULL : $this->response,
            ], 200, $this->headers, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param bool $success
     *
     * @return $this
     */
    public function setSuccess(bool $success)
    {
        $this->success = $success;

        return $this;
    }

    /**
     * @param int $error
     *
     * @return $this
     */
    public function setError(int $error = NULL)
    {
        $this->error = $error;

        return $this;
    }

    /**
     * @param array $message
     *
     * @return $this
     */
    public function setMessage(string $message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @param array $response
     *
     * @return $this
     */
    public function setResponse(array $response)
    {
        $this->response = $response;

        return $this;
    }

    /**
     * @param array $headers
     *
     * @return $this
     */
    public function setHeaders(array $headers)
    {
        foreach ($headers as $key => $value) {
            $this->headers[ $key ] = $value;
        }

        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return $this
     */
    public function addHeader(string $key, string $value)
    {
        $this->headers[ $key ] = $value;

        return $this;
    }

    /**
     * @param string $key
     *
     * @return $this
     */
    public function dropHeader(string $key)
    {
        if (isset($this->headers[ $key ])) {
            unset($this->headers[ $key ]);
        }

        return $this;
    }
}