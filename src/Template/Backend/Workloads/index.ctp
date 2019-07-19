<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Workload'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Files'), ['controller' => 'Files', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New File'), ['controller' => 'Files', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="workloads index large-9 medium-8 columns content">
    <h3><?= __('Workloads') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('dni') ?></th>
                <th scope="col"><?= $this->Paginator->sort('dni_dv') ?></th>
                <th scope="col"><?= $this->Paginator->sort('phone') ?></th>
                <th scope="col"><?= $this->Paginator->sort('message') ?></th>
                <th scope="col"><?= $this->Paginator->sort('file_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($workloads as $workload): ?>
            <tr>
                <td><?= $this->Number->format($workload->id) ?></td>
                <td><?= $this->Number->format($workload->dni) ?></td>
                <td><?= h($workload->dni_dv) ?></td>
                <td><?= $this->Number->format($workload->phone) ?></td>
                <td><?= h($workload->message) ?></td>
                <td><?= $workload->has('file') ? $this->Html->link($workload->file->id, ['controller' => 'Files', 'action' => 'view', $workload->file->id]) : '' ?></td>
                <td><?= h($workload->created) ?></td>
                <td><?= h($workload->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $workload->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $workload->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $workload->id], ['confirm' => __('Are you sure you want to delete # {0}?', $workload->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
