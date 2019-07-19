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

$cakeDescription = 'Gestor de SMS para clientes de InteletGroup';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('base.css') ?>
    <?= $this->Html->css('cake.css?id='.rand()) ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>

    <?= $this->Html->css('jquery-ui-1.12.1/jquery-ui.min.css') ?>
    <?= $this->Html->css('font-awesome-4.7.0/css/font-awesome.css') ?>
    <?= $this->Html->script('jquery-3.2.1') ?>
    <?= $this->Html->script('tether.min') ?>
    <?= $this->Html->script('jquery-ui-1.12.1/jquery-ui.min') ?>
    <?= $this->Html->script('DataTables-1.10.15/media/js/jquery') ?>
    <?= $this->Html->script('DataTables-1.10.15/media/js/jquery.dataTables') ?>
    

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>

    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet">

      <?= $this->Html->css('smsfront.css') ?>
      <?= $this->Html->css('icomoon/style.css') ?>
      <?= $this->Html->css('dataTables/jquery.dataTables.css') ?>
      <script>
		$(document).ready(function(){
		    $('[data-toggle="tooltip"]').tooltip(); 
		});
		</script>
</head>
<body>
    <nav class="top-bar expanded" data-topbar role="navigation">
        <ul class="title-area large-3-1 medium-4 columns">
            <li class="name">
                <!--<h1><a href=""><?= $this->fetch('title') ?></a></h1>-->
                <?=$this->Html->image("logo-top.png", [
                                            "alt" => "Intelet",
                                            "style"=>"width: 162px;margin: 0 auto;position: relative;display: table;margin-top: 17px;",
                                            "border"=>"0",
                                            'url' => ['controller' => 'listas', 'action' => 'index']])?>
]);
                


            </li>
        </ul>
        <div class="top-bar-section">
            <ul class="right">
                
                <li><?=$this->Html->link('<i style="font-size:20px;" class="fa fa-user-circle" aria-hidden="true"></i>
SALIR','/users/logout',['target'=>'_self','escape'=>false]) ?>
                <!--<li><?=$this->Html->link('AYUDA','https://web.telegram.org/#/im?p=@Juanes1eban',['target'=>'_blank']) ?>-->
                
            </ul>
        </div>
    </nav>
    <?= $this->Flash->render() ?>
    <div class="cake-container-fix container clearfix">
        <?= $this->fetch('content') ?>
    </div>
    <footer>
    </footer>
</body>
</html>

