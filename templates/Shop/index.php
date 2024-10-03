<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Product> $products
 */
?>
<?= $this->Html->css('Shop/List.css') ?>
<span style="display: none;" id="csrfToken" data-token="<?= $this->request->getAttribute('csrfToken') ?>"></span>
<div class="products index content">
	<div class="category-buttons">
		<a href="/shop" class="category-button">All</a>
		<?php foreach ($categories as $category): ?>
			<a href="/shop/<?= h($category->id) ?>" class="category-button">
				<?= h($category->name) ?>
			</a>
		<?php endforeach; ?>
	</div>
	<hr>
    <h3><?= __('Products') ?></h3>
	<div class="product-grid">
		<?php foreach ($products as $product): ?>
			<div class="product-card">
				<?php if (!empty($product->image)): ?>
					<img src="<?= h($product->image) ?>" alt="<?= h($product->name) ?>" />
				<?php endif; ?>
				<h3><?= h($product->name) ?></h3>
				<p class="price">$<?= h($product->price) ?></p>
				<button class="button add-to-cart" data-id="<?= $product->id ?>">Add to cart</button>
				<div class="description"><?= h($product->description ?: 'No description') ?></div>
			</div>
		<?php endforeach; ?>
	</div>
	<div id="cart-items">
		
	</div>
	<div id="cart-summary">
		<span>Subtotal (without VAT): <strong>$<span id="cart-subtotal">0.00</span></strong></span>
		<span> | Taxes: <strong>$<span id="cart-taxes">0.00</span></strong></span>
		<span> | Total (with VAT): <strong>$<span id="cart-total">0.00</span></strong></span>
		 <button id="clear-cart">Clear Basket</button>
	</div>
</div>



<?php echo $this->Html->script('Shop/Main'); ?>