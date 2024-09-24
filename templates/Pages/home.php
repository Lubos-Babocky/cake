<?php

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.10.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 * @var \App\View\AppView $this
 */
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Http\Exception\NotFoundException;

//$this->disableAutoLayout();

$checkConnection = function (string $name) {
	$error = null;
	$connected = false;
	try {
		ConnectionManager::get($name)->getDriver()->connect();
		// No exception means success
		$connected = true;
	} catch (Exception $connectionError) {
		$error = $connectionError->getMessage();
		if (method_exists($connectionError, 'getAttributes')) {
			$attributes = $connectionError->getAttributes();
			if (isset($attributes['message'])) {
				$error .= '<br />' . $attributes['message'];
			}
		}
		if ($name === 'debug_kit') {
			$error = 'Try adding your current <b>top level domain</b> to the
                <a href="https://book.cakephp.org/debugkit/5/en/index.html#configuration" target="_blank">DebugKit.safeTld</a>
            config and reload.';
			if (!in_array('sqlite', \PDO::getAvailableDrivers())) {
				$error .= '<br />You need to install the PHP extension <code>pdo_sqlite</code> so DebugKit can work properly.';
			}
		}
	}
	return compact('connected', 'error');
};

if (!Configure::read('debug')) :
	throw new NotFoundException(
					'Please replace templates/Pages/home.php with your own version or re-enable debug mode.'
	);
endif;
?>

<div class="content">
	<div class="row">
		<div class="column">
			<h4>Hello world</h4>
		</div>
	</div>
</div>