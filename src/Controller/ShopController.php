<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Table\CategoriesTable;
use App\Model\Table\ProductsTable;
use Cake\ORM\TableRegistry;

/**
 * Shop Controller.
 */
class ShopController extends AppController
{
    protected ProductsTable $Products;

    protected CategoriesTable $Categories;

    public function initialize(): void
    {
        parent::initialize();

        if (($productsTable = TableRegistry::getTableLocator()->get('Products')) instanceof ProductsTable) {
            $this->Products = $productsTable;
        }

        if (($categoriesTable = TableRegistry::getTableLocator()->get('Categories')) instanceof CategoriesTable) {
            $this->Categories = $categoriesTable;
        }
    }

    /**
     * Index method.
     *
     * @return \Cake\Http\Response|void|null Renders view
     */
    public function index(?int $categoryId = null)
    {
        if (isset($categoryId)) {
            $products = $this->Products->find()
                ->matching('Categories', function ($q) use ($categoryId) {
                    return $q->where(['Categories.id' => $categoryId]);
                });
        } else {
            $products = $this->paginate($this->Products->find());
        }
        $categories = $this->paginate($this->Categories->find());
        $this->set(\compact('products', 'categories'));
    }
}
