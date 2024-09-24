<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div class="row">
	<div class="column">
		<div class="users form content" style="margin-top: 1em;">
			<?= $this->Form->create($user) ?>
			<?= $this->Form->control('login') ?>
			<?= $this->Form->control('password', ['type' => 'password']) ?>
			<?= $this->Form->control('password_confirm', ['type' => 'password']) ?>
			<?= $this->Form->button(__('Register')) ?>
			<?= $this->Form->end() ?>
		</div>
	</div>
</div>
