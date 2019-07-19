<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Lyric $lyric
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Lyrics'), ['action' => 'index',$ip]) ?></li>
        <li><?= $this->Html->link(__('List Sentlogs'), ['controller' => 'Sentlogs', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Sentlog'), ['controller' => 'Sentlogs', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="lyrics form large-9 medium-8 columns content">
    <?= $this->Form->create($lyric) ?>
    <fieldset>
        <legend><?= __('Add Lyric') ?></legend>
        <?php
            echo $this->Form->control('ip');
            echo $this->Form->control('username');
            echo $this->Form->control('password');
            echo $this->Form->control('web_username');
            echo $this->Form->control('web_password');
            echo $this->Form->control('channel');
            echo $this->Form->control('api');
            echo $this->Form->control('active');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
