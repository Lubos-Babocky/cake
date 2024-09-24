<?php

declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateCategoriesProducts extends AbstractMigration {

	/**
	 * Change Method.
	 *
	 * More information on this method is available here:
	 * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
	 * @return void
	 */
	public function change(): void {
		$table = $this->table('categories_products');
		$table->addColumn('category_id', 'integer', ['null' => false])
				->addColumn('product_id', 'integer', ['null' => false])
				->addForeignKey('category_id', 'categories', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
				->addForeignKey('product_id', 'products', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
				->create();
	}
}
