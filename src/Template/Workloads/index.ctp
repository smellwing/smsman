<?php

/**
  * @var \App\View\AppView $this
  */
$this->assign('title','DESTINOS');
?>
<nav class="large-3-1 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Opciones') ?></li>
                
        <li><?= $this->Html->link(__('Cargar destinos masivamente'), ['controller' => 'Files', 'action' => 'add', $lista_id]) ?></li>
        <li><?= $this->Html->link(__('Ver tareas de envío'), ['controller' => 'Tasks', 'action' => 'index',$lista_id]) ?> </li>
        <li><?= $this->Html->link(__('Ver campañas'), ['controller' => 'Listas', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('Ver lista negra'), ['controller'=>'Blacklists','action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('Ver mensajes'), ['controller'=>'messages','action' => 'index']) ?></li>
    </ul>
</nav>
<div class="workloads index large-9 medium-8 columns content">
    <h3><?= __('Destinos') ?></h3>
    
    
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

</div>
    
    
     
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <!-- <th scope="col" class="col-id"><?= $this->Paginator->sort('id') ?></th>  -->
                <th scope="col" ><?= $this->Paginator->sort('dni','RUT') ?></th>
                
                <th scope="col"><?= $this->Paginator->sort('phone','Telefono') ?></th>
                <th scope="col"><?= $this->Paginator->sort('nombre','Nombre') ?></th>
                <th scope="col"><?= $this->Paginator->sort('deuda','Deuda') ?></th>
                <th scope="col"><?= $this->Paginator->sort('sucursal','Sucursal') ?></th>
                <th scope="col"><?= $this->Paginator->sort('cuenta') ?></th>
                <th scope="col"><?= $this->Paginator->sort('link','Link') ?></th>
                
                <th scope="col"><?= $this->Paginator->sort('lista_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('message_id','Mensaje') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created','Creado') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified','Modificado') ?></th>
                <th scope="col" class="actions"><?= __('Acciones') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($workloads as $workload):   ?>
            <tr>
                <!-- <td><?= $this->Number->format($workload->id) ?></td>  -->
                <td><?= number_format($workload->dni,0,'.','.').'-'.h($workload->dni_dv)?></td>
                
                <!--<td><?= $this->Number->format($workload->phone) ?></td>-->
                <td><?= ($workload->phone) ?></td>  
                <td><?= h($workload->nombre) ?></td>
                <td><?= $this->Number->format($workload->deuda) ?></td>
                <td><?= h($workload->sucursal) ?></td>
                <td><?= h($workload->cuenta) ?></td>
                <td><?php 
                //Esto rescate el enlace si hay en la tabla links o cargas (como al inicio), por retro compatibilidad.
                if(!empty($workload->link))
                {
                	
                	echo $this->Html->link("<i class='fa fa-info-circle' ></i>", ['controller'=>'Links','action'=>'index',$workload->link->id], ['class' => 'add', 'escape' => false, 'title'=>'Ver visitas']);
                	echo "&nbsp;";
                	echo $this->Html->link("<i class='fa fa-external-link' ></i>",$workload->link->url,["target"=>"_blank","title"=>"Abrir enlace",'class' => 'add', 'escape' => false,]);
                	echo "&nbsp;";
                	if(strlen($workload->link->url)>24) $linkc = substr($workload->link->url,0,24).'...';
                	else $linkc = $workload->link->url;
                	echo $this->Html->link($linkc,$workload->link->url,["target"=>"_blank","title"=>$workload->link->url]);
                	
                }
                else 
                {
                	echo (!empty($workload->enlace))  ? $this->Html->link("<i class='fa fa-external-link' ></i>", $workload->enlace,['target'=>'_blank','title'=>'Abrir enlace','class' => 'add', 'escape' => false,]).'&nbsp;':"--";
                	echo (!empty($workload->enlace))  ? $this->Html->link($workload->enlace, $workload->enlace,['target'=>'_blank','title'=>'Abrir enlace']):"--";
                	
                };?></td>
                <td><?= $this->Number->format($workload->lista_id) ?></td>
                <td><?= h($workload->message->message) ?></td>
                <td><?= h($workload->created) ?></td>
                <td><?= h($workload->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__("<i class='fa fa-edit'></i>"), ['action' => 'edit',$workload->id,  $lista_id], ['class' => 'add', 'escape' => false, 'title'=>'Editar']); ?>&nbsp

                    <?= $this->Form->postLink(__('<i class="fa fa-trash"></i>'), ['action' => 'delete', $workload->id, $lista_id] ,['confirm' => __('Confirme que desa quitar la lista ID # {0}.', $workload->id),'title'=>'Quitar','escape' => false], $workload->id); ?>
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
