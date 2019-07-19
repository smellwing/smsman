<?php
/**
  * @var \App\View\AppView $this
  */

?>

<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Acconess') ?></li>        
        <li><?= $this->Html->link(__('Volver a campañas'), ['controller' => 'Listas', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('Ver tareas de envío'), ['controller' => 'Tasks', 'action' => 'index',$lista_id]) ?> </li>
        <li><?= $this->Html->link(__('Volver a los destinatarios'), ['controller' => 'Workloads', 'action' => 'index',$lista_id]) ?></li>
        
    </ul>
</nav>
<div class="workloads form large-9 medium-8 columns content">
CORRECTOS
	<table>
		<thead>
			<tr>
				<th>RUT</th><th>TELEFONO</th>
			</tr>			
		</thead>
		<tbody>
		<?php foreach($correcto as $linea):?>
			<tr>
				<td><?=$linea['dni']?></td><td><?=$linea['telefono']?></td>
			</tr>
		<?php endforeach;?>
		</tbody>
	</table>    
NO CORRECTOS
	<table>
		<thead>
			<tr>
				<th>RUT</th><th>TELEFONO</th><th>MOTIVO</th>
			</tr>			
		</thead>
		<tbody>
		<?php foreach($incorrecto as $linea):?>
			<tr>
				<td><?=empty($linea['dni'])?'--':$linea['dni']?></td>
				<td><?=empty($linea['telefono'])?'--':$linea['telefono']?></td>
				<td><?=$linea['error']?></td>
			</tr>
		<?php endforeach;?>
		</tbody>
	</table>
</div>
