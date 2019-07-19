<?php
/**
  * @var \App\View\AppView $this
  */
$this->assign('title','EDITAR MENSAJE');
?>
<nav class="large-3-1 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Quitar'),
                ['action' => 'delete', $message->id],
                ['confirm' => __('Confirme que desea elimnar el mensaje de ID # {0}', $message->id)]
            )
        ?></li>
        
        <?php if(!is_null($lista_id)):?>
        <li><?= $this->Html->link(__('Ver tareas'), ['controller' => 'tasks', 'action' => 'index',$lista_id]) ?></li>
        <?php endif;?>

        <li><?= $this->Html->link(__('Ver listas'), ['controller' => 'Listas', 'action' => 'index']) ?></li>        
        <li><?= $this->Html->link(__('Ver Mensajes'), ['controller' => 'Messages', 'action' => 'index',$lista_id]) ?></li>
        <li><?= $this->Html->link(__('Crear Mensaje'), ['controller' => 'Messages', 'action' => 'add',$lista_id]) ?></li>
        <li><?= $this->Html->link(__('Volver a mensajes'), ['action' => 'index', $lista_id]) ?></li>
    </ul>
</nav>
<div class="messages form large-9 medium-8 columns content">
    <?= $this->Form->create($message) ?>
    <fieldset>
        <legend><?= __('Editar mensaje') ?></legend>
        <?php
            
            echo $this->Form->control('message',['label'=>'Mensaje','type'=>'textarea', 'maxlength'=>140,'placeholder'=>'MENSAJE MAX. 140 CARACTERES.']);
            echo $this->Form->control('client_id', ['options' => $clients,'label'=>'Cliente']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Guardar')) ?>
    <?= $this->Form->end() ?>
      <br>  
<p>IMPORTANTE: utilizando los comodines #{rut} #{nombre} #{deuda} #{sucursal} #{link} #{cuenta} para hacer un mensaje din&aacute;mico. Recuerde agregar estos campos en el archivo de carga CSV.</p>
<p>CUALQUIER MENSAJE MAYOR A 160 CARACTERES, INCLUYENDO LA INFORMACIO DE CAMPOS DIN&Aacute;MICOS, SER&Aacute; IGNORADO.</p>
    
</div>
