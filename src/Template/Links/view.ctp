<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Link'), ['action' => 'edit', $link->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Link'), ['action' => 'delete', $link->id], ['confirm' => __('Are you sure you want to delete # {0}?', $link->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Links'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Link'), ['action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="links view large-9 medium-8 columns content">
    <h3><?= h($link->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Hash') ?></th>
            <td><?= h($link->hash) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($link->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($link->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($link->modified) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Url') ?></h4>
        <?= $this->Text->autoParagraph(h($link->url)); ?>
    </div>
</div>
