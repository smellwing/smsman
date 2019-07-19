
<?php

/**
  * @var \App\View\AppView $this
  */
$this->assign('title','TAREAS');
?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script type="text/javascript">
		
		 
		google.charts.load('current', {'packages':['corechart','table']});
		google.charts.setOnLoadCallback(drawChart);
		google.charts.setOnLoadCallback(drawTable);
			
		function drawChart() {
						
			 var jsonData = $.ajax({
		          url: "../../pieChart/<?=$task->id?>",
		          dataType: "json",
		          async: false
		     }).responseText;		          
		          
			var data = new google.visualization.DataTable($.parseJSON(jsonData));
			var options = {
				title: 'Estado de la tarea',
				sliceVisibilityThreshold: 0
			};		

			var chart = new google.visualization.PieChart(document.getElementById('piechart'));
			
            chart.draw(data, options);

            setInterval(function() {
                
            	 var jsonData = $.ajax({
   		          url: "../../pieChart/<?=$task->id?>",
   		          dataType: "json",
   		          async: false
   		     }).responseText;	
            	var data = new google.visualization.DataTable($.parseJSON(jsonData));				
				var chart = new google.visualization.PieChart(document.getElementById('piechart'));
								
	            chart.draw(data, options);
	          }, 10000);
		}

		 function drawTable() {
			 var jsonData = $.ajax({
		          url: "../../tableChart/<?=$task->id?>",
		          dataType: "json",
		          async: false
		     }).responseText;		          
		          
			 var data = new google.visualization.DataTable($.parseJSON(jsonData));
		        

		        var table = new google.visualization.Table(document.getElementById('tablediv'));

		        table.draw(data, { width: '100%', height: '185px'});
		        
		        setInterval(function() {
		        	 var jsonData = $.ajax({
				          url: "../../tableChart/<?=$task->id?>",
				          dataType: "json",
				          async: false
				     }).responseText;		          
				          
					 var data = new google.visualization.DataTable($.parseJSON(jsonData));
				        

				        var table = new google.visualization.Table(document.getElementById('tablediv'));

				        table.draw(data, { width: '100%', height: '185px'});
		          }, 10000);
		        
		      }
				
</script>


<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Opciones') ?></li>
        <li><?= $this->Html->link(__('Volver a las Tareas Envíos'), ['action' => 'index',$lista_id]) ?> </li>
        <li><?= $this->Html->link(__('Editar esta Tarea de Envío'), ['action' => 'edit', $task->id, $lista_id]) ?> </li>
        <li><?= $this->Form->postLink(__('Quitar esta Tarea de Envío'), ['action' => 'delete', $task->id], ['confirm' => __('Confirme que desea quitar la tarea de ID # {0}', $task->id)]) ?> </li>        
        <li><?= $this->Html->link(__('Nueva Tarea de Envío'), ['action' => 'add',$lista_id]) ?> </li>
        <li><?= $this->Html->link(__('Ver Campañas'), ['controller' => 'Listas', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('Ver lista negra'), ['controller'=>'Blacklists','action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('Ver mensajes'), ['controller' => 'Messages', 'action' => 'index',$lista_id]) ?> </li>

    </ul>
</nav>
<div class="tasks view large-9 medium-8 columns content">
    <h3><?= h($task->name) ?></h3>
    <div id="tablediv" style="width: 100%; "></div>
    <div id="piechart" style="width: 100%; height: 500px;"></div>
     <br>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Nombre') ?></th>
            <td><?= h($task->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Lista') ?></th>
            <td><?= $task->has('lista') ? $this->Html->link($task->lista->name, ['controller' => 'Listas', 'action' => 'view', $task->lista->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Mensaje') ?></th>
            <td><?= $task->has('message') ? $this->Html->link($task->message->id, ['controller' => 'Messages', 'action' => 'view', $task->message->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($task->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Inicio programado') ?></th>
            <td><?= h($task->datetime_start) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Fin programado') ?></th>
            <td><?= h($task->datetime_end) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Creada') ?></th>
            <td><?= h($task->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modificada') ?></th>
            <td><?= h($task->modified) ?></td>
        </tr>
    </table>
    
     
</div>

