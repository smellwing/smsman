<?php
/**
  * @var \App\View\AppView $this
  */
$this->assign('title','LISTA NEGRA');
?>
<nav class="large-3-1 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Opciones') ?></li>
        <li><?= $this->Html->link(__('Agregar a lista negra'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(utf8_encode('Volver a campañas'), ['controller'=>'Listas','action' => 'index']) ?></li>
        
    </ul>
</nav>
<div class="blacklists index large-9 medium-8 columns content">
    <h3><?= __('Lista negra') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('dni','RUT') ?></th>
                
                <th scope="col"><?= $this->Paginator->sort('phone','Teléfono') ?></th>
                <th scope="col"><?= $this->Paginator->sort('client_id','Cliente') ?></th>
                <!-- <th scope="col"><?= $this->Paginator->sort('lista_negra_id') ?></th> -->
                <th scope="col"><?= $this->Paginator->sort('created','Agregado') ?></th>
                
                <th scope="col" class="actions"><?= __('Acciones') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($blacklists as $blacklist): ?>
            <tr>
                <td><?= $this->Number->format($blacklist->id) ?></td>
                <td><?= empty($blacklist->dni)?'--':number_format($blacklist->dni,0,'.','.').'-'.$blacklist->dni_dv ?></td>                
                <td><?= empty($blacklist->phone)?'--':$blacklist->phone ?></td>
                <td><?= $blacklist->client->name ?></td>
                <!-- <td><?= $this->Number->format($blacklist->lista_negra_id) ?></td> -->
                <td><?= h($blacklist->created) ?></td>

                <td><?= $this->Form->postLink(__('<i class="fa fa-trash"></i>'), ['action' => 'delete', $blacklist->id] ,['confirm' => __('Confirme que desa quitar la lista ID # {0}.', $blacklist->id),'title'=>'Quitar','escape' => false]); ?>
                </td>
                
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('primer')) ?>
            <?= $this->Paginator->prev('< ' . __('anterior')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('siguiente') . ' >') ?>
            <?= $this->Paginator->last(__('ultimo') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Pag. {{page}} de {{pages}}, mostrando {{current}} registros de {{count}}')]) ?></p>
    </div>
</div>
