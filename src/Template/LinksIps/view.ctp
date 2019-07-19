<?php
/**
 * @var \App\View\AppView $this
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Opcion') ?></li>
        <li><?= $this->Html->link(__('Volver'), "javascript:window.history.back();") ?></li>
    </ul>
</nav>
<div class="linksIps index large-9 medium-8 columns content">
    <h3><?= __('Visitas') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col"><?= $this->Paginator->sort('links_id','LINK') ?></th>
                <th scope="col"><?= $this->Paginator->sort('ip') ?></th>
                <th scope="col"><?= $this->Paginator->sort('http_user_agent','Navegador') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created','Visitado') ?></th>
                
             
            </tr>
        </thead>
        <tbody>
            <?php 
            $i=0;
            foreach ($linksIps as $linksIp): ?>
            <tr>
                <td><?= $this->Number->format(++$i) ?></td>
                <td><?= $linksIp->has('link') ? $this->Html->link($linksIp->link->url, ['controller' => 'Links', 'action' => 'view', $linksIp->link->id]) : '' ?></td>
                <td><?= h($linksIp->ip) ?></td>
                <td><?= h($linksIp->http_user_agent) ?></td>
                
                <td><?= h($linksIp->created) ?></td>
                
             
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
