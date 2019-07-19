<?php
/**
  * @var \App\View\AppView $this
  */
$this->assign('title','TAREAS');
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Opciones') ?></li>
        
        <li><?= $this->Html->link(__('Volver a tareas'), ['action' => 'index',$lista_id]) ?></li>
        <li><?= $this->Html->link(__('Ir a listas'), ['controller' => 'Listas', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('Nueva Lista'), ['controller' => 'Listas', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('Ver Mensajes'), ['controller' => 'Messages', 'action' => 'index',$lista_id]) ?></li>
        <li><?= $this->Html->link(__('Crear Mensaje'), ['controller' => 'Messages', 'action' => 'add',$lista_id]) ?></li>
    </ul>
</nav>
<div class="tasks form large-9 medium-8 columns content">
    <?= $this->Form->create($task) ?>
    <fieldset>
        <legend><?= __('Editar Tarea') ?></legend>
        <?php 
            echo $this->Form->control('name',['label'=>'Nombre de tarea']);
            echo $this->Form->control('datetime_start', ['label'=>'Inicio','empty' => true,'options'=>$horarios]);
            $until8Pm = floor((strtotime(date("Y-m-d 20:00:00"))-strtotime(date("Y-m-d H:i:s"))) /60);
            echo $this->Form->control('datetime_end', ['type'=>'hidden','value' => $until8Pm]);
            echo '<span style="color:red;" >La tarea acaba a las 8 PM</span>';
            echo $this->Form->control('lista_id', ['options' => $listas,'label'=>'Lista']);
            if(!$msjEnCarga) {
                $disabled['disabled'] = 'disabled';
                $default = 0;
            }
            else
            {
                $disabled = null;
                if(empty($task->message_id)) $default = 1;
                else $default = 0;
            }
            
                  
            
            echo $this->Form->control('msjCarga', ['options'=>[0=>'NO',1=>'SI'],$disabled,'default'=>$default,'label'=>'Usar mensajes de la carga']);
            echo $this->Form->control('message_id', ['options' => $messages,'label'=>'Mensaje']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Guardar')) ?>
    <?= $this->Form->end() ?>
</div>
