<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Riesenia\Cart\CartContext;
use Riesenia\Cart\CartItemInterface;

/**
 * Product Entity.
 *
 * @property int                          $id
 * @property string                       $name
 * @property string|null                  $description
 * @property string                       $price
 * @property string                       $vat_rate
 * @property string|null                  $image
 * @property float                        $cart_quantity
 * @property \Cake\I18n\DateTime          $created
 * @property \Cake\I18n\DateTime|null     $modified
 * @property \App\Model\Entity\Category[] $categories
 */
class Product extends Entity implements CartItemInterface
{
    protected array $_accessible = [
        '*' => true,
        'id' => false,
    ];

    public function getCartId(): string
    {
        return (string) $this->id;
    }

    public function getCartType(): string
    {
        return 'product';
    }

    public function getCartName(): string
    {
        return $this->name;
    }

    public function setCartQuantity(float $quantity): void
    {
        $this->cart_quantity = $quantity;
    }

    public function getCartQuantity(): float
    {
        return $this->cart_quantity ?? 1.0;
    }

    public function getUnitPrice(): float
    {
        return (float) $this->price;
    }

    public function getTaxRate(): float
    {
        return (float) $this->vat_rate ?: 0.0;
    }

    public function setCartContext(CartContext $context): void
    {
    }
}
