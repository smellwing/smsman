<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Clients'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Blacklists'), ['controller' => 'Blacklists', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Blacklist'), ['controller' => 'Blacklists', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Listas'), ['controller' => 'Listas', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Lista'), ['controller' => 'Listas', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Messages'), ['controller' => 'Messages', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Message'), ['controller' => 'Messages', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="clients form large-9 medium-8 columns content">
    <?= $this->Form->create($client) ?>
    <fieldset>
        <legend><?= __('Add Client') ?></legend>
        <?php
            echo $this->Form->control('name');
            echo $this->Form->control('codcli');
            echo $this->Form->control('yx_login_id',['type'=>'number']);
            echo $this->Form->control('tipo');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
