<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit File'), ['action' => 'edit', $file->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete File'), ['action' => 'delete', $file->id], ['confirm' => __('Are you sure you want to delete # {0}?', $file->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Files'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New File'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Tasks'), ['controller' => 'Tasks', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Task'), ['controller' => 'Tasks', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Workloads'), ['controller' => 'Workloads', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Workload'), ['controller' => 'Workloads', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="files view large-9 medium-8 columns content">
    <h3><?= h($file->filename) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Filename') ?></th>
            <td><?= h($file->filename) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Task') ?></th>
            <td><?= $file->has('task') ? $this->Html->link($file->task->name, ['controller' => 'Tasks', 'action' => 'view', $file->task->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('User') ?></th>
            <td><?= $file->has('user') ? $this->Html->link($file->user->username, ['controller' => 'Users', 'action' => 'view', $file->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($file->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($file->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($file->modified) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Workloads') ?></h4>
        <?php if (!empty($file->workloads)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Dni') ?></th>
                <th scope="col"><?= __('Dni Dv') ?></th>
                <th scope="col"><?= __('Phone') ?></th>
                <th scope="col"><?= __('Message') ?></th>
                <th scope="col"><?= __('File Id') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($file->workloads as $workloads): ?>
            <tr>
                <td><?= h($workloads->id) ?></td>
                <td><?= h($workloads->dni) ?></td>
                <td><?= h($workloads->dni_dv) ?></td>
                <td><?= h($workloads->phone) ?></td>
                <td><?= h($workloads->message) ?></td>
                <td><?= h($workloads->file_id) ?></td>
                <td><?= h($workloads->created) ?></td>
                <td><?= h($workloads->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Workloads', 'action' => 'view', $workloads->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Workloads', 'action' => 'edit', $workloads->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Workloads', 'action' => 'delete', $workloads->id], ['confirm' => __('Are you sure you want to delete # {0}?', $workloads->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
