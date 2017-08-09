<?php


namespace NetteEmailOnAcid\Tracy;


class Log
{
	/**
	 * @var string
	 */
	private $url;

	/**
	 * @var string
	 */
	private $httpMethod;

	/**
	 * @var array
	 */
	private $requestBody;

	/**
	 * @var array
	 */
	private $responseBody;

	/**
	 * @var bool
	 */
	private $error;

	/**
	 * Log constructor.
	 * @param string $url
	 * @param string $httpMethod
	 * @param array $requestBody
	 * @param array $responseBody
	 * @param bool $isError
	 */
	public function __construct(string $url, string $httpMethod, array $requestBody, array $responseBody, bool $isError = false)
	{
		$this->url = $url;
		$this->httpMethod = $httpMethod;
		$this->requestBody = $requestBody;
		$this->responseBody = $responseBody;
		$this->error = $isError;
	}

	/**
	 * @return string
	 */
	public function getUrl(): string
	{
		return $this->url;
	}

	/**
	 * @return string
	 */
	public function getHttpMethod(): string
	{
		return $this->httpMethod;
	}

	/**
	 * @return array
	 */
	public function getRequestBody(): array
	{
		return $this->requestBody;
	}

	/**
	 * @return array
	 */
	public function getResponseBody(): array
	{
		return $this->responseBody;
	}

	/**
	 * @return bool
	 */
	public function isError(): bool
	{
		return $this->error;
	}




}