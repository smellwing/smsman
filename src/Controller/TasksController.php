<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
//use Cake\Chronos\Date;
use Cake\I18n\Time;
use App\Controller\Backend;
/**
 * Tasks Controller
 *
 * @property \App\Model\Table\TasksTable $Tasks
 */
class TasksController extends AppController
{

	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('Auth');
	}
    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index($lista_id)
    {
    	//debug($this->Tasks->Listas->Workloads->Sentlogs->find('all')->toArray());
        $this->paginate = [
            'contain' => ['Listas'=>['Workloads'], 'Messages']
        	,'order'	=>['Tasks.datetime_start'=>'DESC']
        	,'conditions'=>['Tasks.lista_id'=>$lista_id] 
        ];

        $this->loadModel('Tips');
        $tip_qry = $this->Tips->find();
        $tip_qry->select(['id','tip']);
        $tips = $tip_qry->toArray();
        $tip = $tips[rand(0,$tip_qry->count()-1)]['tip'];
        
        //Necesistamos la carga        
        $carga_maxima_hora = $this->Tasks->getCargaMaxima();
        
        $tasks = $this->paginate($this->Tasks);

        //Obtengo el nombre de la lista
        $nombreLista = $this->Tasks->Listas->get($lista_id)->name;        
        //Obtengo la carga de la lista
        $cantidadCarga = $this->Tasks->Listas->Workloads->cuentaCargaLista($lista_id);
        //Calculo el tiempo de la carga
        
        $time = $cantidadCarga/$carga_maxima_hora;        
        $tiempoCarga = sprintf('%02d:%02d', (int) ($time), (fmod($time, 1) * 60));
        
        //Obtengo la carga de PhoneSMS
        $tiempoRestanteCargaPhoneSMS = $this->calculoCargaPhoneSMS();
      	 ;
      	 $user = $this->Auth->user();
      	 $this->loadModel('Roles');
      	 $role = $this->Roles->get($user['role_id']);
      	 $cuota_diaria = null;
      	 $tasks_ids = [];
      	 if(($user['cuota']!=0)||($role->name!='Administrador')){
      	     $qry_listas = $this->Tasks->Listas->find();
      	     $qry_listas->select(['id']);
      	     
      	     $qry_listas->where(['Listas.user_id'=>$user['id']]);
      	     foreach ($qry_listas as $lista){
      	         $listas_ids[]=$lista->id;
      	     }; 
      	     
      	     //
      	     
      	     $qry_wls = $this->Tasks->Listas->Workloads->find();
      	     if(!empty($listas_ids))
      	         $qry_wls->where(['Workloads.lista_id IN'=>$listas_ids,'DATE(Workloads.created) LIKE DATE(NOW())']);
      	         else $qry_wls->where(['DATE(Workloads.created) LIKE DATE(NOW())']);
      	     $cuota_diaria = $user['cuota'] - $qry_wls->count();
      	  
      	    /* $qry_tasks = $this->Tasks->find();
      	     $qry_tasks->select(['id']);
      	     $qry_tasks->where(['Tasks.lista_id IN'=>$listas_ids,'DATE(Tasks.created) LIKE DATE(NOW())']);
      	     
      	     foreach ($qry_tasks as $task){
      	         $tasks_ids[]=$task->id;
      	     };
      	     if(count($tasks_ids) != 0) {
      	         
      	         
          	     $qry_sentlogs = $this->Tasks->Sentlogs->find();
          	     $qry_sentlogs->where(['Sentlogs.task_id IN'=>$tasks_ids]);
          	     
          	     $cuota_diaria = (int)$user['cuota']-$qry_sentlogs->count();
      	     } else $cuota_diaria = $user['cuota'];*/
      	     if($cuota_diaria<0) $cuota_diaria = 0;
      	     if(is_null($cuota_diaria)) $cuota_diaria = 0;
      	 }
      	 
      	 
        $this->set(compact('tasks','nombreLista','tiempoRestanteCargaPhoneSMS','tip'));
        $this->set(compact('cantidadCarga','tiempoCarga','lista_id','cuota_diaria'));
        $this->set('_serialize', ['tasks']);
    }

    /**
     * Obtiene la carga ACTIVA y PAUSADA directo del PHONESMS
     * @return StdClass
     */
    public function calculoCargaPhoneSMS(){
    	$cargaPhoneSMS = $this->Tasks->cargaListasActivaPhoneSMS();
    	$cm = $this->Tasks->getCargaMaxima();
    	$time = $cargaPhoneSMS/$cm;
    	$cargaHoraActiva = sprintf('%02d:%02d', (int) $time, fmod($time, 1) * 60);
    	
    	$cargaPhoneSMS = $this->Tasks->cargaListasPausadaPhoneSMS();
    	$time = $cargaPhoneSMS/$cm;
    	
    	$cargaHoraPausada = sprintf('%02d:%02d', (int) $time, fmod($time, 1) * 60);
    	 
    	return (object)['ACTIVA'=>$cargaHoraActiva,'PAUSADA'=>$cargaHoraPausada];
    }

    /**
     * View method
     *
     * @param string|null $id Task id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($task_id,$lista_id)
    {
        $task = $this->Tasks->get($task_id, [
            'contain' => ['Listas', 'Messages']
        ]);
        
        $yx_lista_id = $task->yx_lista_id;
        if(empty($task->lyric)){
            if(!empty($yx_lista_id)) {        	
       	
            $estadisticas = $this->Tasks->getListStatus($yx_lista_id);            
        
            
        
    }}
    else{
        
        $estadisticas = $this->Tasks->getListLyricStatus($task_id);
        
    };
    $this->set('estadisticas', $estadisticas);
    $this->set('lista_id', $lista_id);
    $this->set('task', $task);
    $this->set('_serialize', ['task']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($lista_id)
    {
        $task = $this->Tasks->newEntity();
        $now = new Time('now','America/Santiago');
        
        
        if ($this->request->is('post')) {        
        	
        	$data = $this->request->getData();     	
        	
        	$start_minutes = $data['datetime_start'];
        	
        	
        	$start = "+ $start_minutes minutes";        	
        	$data['datetime_start'] = new Time($start ,'America/Santiago');
        	$data['datetime_end'] = new Time(date("Y-m-d 20:00:00") ,'America/Santiago');
        	        	
        	//Vemos la carga proyectada.
        	$horarios_proyectados = $this->Tasks->proyectarCarga($data['datetime_start'], $data['datetime_end'], $lista_id);
        	$carga_valida = true;
        	$cadena = $data['name'];
        	$originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
        	$modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
        	$cadena = utf8_decode($cadena);
        	$cadena = strtr($cadena, utf8_decode($originales), $modificadas);
        	$cadena = strtolower($cadena);
        	$data['name'] = utf8_encode($cadena);
        	
        	if(isset($data['msjCarga'])&&($data['msjCarga']==1)){
        	    $data['message_id'] = null;
        	}
        	    
        	
        	foreach($horarios_proyectados as $hora=>$carga_proyectada){
        		if($carga_proyectada>1) {
        			$carga_valida = false;
        			break;
        		}
        	}
        	
//        $carga_valida = true;	
        	if($carga_valida) {       		
        	
            	$task = $this->Tasks->patchEntity($task, $data);
            	if ($this->Tasks->save($task)) {
                	$this->Flash->success(__('Tarea creada.'));
                	return $this->redirect(['action' => 'index',$lista_id]);
            	}
            	$this->Flash->error(__('Error al intentar crear.'));
        	}
        	else $this->Flash->error(__('Error al intentar crear, el horario elegido no permite esta carga.'));
        }
        //permisos
        if(!$this->isAdmin){
        	$listas = $this->Tasks->Listas->find('list', ['conditions' => ['Listas.id'=>$lista_id,'Listas.client_id IN'=>$this->clienteId]]);        	
        }
        else 
        	$listas = $this->Tasks->Listas->find('list', ['conditions' => ['Listas.id'=>$lista_id]]);
        
        $conditions_messages = null;
        
        if(!$this->isAdmin){	$conditions_messages = ['Messages.client_id IN'=>$this->clienteId,'Messages.hidden'=>0];}
        else { $conditions_messages = ['Messages.hidden'=>0];}
        $messages = $this->Tasks->Messages->find('list', ['limit' => 200,'conditions'=>$conditions_messages, 'order'=>['id'=>'DESC']]);
        
        $horarios = [0=>'AHORA',15=>'+15 minutos',30=>'+30 minutos',60=>'+1 hora',120=>'+2 horas',240=>'+4 horas'];
        //$duracion = [15=>'15 minutos',30=>'30 minutos',60=>'1 hora',120=>'2 horas',240=>'4 horas',360=>'6 horas',480=>'8 horas'];
         
        $cargaLista = $this->Tasks->Listas->Workloads->cuentaCargaLista($lista_id)/$this->Tasks->getCargaMaxima();
        $cargaConMsg_qry = $this->Tasks->Listas->Workloads->find();
        $cargaConMsg_qry->where(['Workloads.lista_id'=>$lista_id,'Workloads.message_id IS NOT'=>null]);
        
        $cargaSinMsg_qry = $this->Tasks->Listas->Workloads->find();
        $cargaSinMsg_qry->where(['Workloads.lista_id'=>$lista_id,'Workloads.message_id IS'=>null]);
        
        $msjEnCarga = false;
        if($cargaSinMsg_qry->count()==0){
            if($cargaConMsg_qry->count()>0) $msjEnCarga = true;
        }
        $carga_maxima_hora = $this->Tasks->getCargaMaxima();
        $tiempoCarga = sprintf('%02d:%02d', (int) ($cargaLista), (fmod($cargaLista, 1) * 60));
        
        $this->set(compact('task', 'listas', 'messages','horarios','duracion','tiempoCarga','lista_id','carga_maxima_hora','msjEnCarga'));
        $this->set('_serialize', ['task']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Task id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null,$lista_id)
    {
        $task = $this->Tasks->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            //$task = $this->Tasks->patchEntity($task, $this->request->getData());
            
            $data = $this->request->getData();
             
            $start_minutes = $data['datetime_start'];
            
             
            $start = "+ $start_minutes minutes";
            $data['datetime_start'] = new Time($start ,'America/Santiago');
             
            $data['datetime_end'] = new Time(date("Y-m-d 20:00:00") ,'America/Santiago');
             
            //Vemos la carga proyectada.
            $horarios_proyectados = $this->Tasks->proyectarCarga($data['datetime_start'], $data['datetime_end'], $lista_id);
            $carga_valida = true;
            
            
            if(isset($data['msjCarga'])&&($data['msjCarga']==1)){
                $data['message_id'] = null;
            }
            
            if($carga_valida) {
            	 
            	$task = $this->Tasks->patchEntity($task, $data);
            	if ($this->Tasks->save($task)) {
            		$this->Flash->success(__('Tarea creada.'));
            		return $this->redirect(['action' => 'index',$lista_id]);
            	}
            	$this->Flash->error(__('Error al intentar crear.'));
            }
            else $this->Flash->error(__('Error al intentar crear, el horario elegido no permite esta carga.'));            
            $this->Flash->error(__('Error. Intente de nuevo.'));
        }
        $horarios = [0=>'AHORA',15=>'+15 minutos',30=>'+30 minutos',60=>'+1 hora',120=>'+2 horas',240=>'+4 horas'];
        $duracion = [15=>'15 minutos',30=>'30 minutos',60=>'1 hora',120=>'2 horas',240=>'4 horas'];
        $msjEnCarga = false;
        $cargaConMsg_qry = $this->Tasks->Listas->Workloads->find();
        $cargaConMsg_qry->where(['Workloads.lista_id'=>$lista_id,'Workloads.message_id IS NOT'=>null]);
        
        $cargaSinMsg_qry = $this->Tasks->Listas->Workloads->find();
        $cargaSinMsg_qry->where(['Workloads.lista_id'=>$lista_id,'Workloads.message_id IS'=>null]);
        
        if($cargaSinMsg_qry->count()==0){
            if($cargaConMsg_qry->count()>0) $msjEnCarga = true;
        }
        $conditions_listas = null;
        $conditions_messages = null;
        if(!$this->isAdmin){
        	$conditions_listas = ['Listas.client_id IN'=>$this->clienteId];
        	$conditions_messages = ['Messages.client_id IN'=>$this->clienteId,'Messages.hidden'=>0];
        }
        else { $conditions_messages = ['Messages.hidden'=>0];}
        $listas = $this->Tasks->Listas->find('list', [
        		'limit' => 200,
        		'conditions'=>$conditions_listas
        ]);
        $messages = $this->Tasks->Messages->find('list', [
        		'limit' => 200,
        		'conditions'=>$conditions_messages
        		
        ]);
        $this->set(compact('task', 'listas', 'messages','horarios','duracion','lista_id','msjEnCarga'));
        $this->set('_serialize', ['task']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Task id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $task = $this->Tasks->get($id);
        if ($this->Tasks->delete($task)) {
            $this->Flash->success(__('The task has been deleted.'));
        } else {
            $this->Flash->error(__('The task could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Activa o desactiva una tarea
     * @param int $task_id
     * @param int $lista_id
     * @return \Cake\Http\Response|NULL
     */
    public function switchActive($active,$task_id,$lyric,$lista_id){
    	
    	$this->request->allowMethod(['post']);
    	$task = $this->Tasks->get($task_id);
    	$task->active = $active;
    	$task->lyric = $lyric;
    	
    	if ($this->Tasks->save($task)) {
    		$this->Flash->success(__('La TAREA ha cambiado de estado.'));
    	
    		return $this->redirect(['action' => 'index',$lista_id]);
    	}
    	$this->Flash->error(__('No se pudo cambiar el estado.'));    	 
    }
    
    
    public function sendNow($task_id){
        
    	$task = $this->Tasks->get($task_id);
    	
    	$lista_id = $task->lista_id;
    	
    	
    	if($task->active){
    	    if(empty($task->lyric)){
        		$crea = $this->creaListaPhoneSMS($task_id);        		
        		
        		
        		if($crea) {
        			$carga = $this->cargaListaPhoneSMS($task_id);        		
        			if($carga['correcto']>0){
        				$this->activaListaPhoneSMS($task_id);
        				$this->Flash->success(__('La tarea se está enviando.'));				
        				return $this->redirect(['action' => 'index',$lista_id]);
        			}
        		}
    	    }
    	    else 
    	    {
    	        $send = $this->sendByLyrics($task_id);
    	        
    	        
    	        
    	    }
    	}
    }
    
    /**
     * Crea una lista en la plataforma PhoneSMS
     * @param unknown $task_id
     * @return boolean
     */
    public function creaListaPhoneSMS($task_id){    	
    	/*
    	 * Para CREAR una LISTA en SMS
    	 * http://192.168.1.120/api_mass/request.php?tipo=HEAD&codcli=ABC&camp=PRUEBANOMBRE
    	 */
    	$createTask = false;
    	//Estos horarios están en America/Santiago
    	$time = new Time('now','America/Santiago');
    	$queryHoraInicio = $this->Tasks->find();
    	$queryHoraInicio->select(['datetime_start','datetime_end'])->where(['Tasks.id'=>$task_id]);
    	$rsHoraInicio = $queryHoraInicio->first();
    	$horaInicio = $rsHoraInicio->datetime_start;
    	$horaFin 	= $rsHoraInicio->datetime_start;
    
    	$diff = $horaInicio->toUnixString()-$time->toUnixString();
    	//10 minutos de margen.
    	if(abs($diff)<(10*60*60)) 
    		$createTask = true;
    		
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
    					return true;    		    						
    				}
    				else {
    					return false;
    		
    			}}}}    				
    }
    
    
	/**
	 * Carga con SMS la lista
	 * @param int $task_id
	 */
    public function cargaListaPhoneSMS($task_id){
    	
    	$sms = '';
    	
    	//Datos de la tarea
    	$task = $this->Tasks->get($task_id);
    
    	//Relación con phonesms
    	$yx_lista_id = $task->yx_lista_id;
    	//Mensaje
    	$msg_id = $task->message_id;
    
    	//Id de lista	
    	$lista_id = $task->lista_id;
    	//Detalles de lista
    	$lista = $this->Tasks->Listas->get($lista_id);
    	//cliente
    	$client_id = $lista->client_id;    		
    	$client = $this->Tasks->Listas->Clients->get($client_id);    	
    	$codCli = urlencode($client->codcli);
    
    	$queryCargas = $this->Tasks->Listas->Workloads->find();
    	$queryCargas->select(['phone','dni','dni_dv', 'nombre','deuda','sucursal','link_id','cuenta'])->where(['Workloads.lista_id'=>$lista_id]);
    	
    	$msg = $this->Tasks->Messages->get($msg_id);   	
    	$mensaje = $msg->message;
    	$comodines = ['#{rut}','#{nombre}','#{deuda}','#{sucursal}','#{link}','#{cuenta}'];    	
    	$correcto = 0; $incorrecto=0;
    	
    	foreach($queryCargas as $rs){
    	    if(is_numeric($rs->link_id)){
    	       $link_rs = $this->Tasks->Listas->Workloads->Links->get($rs->link_id);
    	       $link = $link_rs->url_google;
    	    }
    	       else $link = $rs->link_id;
    	    
    		$RUT = $rs->dni.'-'.$rs->dni_dv;
    		$reemplazo = [$RUT,$rs->nombre,'$'.number_format($rs->deuda,0,',','.'),$rs->sucursal,$link,$rs->cuenta];
    		
    		//Codifica el mensaje para ser enviado desde le JSON
    		$sms = str_ireplace($comodines, $reemplazo, $mensaje);
    		/*
    		$acentos     = ['á','à','Á','À','é'.'è','É','È','í','ì','Í','Ì','ó','ò','Ó','Ò','ú','ù','ü','Ú','Ù','Ü','ñ','Ñ'];
    		$sinacentos  = ['a','a','A','A','e','e','E','E','i','i','I','I','o','o'.'O','O','u','u','u','U','U','U','n','N'];;
    		$sms = str_ireplace($acentos, $sinacentos, $sms);
    		*/
    		$cadena = $sms;
    		$originales = 'àáâãäåæçèéêëìíîïðñòóôõöøùúûüýþßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŕŕ';
    		$modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyrr';
    		$cadena = utf8_decode($cadena);
    		$cadena = strtr($cadena, utf8_decode($originales), $modificadas);
    		$cadena = strtolower($cadena);
    		$sms = null;
    		$sms = utf8_encode($cadena);
    		
    		$sms = urlencode(utf8_decode($sms));
    		$RUT = urlencode($RUT);
    		$jsonurl = "http://192.168.1.120/api_mass/request.php?tipo=SMS&codcli=$codCli&id_ext=$RUT&fono=$rs->phone&msg=$sms&lista_id=$yx_lista_id";
    	
    		$JSON = file_get_contents($jsonurl);
    		$phoneSmsResponse = json_decode($JSON);
    	
    		if($phoneSmsResponse->success) {
    			//return true;
    			$correcto++; 
    		} else {
    			//return false;
    			$incorrecto++;
    		}
    	}
    	return ['correcto'=>$correcto,'incorrecto'=>$incorrecto];
    }
    
    /**
     * Activa la lista de numero ### (Asociado al task) en la plataforma phoneSMS
     * @param unknown $task_id
     * @return boolean
     */
    public function activaListaPhoneSMS($task_id){
    	//http://192.168.1.120/api_mass/request.php?tipo=FOOT&codcli=ALL&lista_id=15716
    	$task = $this->Tasks->get($task_id);
    	$yx_lista_id = $task->yx_lista_id;
    
    	$lista_id = $task->lista_id;
    	$lista = $this->Tasks->Listas->get($lista_id);
    	$client_id = $lista->client_id;
    
    	$client = $this->Tasks->Listas->Clients->get($client_id);
    	$codCli = urlencode($client->codcli);    
    	
    	$jsonurl = "http://192.168.1.120/api_mass/request.php?tipo=FOOT&codcli=$codCli&lista_id=$yx_lista_id";
    	$JSON = file_get_contents($jsonurl);
    	$phoneSmsResponse = json_decode($JSON);
    	
    	
    	if($phoneSmsResponse->success) {
    		
    		//desactiva la tarea
    		$task = $this->Tasks->get($task_id);
    		$task->active = 0;    		 
    		$this->Tasks->save($task);
    		return true;
    	} else {
    		return false;
    	}
    }
    
    
    /**
     * Responde con el id de la lista en PhoneSMS
     * y los telefonos asociados
     * @param int $task_id
     * @return mixed|boolean
     */
    public function phoneListPhoneSMS($task_id){
    
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
    	if($phoneSmsResponse) {
    		return $phoneSmsResponse;
    	} else {
    		return false;
    	}
    
    }
    
	/**
	 * Responde con el estado del telefono consultado
	 * @param int $task_id
	 * @param int  $telefono
	 * @return mixed|boolean
	 */
    public function phoneStatusPhoneSMS($task_id,$telefono){
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
    	if($phoneSmsResponse) {
    		return $phoneSmsResponse;
    	} else {
    		return false;
    	}
    	 
    }
    
	public function results($task_id){
		
			$task = $this->Tasks->get($task_id);
			$yx_lista_id = $task->yx_lista_id;
			if(empty($yx_lista_id)) return false;
			
			$estadisticas = $this->Tasks->getListStatus($yx_lista_id);		
		//	$this->set(compact('estadisticas'));
			
	}
	
	public function getCargaMaximaJSON(){
		$this->autoRender = false;
		
		echo json_encode(['max'=>$this->Tasks->getCargaMaxima()]);
	}
	
	public function getCarga($lista_id){
		$this->autoRender = false;
		$cantidadCarga = $this->Tasks->Listas->Workloads->cuentaCargaLista($lista_id);
		echo json_encode(['carga'=>$cantidadCarga]);
	}
	
	public function report($task_id,$lista_id){
		
		set_time_limit(0);
		//ini_set('memory_limit', '1512M');
		$task = $this->Tasks->get($task_id);
		$i=0;
		$this->viewBuilder()->setLayout('excel_task_basic_report');
		if(empty($task->lyric)){
    		    		
    		$listas  = $this->phoneListPhoneSMS($task_id);
    		
    		foreach ($listas as $list){
    			if(is_null($list)) continue;
    			foreach ($list as $yx_lista_id => $phones ){
    				if($yx_lista_id==$task->yx_lista_id) {
    					
    					$resultados = $this->Tasks->phoneStatusDirect($task_id);
    					//foreach ($phones as $phone)
    					foreach ($resultados as $resultado)
    					{
    						$reports[$i] = (object)$resultado;
    						$qCargas = $this->Tasks->Listas->Workloads->find();
    						$qCargas->select(['phone','dni','dni_dv','nombre','deuda','sucursal','link_id','cuenta']);				
    	                    $qCargas->where(['Workloads.phone'=>$reports[$i]->fono_destino]);
    						
    						$rsCarga = $qCargas->first();
    						
    						if(is_numeric($rsCarga->link_id)){
    						    $link_rs = $this->Tasks->Listas->Workloads->Links->get($rsCarga->link_id);
    						    $link = $link_rs->url_google;
    						}
    						else $link = $rs->link_id;
    						$reports[$i]->link = $link;
    						$reports[$i]->rut = number_format($rsCarga->dni,0,',','.').'-'.$rsCarga->dni_dv;						
    						$i++;										
    //break;
    					} 
    				}
    				
    			}
    		}
    		$lyric = false;
    		
		}
		else {
		    
		    $reports = $this->Tasks->Sentlogs->find();
		    $reports->contain(['Workloads'=>['Messages'],'Tasks'=>['Listas'],'Lyrics']);
		    $reports->where(['Sentlogs.task_id'=>$task_id]);
		    $reports->order(['Sentlogs.id'=>'ASC']);
		    if(!empty($task->message_id)){
    		    $msg_id = $task->message_id; 
    		    $msg = $this->Tasks->Messages->get($msg_id);
    		    $mensaje = $msg->message;
    		    $this->set(compact('mensaje'));
		    }
		    
		    $lyric = true;
		    
		}
		$this->set(compact('lyric'));
		$this->set(compact('reports'));
		
	}
	
	public function inbox($task_id,$lista_id)
	{
		
		$task = $this->Tasks->get($task_id);
		$yx_lista_id = $task->yx_lista_id;
		if(empty($yx_lista_id)) return false;
		$SMSs = $this->Tasks->getInboxSMS($yx_lista_id);
		$this->set(compact('SMSs','task_id','lista_id'));
		//debug($this->Tasks->existsInboxSMS($yx_lista_id));
		
	}
	
	public function hayInbox($task_id){
		$task = $this->Tasks->get($task_id);
		$yx_lista_id = $task->yx_lista_id;
		if(empty($yx_lista_id)) return false;
		return $this->Tasks->existsInboxSMS($yx_lista_id);
		
		$this->set(compact('SMSs'));
	}
	

	
	public function sendByLyrics($task_id){
	    
		//$this->autoRender = false;		
		
		$task = $this->Tasks->get($task_id);
		$lista_id = $task->lista_id;		
		$this->creaListaLyric($task_id,$lista_id);		
		$this->enviaListaLyric($task_id,$lista_id);		
		
	}
	
	public function creaListaLyric($task_id,$lista_id){
	    
		set_time_limit(6000);
		
		$query = $this->Tasks->Listas->Workloads->find();
		$query->where(['Workloads.lista_id'=>$lista_id]);
		
		foreach ($query as $row){
		    
		    $qry_chk = $this->Tasks->Sentlogs->find();
		    $qry_chk->where(
		          ['Sentlogs.workload_id'=>$row->id,
		          'Sentlogs.task_id'=>$task_id]
		        );
		    //Esto es para no enviar la misma carga de nuevo en la misma tarea
		    if(empty($qry_chk->first())){
    			$sentLog = $this->Tasks->Sentlogs->newEntity();
    			$data['task_id'] = $task_id; 
    			$data['workload_id'] = $row->id; 
    			$data['message_status'] = null;
    			$data['n_tries'] = 0;
    			$data['last_error'] = 0;
    			$sentLog = $this->Tasks->Sentlogs->patchEntity($sentLog, $data);
    			$this->Tasks->Sentlogs->save($sentLog);
		    }
		}
		
	}
	

	public function enviaListaLyric($task_id,$lista_id){
		set_time_limit(6000);
		//Cargamos Lyrics
		$this->loadModel('Lyrics');
		//Ex: http://192.168.1.234/cgi-bin/exec?cmd=api_queue_sms&username=lyric_api&password=lyric_api&content=hola&destination=998115373&channel=1&api_version=0.08
		
		$query = $this->Tasks->Sentlogs->find();
			
		$query->where(['Sentlogs.task_id'=>$task_id]);
		
		$lyrics_qry = $this->Lyrics->find();
		$lyrics_qry->where(["Lyrics.active"=>1]);
		foreach ($lyrics_qry as $lyric_row){
			$lyric[] = $lyric_row;
		}
		$i = 0;
		$sms = '';
		
		//Datos de la tarea
		$task = $this->Tasks->get($task_id);		
		//Relación con phonesms
		$yx_lista_id = -1;
		//Mensaje
		$msg_id = $task->message_id;
		
		//Id de lista
		$lista_id = $task->lista_id;
		//Detalles de lista
		$lista = $this->Tasks->Listas->get($lista_id);
		if(!empty($msg_id)){
		    $msg = $this->Tasks->Messages->get($msg_id);
		    $mensaje = $msg->message;
		}
		
		foreach ($query as $slog){
		    
			
			$wload = $this->Tasks->Listas->Workloads->get($slog->workload_id);
			if(empty($msg_id)){
			    $msg = $this->Tasks->Messages->get($wload->message_id);
			    $mensaje = $msg->message;
			} 
			$comodines = ['#{rut}','#{nombre}','#{deuda}','#{sucursal}','#{link}','#{cuenta}'];
			
			$RUT = $wload->dni.'-'.$wload->dni_dv;
			$reemplazo = [$RUT,$wload->nombre,'$'.number_format($wload->deuda,0,',','.'),$wload->sucursal,$wload->link,$wload->cuenta];
			
			
			

				//Codifica el mensaje para ser enviado desde le JSON
			$sms = str_ireplace($comodines, $reemplazo, $mensaje);
			
			$cadena = $sms;
			$originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
			$modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
			$cadena = utf8_decode($cadena);
			$cadena = strtr($cadena, utf8_decode($originales), $modificadas);
			$cadena = strtolower($cadena);
			$sms = null;
			$sms = utf8_encode($cadena);
			$sms = urlencode($sms);
			
			//$sms = urlencode(utf8_decode($sms));
			
			
				$jsonurl = "http://".$lyric[$i]->web_username.":".$lyric[$i]->web_username."@".$lyric[$i]->ip."/cgi-bin/exec?cmd=api_queue_sms&".
						"username=".$lyric[$i]->username."&password=".$lyric[$i]->username.
						"&content=".$sms."&destination=".$wload->phone."&channel=".$lyric[$i]->channel."&api_version=".$lyric[$i]->api;
				
			
			$JSON = file_get_contents($jsonurl);
			$lyricsResponse = json_decode($JSON);
			
			$sentLog = $this->Tasks->Sentlogs->get($slog->id);
			$data['id'] = $slog->id;
			$data['task_id'] = $task_id;
			$data['lyric_id'] = $lyric[$i]->id;
			$data['workload_id'] = $slog->workload_id;
			$data['message_status'] = 0;
			$data['n_tries'] = 0;
			$data['last_error'] = 0;
			$data['channel'] = $lyric[$i]->channel;
			$data['success'] = ($lyricsResponse->success=='true')?1:0;
			$data['lyric_message_id'] = $lyricsResponse->message_id;
			$sentLog = $this->Tasks->Sentlogs->patchEntity($sentLog, $data);
			$this->Tasks->Sentlogs->save($sentLog);
			
			
			$i++;
			if($i>=count($lyric)) $i=0;
			
		}
		
	}
	
	public function taskStatusLyrics($task_id){
		set_time_limit(6000);
		//http://192.168.1.234/cgi-bin/exec?cmd=api_get_status&username=lyric_api&password=lyric_api&channel=1&api_version=0.08&message_id=2
		$this->autoRender = false;
		$this->loadModel('Lyrics');
		$task = $this->Tasks->get($task_id);
		$lista_id = $task->lista_id;
		
		$query = $this->Tasks->Sentlogs->find();
		$query->where(['Sentlogs.task_id'=>$task_id/*,'SentLogs.report_stage !='=>2*/]);
		$cargados = $query->count();
		foreach ($query as $rs){
			//debug($rs);
		    if(!empty($rs->lyric_id)){
    			$lyric = $this->Lyrics->get($rs->lyric_id);
    			
    			
    			$jsonurl = "http://".$lyric->web_username.":".$lyric->web_username."@".$lyric->ip."/cgi-bin/exec?cmd=api_get_status&".
    					"username=".$lyric->username."&password=".$lyric->username.
    					"&channel=".$rs->channel."&api_version=".$lyric->api."&message_id=".$rs->lyric_message_id;
    			
    			$JSON = file_get_contents($jsonurl);
    			$lyricsResponse = json_decode($JSON);
    			debug($lyricsResponse);
    		//	debug($lyricsResponse);
    			$sentLog = $this->Tasks->Sentlogs->get($rs->id);
    			$data['id'] = $rs->id;
    			$data['task_id'] = $task_id;
    			$data['lyric_id'] = $lyric->id;
    			$data['workload_id'] = $rs->workload_id;
    			$data['success'] = ($lyricsResponse->success=='true')?1:0;
    			$data['message_status'] = $lyricsResponse->message_status;
    			$data['channel'] = $rs->channel;
    			$data['last_error'] = $lyricsResponse->last_error;
    			$data['n_tries'] = $lyricsResponse->n_tries;
    			$data['delivery_status'] = $lyricsResponse->delivery_status;
    			$data['report_stage'] = $lyricsResponse->report_stage; 
    			$data['send_date'] = $lyricsResponse->send_date;
    			$data['recv_date'] = $lyricsResponse->recv_date;
    			$data['delivery_date'] = $lyricsResponse->delivery_date;
    			$data['num'] = $lyricsResponse->num;
    			if(isset($lyricsResponse->error_code))
    				$data['error_code'] = $lyricsResponse->error_code;
    				else $data['error_code'] = NULL;
    			$sentLog = $this->Tasks->Sentlogs->patchEntity($sentLog, $data);
    			
    			$this->Tasks->Sentlogs->save($sentLog);
    			
    		}
		}
		
		$query = $this->Tasks->Sentlogs->find();
		$query->where([
            'Sentlogs.task_id'=>$task_id,
            'SentLogs.success'=>1,
		    'SentLogs.message_status >=' => 2
		    
		]);
		$enviados = $query->count();
		$query = $this->Tasks->Sentlogs->find();
		$query->where([
		    'Sentlogs.task_id'=>$task_id,
		    'SentLogs.success'=>0,
		    'SentLogs.error_code IS NOT'=>null,
		    
		]);
		
		$error = $query->count();
		
		$query = $this->Tasks->Sentlogs->find();
		$query->where([
		    'Sentlogs.task_id'=>$task_id,
		    'SentLogs.success'=>0,
		    'SentLogs.error_code IS'=>null,
		    
		]);
		
		$no_enviados = $query->count();
		return (object)['cargados'=>$cargados,'enviados'=>$enviados,'no_enviados'=>$no_enviados,'errores'=>$error];
	}
	
	public function checkCargando(){
	    $this->autoRender = false;
	    if ($this->request->is('post')) {
	        
	    $data = $this->request->getData();
	    $task_id = $data['task_id'];
	    
	    $task = $this->Tasks->get($task_id);
	    /**
	     * Leer carga, leer sentlogs enviados a lyrics + blacklist
	     */
	    $wlq = $this->Tasks->Listas->Workloads->find();
	    $wlq->where(['Workloads.lista_id'=>$task->lista_id]);
	    $carga_total = $wlq->count();
	    
	    $slq = $this->Tasks->Sentlogs->find();
	    $slq->where([
	        'Sentlogs.task_id'=>$task_id, 
	        'OR'=>[['Sentlogs.lyric_id >'=>0],['Sentlogs.error_code LIKE'=>'BLACKLIST']]]);
	    
	    $carga_en_lyric = $slq->count();
	    if($carga_total > 0)
	       $carga_porc = round(100*($carga_en_lyric/$carga_total));
	   else $carga_porc = 0;
	    //debug($carga_porc);
	   //echo json_encode(['task_id'=>$task_id,'tasa'=>$carga_porc]);
	   echo json_encode($carga_porc);
	    }
	}
	
	public function pieChart($task_id){
	    $this->autoRender = false;
	    $estadisticas = $this->Tasks->getListLyricStatus($task_id);
	   
	    $stats = ["cols"=>[
	                   ["id"=>"","label"=>"Estado"  ,"pattern"=>"","type"=>"string"],
	                   ["id"=>"","label"=>"Cantidad","pattern"=>"","type"=>"number"]
	             ]
	            ,"rows"=>[
	                   
	                       ["c"=>[["v"=>"ENTREGADOS","f"=>null],["v"=>$estadisticas->entregados,"f"=>null]]]
	                       ,["c"=>[["v"=>"ENVIADOS","f"=>null],["v"=>$estadisticas->enviados,"f"=>null]]]
	                       ,["c"=>[["v"=>"EN COLA","f"=>null],["v"=>$estadisticas->en_cola,"f"=>null]]]
	                       ,["c"=>[["v"=>"LISTA NEGRA","f"=>null],["v"=>$estadisticas->blacklists,"f"=>null]]]
	                       ,["c"=>[["v"=>"FALLADOS","f"=>null],["v"=>$estadisticas->fallados,"f"=>null]]]
	                       ,["c"=>[["v"=>"SIN PROCESAR","f"=>null],["v"=>$estadisticas->sin_proc,"f"=>null]]]	                       	                       
	                      
	            ]	        
	    ];
	    $json_estadisticas = json_encode($stats);
	    echo ($json_estadisticas);
	}
	
	public function tableChart($task_id){
	    $this->autoRender = false;
	    $estadisticas = $this->Tasks->getListLyricStatus($task_id);
	    
	    $stats = ["cols"=>[
	        ["id"=>"","label"=>"Estado"  ,"pattern"=>"","type"=>"string"],
	        ["id"=>"","label"=>"Cantidad","pattern"=>"","type"=>"number"]
	    ]
	        ,"rows"=>[
	            ["c"=>[["v"=>"CARGA","f"=>null],["v"=>$estadisticas->cargados,"f"=>null]]]
	            ,["c"=>[["v"=>"ENTREGADOS","f"=>null],["v"=>$estadisticas->entregados,"f"=>null]]]
	            ,["c"=>[["v"=>"ENVIADOS","f"=>null],["v"=>$estadisticas->enviados,"f"=>null]]]
	            ,["c"=>[["v"=>"EN COLA","f"=>null],["v"=>$estadisticas->en_cola,"f"=>null]]]
	            ,["c"=>[["v"=>"LISTA NEGRA","f"=>null],["v"=>$estadisticas->blacklists,"f"=>null]]]
	            ,["c"=>[["v"=>"FALLADOS","f"=>null],["v"=>$estadisticas->fallados,"f"=>null]]]
	            ,["c"=>[["v"=>"SIN PROCESAR","f"=>null],["v"=>$estadisticas->sin_proc,"f"=>null]]]
	            
	        ]
	    ];
	    $json_estadisticas = json_encode($stats);
	    echo ($json_estadisticas);
	}
}
