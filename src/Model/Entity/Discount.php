<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Riesenia\Cart\CartContext;
use Riesenia\Cart\CartItemInterface;

/**
 * Disccount Entity.
 *
 * @property string                       $id
 * @property string                       $name
 * @property string|null                  $description
 * @property float                        $price
 * @property string                       $vat_rate
 * @property string|null                  $image
 * @property float                        $cart_quantity
 * @property \Cake\I18n\DateTime          $created
 * @property \Cake\I18n\DateTime|null     $modified
 * @property \App\Model\Entity\Category[] $categories
 */
class Discount extends Entity implements CartItemInterface
{
    public function __construct(
        string $name,
        string $id,
        float $price
    ) {
        $this->id = $id;
        $this->name = \sprintf('Discount of %s', $name);
        $this->price = -1 * $price;
    }

    public function getTaxRate(): float
    {
        return 0;
    }

    public function getUnitPrice(): float
    {
        return $this->price;
    }

    public function setUnitPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getCartQuantity(): float
    {
        return 1;
    }

    public function setCartQuantity(float $quantity): void
    {
    }

    public function setCartContext(CartContext $context): void
    {
    }

    public function getCartName(): string
    {
        return $this->name;
    }

    public function getCartType(): string
    {
        return 'discount';
    }

    public function getCartId(): string
    {
        return $this->id;
    }
}
