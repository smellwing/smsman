<?php
/**
  * @var \App\View\AppView $this
  */
$this->assign('title','EDITAR CAMPA&NTILDE;A');
?>
<nav class="large-3-1 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Opciones') ?></li>
        <li><?= $this->Form->postLink(
                __('Quitar'),
                ['action' => 'delete', $lista->id],
                ['confirm' => __(utf8_encode('Confirme que desea elminar esta campaña.'), $lista->id)]
            )
        ?></li>
        <li><?= $this->Html->link(utf8_encode('Volver a las campañas'), ['action' => 'index']) ?></li>        
    </ul>
</nav>
<div class="listas form large-9 medium-8 columns content">
    <?= $this->Form->create($lista) ?>
    <fieldset>
        <h3><?= utf8_encode('Editar Campaña') ?></h3>
        <?php
            echo $this->Form->control('name',['label'=>utf8_encode('Nombre de la campña')]);
            echo $this->Form->control('user_id', ['options' => $users,'label'=>'Usuario que crea']);
            echo $this->Form->control('client_id', ['options' => $clients,'label'=>'Cliente']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Guardar')) ?>
    <?= $this->Form->end() ?>
</div>
