<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Quitar'),
                ['action' => 'delete', $link->id],
                ['confirm' => __('Confirmar que desea elminar el link.', $link->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('Volver a links'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="links form large-9 medium-8 columns content">
    <?= $this->Form->create($link) ?>
    <fieldset>
        <legend><?= __('Editar Link') ?></legend>
        <?php
            echo $this->Form->control('hash',['label'=>'Alias','placeholder'=>'http://smsfront/ALIAS']);
            echo $this->Form->control('url', ['label'=>'Link destino']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
