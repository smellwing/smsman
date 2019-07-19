<?php
/**
  * @var \App\View\AppView $this
  */
$this->assign('title','VER MENSAJE');
?>
<nav class="large-3-1 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Opciones') ?></li>
        <li><?= $this->Html->link(__('Crear mensaje'), ['action' => 'add',$lista_id]) ?> </li>
        <li><?= $this->Html->link(__('Editar mensaje'), ['action' => 'edit', $message->id,$lista_id]) ?> </li>
        <li><?= $this->Form->postLink(__('Quitar mensaje'), ['action' => 'delete', $message->id], ['confirm' => __('Confirme que desea quitar el mensaje de ID # {0}', $message->id),$lista_id]) ?> </li>
        
        
        <li><?= $this->Html->link(__('Volver a mensajes'), ['action' => 'index',$lista_id]) ?> </li>

    </ul>
</nav>
<div class="messages view large-9 medium-8 columns content">

<div class="row formato-mensaje">
    <div class="title-mensaje"><i class="fa fa-file" aria-hidden="true"></i> Mensaje N° <?= h($message->id) ?></div>
    <hr class="hr-mensajes">
    
        <div class="large-5">
            <div class="row labels-mensaje label-mensaje-row" style="width: 100% !important;">
                <div class="large-4 label-bold">
                    &nbsp <?= __('Cliente') ?>
                    
                </div>
                <div class="large-7">
                    <?= $message->client->name?>
                    
                </div>
            </div>
            <div class="row labels-mensaje label-mensaje-row" style="width: 100% !important;">
                <div class="large-4 label-bold">
                    &nbsp <?= __('Creado') ?>
                    
                </div>
                <div class="large-7">
                    <?= h($message->created) ?>
                    
                </div>
            </div>
            <div class="row labels-mensaje label-mensaje-row" style="width: 100% !important;">
                <div class="large-4 label-bold">
                    &nbsp <?= __('Modificado') ?>
                    
                </div>
                <div class="large-7">
                    <?= h($message->modified) ?>
                    
                </div>
            </div>
        </div>
        <div class="large-7 labels-mensaje-msg" style="width:width: 100% !important;">
            <?= h($message->message) ?>
        </div>
    

</div>

    <!--<h3><?= h($message->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Mensaje') ?></th>
            <td><?= h($message->message) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Cliente') ?></th>
            <td><?= $message->client->name?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($message->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Creado') ?></th>
            <td><?= h($message->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modificado') ?></th>
            <td><?= h($message->modified) ?></td>
        </tr>
    </table>-->
</div>
