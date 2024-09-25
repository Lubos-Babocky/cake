<?php

declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateUsers extends AbstractMigration {

	/**
	 * Change Method.
	 *
	 * More information on this method is available here:
	 * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
	 * @return void
	 */
	public function change(): void {
		$this->table('users')
				->addColumn('login', 'string', ['limit' => 255, 'null' => false])
				->addColumn('password', 'string', ['limit' => 255, 'null' => false])
				->addColumn('created', 'datetime', ['null' => false])
				->addColumn('modified', 'datetime', ['null' => false])
				->addIndex(['login'], ['unique' => true])
				->create();
	}
}
