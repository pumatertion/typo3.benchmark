<?php
namespace TYPO3\Benchmark\Composer;

use Composer\Script\CommandEvent;
use TYPO3\Flow\Utility\Files;

/**
 * Class InstallerScripts
 *
 * @package TYPO3\Benchmark\Composer
 */
class InstallerScripts {

	/**
	 * @param CommandEvent $event
	 * @return void
	 */
	static public function postUpdateAndInstall(CommandEvent $event) {
		Files::copyDirectoryRecursively('Packages/Framework/TYPO3.Benchmark/Resources/Private/Installer/Distribution/Defaults', './', FALSE, TRUE);
	}
}
