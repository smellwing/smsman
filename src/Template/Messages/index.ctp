<?php
/**
  * @var \App\View\AppView $this
  */
$this->assign('title','MENSAJES');
?>
<nav class="large-3-1 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Opciones') ?></li>
        <li><?= $this->Html->link(__('Crear mensaje'), ['action' => 'add',$lista_id]) ?></li>                
        <?php if(!is_null($lista_id)):?>
        <li><?= $this->Html->link(__('Ver tareas'), ['controller' => 'tasks', 'action' => 'index',$lista_id]) ?></li>
        <?php endif;?>
                
        <li><?= $this->Html->link(__('Ver lista negra'), ['controller'=>'Blacklists','action' => 'index']) ?></li>
        
        <li><?= $this->Html->link(__('Volver a listas'), ['controller' => 'Listas', 'action' => 'index']) ?></li>
        
    </ul>
</nav>
<div class="messages index large-9 medium-8 columns content">
    <h3><?= __('Mensajes') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col" class="col-id"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col" class="col-descripcion"><?= $this->Paginator->sort('message','Mensajes') ?></th>
                <th scope="col"><?= $this->Paginator->sort('client_id','Cliente') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created','Creado') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified','Modificado') ?></th>
                <th scope="col" class="actions"><?= __('Acciones') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($messages as $message): ?>
            <tr>
                <td><?= $this->Number->format($message->id) ?></td>
                <td><?= h($message->message) ?></td>
                <td><?= $message->client->name?></td>
                <td><?= h($message->created) ?></td>
                <td><?= h($message->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__("<i class='fa fa-eye'></i>"), ['action' => 'view',$message->id, $lista_id], ['class' => 'add', 'escape' => false, 'title'=>'Ver detalles']); ?>&nbsp

                    <?= $this->Html->link(__("<i class='fa fa-edit'></i>"), ['action' => 'edit',$message->id,$lista_id], ['class' => 'add', 'escape' => false, 'title'=>'Editar']); ?>&nbsp

                    <?= $this->Form->postLink(__('<i class="fa fa-trash"></i>'), ['action' => 'delete', $message->id,$lista_id] ,['confirm' => __('Confirme que desa quitar la lista ID # {0}.', $message->id),'title'=>'Quitar','escape' => false],$lista_id); ?>
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
