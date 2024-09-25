<?php

declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateProducts extends AbstractMigration {

	/**
	 * Change Method.
	 *
	 * More information on this method is available here:
	 * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
	 * @return void
	 */
	public function change(): void {
		$table = $this->table('products');
		$table->addColumn('name', 'string', ['limit' => 255])
				->addColumn('description', 'text', ['null' => true])
				->addColumn('price', 'decimal', ['precision' => 10, 'scale' => 2])
				->addColumn('vat_rate', 'decimal', ['precision' => 5, 'scale' => 2])
				->addColumn('image', 'string', ['limit' => 255, 'null' => true])
				->addTimestamps()
				->create();
	}
}
