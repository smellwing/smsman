<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Workload'), ['action' => 'edit', $workload->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Workload'), ['action' => 'delete', $workload->id], ['confirm' => __('Are you sure you want to delete # {0}?', $workload->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Workloads'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Workload'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Files'), ['controller' => 'Files', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New File'), ['controller' => 'Files', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="workloads view large-9 medium-8 columns content">
    <h3><?= h($workload->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Dni Dv') ?></th>
            <td><?= h($workload->dni_dv) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Message') ?></th>
            <td><?= h($workload->message) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('File') ?></th>
            <td><?= $workload->has('file') ? $this->Html->link($workload->file->id, ['controller' => 'Files', 'action' => 'view', $workload->file->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($workload->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Dni') ?></th>
            <td><?= $this->Number->format($workload->dni) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Phone') ?></th>
            <td><?= $this->Number->format($workload->phone) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($workload->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($workload->modified) ?></td>
        </tr>
    </table>
</div>
