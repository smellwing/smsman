<?php
namespace App\Shell;

use Cake\Console\Shell;
use Cake\Datasource\ConnectionManager;
use Cake\I18n\Time;
use Cake\ORM\TableRegistry;

class SendTasksShell extends Shell
{
	
	private $lista_id;
	private $yx_lista_id;
	private $client_id;
	private $codCli;
	private $listaNom;
	private $msg_id;
	private $msg;
	private $lyric;
    private $acentos;
    private $sinacentos;
    
	public function initialize()
	{
	    set_time_limit(0);
		parent::initialize();
		$this->loadModel('Listas');
	}
	
	public function main()
	{
	    set_time_limit(0);
	    	    
		$this->out('Analizando las tareas a enviar.');
		$now = new Time('now','America/Santiago');
		$this->out(print_r($now,true));
		$this->out('Cargando Tareas.');

		$this->reciclaje_canal_cerrado();  
		$this->iniciar();		
		$this->finalizar();		
		$this->cierre();
		$this->monitorizar();
		
	}
	
	
	private function iniciar(){
	    set_time_limit(0);
		$now = new Time('now','America/Santiago');

		
		
		//BUSCA TAREAS A ENVIAR
		$qTasks = $this->Listas->Tasks->find();
		$qTasks->select(['datetime_start','lyric','id'])->where([
				'Tasks.active'=>1,
				'Tasks.status'=>'NUEVA',
				'Tasks.datetime_start <='=>$now->i18nFormat('yyyy-MM-dd HH:mm:ss'),
				'Tasks.datetime_end >='=>$now->i18nFormat('yyyy-MM-dd HH:mm:ss'),
		]);        
		$i=0;
 
		foreach ($qTasks as $rsTask){
			$i++;                 
			$t = $this->Listas->Tasks->get($rsTask->id);
			$l = $this->Listas->get($t->lista_id);
			$c = $this->Listas->Clients->get($l->client_id);
			
			//$m = $this->Listas->Tasks->Messages->get($t->message_id);
				
			$this->lyric = $rsTask->lyric;
			$this->lista_id = $t->lista_id;
			$this->yx_lista_id = $t->yx_lista_id;
			$this->msg_id = $t->message_id;
			$this->client_id = $l->client_id;
			$this->codCli = urlencode($c->codcli);
			$this->listaNom = urlencode($t->name);
			
	    	$crea 	= $this->creaListaLyric($t,$l);
	    	
			$envia 	= $this->enviaListaLyric($t,$l);
			
			$monitor = $this->statusTask($t);
			
		}
		if($i==0) $this->out("Nada que iniciar.");
	}
	
	private function finalizar(){
	    set_time_limit(0);
		$now = new Time('now','America/Santiago');
		$qTasks = $this->Listas->Tasks->find();
		$qTasks->select(['datetime_start','lyric','id','name','status'])->where([
				'Tasks.active'=>1,
				'Tasks.status'=>'ENVIANDO',
	/*			'Tasks.datetime_start <='=>$now->i18nFormat('yyyy-MM-dd HH:mm:ss'),*/
				'Tasks.datetime_end <='=>$now->i18nFormat('yyyy-MM-dd HH:mm:ss'),		    
		]);
		$i=0;
		$this->out("Finalizar.");
		foreach ($qTasks as $rsTask){
			$i++;
			$t = $this->Listas->Tasks->get($rsTask->id);
			$this->out("rs Finalizar tarea $rsTask->id de nombre $rsTask->name en estado $rsTask->status.");
			$this->out("t Finalizar tarea $t->id de nombre $t->name en estado $t->status.");
		    $this->out("Analisis para cerrar la tarea ".$rsTask->id);
		    $this->out("Analisis para cerrar la tarea ".$rsTask->name);
		    $t->status = 'ANALIZANDO';
		    $t->active = 0;
		    $save = $this->Listas->Tasks->save($t);
		    
		    $sentlogs_qry = $this->Listas->Tasks->Sentlogs->find();
		    //busca mensajes no cargados y enviados
		    $sentlogs_qry->where(
		        [
		            'Sentlogs.task_id'=>$rsTask->id,			            
		            'OR'=>[['Sentlogs.lyric_id >'=>0],['Sentlogs.message_status ='=>0]]	
		        ]);
		    
		    
		    foreach ($sentlogs_qry as $sl_row){
		        $this->statusSL($sl_row);
		        $sentLog = $this->Listas->Tasks->Sentlogs->get($sl_row->id);
		        
		        if(($sentLog->lyric_id>0)&&( $sentLog->message_status==0)) {
    			        $lyric = $this->Listas->Tasks->Sentlogs->Lyrics->get($sl_row->lyric_id);   
    			        
    			        //Borra los mensajes salientes por ID
    			        $jsonurl = "http://".$lyric->web_username.":".$lyric->web_password."@"
    			            .$lyric->ip."/cgi-bin/exec?cmd=api_sms_delete_by_id&username="
    			                .$lyric->username."&password=".$lyric->password."&api_version=".$lyric->api
    			                ."&sms_dir=out&id=".$sl_row->lyric_message_id;
    			                
    			       $JSON = file_get_contents($jsonurl);
    			       $lyricsResponse = json_decode($JSON);
		        }
		    }
			    
			$t->status = 'FINALIZADA';
			$t->active = 0;
			$save = $this->Listas->Tasks->save($t);
			debug($save);
			$this->out("Finalizada la lista ".$rsTask->id);
			
			if(empty($rsTask->lyrics))
			     $this->activaListasPhoneSMS();
		};
		if($i==0) $this->out("Nada que finalizar.");
	}
	
	private function monitorizar(){
	    set_time_limit(0);
		$this->out("Monitor.");
		$now = new Time('now','America/Santiago');
		$qTasks = $this->Listas->Tasks->find();
		$qTasks->select(['datetime_start','status','lyric','id'])->where([
				/*'Tasks.active'=>1,*/
		          'OR'=>[
		              /*['Tasks.status'=>'CARGANDO'],*/
		              ['Tasks.status LIKE'=>'ENVIANDO'],
		              ['Tasks.status LIKE'=>'FINALIZADA'],
		              ['Tasks.status LIKE'=>'VENCIDA']],
				    'CONCAT(DATE(`datetime_end`)," 22:00:00")  >'=>$now->i18nFormat('yyyy-MM-dd HH:mm:ss'),
		]);
		
		foreach ($qTasks as $rsTask){
			$this->out("Monitorizando la lista ".$rsTask->id);
			$t = $this->Listas->Tasks->get($rsTask->id);
			if($t->status != 'ANALIZANDO' ){
			    $state = $t->status;
    			$t->status = 'ANALIZANDO';			
    			$save = $this->Listas->Tasks->save($t);
    			
    		    $this->out("Monitor");
			    $this->out("rs tarea $rsTask->id nombre $rsTask->name estado $rsTask->status");
			    $this->out("t tarea $t->id nombre $t->name estado $t->status");
			//    debug($state); debug($t->name);
			    $status = $this->taskStatusLyrics($t);
			    $this->out("listo");
			    
			    /**
			     * SIN PROCESAR
			     */
			    /*$query = null;
			    $query = $this->Listas->Tasks->Sentlogs->find();
			    $query->where([
			        'Sentlogs.task_id'=>$t->id,
			        'Sentlogs.success IS NULL',
			        'Sentlogs.lyric_id'=>0,
			    ]);
			    if($query->count()>0){			        
			        $t->status = 'CARGANDO';			        
			        $save = $this->Listas->Tasks->save($t);
			    }
			    else
			    {*/
			        if($status->cargados == ($status->entregados+$status->errores+$status->lista_negra)){			    
    			        $t->status = 'FINALIZADA';
    			        $t->active = 0;
    			        $save = $this->Listas->Tasks->save($t);
			         }
			         else {
			             /*if($state=='CARGANDO'){
			                 $t->status = 'ENVIANDO';
			                 $save = $this->Listas->Tasks->save($t);
			             }
			             else*/ 
    			             $t->status = $state;			        
    			             $save = $this->Listas->Tasks->save($t);
			            // }
			         }
			    //}
			}
		};
		
		$qTasks = $this->Listas->Tasks->find();
		$qTasks->select(['datetime_start','id'])->where([				
				'Tasks.status'=>'NUEVA',
				'Tasks.datetime_start <='=>$now->i18nFormat('yyyy-MM-dd HH:mm:ss'),
				'Tasks.datetime_end <='=>$now->i18nFormat('yyyy-MM-dd HH:mm:ss'),
		]);
		
		foreach ($qTasks as $rsTask){
			$this->out("Vencida la tarea ".$rsTask->id);
			$t = $this->Listas->Tasks->get($rsTask->id);							
			$t->status = 'VENCIDA';
			$t->active = 0;
			$save = $this->Listas->Tasks->save($t);
		}
		
	}
	
	private function statusTask($task){
	    $this->out("Monitor de una tarea.");
	    $now = new Time('now','America/Santiago');
	    $state = $task->status;
	    $this->out("Monitorizando la tarea recien cargada ".$task->id);

        $t = $this->Listas->Tasks->get($task->id);
        if($t->status != 'ANALIZANDO'){        
            $t->status = 'ANALIZANDO';
            $save = $this->Listas->Tasks->save($t);
            $status = $this->taskStatusLyrics($t);
           
        //  Si se proceosaron todos se acaba
            if($status->cargados == ($status->entregados+$status->errores)){
                $t->status = 'FINALIZADA';
                $t->active = 0;
                $save = $this->Listas->Tasks->save($t);
            }
            else {
                $t->status = $state;
                $save = $this->Listas->Tasks->save($t);
            }
        }
	}
	
	
	private function cierre(){
	    set_time_limit(0);
	    $now = new Time('now','America/Santiago');
	    $qTasks = $this->Listas->Tasks->find();
	    $qTasks->select(['datetime_start','lyric','id'])->where([
	        /*'Tasks.active'=>1,*/	       
	        'OR'=>[
	            ['Tasks.status'=>'CARGANDO'],
	            ['Tasks.status'=>'ENVIANDO'],	            
	            ['Tasks.status'=>'ANALIZANDO'],
	        ],
	        'Tasks.datetime_start <='=>$now->i18nFormat('yyyy-MM-dd HH:mm:ss'),
	        'CONCAT(DATE(`datetime_end`)," 19:30:00")  <'=>$now->i18nFormat('yyyy-MM-dd HH:mm:ss'),
	    ]);
	    $i=0;
	    
	    $lyrics_ips = $this->Listas->Tasks->Sentlogs->Lyrics->find();	
	    $lyrics_ips->select(['Lyrics.ip','Lyrics.web_username','Lyrics.web_password']);
	    $lyrics_ips->order(["Lyrics.ip"=>'ASC']);    
	    $lyrics_ips->group(['Lyrics.ip','Lyrics.web_username','Lyrics.web_password']);
	    
	    foreach ($lyrics_ips as $lyric_ip_row){
	        
	        $isAlive = $this->urlExists("http://".$lyric_ip_row->web_username.":".$lyric_ip_row->web_password."@"
	            .$lyric_ip_row->ip."/");   
	        
	        if(!$isAlive) continue;
	        
    	    foreach ($qTasks as $rsTask){
    	        $i++;
    	        $t = $this->Listas->Tasks->get($rsTask->id);
                  
                //borraremos los mensajes pendientes
                //api para borrar
                //http://web_user:web_pass@192.168.200.10/cgi-bin/exec? cmd=api_sms_delete_by_status&username=lyric_api&password=lyric_api&api_version=0.08&sms_dir=out&channel=5&status=sent
                
                //Lyrics activos
                $lyrics_qry = $this->Listas->Tasks->Sentlogs->Lyrics->find();         
    	        
                $lyrics_qry->order(["Lyrics.channel"=>'ASC']);
                
                //$lyrics_qry->where(["Lyrics.active"=>1]);
                $lyrics_qry->where(["Lyrics.ip"=>$lyric_ip_row->ip]);
                foreach ($lyrics_qry as $lyric_row){
                    
                    //Borra los mensajes salientes nuevos es decir pendientes.
                    $jsonurl = "http://".$lyric_row->web_username.":".$lyric_row->web_password."@"
                               .$lyric_row->ip."/cgi-bin/exec?cmd=api_sms_delete_by_status&username="
                               .$lyric_row->username."&password=".$lyric_row->password."&api_version=".$lyric_row->api
                               ."&sms_dir=out&channel=".$lyric_row->channel."&status=new";
                    
                    $JSON = file_get_contents($jsonurl);
                    $lyricsResponse = json_decode($JSON);
                               
                }
                
                $t->status = 'FINALIZADA';
                $t->active = 0;
                $save = $this->Listas->Tasks->save($t);
                $this->out("Finalizada Lyric: tarea ".$rsTask->id);
    	        	        
    	    }
    	    if($i==0) $this->out("Nada que cerrar.");
	    };
	    
	}
	
	
	/**
	 * 
	 * @param unknown $task_id
	 * @param unknown $lista_id
	 */
	private function creaListaLyric($task,$lista){
	    set_time_limit(0);
	    
	    $this->out('Creando Lista.');
	    $task->status = 'CARGANDO';
	    $this->Listas->Tasks->save($task);

	    $query = $this->Listas->Workloads->find();
	    $query->where(['Workloads.lista_id'=>$lista->id]);
	    
	    if($query->count()>0){
    	    foreach ($query as $row){
    	      $data = null; 
    	        $qry_chk = $this->Listas->Tasks->Sentlogs->find();
    	        $qry_chk->where(
    	            ['Sentlogs.workload_id'=>$row->id,
    	                'Sentlogs.task_id'=>$task->id]
    	            );
    	        //Esto es para no enviar la misma carga de nuevo en la misma tarea
    	        if(empty($qry_chk->count())){
    	            $sentLog = $this->Listas->Tasks->Sentlogs->newEntity();
    	            $data['task_id'] = $task->id;
    	            $data['workload_id'] = $row->id;
    	            $data['message_status'] = null;
    	            $data['n_tries'] = 0;
    	            $data['last_error'] = 0;
    	            $sentLog = $this->Listas->Tasks->Sentlogs->patchEntity($sentLog, $data);
    	            $this->Listas->Tasks->Sentlogs->save($sentLog);
    	        } else  $this->out(print_r('Error. Destino duplicado.',true));
    	    }
	    } else  $this->out(print_r('Error. No hay destinatarios para esta tarea',true));
	}
	
	
	
	private function enviaListaLyric($task,$lista){
	    set_time_limit(0);
	    $this->out('Envia Lista.');
	    //Marcamos la tarea como "CARGANDO"
	    $task->status = 'ENVIANDO';
	    $this->Listas->Tasks->save($task);
	    
	    //Cargamos Lyrics
	    //Ex: http://192.168.1.234/cgi-bin/exec?cmd=api_queue_sms&username=lyric_api&password=lyric_api&content=hola&destination=998115373&channel=1&api_version=0.08
	    
	    $query = $this->Listas->Tasks->Sentlogs->find();
	    
	    $query->where([
				'Sentlogs.task_id'=>$task->id,
				'Sentlogs.lyric_id'=>0,
				'Sentlogs.success IS'=>null,
				]);
	   $query->order(['Sentlogs.id'=>'ASC']); 
	   
	      
	   
	    $i = 0;
	    $sms = '';
	    	    
	    //Relaci�n con phonesms
	    $yx_lista_id = -1;
	    //Mensaje
	    $msg_id = $task->message_id;	    
	    
	    if(!empty($msg_id)){
	        $msg = $this->Listas->Tasks->Messages->get($msg_id);
	        $sms = $this->mensaje($msg, $wload);	        
	    }	     
	    	    
	    foreach ($query as $slog){
	        
	      $data = null;
		  $now  = new Time('now','America/Santiago');
		  
		 if(($task->datetime_end->i18nFormat('yyyy-MM-dd HH:mm:ss')<=$now->i18nFormat('yyyy-MM-dd HH:mm:ss'))||$now->i18nFormat('yyyy-MM-dd 19:25:00') <= $now->i18nFormat('yyyy-MM-dd HH:mm:ss')) break;
	         $sentLog = $this->Listas->Tasks->Sentlogs->get($slog->id);
	         $wload = $this->Listas->Workloads->get($slog->workload_id);
	         
	         $queryBlacklist = $this->Listas->Clients->Blacklists->find();
	         $queryBlacklist->select(['phone','dni']);
	         
	         $queryBlacklist->where([
				'Blacklists.client_id' => $lista->client_id,
				'OR' =>[['Blacklists.dni'=>$wload->dni],['Blacklists.phone'=>$wload->phone]]
				]);
	         
	         if($queryBlacklist->count() == 0){
      
	             if(empty($msg_id)){
	                 $msg = $this->Listas->Tasks->Messages->get($wload->message_id);
	                 $sms = $this->mensaje($msg, $wload);                 
	             }
        	        
        	        $sms = urlencode(utf8_decode($sms));        	        
        	        //Lyrics
        	        /*
        	         * Sería más inteligente consultar los lyrics antes. Es necesario de esta manera porque
        	         * es posible que cierren el Lyric en la mitad. 
        	         */
        	        $lyric = null;
        	        $lyrics_qry = null;
        	        
        	        $lyrics_qry = $this->Listas->Tasks->Sentlogs->Lyrics->find();
        	        
        	        $lyrics_qry->where(["Lyrics.active"=>1]);
        	        $lyrics_qry->order(["Lyrics.ip"=>'ASC',"Lyrics.channel"=>'ASC']);
        	        
        	        $lyrics_active = null;
        	        $lyrics_active = $lyrics_qry->count();
        	        
        	        if ($lyrics_active > 0) {     	            
        	            
        	           foreach ($lyrics_qry as $lyric_row){
        	               $lyric[] = $lyric_row;
        	           }       	        
            	        $i = null;
            	        $i = rand(0,$lyrics_active-1);
            	        
            	        $jsonurl = "http://".$lyric[$i]->web_username.":".$lyric[$i]->web_username."@".$lyric[$i]->ip."/cgi-bin/exec?cmd=api_queue_sms&".
            	   	        "username=".$lyric[$i]->username."&password=".$lyric[$i]->username.
            	   	        "&content=".$sms."&destination=".$wload->phone."&channel=".$lyric[$i]->channel."&api_version=".$lyric[$i]->api;
            
            	       //debug($lyric[$i]);  
            	        $JSON = file_get_contents($jsonurl);
            	        $lyricsResponse = json_decode($JSON);
            	        
            	        
            	        $data['id'] = $slog->id;
            	        $data['task_id'] = $task->id;
            	        $data['lyric_id'] = $lyric[$i]->id;
            	        $data['workload_id'] = $slog->workload_id;
            	        $data['message_status'] = 0;
            	        $data['n_tries'] = 0;
            	        $data['last_error'] = 0;
            	        $data['channel'] = $lyric[$i]->channel;
            	        $data['success'] = ($lyricsResponse->success=='true')?1:0;
            	        $data['lyric_message_id'] = $lyricsResponse->message_id;
            	        //$i++;
            	        //if($i>=count($lyric)) $i=0;
	           } //if ($lyrics_qry->count() > 0) 
	         }
	         else {
	             $data['last_error'] = 1;
	             $data['delivery_status'] = 999;
	             $data['error_code'] = 'BLACKLIST';
	             
	         } 
	        $sentLog = $this->Listas->Tasks->Sentlogs->patchEntity($sentLog, $data);
	        $this->Listas->Tasks->Sentlogs->save($sentLog);
	          
	        
	    }
	    //die();
		    
	}
	
	private function taskStatusLyrics($task){
	    set_time_limit(0);
	    //http://192.168.1.234/cgi-bin/exec?cmd=api_get_status&username=lyric_api&password=lyric_api&channel=1&api_version=0.08&message_id=2
	    
	    $query = $this->Listas->Tasks->Sentlogs->find();
	    $query->where(['Sentlogs.task_id'=>$task->id]);
	    
	    $cargados = $query->count();
	    $query = null;
	    
	    $query = $this->Listas->Tasks->Sentlogs->find();
	    $query->where([
	        'Sentlogs.task_id'=>$task->id,
            'Sentlogs.delivery_date IS NULL',
	        'Sentlogs.error_code IS NULL',              
	    ]);	    
	    
	    $query->order(['Sentlogs.created'=>'ASC']);
	    $cargados_q = $query->count();
		$i = 0;
		$this->out('ANALIZANDO'); 
	    foreach ($query as $rs){
	        
		    $data = null;
	        if(!empty($rs->lyric_id)){
	            $this->out("OBTENIENDO LIRYC");
	            $now = new Time('now','America/Santiago');
	            $this->out(print_r($now,true));
	            
    	        $lyric = $this->Listas->Tasks->Sentlogs->Lyrics->get($rs->lyric_id);
    	        $this->out("OBTENIENDO sentlog");
    	        $now = new Time('now','America/Santiago');
    	        $this->out(print_r($now,true));
    	        
    	        $sentLog = $this->Listas->Tasks->Sentlogs->get($rs->id);
    	        
    	        $data['message_status'] = null;
    	        $data['last_error'] = null;
    	        $data['n_tries'] = null;
    	        $data['delivery_status'] = null;
    	        $data['report_stage'] = null;
    	        $data['send_date'] = null;
    	        $data['recv_date'] = null;
    	        $data['delivery_date'] = null;
    	        $data['num'] = null;
    	        $data['success'] = 0;
    	        
    	        $data['id'] = $rs->id;
    	        $data['task_id'] = $task->id;
    	        $data['lyric_id'] = $lyric->id;
    	        $data['workload_id'] = $rs->workload_id;
    	        $data['channel'] = $rs->channel;
    	        
    	        if($lyric->channel>0){
    	            $this->out("CONSULTANDO LIRYC");
    	            $now = new Time('now','America/Santiago');
    	            $this->out(print_r($now,true));
        	        $jsonurl = "http://".$lyric->web_username.":".$lyric->web_username."@".$lyric->ip."/cgi-bin/exec?cmd=api_get_status&".
        	   	        "username=".$lyric->username."&password=".$lyric->username.
        	   	        "&channel=".$rs->channel."&api_version=".$lyric->api."&message_id=".$rs->lyric_message_id;
        	        
        	        $JSON = file_get_contents($jsonurl);
        	        $lyricsResponse = json_decode($JSON);
        	        $this->out("RESPUESTA LIRYC");
        	        $this->out(print_r($JSON,true));
        	        
        	        $now = new Time('now','America/Santiago');
        	        $this->out(print_r($now,true));
        	    
        	        $data['success'] = (isset($lyricsResponse->success)&&($lyricsResponse->success=='true'))?1:0;
    				
        	        if(isset($lyricsResponse->success)&&($lyricsResponse->success=='true')){
    					$data['message_status'] = $lyricsResponse->message_status;    	        
    					$data['last_error'] = $lyricsResponse->last_error;
    					$data['n_tries'] = $lyricsResponse->n_tries;
    					$data['delivery_status'] = $lyricsResponse->delivery_status;
    					$data['report_stage'] = $lyricsResponse->report_stage;
    					$data['send_date'] = empty($lyricsResponse->send_date)?null:$lyricsResponse->send_date;
    					$data['recv_date'] = empty($lyricsResponse->recv_date)?null:$lyricsResponse->recv_date;
    					$data['delivery_date'] = empty($lyricsResponse->delivery_date)?null:$lyricsResponse->delivery_date;
    					$data['num'] = $lyricsResponse->num;
    				}
    				
        	        if(isset($lyricsResponse->error_code))
        	            $data['error_code'] = $lyricsResponse->error_code;
        	            else $data['error_code'] = NULL;
        	            $sentLog = $this->Listas->Tasks->Sentlogs->patchEntity($sentLog, $data);
    	    }
    	   
    	    if(empty($data['num'])){
    	            $w = $this->Listas->Workloads->get($rs->workload_id);
    	            $data['num'] = $w->phone;    	        
    	    }
    	    $this->out("GUARDANDO");
    	    $now = new Time('now','America/Santiago');
    	    $this->out(print_r($now,true));
    	    
    	    $this->Listas->Tasks->Sentlogs->save($sentLog);
    	    $this->out("CONSULTANDO");
    	    $now = new Time('now','America/Santiago');
    	    $this->out(print_r($now,true));
    	    
	      }
			
	    };
	    /*
	    
        $query = $this->Listas->Tasks->Sentlogs->find();
        $query->where(['Sentlogs.task_id'=>$task_id]);
        $cargados = $query->count();
        $query = null;
        
        /**
         * ENTREGADOS
         */
        $query = $this->Listas->Tasks->Sentlogs->find();
        $query->where([
            'Sentlogs.task_id'=>$task->id,
            'Sentlogs.success IS NOT NULL',   
            'Sentlogs.delivery_date IS NOT NULL',
            'Sentlogs.delivery_status <' => 3,            
        ]);
        $entregados = $query->count();
        $query = null;
        
        /**
         * ENVIADOS
         */
        $query = $this->Listas->Tasks->Sentlogs->find();
        $query->where([
            'Sentlogs.task_id'=>$task->id,
            'Sentlogs.success IS NOT NULL',        
            'Sentlogs.delivery_date IS NULL',
            'Sentlogs.recv_date IS NOT NULL',
            'Sentlogs.send_date IS NOT NULL',
            
        ]);
        $enviados = $query->count();
        $query = null;
        
        /**
         * EN COLA
         */
        $query = $this->Listas->Tasks->Sentlogs->find();
        $query->where([
            'Sentlogs.task_id'=>$task->id,
            'Sentlogs.success IS NOT NULL',     
            'Sentlogs.delivery_date IS NULL',
            'Sentlogs.recv_date IS NOT NULL',
            'Sentlogs.send_date IS NULL',
            
        ]);
        $en_cola = $query->count();
        $query = null;
        
        /**
         * ERROR
         */
        
        $query = $this->Listas->Tasks->Sentlogs->find();
        $query->where([
            'Sentlogs.task_id'=>$task->id,            
            'OR'=>[['Sentlogs.error_code IS NOT NULL','Sentlogs.error_code NOT LIKE'=>'BLACKLIST',],['Sentlogs.delivery_status >'=>2]],
            'Sentlogs.success IS NOT NULL',
            
            
        ]);
        
        $error = $query->count();
        $query = null;

        /**
         * LISTA NEGRA
         */
        
        $query = $this->Listas->Tasks->Sentlogs->find();
        $query->where([
            'Sentlogs.task_id'=>$task->id,            
            'Sentlogs.error_code LIKE'=>'BLACKLIST',            
        ]);
        
        $blacklist = $query->count();
        
        /**
         * SIN PROCESAR
         */
        $query = null;
        $query = $this->Listas->Tasks->Sentlogs->find();
        $query->where([
            'Sentlogs.task_id'=>$task->id,
            'OR' =>[['Sentlogs.success IS NULL'],['Sentlogs.success IS NOT NULL','Sentlogs.recv_date IS NULL', 'Sentlogs.error_code IS NULL' ]],            
        ]);
        $sin_proc= $query->count();
        
        $report = new \stdClass();
        
        
        $report->entregados = $entregados;
        $report->en_cola = $en_cola;
        $report->enviados = $enviados;
        $report->fallados = $error;
        $report->sin_proc = $sin_proc;
        
        $report->blacklists = $blacklist;
        //$report->errorPendientes = #en_cola ; //rechazados
        $report->cargados = $cargados;
        
        $this->out(print_r(['cargados'=>$cargados,'enviados'=>$enviados,'entregados'=>$entregados,'en_cola'=>$en_cola,'errores'=>$error,'sin_procesar'=>$sin_proc,'lista_negra'=>$blacklist],true));
	    return (object)['cargados'=>$cargados,'enviados'=>$enviados,'entregados'=>$entregados,'en_cola'=>$en_cola,'errores'=>$error,'sin_procesar'=>$sin_proc,'lista_negra'=>$blacklist];
	}
	
	private function mensaje($msg,$wload){
	    
	    $mensaje = $msg->message;	    
	    $RUT = $wload->dni.'-'.$wload->dni_dv;
	    $comodines   = ['#{rut}','#{nombre}','#{deuda}','#{sucursal}','#{link}','#{cuenta}'];
	    $reemplazo = [$RUT,$wload->nombre,'$'.number_format($wload->deuda,0,',','.'),$wload->sucursal,$wload->link,$wload->cuenta];
	    $msj = str_replace($comodines, $reemplazo, $mensaje);	    
	    $cadena = $msj;
	    $originales  = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
	    $modificadas = 'AAAAAAACEEEEIIIIDNOOOOOOUUUUYBSaaaaaaaceeeeiiiidnoooooouuuyybyRr';
	    $cadena = utf8_decode($cadena);
	    $cadena = strtr($cadena, utf8_decode($originales), $modificadas);
	  //  $cadena = strtolower($cadena);
	    $sms = utf8_encode($cadena);
	    	    	    
	    //$sms = str_replace($acentos, $sinacentos, utf8_decode($msj));
	    
	    return $sms;	    
	
	}
	
	public function statusSL($rs){
	 //   debug($rs);
	    $lyric = $this->Listas->Tasks->Sentlogs->Lyrics->get($rs->lyric_id);
	    $sentLog = $rs;//$this->Listas->Tasks->Sentlogs->get($rs->id);
	    $data = null;
	    $data['message_status'] = null;
	    $data['last_error'] = null;
	    $data['n_tries'] = null;
	    $data['delivery_status'] = null;
	    $data['report_stage'] = null;
	    $data['send_date'] = null;
	    $data['recv_date'] = null;
	    $data['delivery_date'] = null;
	    $data['num'] = null;
	    $data['success'] = 0;
	    
	    $data['id'] = $rs->id;
	    $data['task_id'] = $rs->task_id;
	    $data['lyric_id'] = $lyric->id;
	    $data['workload_id'] = $rs->workload_id;
	    $data['channel'] = $rs->channel;
	    debug($data);
	    if($lyric->channel>0){
	        
	        
	        $jsonurl = "http://".$lyric->web_username.":".$lyric->web_username."@".$lyric->ip."/cgi-bin/exec?cmd=api_get_status&".
	   	        "username=".$lyric->username."&password=".$lyric->username.
	   	        "&channel=".$rs->channel."&api_version=".$lyric->api."&message_id=".$rs->lyric_message_id;
	        
	        $JSON = file_get_contents($jsonurl);
	        $lyricsResponse = json_decode($JSON);
	        
	        
	        
	        $data['success'] = (isset($lyricsResponse->success)&&($lyricsResponse->success=='true'))?1:0;
	        
	        if(isset($lyricsResponse->success)&&($lyricsResponse->success=='true')){
	            $data['message_status'] = $lyricsResponse->message_status;
	            $data['last_error'] = $lyricsResponse->last_error;
	            $data['n_tries'] = $lyricsResponse->n_tries;
	            $data['delivery_status'] = $lyricsResponse->delivery_status;
	            $data['report_stage'] = $lyricsResponse->report_stage;
	            $data['send_date'] = empty($lyricsResponse->send_date)?null:$lyricsResponse->send_date;
	            $data['recv_date'] = empty($lyricsResponse->recv_date)?null:$lyricsResponse->recv_date;
	            $data['delivery_date'] = empty($lyricsResponse->delivery_date)?null:$lyricsResponse->delivery_date;
	            $data['num'] = $lyricsResponse->num;
	        }
	        
	        if(isset($lyricsResponse->error_code))
	            $data['error_code'] = $lyricsResponse->error_code;
	            else $data['error_code'] = NULL;
	            $sentLog = $this->Listas->Tasks->Sentlogs->patchEntity($sentLog, $data);
	    }
	    
	    if(empty($data['num'])){
	        $w = $this->Listas->Workloads->get($rs->workload_id);
	        $data['num'] = $w->phone;
	    }
	    $this->Listas->Tasks->Sentlogs->save($sentLog);
	}
	
	public function reciclaje_canal_cerrado(){
	    
	    
	    $lyrics = $this->Listas->Tasks->Sentlogs->Lyrics->find();
	    $lyrics->where(['Lyrics.active'=>'1']);
	    
	    if($lyrics->count() > 0){
	        
        
	        
    	    $SMSsParaRecilar =  $this->Listas->Tasks->Sentlogs->find()
    	    ->hydrate(false)
    	    ->join([    	        
    	        'l'=>
    	        [
    	        'table' => 'lyrics',    	        
    	        'type' => 'INNER',
    	        'conditions' => 'Sentlogs.lyric_id = l.id',
    	       ],
    	        't'=>[
    	            'table' => 'tasks',
    	            'type' => 'INNER',
    	            'conditions' => 'Sentlogs.task_id = t.id',
    	        ],
    	    ])->where([
    	        't.status'=>'ENVIANDO'
                ,'t.active'=>1
    	        ,'l.active = 0'
    	        ,'Sentlogs.created >= CURDATE()'
    	        ,'Sentlogs.message_status <' => 1
    	        ,'Sentlogs.send_date IS NULL'
    	        ,'Sentlogs.error_code IS NULL'
    	    ]);
    	    
    	    if($SMSsParaRecilar->count()>0){
    	        
    	        $canales_para_borrar = $SMSsParaRecilar;
    	        $canales_para_borrar
    	           ->select(['Sentlogs.lyric_id'])
    	           ->group(['Sentlogs.lyric_id']);
    	        
    	           foreach ($canales_para_borrar as $lyric_sl){ 
    	               $lyric = $this->Listas->Tasks->Sentlogs->Lyrics->get($lyric_sl['lyric_id']);
    	               
    	               //Borra los mensajes salientes nuevos es decir pendientes.
    	               $jsonurl = "http://".$lyric['web_username'].":".$lyric['web_password']."@"
    	                   .$lyric['ip']."/cgi-bin/exec?cmd=api_sms_delete_by_status&username="
    	                       .$lyric['username']."&password=".$lyric['password']."&api_version=".$lyric['api']
    	                       ."&sms_dir=out&channel=".$lyric['channel']."&status=new";
    	               
    	                       $JSON = file_get_contents($jsonurl);
    	                       $lyricsResponse = json_decode($JSON);
    	           }
    	        
    	        $SMSsParaRecilar
    	        ->select(['Sentlogs.task_id'])
    	        ->group(['Sentlogs.task_id']);
    	        
    	        foreach ($SMSsParaRecilar as $sentlog){
    	            
    	            $t = $this->Listas->Tasks->get($sentlog['task_id']);
        	        $l = $this->Listas->get($t->lista_id);
        	        
        	        
        	        if(($t->active == 1)&&($t->status == 'ENVIANDO')){
        	            
                	    $conn = ConnectionManager::get('default');
                	    $sql = "
                                UPDATE sentlogs,lyrics,tasks 
                                SET sentlogs.lyric_id = 0, success = null
                                WHERE   
                                    sentlogs.task_id = $t->id                                      
                                    AND sentlogs.lyric_id = lyrics.id
                                    AND lyrics.active = 0
                                    AND sentlogs.message_status < 1 
                                    AND sentlogs.last_error = 0
                                    AND sentlogs.send_date IS NULL 
                                    AND sentlogs.error_code IS NULL
                                    AND sentlogs.created >= CURDATE();";
                                     
                                    
                       
                	    $results = $conn
                	    ->execute($sql);
                	    
                	    $this->enviaListaLyric($t,$l);
        	    }
    	    }
	    }
	   }
	}
	
	function urlExists($url=NULL)
	{
	    if($url == NULL) return false;
	    $ch = curl_init($url);
	    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
	    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    $data = curl_exec($ch);
	    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	    curl_close($ch);	    
	    if($httpcode>=200 && $httpcode<300){
	        return true;
	    } else {
	        return false;
	    }
	}  
}
