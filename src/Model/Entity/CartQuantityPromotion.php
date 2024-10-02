<?php

namespace App\Model\Entity;

use Riesenia\Cart\Cart;
use Riesenia\Cart\PromotionInterface;

/**
 * Description of CartQuantityPromotion
 * @author Luboš Babocký <babocky@gmail.com>
 * @copyright (c) 2. 10. 2024, Luboš Babocký
 */
class CartQuantityPromotion implements PromotionInterface {

	public function __construct(
			protected float $discountPercentage,
			protected int $minimumQuantity
	) {
		
	}

	public function afterApply(Cart $cart): void {
		
	}

	public function apply(Cart $cart): void {
		$cart->addItem(
				new Discount(
						'Cart discount',
						'cart_discount',
						$cart->getTotal()->asFloat() * $this->discountPercentage / 100
				)
		);
	}

	public function beforeApply(Cart $cart): void {
		
	}

	public function isEligible(Cart $cart): bool {
		$totalCount = 0;
		foreach ($cart->getItems() as $item) {
			$totalCount += $item->getCartQuantity();
		}
		return $totalCount >= $this->minimumQuantity;
	}
}
