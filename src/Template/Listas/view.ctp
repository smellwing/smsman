<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="large-3-1 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Opciones') ?></li>
        <li><?= $this->Html->link(__('Editar esta lista'), ['action' => 'edit', $lista->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Quitar esta lista'), ['action' => 'delete', $lista->id], ['confirm' => __('Confirme que desea quitar la lista ID # {0}', $lista->id)]) ?> </li>        
        <li><?= $this->Html->link(__('Crear lista'), ['action' => 'add']) ?> </li>
		<li><?= $this->Html->link(__('Ver lista negra'), ['controller'=>'Blacklists','action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('Ver mensajes'), ['controller' => 'Messages', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('Volver a las listas'), ['action' => 'index']) ?> </li>
    </ul>
</nav>
<div class="listas view large-9 medium-8 columns content">
    <h3><?= h($lista->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Nombre de la lista') ?></th>
            <td><?= h($lista->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($lista->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Creado por') ?></th>
            <td><?= $lista->user->username ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Cliente') ?></th>
            <td><?= $lista->client->name ?></td>
        </tr>
        
        <tr>
            <th scope="row"><?= __('Creada') ?></th>
            <td><?= h($lista->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modificada') ?></th>
            <td><?= h($lista->modified) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Tareas de la lista') ?></h4>
        <?php if (!empty($lista->tasks)): ?>
        <table cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th scope="col"><?= __('Id') ?></th>
                    <th scope="col"><?= __('Nombre') ?></th>
                    <th scope="col"><?= __('Inicio programado') ?></th>
                    <th scope="col"><?= __('Fin programado') ?></th>
                    <th scope="col"><?= __('Lista') ?></th>
                    <th scope="col"><?= __('Mensaje') ?></th>
                    <th scope="col"><?= __('Creado') ?></th>
                    <th scope="col"><?= __('Modificado') ?></th>

                </tr>
            </thead>
            <tbody>
                <?php foreach ($lista->tasks as $tasks): ?>
                <tr>
                    <td><?= h($tasks->id) ?></td>
                    <td><?= h($tasks->name) ?></td>
                    <td><?= h($tasks->datetime_start) ?></td>
                    <td><?= h($tasks->datetime_end) ?></td>
                    <td><?= h($tasks->lista_id) ?></td>
                    <td><?= h($tasks->message_id) ?></td>
                    <td><?= h($tasks->created) ?></td>
                    <td><?= h($tasks->modified) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Cargas Asignadas') ?></h4>
        <?php if (!empty($lista->workloads)): ?>
        <table id="tabla" class="display stripe" cellpadding="0" cellspacing="0">
            <thead> 
                <tr>
                    <th scope="col"><?= __('Id') ?></th>
                    <th scope="col"><?= __('RUT') ?></th>
                    
                    <th scope="col"><?= __('Telefono') ?></th>
                    <th scope="col"><?= __('Lista') ?></th>                
                    <th scope="col"><?= __('Creado') ?></th>
                    <th scope="col"><?= __('Modificado') ?></th>                
                </tr>
            </thead> 
            <tbody>
                <?php foreach ($lista->workloads as $workloads): ?>
                <tr>
                    <td><?= h($workloads->id) ?></td>
                    <td><?= h(number_format($workloads->dni,0,',','.')).'-'.$workloads->dni_dv ?></td>                
                    <td><?= h($workloads->phone) ?></td>
                    <td><?= h($workloads->lista_id) ?></td>

                    <td><?= h($workloads->created) ?></td>
                    <td><?= h($workloads->modified) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
    <br><br>
</div>



<script type="text/javascript">
    $('#tabla').DataTable({
        "order": [[ 0, "asc" ]],
        stateSave: true,
        
        "language": {
            "decimal":        "",
            "emptyTable":     "sin datos disponibles",
            "info":           "Mostrando _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty": "Sin registros disponibles",
            "infoFiltered": "(filtrado de _MAX_ registros totales)",
            "infoPostFix":    "",
            "thousands":      ",",
            "lengthMenu": "Mostrando _MENU_registros por pagina",
            "loadingRecords": "Cargando...",
            "processing":     "Procesando...",
            "search":         "Buscar:",
            "zeroRecords": "No se encontraron registros",
            "paginate": {
                "first":      "Primer",
                "last":       "Ultimo",
                "next":       "Siguiente",
                "previous":   "Anterior"
            },
            "aria": {
                "sortAscending":  ": activate to sort column ascending",
                "sortDescending": ": activate to sort column descending"
            }
        }
    });    
</script>