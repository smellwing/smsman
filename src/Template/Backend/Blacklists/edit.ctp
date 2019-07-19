<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $blacklist->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $blacklist->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Blacklists'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Clients'), ['controller' => 'Clients', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Client'), ['controller' => 'Clients', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="blacklists form large-9 medium-8 columns content">
    <?= $this->Form->create($blacklist) ?>
    <fieldset>
        <legend><?= __('Edit Blacklist') ?></legend>
        <?php
            echo $this->Form->control('dni');
            echo $this->Form->control('dni_dv');
            echo $this->Form->control('phone');
            echo $this->Form->control('client_id', ['options' => $clients]);
            echo $this->Form->control('lista_negra_id');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
