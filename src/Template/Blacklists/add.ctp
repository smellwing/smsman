<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3-1 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Opciones') ?></li>
        <li><?= $this->Html->link(__('Volver a lista negra'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="blacklists form large-9 medium-8 columns content">
    <?= $this->Form->create($blacklist) ?>
    <fieldset>
        <legend><?= __('Agregar carga a lista negra') ?></legend>
        <?php
            echo $this->Form->control('dni',['label'=>'RUT']);
            echo $this->Form->control('dni_dv',['label'=>'DV']);
            echo $this->Form->control('phone',['label'=>'TELEFONO']);            
            echo $this->Form->control('client_id',['label'=>'CLIENTE']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('AGREGAR')) ?>
    <?= $this->Form->end() ?>
</div>
