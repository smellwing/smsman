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
$this->assign('title','&Iacute;NDICE DE CAMPA&NTILDE;AS');
?>
<nav class="large-3-1 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Opciones') ?></li>
        <li><?= $this->Html->link(__('Crear otra campaña'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('Ver lista negra'), ['controller'=>'Blacklists','action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('Ver mensajes'), ['controller'=>'messages','action' => 'index']) ?></li>
        <?php if($isAdmin):?>
        <li><?= $this->Html->link(__('Ir a lyrics'), ['prefix'=>'backend','controller'=>'lyrics','action' => 'index']) ?></li>
		<?php endif;?>
    </ul>
    
<div class="container">
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
<?php if(isset($tip)):?>
<div class="alert alert-info">
  <strong>Tip:</strong> <?=$tip ?>
</div>
<?php endif; ?>


</div>
    
</nav>
<div class="listas index large-9 medium-8 columns content">
    <h3><?= __('Campañas') ?></h3>
    <p> Las <strong>Campa&ntilde;as</strong> son una o m&aacute;s cargas de destinatarios a los que se les puede enviar distintos 
    <strong>Mensajes</strong> de <strong>SMS</strong> distribuidos en <strong>Tareas de env&iacute;o</strong> programadas. 
    Las <strong>Campa&ntilde;as</strong> pueden tener una o m&aacute;s <strong>Tareas de env&iacute;o</strong> programadas en diferentes horarios en el d&iacute;a. 
    Las <strong>Tareas de env&iacute;o</strong> de una misma <strong>Campa&ntilde;a</strong> siempre tienen los mismos <strong>destinatarios</strong>.</p>
    <table  id="ListaCampaigns" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <!--<th class="col1 col-id" scope="col"><?= $this->Paginator->sort('id') ?></th>-->
                <th class="col2 col-descripcion" scope="col"><?= $this->Paginator->sort('name',__('Campaña')) ?></th>
                
                <th class="col3" scope="col"><?= $this->Paginator->sort('user_id','creador') ?></th>
                <th class="col4" scope="col"><?= $this->Paginator->sort('client_id','cliente') ?></th>
                <th class="col5" scope="col"><?= $this->Paginator->sort('created','creada') ?></th>
                
                <th class="col7" scope="col" class="actions"><?= __('Acciones') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listas as $lista): ?>
            <tr>
                <!--<td class="col1" ><?= $this->Number->format($lista->id) ?></td>-->
                <td class="col2" >
                <?php 
                if(count($lista->workloads)>0)
                echo $this->Html->link(h($lista->name) , ['controller'=>'tasks','action' => 'index',$lista->id], ['class' => 'add', 'escape' => false, 'title'=>'Ver tareas de envio']);
                else echo "<span title='Ver tareas de envio (Deshabilitado porque no hay destinatarios)' >".h($lista->name).'</span>';?>
                <?=' <i>('.number_format(count($lista->workloads),0,',','.').' dest. )</i>'; ?>
                <?php //debug($lista->tasks);
                $active_task = false;
                $color = '#E57373';
                $title = "Ninguna tarea activa";
                if(!empty($lista->tasks))
                    foreach ($lista->tasks as $task){
                        if($task->active){
                            $active_task = true;   
                            $title = "Existen tareas activas";
                            break;
                        }
                }
                if($active_task) $color = "#66BB6A";
                echo "<i class='fa fa-circle' title='$title' style='color:$color; font-size:60%;'></i>";
                ?>
                </td>
                <td class="col3" ><?= $lista->user->username ?></td>
                <td class="col4" ><?= $lista->client->name ?></td>
                <td class="col5" ><?= h($lista->created) ?></td>

                <td class="actions class">
                    <?php
                    if(count($lista->workloads)>0)
                    echo $this->Html->link(__("<i class='fa fa-paper-plane' style='font-size:120%;'></i>"), ['controller'=>'tasks','action' => 'index',$lista->id], ['class' => 'add', 'escape' => false, 'title'=>'Ver tareas de envio']); 
                    else echo __("<i title='Ver tareas de envio (Deshabilitado porque no hay destinatarios)' class='fa fa-paper-plane' style='color:#B3E5FC; font-size:120%;'></i>"); ?>&nbsp
                    <?= $this->Html->link(__("<i class='fa fa-address-book'  style='font-size:120%;'></i>"), ['controller'=>'workloads','action' => 'index',$lista->id], ['class' => 'add', 'escape' => false, 'title'=>'Ver destinatarios']); ?> &nbsp
                    
                    &nbsp;&nbsp;&nbsp;
                    <?= $this->Html->link(__("<i class='fa fa-info'></i>"), ['action' => 'view',$lista->id], ['class' => 'add', 'escape' => false, 'title'=>'Ver informacion']); ?>&nbsp

                    <?= $this->Html->link(__("<i class='fa fa-edit'></i>"), ['action' => 'edit',$lista->id], ['class' => 'add', 'escape' => false, 'title'=>'Editar']); ?>&nbsp

                    <?= $this->Form->postLink(__('<i class="fa fa-trash"></i>'), ['action' => 'delete', $lista->id] ,['confirm' => __('Confirme que desa quitar la lista ID # {0}.', $lista->id),'title'=>'Quitar','escape' => false]); ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('primera')) ?>
            <?= $this->Paginator->prev('< ' . __('anterior')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('siguiente') . ' >') ?>
            <?= $this->Paginator->last(__('final') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Pag. {{page}} de {{pages}}, mostrando {{current}} registros de {{count}}')]) ?></p>
    </div>
</div>
