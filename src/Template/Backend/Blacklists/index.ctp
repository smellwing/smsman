<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Blacklist'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Clients'), ['controller' => 'Clients', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Client'), ['controller' => 'Clients', 'action' => 'add']) ?></li>
              <li><?= $this->Html->link(__('Sync Blacklists'), ['action' => 'sync_blacklists']) ?></li>
    </ul>
</nav>
<div class="blacklists index large-9 medium-8 columns content">
    <h3><?= __('Blacklists') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('dni') ?></th>
                <th scope="col"><?= $this->Paginator->sort('dni_dv') ?></th>
                <th scope="col"><?= $this->Paginator->sort('phone') ?></th>
                <th scope="col"><?= $this->Paginator->sort('client_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('lista_negra_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($blacklists as $blacklist): ?>
            <tr>
                <td><?= $this->Number->format($blacklist->id) ?></td>
                <td><?= $this->Number->format($blacklist->dni) ?></td>
                <td><?= h($blacklist->dni_dv) ?></td>
                <td><?= $this->Number->format($blacklist->phone) ?></td>
                <td><?= $blacklist->has('client') ? $this->Html->link($blacklist->client->name, ['controller' => 'Clients', 'action' => 'view', $blacklist->client->id]) : '' ?></td>
                <td><?= $this->Number->format($blacklist->lista_negra_id) ?></td>
                <td><?= h($blacklist->created) ?></td>
                <td><?= h($blacklist->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $blacklist->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $blacklist->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $blacklist->id], ['confirm' => __('Are you sure you want to delete # {0}?', $blacklist->id)]) ?>
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
