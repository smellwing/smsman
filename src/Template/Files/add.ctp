<?php
/**
  * @var \App\View\AppView $this
  */
$this->assign('title','CARGA MASIVA');
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Opciones') ?></li>
        <li><?= $this->Html->link(__('Volver a destinatarios'), ['controller' => 'Workloads', 'action' => 'index',isset($lista_id)?$lista_id:null]) ?></li>
        <li><?= $this->Html->link(__('Ver tareas de envío'), ['controller' => 'Tasks', 'action' => 'index',isset($lista_id)?$lista_id:null]) ?></li>
        <li><?= $this->Html->link(__('Volver a campñas'), ['controller' => 'Listas', 'action' => 'index',isset($lista_id)?$lista_id:null]) ?></li>
    </ul>
</nav>
<div class="files form large-9 medium-8 columns content">
    <?= $this->Form->create($file,['type'=>'file']) ?>
    <fieldset>
        <legend><?= __('Cargar Archivo') ?></legend>
        <?php
            echo $this->Form->control('filename',['type'=>'file','label'=>'Archivo CSV']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Subir')) ?>
    <?= $this->Form->end() ?>
    <ul><li><?= $this->Html->link(__('Descargar Ejemplo Archivo CSV de Carga'), ['action' => 'descargar',1]) ?></li></ul>
</div>

