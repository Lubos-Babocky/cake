<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\CartQuantityPromotion;
use App\Model\Entity\ItemQuantityPromotion;
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
    public function addToCart(string $productId): Response
    {
        $this->request->allowMethod(['post']);
        $product = $this->Products->get($productId);

        if (!$product) {
            throw new NotFoundException(__('Produkt nebol nájdený'));
        }
        $cart = $this->openCart();

        if ($cart->hasItem($productId)) {
            $cart->setItemQuantity($productId, $cart->getItem($productId)->getCartQuantity() + 1);
        } else {
            $cart->addItem($product);
        }
        $this->saveCart($cart);
        $jsonResponse = $this->getResponse()
            ->withType('application/json')
            ->withStringBody($this->getCartJsonDataForResponse());
        $this->setResponse($jsonResponse);

        return $this->response;
    }

    public function clearBasket(): Response
    {
        $this->request->getSession()->delete('Cart');

        return $this->getResponse()
            ->withType('application/json')
            ->withStringBody($this->getCartJsonDataForResponse());
    }

    public function cartInfo(): Response
    {
        return $this->getResponse()
            ->withType('application/json')
            ->withStringBody($this->getCartJsonDataForResponse());
    }

    public function decrease(string $productId): Response
    {
        $cart = $this->openCart();
        $itemQuantity = $cart->getItem($productId)->getCartQuantity();

        if ((int) $itemQuantity <= 1) {
            $cart->removeItem($productId);
        } else {
            $cart->setItemQuantity($productId, $itemQuantity - 1);
        }
        $this->saveCart($cart);

        return $this->getResponse()
            ->withType('application/json')
            ->withStringBody($this->getCartJsonDataForResponse());
    }

    public function remove(int $productId): Response
    {
        $cart = $this->openCart();
        $cart->removeItem((string) $productId);
        $this->saveCart($cart);

        return $this->getResponse()
            ->withType('application/json')
            ->withStringBody($this->getCartJsonDataForResponse());
    }

    protected function openCart(): Cart
    {
        if (empty($cart = $this->request->getSession()->read('Cart'))) {
            $cart = new Cart();
            $cart->setPromotions($this->getPromotions());
        }
        $this->removeDiscounts($cart);

        return $cart;
    }

    protected function saveCart(Cart $cart): void
    {
        $this->request->getSession()->write('Cart', $cart);
    }

    protected function getCartJsonDataForResponse(): ?string
    {
        $cart = $this->openCart();

        return \json_encode([
            'subtotal' => $cart->getSubtotal()->asFloat(),
            'taxes' => (float) \array_sum(\array_map(function ($tax) {
                return $tax->asFloat();
            }, $cart->getTaxes())),
            'total' => $cart->getTotal()->asFloat(),
            'items' => \array_values($cart->getItems())
        ]) ?: null;
    }

    /**
     * @return array<\Riesenia\Cart\PromotionInterface>
     */
    protected function getPromotions(): array
    {
        return [new ItemQuantityPromotion(10, 4), new CartQuantityPromotion(5, 10)];
    }

    protected function removeDiscounts(Cart $cart): void
    {
        $discountIds = \array_filter(\array_keys($cart->getItems()), function ($value) {
            return !\is_numeric($value);
        });

        foreach ($discountIds as $discountId) {
            $cart->removeItem($discountId);
        }
    }
}
