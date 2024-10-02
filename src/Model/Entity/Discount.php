<?php

namespace App\Model\Entity;

use Riesenia\Cart\CartItemInterface;
use Riesenia\Cart\CartContext;
use Cake\ORM\Entity;

/**
 * Disccount Entity.
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
class Discount extends Entity implements CartItemInterface {

	public function __construct(
			string $name,
			string $id,
			string $price
	) {
		$this->id = sprintf('%s_discount', $id);
		$this->name = sprintf('Discount of %s', $name);
		$this->price = -$price;
	}

	public function getTaxRate(): float {
		return 0;
	}

	public function getUnitPrice(): float {
		return $this->price;
	}

	public function getCartQuantity(): float {
		return 1;
	}

	public function setCartQuantity(float $quantity): void {
		
	}

	public function setCartContext(CartContext $context): void {
		
	}

	public function getCartName(): string {
		return $this->name;
	}

	public function getCartType(): string {
		return 'discount';
	}

	public function getCartId(): string {
		return $this->id;
	}
}
