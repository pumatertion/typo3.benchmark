<?php
namespace TYPO3\Benchmark;

/**
 *
 */
class Package extends \TYPO3\Flow\Package\Package {

	/**
	 * @param \TYPO3\Flow\Core\Bootstrap $bootstrap
	 */
	public function boot(\TYPO3\Flow\Core\Bootstrap $bootstrap) {
		$bootstrap->registerRequestHandler(new \TYPO3\Benchmark\Http\RequestHandler($bootstrap));
		$bootstrap->setPreselectedRequestHandlerClassName('TYPO3\Benchmark\Http\RequestHandler');
	}


}