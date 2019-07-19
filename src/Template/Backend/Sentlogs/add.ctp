<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Sentlogs'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Workloads'), ['controller' => 'Workloads', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Workload'), ['controller' => 'Workloads', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="sentlogs form large-9 medium-8 columns content">
    <?= $this->Form->create($sentlog) ?>
    <fieldset>
        <legend><?= __('Add Sentlog') ?></legend>
        <?php
            echo $this->Form->control('workload_id', ['options' => $workloads]);
            echo $this->Form->control('result');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
