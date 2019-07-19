<?php $this->assign('title','INGRESAR'); ?>
<div class="users form">
<?= $this->Flash->render() ?>
<?= $this->Form->create() ?>
    <fieldset>
        <legend><?=utf8_encode('Por favor, ingrese su usuario y contraseņa') ?></legend>
        <?= $this->Form->control('username',['label'=>'Usuario']) ?>
        <?= $this->Form->control('password',['label'=>utf8_encode('Contraseņa')]) ?>
    </fieldset>
<?= $this->Form->button(__('Entrar')); ?>
<?= $this->Form->end() ?>
</div>