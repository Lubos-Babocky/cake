<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Product $product
 * @var string[]|\Cake\Collection\CollectionInterface $categories
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $product->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $product->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Products'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="products form content">
            <?= $this->Form->create($product, ['type' => 'file']) ?>
            <fieldset>
                <legend><?= __('Edit Product') ?></legend>
                <div class="form-row">
                    <div class="form-group">
                        <?php
                            echo $this->Form->control('name');
                            echo $this->Form->control('description');
                            echo $this->Form->control('price');
                            echo $this->Form->control('vat_rate');
                            echo $this->Form->control('image', ['type' => 'file']);
                        ?>
                    </div>
                    <div class="form-group" style="text-align: right;">
                        <img style="max-width: 100%; height: auto; width: 5em;" src="<?= h($product->image) ?>" alt="<?= h($product->name) ?>" />
                    </div>
                </div>
                <?php
                    echo $this->Form->control('categories._ids', ['options' => $categories]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
