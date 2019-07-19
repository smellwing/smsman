<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Opciones') ?></li>
        <li><?= $this->Html->link(__('Crear'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('Volver'), "javascript:window.history.back();") ?></li>
    </ul>
</nav>
<div class="links index large-9 medium-8 columns content">
    <h3><?= __('Links') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('url','link') ?></th>
                <th scope="col"><?= $this->Paginator->sort('hash','link intelet') ?></th>                
                <th scope="col"><?= $this->Paginator->sort('url_google','goo.gl') ?></th>
                <th scope="col"><?= $this->Paginator->sort('visits','Visitas') ?></th>
                <th scope="col"><?= $this->Paginator->sort('visited','Visitado') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created','Creado') ?></th>
                <th scope="col" class="actions"><?= __('Acciones') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($links as $link): ?>
            <tr>
                <td><?php
                echo $this->Html->link("<i class='fa fa-external-link'></i>",$link->url,['target'=>'_blank','title'=>'Abrir enlace','class' => 'add', 'escape' => false,]);
                echo "&nbsp;";
                echo $this->html->link($link->url,$link->url,['target'=>'_blank']) ?></td>
                <td><?php 
                echo $this->Html->link("<i class='fa fa-external-link'></i>",$uri.$link->hash,['target'=>'_blank','title'=>'Abrir enlace','class' => 'add', 'escape' => false,]);
                echo "&nbsp;";
                echo $this->html->link($uri.$link->hash,$uri.$link->hash,['target'=>'_blank']) ?></td>                
                <td><?php
                echo $this->Html->link("<i class='fa fa-external-link'></i>",$link->url_google,['target'=>'_blank','title'=>'Abrir enlace','class' => 'add', 'escape' => false,]);
                echo "&nbsp;";
                echo $this->html->link($link->url_google,$link->url_google,['target'=>'_blank']) ?></td>
                <td><?= $this->Number->format($link->visits)?></td>
                
                <td><?= h($link->visited) ?></td>
                <td><?= h($link->created) ?></td>
                <td class="actions">
                    <?= $this->Html->link("<i class='fa fa-list-ol' ></i>", ['controller'=>'linksIps','action' => 'view', $link->id],['title'=>'Detalle visitas','class' => 'add', 'escape' => false,]) ?>
                    <?= $this->Html->link("<i class='fa fa-edit'></i>", ['action' => 'edit', $link->id],['title'=>'Editar link','class' => 'add', 'escape' => false,]) ?>
                    <?= $this->Form->postLink("<i class='fa fa-remove'></i>", ['action' => 'delete', $link->id], ['confirm' => __('Confirme que desa elimnar el link', $link->id),'title'=>'Eliminar link','class' => 'add', 'escape' => false,]) ?>
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
            <?= $this->Paginator->last(__('ultima') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Pagina {{page}} de {{pages}}, mostrando {{current}} registro(s) de {{count}}')]) ?></p>
    </div>
</div>
