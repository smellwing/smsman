<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Workloads'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Files'), ['controller' => 'Files', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New File'), ['controller' => 'Files', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="workloads form large-9 medium-8 columns content">
    <?= $this->Form->create($workload) ?>
    <fieldset>
        <legend><?= __('Add Workload') ?></legend>
        <?php
            echo $this->Form->control('dni');
            echo $this->Form->control('dni_dv');
            echo $this->Form->control('phone');
            echo $this->Form->control('message');
            echo $this->Form->control('file_id', ['options' => $files]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
