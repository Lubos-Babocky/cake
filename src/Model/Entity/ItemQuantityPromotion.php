<?php

namespace App\Model\Entity;

use Riesenia\Cart\Cart;
use Riesenia\Cart\PromotionInterface;

/**
 * Description of ItemQuantityPromotion
 * @author Luboš Babocký <babocky@gmail.com>
 * @copyright (c) 2. 10. 2024, Luboš Babocký
 */
class ItemQuantityPromotion implements PromotionInterface {
	const Min = 4;

	public function __construct(
			protected float $discountPercentage,
			protected int $minimumQuantity
	) {
		
	}

	public function afterApply(Cart $cart): void {
		
	}

	public function apply(Cart $cart): void {
		foreach ($cart->getItems() as $item) {
			if($item->getCartQuantity() >= $this->minimumQuantity) {
				$discount = new Discount($item->getCartName(), $item->getCartId(), $item->getCartQuantity() * $item->getUnitPrice() * $this->discountPercentage / 100);
				$cart->addItem($discount);
			}
		}
	}

	public function beforeApply(Cart $cart): void {
		
	}

	public function isEligible(Cart $cart): bool {
		foreach ($cart->getItems() as $item) {
			if($item->getCartQuantity() >= $this->minimumQuantity) {
				return true;
			}
		}
		return false;
	}
}
