<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Client'), ['action' => 'edit', $client->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Client'), ['action' => 'delete', $client->id], ['confirm' => __('Are you sure you want to delete # {0}?', $client->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Clients'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Client'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Blacklists'), ['controller' => 'Blacklists', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Blacklist'), ['controller' => 'Blacklists', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Listas'), ['controller' => 'Listas', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Lista'), ['controller' => 'Listas', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Messages'), ['controller' => 'Messages', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Message'), ['controller' => 'Messages', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="clients view large-9 medium-8 columns content">
    <h3><?= h($client->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($client->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Codcli') ?></th>
            <td><?= h($client->codcli) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Tipo') ?></th>
            <td><?= h($client->tipo) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($client->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Yx Login Id') ?></th>
            <td><?= $this->Number->format($client->yx_login_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($client->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($client->modified) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Blacklists') ?></h4>
        <?php if (!empty($client->blacklists)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Dni') ?></th>
                <th scope="col"><?= __('Dni Dv') ?></th>
                <th scope="col"><?= __('Phone') ?></th>
                <th scope="col"><?= __('Client Id') ?></th>
                <th scope="col"><?= __('Lista Negra Id') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($client->blacklists as $blacklists): ?>
            <tr>
                <td><?= h($blacklists->id) ?></td>
                <td><?= h($blacklists->dni) ?></td>
                <td><?= h($blacklists->dni_dv) ?></td>
                <td><?= h($blacklists->phone) ?></td>
                <td><?= h($blacklists->client_id) ?></td>
                <td><?= h($blacklists->lista_negra_id) ?></td>
                <td><?= h($blacklists->created) ?></td>
                <td><?= h($blacklists->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Blacklists', 'action' => 'view', $blacklists->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Blacklists', 'action' => 'edit', $blacklists->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Blacklists', 'action' => 'delete', $blacklists->id], ['confirm' => __('Are you sure you want to delete # {0}?', $blacklists->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Listas') ?></h4>
        <?php if (!empty($client->listas)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('User Id') ?></th>
                <th scope="col"><?= __('Client Id') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($client->listas as $listas): ?>
            <tr>
                <td><?= h($listas->id) ?></td>
                <td><?= h($listas->name) ?></td>
                <td><?= h($listas->user_id) ?></td>
                <td><?= h($listas->client_id) ?></td>
                <td><?= h($listas->created) ?></td>
                <td><?= h($listas->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Listas', 'action' => 'view', $listas->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Listas', 'action' => 'edit', $listas->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Listas', 'action' => 'delete', $listas->id], ['confirm' => __('Are you sure you want to delete # {0}?', $listas->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Messages') ?></h4>
        <?php if (!empty($client->messages)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Message') ?></th>
                <th scope="col"><?= __('Client Id') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($client->messages as $messages): ?>
            <tr>
                <td><?= h($messages->id) ?></td>
                <td><?= h($messages->message) ?></td>
                <td><?= h($messages->client_id) ?></td>
                <td><?= h($messages->created) ?></td>
                <td><?= h($messages->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Messages', 'action' => 'view', $messages->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Messages', 'action' => 'edit', $messages->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Messages', 'action' => 'delete', $messages->id], ['confirm' => __('Are you sure you want to delete # {0}?', $messages->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Users') ?></h4>
        <?php if (!empty($client->users)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Firstname') ?></th>
                <th scope="col"><?= __('Lastname') ?></th>
                <th scope="col"><?= __('Username') ?></th>
                <th scope="col"><?= __('Password') ?></th>
                <th scope="col"><?= __('Email') ?></th>
                <th scope="col"><?= __('Role Id') ?></th>
                <th scope="col"><?= __('Client Id') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($client->users as $users): ?>
            <tr>
                <td><?= h($users->id) ?></td>
                <td><?= h($users->firstname) ?></td>
                <td><?= h($users->lastname) ?></td>
                <td><?= h($users->username) ?></td>
                <td><?= h($users->password) ?></td>
                <td><?= h($users->email) ?></td>
                <td><?= h($users->role_id) ?></td>
                <td><?= h($users->client_id) ?></td>
                <td><?= h($users->created) ?></td>
                <td><?= h($users->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Users', 'action' => 'view', $users->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Users', 'action' => 'edit', $users->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Users', 'action' => 'delete', $users->id], ['confirm' => __('Are you sure you want to delete # {0}?', $users->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
