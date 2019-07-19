<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Blacklist'), ['action' => 'edit', $blacklist->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Blacklist'), ['action' => 'delete', $blacklist->id], ['confirm' => __('Are you sure you want to delete # {0}?', $blacklist->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Blacklists'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Blacklist'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Clients'), ['controller' => 'Clients', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Client'), ['controller' => 'Clients', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="blacklists view large-9 medium-8 columns content">
    <h3><?= h($blacklist->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Dni Dv') ?></th>
            <td><?= h($blacklist->dni_dv) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Client') ?></th>
            <td><?= $blacklist->has('client') ? $this->Html->link($blacklist->client->name, ['controller' => 'Clients', 'action' => 'view', $blacklist->client->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($blacklist->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Dni') ?></th>
            <td><?= $this->Number->format($blacklist->dni) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Phone') ?></th>
            <td><?= $this->Number->format($blacklist->phone) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Lista Negra Id') ?></th>
            <td><?= $this->Number->format($blacklist->lista_negra_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($blacklist->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($blacklist->modified) ?></td>
        </tr>
    </table>
</div>
