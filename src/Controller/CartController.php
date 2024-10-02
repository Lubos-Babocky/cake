<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Table\ProductsTable;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Cake\ORM\TableRegistry;
use Riesenia\Cart\Cart;
use App\Model\Entity\CartQuantityPromotion;
use App\Model\Entity\ItemQuantityPromotion;

/**
 * Cart Controller.
 *
 * @property \App\Model\Table\ProductsTable $Products
 */
class CartController extends AppController
{
    protected ProductsTable $Products;

    public function initialize(): void
    {
        parent::initialize();
        $productTable = TableRegistry::getTableLocator()->get('Products');

        if ($productTable instanceof ProductsTable) {
            $this->Products = $productTable;
        }
    }

    /**
     * @throws NotFoundException
     */
    public function addToCart(int $productId): Response
    {
        $this->request->allowMethod(['post']);
        $product = $this->Products->get($productId);

        if (!$product) {
            throw new NotFoundException(__('Produkt nebol nájdený'));
        }
        $cart = $this->openCart();
        $cart->addItem($product);
        $this->saveCart($cart);
        $jsonResponse = $this->getResponse()
            ->withType('application/json')
            ->withStringBody($this->getCartJsonDataForResponse($cart));
        $this->setResponse($jsonResponse);

        return $this->response;
    }

    public function clearBasket(): Response
    {
		$this->request->getSession()->delete('Cart');
        return $this->getResponse()
            ->withType('application/json')
            ->withStringBody($this->getCartJsonDataForResponse($this->openCart()));
    }

    public function cartInfo(): Response
    {
		return $this->getResponse()
            ->withType('application/json')
            ->withStringBody($this->getCartJsonDataForResponse($this->openCart()));
    }

    protected function openCart(): Cart
    {
		if(empty($cart = $this->request->getSession()->read('Cart'))) {
			$cart = new Cart();
			$cart->setPromotions($this->getPromotions());
		}
		return $cart;
    }

    protected function saveCart(Cart $cart): void
    {
        $this->request->getSession()->write('Cart', $cart);
    }

    protected function getCartJsonDataForResponse(Cart $cart): ?string
    {
        return \json_encode([
            'subtotal' => $cart->getSubtotal()->asFloat(),
            'taxes' => (float) \array_sum(\array_map(function ($tax) {
                return $tax->asFloat();
            }, $cart->getTaxes())),
            'total' => $cart->getTotal()->asFloat(),
			'items' => array_values($cart->getItems())
        ]) ?: null;
    }

	/**
	 * @return array
	 */
	protected function getPromotions(): array {
		return [new ItemQuantityPromotion(10, 4), new CartQuantityPromotion(5, 10)];
	}
}
