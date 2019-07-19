<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Network\Exception\NotFoundException;

$this->layout = false;

/*if (!Configure::read('debug')):
    throw new NotFoundException('Please replace src/Template/Pages/home.ctp with your own version.');
endif;
*/
$cakeDescription = 'Gestor de SMS para clientes de InteletGroup';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $cakeDescription ?>
    </title>

    <?= $this->Html->meta('icon') ?>
    <?= $this->Html->css('base.css') ?>
    <?= $this->Html->css('cake.css') ?>
    <?= $this->Html->css('home.css') ?>
    <link href="https://fonts.googleapis.com/css?family=Raleway:500i|Roboto:300,400,700|Roboto+Mono" rel="stylesheet">
</head>
<body class="home">

<!--<header class="row">
    <div class="header-image"><?= $this->Html->image('logo-top.png') ?></div>
    <div class="header-title">
        <h1>Bienvenido al Gestor de SMS para clientes de Intelet Group.</h1>
    </div>
</header>-->

<div class="row">
  <div class="columns large-3">
    <div style="color:white;">.</div>
  </div>
  <div class="columns large-6">
    <br>
    <div style="position:relative;margin:0 auto;display:table;"><?= $this->Html->image('logo-top.png') ?></div>
    
    <div class="users form">
<?= $this->Flash->render() ?>
<?= $this->Form->create() ?>
    <fieldset>
        <legend><?=__('Por favor, ingrese su usuario y contraseña') ?></legend>
        <?= $this->Form->control('username',['label'=>'Usuario']) ?>
        <?= $this->Form->control('password',['label'=>__('Contraseña')]) ?>
    </fieldset>
   <?=$this->Html->link('Ir al Validador de SMS','http://200.73.2.67/santander_v2/') ?>
<?= $this->Form->button(__('Entrar')); ?>
<?= $this->Form->end() ?>
</div>

    
    <div style="clear:both;"></div>
    <div class="ctp-warning alert text-center">
            <p><?=__('Recuerde revisar la carga antes de enviar una campa&ntilde;a.')?></p>
    </div>
  </div>
  <div style="color:white;" class="columns large-3">
    .
  </div>
</div>

<div class="row">
  <div style="color:white;" class="columns large-3">
    .
  </div>
  <div class="columns large-6">
    <h4>CARGA ACTUAL</h4>
        <ul>
            <!-- <li class="bullet success"><?php // echo __('En este momento hay una carga m&iacute;nima de ')."$cargaHoraActiva.";?></li>-->
            <li class="bullet success"><?=$this->Html->link('Pedir ayuda en Chat.','https://web.telegram.org/#/im?p=@Juanes1eban',['target'=>'_blank']) ?>
            <!--<li class="bullet problem">Your version of PHP does NOT have the intl extension loaded.</li>-->
        
        </ul>
  </div>
  <div style="color:white;" class="columns large-3">
  .
  </div>
</div>


</body>
</html>
