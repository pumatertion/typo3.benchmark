<?php

namespace TYPO3\Benchmark\Controller;

use TYPO3\Flow\Mvc\Controller\ActionController;

/**
 * Class StandardController
 *
 * @package TYPO3\Benchmark\Controller
 */
class StandardController extends ActionController {

	/**
	 * @return string
	 */
	public function indexAction() {
		return 'Hello World!';
	}
}
