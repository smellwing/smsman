DESTINATARIO;RUT;ESTADO MENSAJE;LYRIC;CANAL;FECHA CARGA;FECHA ENVIO;FECHA ENTREGA;MENSAJE
		<?php 
		$comodines = ['#{rut}','#{nombre}','#{deuda}','#{sucursal}','#{link}','#{cuenta}'];		
		foreach ($reports as $report):  
		echo $report->workload->phone;
		echo ";";
		echo number_format($report->workload->dni,0,',','.').'-'.$report->workload->dni_dv;
		echo ";";
		echo $report->estado_entrega;
		echo ";";
		echo isset($report->lyric->ip)?$report->lyric->ip:'--';
		echo ";";
		echo isset($report->lyric->channel)?$report->lyric->channel:'--';
		echo ";";
		echo $report->recv_date_tz;
		echo ";";
		echo $report->send_date_tz;
		echo ";";
		echo $report->delivery_date_tz;
		echo ";";
		$RUT = $report->workload->dni.'-'.$report->workload->dni_dv;
		$reemplazo = [$RUT,$report->workload->nombre,'$'.number_format($report->workload->deuda,0,',','.'),$report->workload->sucursal,$report->workload->link,$report->workload->cuenta];
			if(!isset($mensaje)&&!empty($report->workload->message->message))
			    $mensaje = ($report->workload->message->message);
			 else $mensaje = null;
			 $sms = str_ireplace($comodines, $reemplazo, $mensaje);
			 $mensaje = null;	unset($mensaje);
			echo $sms;
			echo '
        ';
		endforeach;
		
		?>
