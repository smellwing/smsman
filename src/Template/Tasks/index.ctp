<?=$this->Html->script('Tasks/index.js');?>
<style>
<!--
tbody tr:hover { 
   background-color: #BBDEFB;
}
-->
</style>
<?php

/**
  * @var \App\View\AppView $this
  */
$this->assign('title','TAREAS');
?>
<nav class="large-3-1 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Opciones') ?></li>
        <li><?= $this->Html->link(__('Nueva tarea'), ['action' => 'add',$lista_id]) ?></li>
        <li><?= $this->Html->link(__('Ver campañas'), ['controller' => 'Listas', 'action' => 'index']) ?></li>        
        <li><?= $this->Html->link(__('Ver lista negra'), ['controller'=>'Blacklists','action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('Ver mensajes'), ['controller' => 'Messages', 'action' => 'index']) ?></li>
        
    </ul>
</nav>
<div class="tasks index large-9 medium-8 columns content">
<h3><?= __('Tareas de Envío para ').$nombreLista ?></h3>

<div class="container">
<div class="alert alert-info">
  <strong>Tip:</strong> <?=$tip ?>
</div>

<?php 
if(!is_null($cuota_diaria)):
$alert = "alert-info";
if($cuota_diaria<5000) $alert = "alert-warning";
if($cuota_diaria<1000) $alert = "alert-danger";
?>
<div class="alert <?=$alert?>">
  <strong>Cuota:</strong> Tienes <?=$cuota_diaria?> restantes en env&iacute;o de mensajes.
</div>
<?php endif;?>


</div>
    
    <table id="listaTareas" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
               <!-- <th scope="col" class="col-id"><?= $this->Paginator->sort('id') ?></th>-->
                <th scope="col" ><?= $this->Paginator->sort('name','Tarea') ?></th>
                <th scope="col"><?= $this->Paginator->sort('active','Estado') ?></th>
                <th scope="col"><?= $this->Paginator->sort('datetime_start','Inicio') ?></th>
                <th scope="col"><?= $this->Paginator->sort('datetime_end', 'Fin') ?></th>
                <th scope="col"><?= $this->Paginator->sort('lista_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('message_id','Mensaje') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created','Creada') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified','Modificada') ?></th>
                <th scope="col" class="actions"><?= __('Accion') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php 
            
            foreach ($tasks as $task): ?>
            <tr>
                <!-- <td><?= $this->Number->format($task->id) ?></td>  -->
                <td><?= h($task->name) ?></td>
                <td><?php
                
                if(is_null($task->yx_lista_id)){
                	
                    if($task->status!='VENCIDA'&&$task->status!='FINALIZADA')
                        echo $task->active?h('Activada'):"<span style='color: #F44336;'>".h('DESACTIVADA')."</span>",', ';
                   echo "<span style='color: #4CAF50;'><br>".ucwords(mb_strtolower($task->status));
                   if($task->status=='CARGANDO')
                	   echo " <span class='cargando' id='c_$task->id'>0%</span>";
                   else echo "</span>";
                	
                } else 
                
                {
                	echo ucwords(mb_strtolower($task->status));
                //	echo "Finalizada";
                }
                ?></td>
                		
                <td><?= h($task->datetime_start) ?></td>
                <td><?= h($task->datetime_end) ?></td>
                <td><?= $task->has('lista') ? $this->Html->link($task->lista->name, ['controller' => 'Listas', 'action' => 'view', $task->lista->id]) : '' ?></td>
                <td class="sms_message" >
                <?php if(!empty($task->message)):?>
                	<span title="<?=$task->message->message ?>">
                		<?= $task->has('message') ? $this->Html->link(substr($task->message->message, 0,25)."...", ['controller' => 'Messages', 'action' => 'view', $task->message->id]) : '' ?>
                	</span>
                <?php else: ?>
                <span title="MENSAJES EN CARGA">
                		MENSAJES <br>EN ARCHIVO DE CARGA
                	</span> 
                <?php endif;?>
                </td>
                <td><?= h($task->created) ?></td>
                <td><?= h($task->modified) ?></td>
                <td class="actions">
                <table class="action-icons">
                
                <?php
                echo"<tr>";
                
	            echo"<td>";
	            if( ((!is_null($task->yx_lista_id)||(!empty($task->lyric))))&&((!$task->active)&&(($task->status=='FINALIZADA')||($task->status=='ANALIZANDO') )))
	            {
                	 echo $this->Html->link(__("<i class='fa fa-line-chart' style='font-size:120%;'></i>"), ['action' => 'report',$task->id,$lista_id], ['class' => 'add', 'escape' => false, 'title'=>'Reporte']);
	            };
	            echo "</td><td>";
                echo $this->Html->link(__("<i class='fa fa-info'></i>"), ['action' => 'view',$task->id,$task->lista->id], ['class' => 'add', 'escape' => false, 'title'=>'Ver detalle']);
                echo "</td>";
                
             /*   echo "<td rowspan='1'>";
                if(is_null($task->yx_lista_id)&&(empty($task->lyric))&&($task->status=='NUEVA')&&(is_null($cuota_diaria)||!empty($cuota_diaria))):
                if($task->active)
                	echo $this->Form->postLink(__("<i class='fa fa-mobile' style='color: #4CAF50'></i>"), ['action' => 'switchActive', 0,$task->id,0,$lista_id],['class' => 'add', 'escape' => false, 'title'=>'Descativar envio por PhoneSMS ']).'<br>';
                	else
                		echo $this->Form->postLink(__("<i class='fa fa-mobile' style='color: #F44336'></i>"), ['action' => 'switchActive', 1,$task->id,0,$lista_id],['class' => 'add', 'escape' => false, 'title'=>'Activar envio por PhoneSMS']).'<br>';
                		
                		//echo $this->Html->link(__('Enviar Ahora'), ['action' => 'send_now', $task->id]).'<br>';
                		
                		endif;
           		echo "</td>";*/
           		//echo "<td rowspan='1'>";
                	echo "<td rowspan='2'>";
           		if($task->status=='NUEVA'&&(is_null($cuota_diaria)||!empty($cuota_diaria))):
           		
           		if(($task->active))
           		    echo $this->Form->postLink(__("<i class='fa fa-exchange' style='font-size:120%; color: #4CAF50'></i>"), ['action' => 'switchActive', 0,$task->id,0,$lista_id],['class' => 'add', 'escape' => false, 'title'=>'Descativar envio por Lyric']).'<br>';
           		    else
           		        echo $this->Form->postLink(__("<i class='fa fa-exchange' style='font-size:120%; color: #F44336'></i>"), ['action' => 'switchActive', 1,$task->id,1,$lista_id],['class' => 'add', 'escape' => false, 'title'=>'Activar  envio por Lyric']).'<br>';
           		        
           		        //echo $this->Html->link(__('Enviar Ahora'), ['action' => 'send_now', $task->id]).'<br>';
           		        
           		 endif;
           		        echo "</td>";
           		        
                echo "</tr><tr><td>";
                echo $this->Html->link(__("<i class='fa fa-edit'></i>"), ['action' => 'edit',$task->id,$task->lista->id], ['class' => 'add', 'escape' => false, 'title'=>'Editar']);
                echo "</td><td>";
                echo $this->Html->link(__("<i class='fa fa-inbox'></i>"), ['action' => 'inbox',$task->id,$task->lista->id], ['class' => 'add', 'escape' => false, 'title'=>'Inbox SMSs']);
                echo "</td></tr>";?>
                </table>
               </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('primero')) ?>
            <?= $this->Paginator->prev('< ' . __('anterior')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('siguiente') . ' >') ?>
            <?= $this->Paginator->last(__('final') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Pag. {{page}} de {{pages}}, mostrando {{current}} registros de {{count}}')]) ?></p>
    </div>
</div>
