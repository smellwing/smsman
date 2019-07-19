<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3-1 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Opciones') ?></li>
        <li><?= $this->Html->link(__('Volver a las campañas'), ['action' => 'index']) ?></li>        
    </ul>
</nav>
<div class="listas form large-9 medium-8 columns content">
    <?= $this->Form->create($lista) ?>
    <fieldset>
        <h3><?= __('Crear nueva Campaña') ?></h3>
        <?php
            echo $this->Form->control('name',['label'=>__('Nombre de la campaña')]);
            echo $this->Form->control('user_id', ['options' => $users,'label'=>'Usuario que crea.']);
            echo $this->Form->control('client_id', ['options' => $clients,'label'=>'Cliente']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Crear')) ?>
    <?= $this->Form->end() ?>
</div>
