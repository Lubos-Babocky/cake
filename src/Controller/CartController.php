<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Table\ProductsTable;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Cake\ORM\TableRegistry;
use Riesenia\Cart\Cart;

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
        $cart = $this->openCart();
        $cart->clear();
        $this->saveCart($cart);

        return $this->getResponse()
            ->withType('application/json')
            ->withStringBody($this->getCartJsonDataForResponse($cart));
    }

    public function cartInfo(): Response
    {
        return $this->getResponse()
            ->withType('application/json')
            ->withStringBody($this->getCartJsonDataForResponse($this->openCart()));
    }

    protected function openCart(): Cart
    {
        return $this->request->getSession()->read('Cart') ?: new Cart();
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
            'total' => $cart->getTotal()->asFloat()
        ]) ?: null;
    }
}
