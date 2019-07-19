   <table  cellpadding="0" cellspacing="0">
        <thead>
            <tr>
            
                <th scope="col"><?= $this->Paginator->sort('ip') ?></th>
                <th scope="col"><?= $this->Paginator->sort('username') ?></th>
                <th scope="col"><?= $this->Paginator->sort('password') ?></th>
                <th scope="col"><?= $this->Paginator->sort('web_username') ?></th>
                <th scope="col"><?= $this->Paginator->sort('web_password') ?></th>
                <th scope="col"><?= $this->Paginator->sort('channel') ?></th>
                <th scope="col"><?= $this->Paginator->sort('api') ?></th>
                <th scope="col"><?= $this->Paginator->sort('active','Activado') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody id="ips">
            <?php foreach ($lyrics as $lyric): ?>
            <tr>
            
                <td><?= h($lyric->ip) ?></td>
                <td><?= h($lyric->username) ?></td>
                <td><?= h($lyric->password) ?></td>
                <td><?= h($lyric->web_username) ?></td>
                <td><?= h($lyric->web_password) ?></td>
                <td><?= $this->Number->format($lyric->channel) ?></td>
                <td><?= $this->Number->format($lyric->api) ?></td>
                <td>
                
                <?= $this->Form->postLink(($lyric->active)?'Activada':'Deshabilitada', ['action' => 'active', $lyric->id,$lyric->ip], []) ?>
                
                </td>
                <td class="actions">
                	<?= $this->Form->postLink(($lyric->active)?'Deshabilitar':'Activar', ['action' => 'active', $lyric->id,$lyric->ip], []) ?><br>
                    <?= $this->Html->link(__('View'), ['action' => 'view', $lyric->id,$lyric->ip]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $lyric->id,$lyric->ip]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $lyric->id,$lyric->ip], ['confirm' => __('Are you sure you want to delete # {0}?', $lyric->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>   