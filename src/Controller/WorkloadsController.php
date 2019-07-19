<?php
namespace App\Controller;

use App\Controller\AppController;
use GoogleUrlApi\GoogleUrlApi;
/**
 * Workloads Controller
 *
 * @property \App\Model\Table\WorkloadsTable $Workloads
 */
class WorkloadsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index($lista_id=null)
    {
    	$conditions = null;
    	if(!is_null($lista_id)) $conditions = ['Workloads.lista_id'=>$lista_id];
        $this->paginate = [
            'contain' => ['Files','Links','Messages'],
        	'conditions'=>$conditions
        ];
        $workloads = $this->paginate($this->Workloads);
        $user = $this->Auth->user();
        $this->loadModel('Roles');
        $role = $this->Roles->get($user['role_id']);
        $cuota_diaria = null;
        $tasks_ids = [];
        if(($user['cuota']!=0)||($role->name!='Administrador')){
            $qry_listas = $this->Workloads->Listas->find();
            $qry_listas->select(['id']);
            
            $qry_listas->where(['Listas.user_id'=>$user['id']]);
            foreach ($qry_listas as $lista){
                $listas_ids[]=$lista->id;
            };
            
            $qry_wls = $this->Workloads->find();
            if(!empty($listas_ids))
                $qry_wls->where(['Workloads.lista_id IN'=>$listas_ids,'DATE(Workloads.created) LIKE DATE(NOW())']);
                else $qry_wls->where(['DATE(Workloads.created) LIKE DATE(NOW())']);
            $cuota_diaria = $user['cuota'] - $qry_wls->count();
            /*
            $qry_tasks = $this->Workloads->Listas->Tasks->find();
            $qry_tasks->select(['id']);
            $qry_tasks->where(['Tasks.lista_id IN'=>$listas_ids,'DATE(Tasks.created)'=>'DATE(NOW())']);
            foreach ($qry_tasks as $task){
                $tasks_ids[]=$task->id;
            };
            
            if(count($tasks_ids) != 0) {
                $qry_sentlogs = $this->Workloads->Listas->Tasks->Sentlogs->find();
                $qry_sentlogs->where(['Sentlogs.task_id IN'=>$tasks_ids]);            
                $cuota_diaria = (int)$user['cuota']-$qry_sentlogs->count();
            } else $cuota_diaria = $user['cuota'];*/
            if($cuota_diaria<0) $cuota_diaria = 0;
            if(is_null($cuota_diaria)) $cuota_diaria = 0;
        }
        $this->set(compact('workloads','lista_id','cuota_diaria'));
        $this->set('_serialize', ['workloads']);
    }
    
    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($file_id=null,$lista_id=null)
    {
        set_time_limit(0);
    	/*
    	 * Para agregar una nueva carga, es obligatorio subir un archivo
    	 
         */
         	
    	if(is_null($file_id)||is_null($lista_id)) {
    		$this->Flash->success(__('Se ha producido un error al intentar guardar.'));    		
    		//return $this->redirect(['action' => 'index']);
    	} 
    	
    	//Rescata el contenido del archivo.    	
    	$fileContents = $this->Workloads->Files->getFileData($file_id);    	
    	/*
    	 * Si no pilla el arcivo, da error.
    	 */
    	
    	if(is_null($fileContents)){
    		$this->Flash->success(__('Se ha producido un error al intentar abrir el archivo cargado.'));
    		//return $this->redirect(['action' => 'index',$lista_id]);
    	}  	
    	
    	//$rs = $this->loadFileData($fileContents,$lista_id,$file_id);
    	$this->loadFileData($fileContents,$lista_id,$file_id);
    	
    //	$this->Workloads->saveWorkload($rs);
    	
    	if(count($this->Workloads->correcto)||count($this->Workloads->incorrecto)){
    	    
    		$this->set('correcto', $this->Workloads->correcto);
    		$this->set('incorrecto', $this->Workloads->incorrecto);
    		
    		$this->Flash->success(__('Resultado de la carga.'));
    		
    		//return $this->redirect(['action' => 'index',$lista_id]);
    	}  
    	if(!empty($this->Workloads->incorrecto))
    	$this->Flash->error(__('Hay advertencias o errores en la carga.'));
    	$this->set(compact('lista_id'));
    	//return $this->redirect(['action' => 'index',$lista_id]);
    }

    public function loadFileData($fileContents,$lista_id,$file_id){
        set_time_limit(0);
		//Carga la clase googl url api
    	//require_once(ROOT .DS. "vendor/DavidWalsh" . DS  . "GoogleUrlApi" . DS . "GoogleUrlApi.php");
    	require_once(ROOT .DS. "vendor" . DS  . "GoogleUrlApi" . DS . "GoogleUrlApi.php");
    	/* 
    	$acentos     = ['�','�','�','�','�'.'�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�','�'];
    	foreach ($acentos as $k => $acento) $acentos[$k] = utf8_encode($acento);
    	$sinacentos  = ['a','a','A','A','e','e','E','E','i','i','I','I','o','o','O','O','u','u','u','U','U','U','n','N','',''];;
    	*/
    	
    	
    	$originales  = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
    	$modificadas = 'AAAAAAACEEEEIIIIDNOOOOOOUUUUYBSaaaaaaaceeeeiiiidnoooooouuuyybyRr';
    	
    	
    	//Avanzamos por el objeto de contenidos CSV.
    	$csvData = [];
    	$line=0;
    	$lista = $this->Workloads->Listas->get($lista_id);
    	//Cargamos la clase para acortar URL
    	$googer = new GoogleURLAPI();
    	//Por alguna raz�n, el constructor no anda en Cake
    	$googer->GoogleURLAPI($this->Workloads->key);
    	
    	foreach ($fileContents as $content){
    	
    		$data = new \stdClass();
    		
    		$delimiter = $this->getCSVDelimiter($content);
    		$row = str_getcsv($content,$delimiter);
    	   
    		if($line==0) {    		    
    		    foreach($row as $k=>$r) $row[$k]=strtolower($r);
    		    
    			$col = (object)array_flip($row);
    			$line++;
    			continue;
    		};
    		
    		if(!isset($row[$col->rut])||empty($row[$col->rut])){
    		    continue;
    		};
    		//Error en el RUT
    	/*	if(!$this->isRUT($row[$col->rut])) {
    		    
    			$i = count($this->incorrecto);
    			$this->Workloads->incorrecto[$i]['dni'] = $row['0'];
    			if(isset($row['1']))
    				$this->Workloads->incorrecto[$i]['telefono'] = $row['1'];
    				else $this->Workloads->incorrecto[$i]['telefono']  = null;
    				$this->Workloads->incorrecto[$i]['error'] = 'RUT incorrecto.';
    				$line++;
    				continue;
    		}*/   	
    	
    		//Obtenemos los datos del rut.
    		
    		$rut = $this->getRUT($row[$col->rut]);
    		
    		//Datos de telefono
    		if(isset($row[$col->telefono]) && !empty($row[$col->telefono])) $fono = $row[$col->telefono];
    		elseif(isset($row[$col->fono]) && !empty($row[$col->fono])) $fono = $row[$col->fono];
    		$telefono = preg_replace('/[^k0-9]/i', '', $fono);
    		if(strlen($telefono)<9) {
    			$i = count($this->Workloads->incorrecto);
    			$this->Workloads->incorrecto[$i]['dni'] = $rut->num.'-'.$rut->dv;
    			$this->Workloads->incorrecto[$i]['telefono'] = $telefono;
    			$this->Workloads->incorrecto[$i]['error'] = 'Telefono muy corto';
    			$line++;
    			continue;
    		}
    		
    		//Datos validados, compilamos.
    		$data->dni = $rut->num;
    		$data->dni_dv = $rut->dv;
    		$data->phone = substr($telefono,strlen($telefono)-9,9);
    		$data->lista_id = $lista_id;
    	
    		//Comprobamos que los datos no est�n repetidos.
    		$queryBusca = $this->Workloads->find();
    		$queryBusca->select(['id']);
    		$queryBusca->where([
    				'Workloads.dni'=>$data->dni,
    				'Workloads.phone'=>$data->phone,
    				'Workloads.lista_id'=>$data->lista_id,
    		]);
    		$rsBusca = $queryBusca->first();
    		if(!is_null($rsBusca)) {
    			$i = count($this->Workloads->incorrecto);
    			$this->Workloads->incorrecto[$i]['dni'] = $rut->num.'-'.$rut->dv;
    			$this->Workloads->incorrecto[$i]['telefono'] = $telefono;
    			$this->Workloads->incorrecto[$i]['error'] = 'El registro ya existe en la base de datos.';
    			$line++;
    			continue;
    		}
    	
    		//Seguimos
    		if(isset($col->nombre))
    		    
    		    $data->nombre = ucwords(mb_strtolower(str_ireplace($acentos, $sinacentos, $row[$col->nombre])));
    		//Deuda
    		if(isset($col->deuda)){
    		$deuda = preg_replace('/[^k0-9]/i', '', $row[$col->deuda]);
    		$data->deuda = intval($deuda);
    		}
    		
    		//Enlace    		
    		if(isset($col->link) AND isset($row[$col->link]) AND !empty($row[$col->link]))
    		{
    			$this->loadModel('Links');
    			$d['hash'] = hash('crc32',$row[$col->link]);
    			$data->link_id=null;
    			
    			$link = $this->Links->findByHash($d['hash'], [
    					'contain' => []
    			])->first(); 			
    			
    			
    			if($link==null) $link = $this->Links->newEntity();
    			else $data->link_id=$link->id;
    			
    			$d['url'] = $row[$col->link];
    			
    			//Esto construye la URL corta
    			$uri = $this->request->getUri()->getScheme()
    			.'://'.$this->request->getUri()->getHost()
    			.$this->request->getUri()->base
    			.'/links'.'/go/'.$d['hash'];
    			
    			//$data->link =  $googer->shorten($row[$col->link]);
    			
    			$data->enlace =  $googer->shorten($uri);    			
    			//Grabamos el Link
    			$d['url_google'] = $data->enlace;
    			//Grabamos los datos
    			$link = $this->Links->patchEntity($link, $d);
    			//gaurdamos
    			$link  = $this->Links->save($link);
    			//
    			if(empty($data->link_id))
    				$data->link_id=$link->id;    			
    			
    		}
    		if(isset($col->sucursal))
    		$data->sucursal = ucwords(mb_strtolower($row[$col->sucursal]));

    		if(isset($col->cuenta))
    		    $data->cuenta = str_ireplace($acentos, $sinacentos, $row[$col->cuenta]);
    		
    		    
    		//mensaje 
    		if(isset($col->mensaje)){
    		    
    		    $m['message'] = $row[$col->mensaje];    		    
    		    $cadena = trim($m['message']);
    		    $cadena  = preg_replace('/[\x00-\x1F\x7F\xA0]/u', ' ', $cadena );
    		    //$cadena = mb_convert_encoding($cadena,"ISO-8859-1",mb_detect_encoding($cadena, "UTF-8, ISO-8859-1, ISO-8859-15", true));
		        $cadena = utf8_decode($cadena);
		        
		        $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
    		    $cadena = utf8_encode($cadena);
    		    
    		    $m['message'] = $cadena;
    		    $sms = null;
    		    
    		    
    		    
    		    $mensaje_qry = $this->Workloads->Listas->Tasks->Messages->find();
    		    $mensaje_qry->where(['Messages.message'=>$cadena]);
    		    $mensaje_res = $mensaje_qry->first();
    		    
    		    if(empty($mensaje_res)){
    		        
    		        
    		        
    		        $m['hidden']  = 1;
    		        
    		        $m['client_id'] = $lista->client_id;
    		        $msj = $this->Workloads->Listas->Tasks->Messages->newEntity();    		        
    		        $msj = $this->Workloads->Listas->Tasks->Messages->patchEntity($msj, $m);
    		        
    		        //gaurdamos
    		        
    		        $save_msj  = $this->Workloads->Listas->Tasks->Messages->save($msj);
    		        $data->message_id = $save_msj->id;
    		    }
    		    else {
    		        $data->message_id = $mensaje_res->id;
    		    }
    		        //$data->mesnaje = $row[$col->mensaje];
    		}
    		        
    		$data->file_id = $file_id;   	
    	
    		//$csvData[$line] = $data;
    		
    		$this->Workloads->saveWorkloadEntity($data);
    		$data = null;
    		$line++; 
    	} 
    	return $csvData;
    }
    
    /**
     * Edit method
     *
     * @param string|null $id Workload id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null, $lista_id = null)
    {
        $workload = $this->Workloads->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $workload = $this->Workloads->patchEntity($workload, $this->request->getData());
            if ($this->Workloads->save($workload)) {
                $this->Flash->success(__('Cambios guardados.'));

                return $this->redirect(['action' => 'index',$lista_id]);
            }
            $this->Flash->error(__('Error, intente de nuevo.'));
        }
        //$file = $this->Workloads->Files->find('list', ['limit' => 200]); 
        $this->set(compact('workload', 'files','lista_id'));
        $this->set('_serialize', ['workload']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Workload id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null, $lista_id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $workload = $this->Workloads->get($id);
        if ($this->Workloads->delete($workload)) {
            $this->Flash->success(__('La carga se ha eliminado.'));
        } else {
            $this->Flash->error(__('Error, intente de nuevo.'));
        }

        return $this->redirect(['action' => 'index',$lista_id]);
    }
}
