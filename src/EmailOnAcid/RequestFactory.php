<?php


namespace NetteEmailOnAcid\EmailOnAcid;


use EmailOnAcid\Authentication;
use EmailOnAcid\Exception\EmailOnAcidException;
use GuzzleHttp\Client;
use NetteEmailOnAcid\Tracy\EmailOnAcidPanel;
use NetteEmailOnAcid\Tracy\Log;

class RequestFactory extends \EmailOnAcid\Request\RequestFactory
{
	/**
	 * @var EmailOnAcidPanel
	 */
	private $panel;

	/**
	 * RequestFactory constructor.
	 * @param Client $client
	 * @param Authentication $authentication
	 * @param int $timeout
	 * @param EmailOnAcidPanel $acidPanel
	 */
	public function __construct(Client $client, Authentication $authentication, $timeout = 10, EmailOnAcidPanel $acidPanel)
	{
		parent::__construct($client, $authentication, $timeout);
		$this->panel = $acidPanel;
	}

	/**
	 * @param string $url
	 * @param array $data
	 * @return array
	 * @throws EmailOnAcidException
	 */
	public function delete(string $url, array $data = []): array
	{
		try {
			$error = false;
			$result = parent::delete($url, $data);
			return $result;
		} catch (EmailOnAcidException $exception) {
			$error = true;
			$result = 'Error! :' . $exception->getMessage();
			throw $exception;
		} finally {
			$this->logToPanel($url, RequestFactory::METHOD_DELETE, $data, $result, $error);
		}
	}

	/**
	 * @param string $url
	 * @param array $data
	 * @return array
	 * @throws EmailOnAcidException
	 */
	public function get(string $url, array $data = []): array
	{
		try {
			$error = false;
			$result = parent::get($url, $data);
			return $result;
		} catch (EmailOnAcidException $exception) {
			$error = true;
			$result = 'Error! :' . $exception->getMessage();
			throw $exception;
		} finally {
			$this->logToPanel($url, RequestFactory::METHOD_GET, $data, $result, $error);
		}
	}

	/**
	 * @param string $url
	 * @param array $data
	 * @return array
	 * @throws EmailOnAcidException
	 */
	public function post(string $url, array $data = []): array
	{
		try {
			$error = false;
			$result = parent::post($url, $data);
			return $result;
		} catch (EmailOnAcidException $exception) {
			$error = true;
			$result = 'Error! :' . $exception->getMessage();
			throw $exception;
		} finally {
			$this->logToPanel($url, RequestFactory::METHOD_POST, $data, $result, $error);
		}
	}

	/**
	 * @param string $url
	 * @param array $data
	 * @return array
	 * @throws EmailOnAcidException
	 */
	public function put(string $url, array $data = []): array
	{
		try {
			$error = false;
			$result = parent::put($url, $data);
			return $result;
		} catch (EmailOnAcidException $exception) {
			$error = true;
			$result = 'Error! :' . $exception->getMessage();
			throw $exception;
		} finally {
			$this->logToPanel($url, RequestFactory::METHOD_PUT, $data, $result, $error);
		}
	}

	/**
	 * @param string $url
	 * @param string $method
	 * @param array $data
	 * @param string|array $result
	 * @param bool $error
	 */
	private function logToPanel(string $url, string $method, array $data, $result, bool $error = false)
	{
		if (!is_array($result)) {
			$result = [$result];
		}
		$this->panel->addData(new Log($url, $method, $data, $result, $error));
	}

}