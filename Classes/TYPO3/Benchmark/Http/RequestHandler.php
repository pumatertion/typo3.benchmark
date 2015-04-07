<?php
namespace TYPO3\Benchmark\Http;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Core\Booting\Step;

/**
 * Reduced request handler that does not boot some core components of flow that are not needed for the benchmark here.
 *
 * @Flow\Scope("singleton")
 * @Flow\Proxy(false)
 */
class RequestHandler extends \TYPO3\Flow\Http\RequestHandler implements \TYPO3\Flow\Http\HttpRequestHandlerInterface {

	/**
	 * Returns the priority - how eager the handler is to actually handle the
	 * request.
	 *
	 * @return integer The priority of the request handler.
	 * @api
	 */
	public function getPriority() {
		return 200;
	}

	/**
	 * This request handler can handle any web request.
	 *
	 * @return boolean If the request is a web request, TRUE otherwise FALSE
	 * @api
	 */
	public function canHandleRequest() {
		return (PHP_SAPI !== 'cli' && strpos($_SERVER['REQUEST_URI'], '/flow/benchmark') !== FALSE);
	}

	public function handleRequest() {
		$this->request = \TYPO3\Flow\Http\Request::createFromEnvironment();
		$this->response = new \TYPO3\Flow\Http\Response();

		$this->boot();
		$this->resolveDependencies();

		$actionRequest = new \TYPO3\Flow\Mvc\ActionRequest($this->request);
		$actionRequest->setControllerPackageKey('TYPO3.Benchmark');
		$actionRequest->setControllerName('Standard');
		$actionRequest->setControllerActionName('index');

		$dispatcher = $this->bootstrap->getObjectManager()->get(\TYPO3\Flow\Mvc\Dispatcher::class);
		$dispatcher->dispatch($actionRequest, $this->response);

		$this->response->send();

		$this->exit->__invoke();
	}

	/**
	 * Boots up Flow to runtime
	 *
	 * @return void
	 */
	protected function boot() {
		$sequence = $this->bootstrap->buildEssentialsSequence('runtime');
		$sequence->addStep(new Step('typo3.flow:objectmanagement:proxyclasses', array('TYPO3\Flow\Core\Booting\Scripts', 'initializeProxyClasses')), 'typo3.flow:systemlogger');
		$sequence->addStep(new Step('typo3.flow:classloader:cache', array('TYPO3\Flow\Core\Booting\Scripts', 'initializeClassLoaderClassesCache')), 'typo3.flow:objectmanagement:proxyclasses');
		$sequence->addStep(new Step('typo3.flow:objectmanagement:runtime', array('TYPO3\Flow\Core\Booting\Scripts', 'initializeObjectManager')), 'typo3.flow:classloader:cache');

		if ($this->bootstrap->getContext()->isDevelopment()) {
			$sequence->addStep(new Step('typo3.flow:reflectionservice', array('TYPO3\Flow\Core\Booting\Scripts', 'initializeReflectionService')), 'typo3.flow:objectmanagement:runtime');
		}

		$sequence->invoke($this->bootstrap);
	}
}
