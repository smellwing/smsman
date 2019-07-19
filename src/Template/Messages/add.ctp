<?php
/**
  * @var \App\View\AppView $this
  */
$this->assign('title','CREAR MENSAJE');
?>
<nav class="large-3-1 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Opciones') ?></li>
        
              
         <?php if(!is_null($lista_id)):?>
        <li><?= $this->Html->link(__('Ver tareas'), ['controller' => 'tasks', 'action' => 'index',$lista_id]) ?></li>
        <?php endif;?>

        <!--<li><?= $this->Html->link(__('Ver listas'), ['controller' => 'Listas', 'action' => 'index']) ?></li>-->
        <!--<li><?= $this->Html->link(__('Nueva Lista'), ['controller' => 'Listas', 'action' => 'add']) ?></li>        
        <li><?= $this->Html->link(__('Crear Mensaje'), ['controller' => 'Messages', 'action' => 'add',$lista_id]) ?></li>-->
        <li><?= $this->Html->link(__('Volver a mensajes'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="messages form large-9 medium-8 columns content">
    <?= $this->Form->create($message) ?>
    <fieldset>
        <legend><?= __('Crear mensaje') ?></legend>
        <?php
            echo $this->Form->control('message',['label'=>'Mensaje','type'=>'textarea', 'maxlength'=>160,'placeholder'=>'MENSAJE MAX. 160 CARACTERES.']);
            echo $this->Form->control('client_id', ['options' => $clients,'label'=>'Cliente']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('CREAR')) ?>
    <?= $this->Form->end() ?>
  <br>  
<p>IMPORTANTE: utilizando los comodines #{rut} #{nombre} #{deuda} #{sucursal} #{link} #{cuenta} para hacer un mensaje din&aacute;mico. Recuerde agregar estos campos en el archivo de carga CSV.</p>
<p>CUALQUIER MENSAJE MAYOR A 160 CARACTERES, INCLUYENDO LA INFORMACIO DE CAMPOS DIN&Aacute;MICOS, SER&Aacute; IGNORADO.</p>
    
</div>
