<?php


namespace NetteEmailOnAcid\Nette;

use EmailOnAcid\Authentication;
use EmailOnAcid\Request\RequestFactory;
use EmailOnAcid\Tests\Api;
use GuzzleHttp\Client;
use Nette\DI\CompilerExtension;
use Nette\DI\ContainerBuilder;
use Nette\DI\MissingServiceException;
use Nette\DI\ServiceDefinition;
use NetteEmailOnAcid\Exception\ConfigurationException;
use NetteEmailOnAcid\Tracy\EmailOnAcidPanel;

class EmailOnAcidExtension extends CompilerExtension
{
	private $defaults = [
		'timeout' => 10
	];

	public function loadConfiguration()
	{
		$this->validateConfiguration();
		$this->config += $this->defaults;

		$builder = $this->getContainerBuilder();

		$this->defineDebugPanel($builder);
		$this->defineHttpClient($builder);
		$this->defineAuthentication($builder);
		$this->defineRequestFactory($builder);
		$this->defineTestsApi($builder);
		$this->defineEmailClientsApi($builder);
		$this->defineEmailTesting($builder);
		$this->defineSpamTesting($builder);
	}

	/**
	 * @throws ConfigurationException
	 */
	private function validateConfiguration()
	{
		if (!isset($this->config['apiKey'])) {
			throw new ConfigurationException('Missing required api key configuration');
		}
		if (!isset($this->config['password'])) {
			throw new ConfigurationException('Missing required password');
		}
	}

	private function defineDebugPanel(ContainerBuilder $builder)
	{
		try {
			if ($builder->getDefinitionByType(\Tracy\Bar::class)) {
				$builder
					->addDefinition($this->prefix('emailOnAcidPanel'))
					->setClass(EmailOnAcidPanel::class);
				if ($this->config)
					$builder->getDefinition('tracy.bar')
						->addSetup('addPanel', ['@' . $this->prefix('emailOnAcidPanel')]);
			}
		} catch (MissingServiceException $exception) {

		}

	}

	private function defineHttpClient(ContainerBuilder $builder)
	{
		$builder->addDefinition($this->prefix('httpClient'))
			->setClass(Client::class)
			->setFactory(Client::class);
	}

	private function defineAuthentication(ContainerBuilder $builder)
	{
		$builder->addDefinition($this->prefix('authentication'))
			->setClass(Authentication::class)
			->setFactory(
				Authentication::class,
				[
					$this->config['apiKey'],
					$this->config['password']
				]
			);
	}

	private function defineRequestFactory(ContainerBuilder $builder)
	{
		$builder->addDefinition($this->prefix('requestFactory'))
			->setClass(RequestFactory::class)
			->setFactory(
				\NetteEmailOnAcid\EmailOnAcid\RequestFactory::class,
				[
					'@' . $this->prefix('httpClient'),
					'@' . $this->prefix('authentication'),
					$this->config['timeout']
				]
			);
	}

	private function defineTestsApi(ContainerBuilder $builder)
	{
		$testsApi = new ServiceDefinition();
		$testsApi->setClass(Api::class);
		$testsApi->setFactory(
			Api::class,
			[
				'@' . $this->prefix('requestFactory')
			]
		);

		$builder->addDefinition($this->prefix('tests'), $testsApi);
	}

	private function defineEmailClientsApi(ContainerBuilder $builder)
	{
		$emailClientsApi = new ServiceDefinition();
		$emailClientsApi->setClass(\EmailOnAcid\EmailClients\Api::class);
		$emailClientsApi->setFactory(
			\EmailOnAcid\EmailClients\Api::class,
			[
				'@' . $this->prefix('requestFactory')
			]
		);

		$builder->addDefinition($this->prefix('emailClients'), $emailClientsApi);
	}

	private function defineEmailTesting(ContainerBuilder $builder)
	{
		$emailTestsApi = new ServiceDefinition();
		$emailTestsApi->setClass(\EmailOnAcid\EmailTesting\Api::class);
		$emailTestsApi->setFactory(
			\EmailOnAcid\EmailTesting\Api::class
			,
			[
				'@' . $this->prefix('requestFactory')
			]
		);

		$builder->addDefinition($this->prefix('emailTesting'), $emailTestsApi);
	}

	private function defineSpamTesting(ContainerBuilder $builder)
	{
		$spamTestingApi = new ServiceDefinition();
		$spamTestingApi->setClass(\EmailOnAcid\SpamTesting\Api::class);
		$spamTestingApi->setFactory(
			\EmailOnAcid\SpamTesting\Api::class,
			[
				'@' . $this->prefix('requestFactory')
			]
		);

		$builder->addDefinition($this->prefix('spamTesting'), $spamTestingApi);
	}

}