<?php
/**
  * @var \App\View\AppView $this
  */
$this->assign('title','CARGAS');
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Opciones') ?></li>
        <li><?= $this->Form->postLink(
                __('Quitar'),
                ['action' => 'delete', $workload->id],
                ['confirm' => __('Confirmar que desea quitar la carga de ID # {0}', $workload->id)]
            )
        ?></li>
        <!--<li><?= $this->Html->link(__('Ver tareas'), ['controller' => 'Tasks','action' => 'index',$workload->id]) ?> </li>-->
        <li><?= $this->Html->link(__('Ver listas'), ['controller' => 'Listas', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('Ver lista negra'), ['controller'=>'Blacklists','action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('Ver mensajes'), ['controller'=>'messages','action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('Volver a las cargas'), ['action' => 'index',$workload->id,$lista_id]) ?></li>        
        
    </ul>
</nav>
<div class="workloads form large-9 medium-8 columns content">
    <?= $this->Form->create($workload) ?>
    <fieldset>
        <legend><?= __('Editar carga') ?></legend>
        <?php
            echo $this->Form->control('dni',['label'=>'RUT']);
            echo $this->Form->control('dni_dv',['label'=>'DV']);
            echo $this->Form->control('phone',['label'=>'Telefono']);
            echo $this->Form->control('lista_id',['label'=>'Lista','type'=>'hidden']);
            echo $this->Form->control('file_id', ['type' => 'hidden','label'=>'Archivo fuente']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Guardar')) ?>
    <?= $this->Form->end() ?>
</div>
