<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Lyric $lyric
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Lyric'), ['action' => 'edit', $lyric->id,$ip]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Lyric'), ['action' => 'delete', $lyric->id,$ip], ['confirm' => __('Are you sure you want to delete # {0}?', $lyric->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Lyrics'), ['action' => 'index',$ip]) ?> </li>
        <li><?= $this->Html->link(__('New Lyric'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Sentlogs'), ['controller' => 'Sentlogs', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Sentlog'), ['controller' => 'Sentlogs', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="lyrics view large-9 medium-8 columns content">
    <h3><?= h($lyric->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Ip') ?></th>
            <td><?= h($lyric->ip) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Username') ?></th>
            <td><?= h($lyric->username) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Password') ?></th>
            <td><?= h($lyric->password) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Web Username') ?></th>
            <td><?= h($lyric->web_username) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Web Password') ?></th>
            <td><?= h($lyric->web_password) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sentlog') ?></th>
            <td><?= $lyric->has('sentlog') ? $this->Html->link($lyric->sentlog->id, ['controller' => 'Sentlogs', 'action' => 'view', $lyric->sentlog->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($lyric->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Channel') ?></th>
            <td><?= $this->Number->format($lyric->channel) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Api') ?></th>
            <td><?= $this->Number->format($lyric->api) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($lyric->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($lyric->modified) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Active') ?></th>
            <td><?= $lyric->active ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
</div>
