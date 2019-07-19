<?php
/**
  * @var \App\View\AppView $this
  */
$this->assign('title','TAREAS');
?>
<nav class="large-3-1 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Opciones') ?></li>
        <li><?= $this->Html->link(__('Volver a tareas'), ['action' => 'index',$lista_id,$task_id]) ?></li>
        <li><?= $this->Html->link(__('Nueva tarea'), ['action' => 'add',$lista_id]) ?></li>
        <li><?= $this->Html->link(__('Ver listas'), ['controller' => 'Listas', 'action' => 'index']) ?></li>        
        <li><?= $this->Html->link(__('Ver lista negra'), ['controller'=>'Blacklists','action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('Ver mensajes'), ['controller' => 'Messages', 'action' => 'index']) ?></li>
        
    </ul>
</nav>
<div class="tasks index large-9 medium-8 columns content">
<table  cellpadding="0" cellspacing="0">
		<thead>
            <tr>
                
                <th scope="col" >FECHA LLEGADA</th>
                <th scope="col">TELEFONO</th>
                <th scope="col">MENSAJE</th>                
            </tr>
        </thead>
        <tbody>
        <?php 
        	foreach($SMSs as $sms):        
        ?>
        <tr>
        <td><?=$sms['yx_sms_inbox_fecha_recv']?></td>
        <td><?=$sms['yx_sms_inbox_numorig']?></td>
        <td><?=$sms['yx_sms_inbox_message']?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
</table>
</div>