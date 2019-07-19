<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Acciones') ?></li>
        <li><?= $this->Html->link(__('Lista de enlaces'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="links form large-9 medium-8 columns content">
    <?= $this->Form->create($link) ?>
    <fieldset>
        <legend><?= __('Agregar enlace') ?></legend>
        <?php
            //echo $this->Form->control('hash');
            echo $this->Form->control('url');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Nuevo')) ?>
    <?= $this->Form->end() ?>
</div>
