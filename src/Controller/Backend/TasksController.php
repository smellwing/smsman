<?php
namespace App\Controller\Backend;

use App\Controller\AppController;
use Cake\I18n\Time;
/**
 * Tasks Controller
 *
 * @property \App\Model\Table\TasksTable $Tasks
 */
class TasksController extends AppController
{
	public function crealistaphonesms($task_id){
		$this->autoRender = false;
		$createTask = false;
		/*
		 * Para CREAR una LISTA en SMS
		 * http://192.168.1.120/api_mass/request.php?tipo=HEAD&codcli=ABC&camp=PRUEBANOMBRE
		 */
		$time = new Time('now','America/Santiago');
		 
		//Estos horarios están en America/Santiago
		$queryHoraInicio = $this->Tasks->find();
		$queryHoraInicio->select(['datetime_start'])->where(['Tasks.id'=>$task_id]);
		$rsHoraInicio = $queryHoraInicio->first();
		$horaInicio = $rsHoraInicio->datetime_start;
	
	
		$diff = $horaInicio->toUnixString()-$time->toUnixString();
		//10 minutos de margen.
		if(abs($diff)<(10*60*60)) $createTask = true;
		 
		//Si CreateTask es TRUE creará una LISTA en PhoneSMS
		if($createTask){
			//$tasksTable = TableRegistry::get('Tasks');
			$task = $this->Tasks->get($task_id);
			//Verifica su la tarea ya tiene la lista de phonesms asociada.
			if(is_null($task->yx_lista_id)){ 
				
			$lista_id = $task->lista_id;
			$lista = $this->Tasks->Listas->get($lista_id);
			$client_id = $lista->client_id;
			
			$client = $this->Tasks->Listas->Clients->get($client_id);			
			$codCli = urlencode($client->codcli);
			$listaNom = urlencode($task->name);
			
			$jsonurl = "http://192.168.1.120/api_mass/request.php?tipo=HEAD&codcli=$codCli&camp=$listaNom";
			//llama a la plataforma.
			$JSON = file_get_contents($jsonurl);
			$phoneSmsResponse = json_decode($JSON);
			
			if($phoneSmsResponse->success){				
				$task->yx_lista_id = $phoneSmsResponse->lista_id;
				$save = $this->Tasks->save($task);
				if($save) {
					return $this->redirect(['action' => 'cargalistaphonesms',$task_id]);
					
				}
				else {
					$this->Flash->error(__('Error al crear la tarea en PhoneSMS. Intente nuevamente,'));
					return $this->redirect(['action' => 'index']);
				}
		}}}	
		 
	}
	
	public function cargalistaphonesms($task_id){	
		$task = $this->Tasks->get($task_id);
		
		$yx_lista_id = $task->yx_lista_id;
		$msg_id = $task->message_id;
		
		$msg = $this->Tasks->Messages->get($msg_id);
		
		//Codifica el mensaje para ser enviado desde le JSON
		$mensaje = urlencode($msg->message);
		$lista_id = $task->lista_id;
		$lista = $this->Tasks->Listas->get($lista_id);
		$client_id = $lista->client_id;
			
		$client = $this->Tasks->Listas->Clients->get($client_id);
		$codCli = urlencode($client->codcli);
		
		$queryCargas = $this->Tasks->Listas->Workloads->find(); 
		$queryCargas->select(['phone'])->where(['Workloads.lista_id'=>$lista_id]);
		foreach($queryCargas as $rs){
			$jsonurl = "http://192.168.1.120/api_mass/request.php?tipo=SMS&codcli=$codCli&fono=$rs->phone&msg=$mensaje&lista_id=$yx_lista_id";			
			$JSON = file_get_contents($jsonurl);
			$phoneSmsResponse = json_decode($JSON);				
		}
	}
	
	public function activalistaphonesms($task_id){
		//http://192.168.1.120/api_mass/request.php?tipo=FOOT&codcli=ALL&lista_id=15716
		$task = $this->Tasks->get($task_id);		
		$yx_lista_id = $task->yx_lista_id;		
		
		$lista_id = $task->lista_id;
		$lista = $this->Tasks->Listas->get($lista_id);
		$client_id = $lista->client_id;
			
		$client = $this->Tasks->Listas->Clients->get($client_id);
		$codCli = urlencode($client->codcli);
		
		$queryCargas = $this->Tasks->Listas->Workloads->find();
		$queryCargas->select(['phone'])->where(['Workloads.lista_id'=>$lista_id]);
		foreach($queryCargas as $rs){			
			$jsonurl = "http://192.168.1.120/api_mass/request.php?tipo=FOOT&codcli=$codCli&lista_id=$yx_lista_id";
			$JSON = file_get_contents($jsonurl);
			$phoneSmsResponse = json_decode($JSON);
			if($phoneSmsResponse->success) {
				///
			}
		}
	}
	
	public function phonestatusphonesms($task_id,$telefono){
		//http://192.168.1.120/api_mass/request.php?tipo=PHONE_STATUS&lista_id=15716&fono=998115373&codcli=ALL
		$task = $this->Tasks->get($task_id);
		$yx_lista_id = $task->yx_lista_id;
		
		$lista_id = $task->lista_id;
		$lista = $this->Tasks->Listas->get($lista_id);
		$client_id = $lista->client_id;
			
		$client = $this->Tasks->Listas->Clients->get($client_id);
		$codCli = urlencode($client->codcli);
		
		$jsonurl = "http://192.168.1.120/api_mass/request.php?tipo=PHONE_STATUS&lista_id=$yx_lista_id&fono=$telefono&codcli=$codCli";
		$JSON = file_get_contents($jsonurl);
		$phoneSmsResponse = json_decode($JSON);
		debug($phoneSmsResponse);
	}
	
	public function phonelistphonesms($task_id){
		
		$task = $this->Tasks->get($task_id);
		$yx_lista_id = $task->yx_lista_id;
		
		$lista_id = $task->lista_id;
		$lista_name = urlencode($task->name);
		$lista = $this->Tasks->Listas->get($lista_id);
		$client_id = $lista->client_id;
			
		$client = $this->Tasks->Listas->Clients->get($client_id);
		$codCli = urlencode($client->codcli);
		
		$jsonurl = "http://192.168.1.120/api_mass/request.php?tipo=PHONE_LIST&camp=$lista_name&codcli=$codCli";
		$JSON = file_get_contents($jsonurl);
		$phoneSmsResponse = json_decode($JSON);
		debug($phoneSmsResponse);
		
	}
}
