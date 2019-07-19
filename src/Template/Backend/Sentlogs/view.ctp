<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Sentlog'), ['action' => 'edit', $sentlog->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Sentlog'), ['action' => 'delete', $sentlog->id], ['confirm' => __('Are you sure you want to delete # {0}?', $sentlog->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Sentlogs'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Sentlog'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Workloads'), ['controller' => 'Workloads', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Workload'), ['controller' => 'Workloads', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="sentlogs view large-9 medium-8 columns content">
    <h3><?= h($sentlog->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Workload') ?></th>
            <td><?= $sentlog->has('workload') ? $this->Html->link($sentlog->workload->id, ['controller' => 'Workloads', 'action' => 'view', $sentlog->workload->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Result') ?></th>
            <td><?= h($sentlog->result) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($sentlog->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($sentlog->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($sentlog->modified) ?></td>
        </tr>
    </table>
</div>
