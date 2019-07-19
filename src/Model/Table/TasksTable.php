<?php
/**
 *  Modelo Tablas
 *
 * @category  Tasks
 * @package   Tasks
 * @author    Juan Esteban Valenzuela Rodríguez <juan.esteban.valenzuela@gmail.com>
 * @copyright 2018 Juan Esteban Valenzuela R.
 * @license   https://opensource.org/licenses/GPL-3.0 GPL 3.0 
 * @link      https://dev.azure.com/smellwing/SMSMan Pagina principal proyecto
 */
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;
use Cake\I18n\Time;
use Cake\Chronos\Date;

/**
 * Tasks Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Listas
 * @property \Cake\ORM\Association\BelongsTo $Messages
 *
 * @method \App\Model\Entity\Task get($primaryKey, $options = [])
 * @method \App\Model\Entity\Task newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Task[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Task|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Task patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Task[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Task findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class TasksTable extends Table
{

    private $_horarios=[
                9=>0,
                10=>0,
                11=>0,
                12=>0,
                13=>0,
                14=>0,
                15=>0,
                16=>0,
                17=>0,
                18=>0,
                19=>0,
                20=>0,
        ]; 
    private $_carga_maxima_hora = 0;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * 
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('tasks');
        $this->setDisplayField('message');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo(
            'Listas', 
            [
            'foreignKey' => 'lista_id',
            'joinType' => 'INNER'
            ]
        );
        $this->belongsTo(
            'Messages', [
            'foreignKey' => 'message_id',
            'joinType' => 'LEFT'
            ]
        );
        
        $this->hasMany('Sentlogs');
        
        //Obtnemos datos de Configuraci�n de Carga M�xima por hora
        //El valor alamcenado, en realidad es en base a la experiencia.
        $this->Settings = TableRegistry::get('Settings');

        $msg = 'carga_maxima_hora';
        if (!empty($this->Settings->findBySetting($msg)->value)) {
            $this->_carga_maxima_hora = $this->Settings->findBySetting($msg)->value;
        } else {
            $this->_carga_maxima_hora = 60;
        }
        $this->estadoCargas();        
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * 
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator->add(
            'name', [
                    'unique' => [
                            'rule' => 'validateUnique',
                            'provider' => 'table',
                            'message' => 'ERROR: ESTE NOMBRE DEBE SER UNICO'
                    ]
            ]
        );
        

        $validator
            ->dateTime('datetime_start')
            ->notEmpty('datetime_start', 'ERROR: ELIJA UN INICIO');

        $validator
            ->dateTime('datetime_end')
            ->notEmpty('datetime_end', 'ERROR: ELIJA UN CIERRE');

        return $validator;
    }
    
    
    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * 
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['lista_id'], 'Listas'));

        return $rules;
    }
    
    
    public function cargaListasActivaPhoneSMS(){
        
        $clientesExternos = $this->Listas->Clients->findByTipo('EXTERNO')->toArray();
        foreach ($clientesExternos as $cliente){
            $yx_login_ids[] = $cliente->yx_login_id;
        }
        $lista_yx_login_ids = implode(',',$yx_login_ids);
        $sql = "SELECT 
                    COUNT(*) AS CARGAPHONESMS 
                FROM 
                    yx_lista
                INNER JOIN
                    yx_sms_out
                ON(yx_lista_estado LIKE 'ACTIVA' 
                    AND yx_lista.yx_lista_id=yx_sms_out.yx_lista_id)
                WHERE 
                yx_sms_out_estado NOT LIKE 'ERROR'
                AND yx_lista.yx_lista_create_usuario_id IN ($lista_yx_login_ids);
                ";
        $conn = ConnectionManager::get('phonesms');
        $results = $conn
        ->execute($sql)
        ->fetchAll('assoc');
        
        $resultado = intval($results[0]['CARGAPHONESMS']);
        $conn = ConnectionManager::get('default');
        return $resultado;
    }
    
    
    public function cargaListasPausadaPhoneSMS(){
        $conn = ConnectionManager::get('phonesms');
        $results = $conn
        ->execute('SELECT
                    COUNT(*) AS CARGAPHONESMS
                FROM
                    yx_lista
                INNER JOIN
                    yx_sms_out
                ON(yx_lista_estado LIKE "PAUSADA"
                    AND yx_lista.yx_lista_id=yx_sms_out.yx_lista_id)
                WHERE
                yx_sms_out_estado NOT LIKE :estado',
                ['estado' => 'ERROR'])
                ->fetchAll('assoc');
                $resultado = intval($results[0]['CARGAPHONESMS']);
                $conn = ConnectionManager::get('default');
                return $resultado;
    }
    

    /*
     * function clean($string) {
     $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
    
     return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
     }
     */
  
    public function getListStatus($yx_lista_id){
        $conn = ConnectionManager::get('phonesms');
        $results = $conn
        ->execute('SELECT 
                    COUNT(yx_sms_report_id) as CANTI
                        ,yx_sms_report_status
                        ,yx_sms_report_delivery_estado
                     FROM
                    (
                    SELECT 
                        yx_sms_report_id
                        ,yx_sms_report_status
                        ,yx_sms_report_delivery_estado
                    FROM `yx_sms_report_hist`
                    WHERE yx_lista_id = :yx_lista_id
                    UNION
                    SELECT 
                        yx_sms_report_id
                        ,yx_sms_report_status
                        ,yx_sms_report_delivery_estado
                    
                    FROM `yx_sms_report`
                    WHERE yx_lista_id = :yx_lista_id
                    ) AS CONSOLIDADO
                    GROUP BY 
                        yx_sms_report_status
                        ,yx_sms_report_delivery_estado',
                ['yx_lista_id' => $yx_lista_id]);
            
        $report = new \stdClass();
        $enviado = 0; $fallado = 0;
        $blacklist = 0; $errorPendiente = 0;
        $enviadoCola = 0; $cargados = 0;
        foreach ($results as $yx_sms_report){            
            
            switch($yx_sms_report['yx_sms_report_delivery_estado']){
                case 'DeliveryOK':
                    $enviado += $yx_sms_report['CANTI'];
                    break;
                case 'DeliveryPending':
                    /*
                     * yx_sms_report_status
                     * BLACKLIST: Callo en lista negra
                     * Enviado: Se mando al telef 
                     * Enviando: Se esta enviando
                     * Fallado: Pendiente con error. 
                     */
                    switch($yx_sms_report['yx_sms_report_status']){
                    case 'BLACKLIST':
                            $blacklist+=$yx_sms_report['CANTI'];
                        break;
                    case 'Enviado':
                            $enviado += $yx_sms_report['CANTI'];
                        break;
                    case 'Enviando':
                            $enviadoCola += $yx_sms_report['CANTI'];
                        break;
                    case 'Fallado':                            
                            $errorPendiente += $yx_sms_report['CANTI'];
                        break;
                    }
                    break;
                case 'DeliveryFailed':
                    $fallado += $yx_sms_report['CANTI'];
                    break;
            }
            
        }
        $report->enviados = $enviado;
        $report->fallados = $fallado;
        $report->blacklists = $blacklist;
        $report->errorPendientes = $errorPendiente;
        $report->enviadoColas    = $enviadoCola;
        $report->cargados    = $enviado+$fallado+$blacklist+$errorPendiente+$enviadoCola;
        $conn = ConnectionManager::get('default');
        
        return $report;
    }
    
    public function getListLyricStatus($task_id){
       
        $query = $this->Listas->Tasks->Sentlogs->find();
        $query->where(['Sentlogs.task_id'=>$task_id]);
        $cargados = $query->count();
        $query = null;
        
        /**
         * ENTREGADOS
         */
        $query = $this->Listas->Tasks->Sentlogs->find();
        $query->where([
            'Sentlogs.task_id'=>$task_id,
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
            'Sentlogs.task_id'=>$task_id,
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
            'Sentlogs.task_id'=>$task_id,
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
            'Sentlogs.task_id'=>$task_id,            
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
            'Sentlogs.task_id'=>$task_id,            
            'Sentlogs.error_code LIKE'=>'BLACKLIST',            
        ]);
        
        $blacklist = $query->count();
        
        /**
         * SIN PROCESAR
         */
        $query = null;
        $query = $this->Listas->Tasks->Sentlogs->find();
        $query->where([
            'Sentlogs.task_id'=>$task_id,
            'OR' =>[['Sentlogs.success IS NULL', 'Sentlogs.error_code IS NULL'],['Sentlogs.success IS NOT NULL','Sentlogs.recv_date IS NULL', 'Sentlogs.error_code IS NULL' ]],            
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
        
        return $report;
    }
    
    
    /**
     * 
     * @param \DateTime $start_time
     * @param \DateTime $end_time
     * @param int  $carga
     * @return number|number[]
     */
    private function cargaHelper($start_time,$end_time,$carga){
    
        $_horarios         = $this->_horarios;
        $start_hour        = $start_time->i18nFormat('HH');
        $start_minute    = $start_time->i18nFormat('mm');
    
        
        if((intval($start_minute)>=30)) { 
            $start_ref = intval($start_hour)+0.5; 
        } 
        else {
            $start_ref=intval($start_hour);
        }
        //Hora y minutos de cierre
        $end_hour         = $end_time->i18nFormat('HH');
        $end_minute          = $end_time->i18nFormat('mm');
         
        //Aproxima para arriba los valores
        if((intval($end_minute)>=30)) { 
            $end_ref = intval($end_hour)+0.5;
        }
        else { 
            $end_ref=intval($end_hour);
        }
        
        $tiempo_restante = $carga/$this->_carga_maxima_hora; //Cuanto toma mandar todos los mensajes de la cola.
        $carga_restante = $carga;
    
        //Asigna la carga programada a cada horaria (de 9 a 20)
        //No hago ning�n ajuste para las 20 a 20.30...
        $j=$start_ref;
        for($i=$start_ref;$i<$end_ref;$i++){
            
            //Si ya acabamos, rompemos el bucle
            if(($tiempo_restante<=0)||($carga_restante<=0)) break;
        
            //En este caso, se considera una carga completa para esa hora (100%)
            if($carga_restante>=$this->_carga_maxima_hora){                
                if(isset($_horarios[$i]))  {  $j=$i; }
                $_horarios[$j]++;
            }
            else {
                //En este caso, es una carga parcial.
                if(isset($_horarios[$i])) { $j=$i; }
                $_horarios[$j]+=$carga_restante/$this->_carga_maxima_hora;                
            }
            //Restamos la carga que queda     
            $carga_restante = $carga_restante-$this->_carga_maxima_hora;
            $tiempo_restante--;
        }
        return $_horarios;
    }
    
    
    
    /**
     * Mide la carga del d�a
     * @return number
     */
    public function estadoCargas(){
        
        //Obtnemos la fecha y hora actual
        $dateTimeNow = new Time('now','America/Santiago');
        $dateNow = $dateTimeNow->i18nFormat('yyyy-MM-dd'); 
        
        //Carga  de tareas
        
        //Obtenemos las tareas activas.
        $qTareas = $this->find();
        $qTareas->select([
                'datetime_start',
                'datetime_end',
                'lista_id',
                'diferencia_segundos'=>'TIMESTAMPDIFF(SECOND,datetime_start,datetime_end)'                
        ]);       
        $qTareas->where(['Tasks.active'=>1]);
        $qTareas->where(['Tasks.datetime_end >'=>$dateTimeNow]);
        $qTareas->where(['DATE(Tasks.datetime_start)'=>$dateNow]);
        $qTareas->where(function ($exp, $q) {
            return $exp->in('Tasks.status', ['NUEVA','ENVIANDO']);
            });
        //Ejecutamos la consulta qTareas..
        foreach ($qTareas as $tarea){                 
            
            $qCargas = $this->Listas->Workloads->find();
            $qCargas->select(['count' => $qCargas->func()->count('*')]);
            $qCargas->where(['lista_id'=>$tarea->lista_id]);
            
            foreach ($qCargas as $carga){
                $this->_horarios = $this->cargaHelper($tarea->datetime_start, $tarea->datetime_end, $carga->count);
            }
        }        
        return $this->_horarios;        
    }
    
    /**
     * 
     * @param \DateTime $start_time
     * @param \DateTime $end_time
     * @param int $lista_id
     * @return number|\App\Model\Table\number[]|number[]
     */    
    public function proyectarCarga($start_time,$end_time,$lista_id){
        
        //rescatamos los horarios
        $_horarios = $this->_horarios;
        
        //Rescatamos las cargas subidas, pero de la listas del argumento
        $qCargas  = $this->Listas->Workloads->find();        
        //$qCargas->select(['count' => $qCargas->func()->count('*')]);
        $qCargas->where(['lista_id'=>$lista_id]);
        
        //Obtenemos las cargas desde el helper personalizado. 
           $_horarios = $this->cargaHelper($start_time, $end_time, $qCargas->count());        
               
        return $_horarios;
        
    }
    
    /**
     * Obtine la carga en ese horario
     * @param int $hora
     * @return number|\App\Model\Table\number
     */
    public function getCargaHora($hora){
        return $this->_horarios[$hora];
    }

    /**
     * Obtiene la carga m�cima configurada en el sistema
     * @return number
     */
    public function getCargaMaxima(){
     return $this->_carga_maxima_hora;
    }
    
    public function getInboxSMS($yx_lista_id)
    {
        $sql = "
            SELECT
            * 
            FROM yx_sms_inbox
            WHERE yx_lista_id = :yx_lista_id            
        ";
        $conn = ConnectionManager::get('phonesms');
        $results = $conn
        ->execute(
                $sql,
                [
                    'yx_lista_id' => $yx_lista_id                
                ]);
        $conn = ConnectionManager::get('default');
        return $results;
    }
    
    public function existsInboxSMS($yx_lista_id)
    {
        $sql = "
            SELECT
            count(*) as haySMSIbox
            FROM yx_sms_inbox
            WHERE yx_lista_id = :yx_lista_id
        ";
        $conn = ConnectionManager::get('phonesms');
        $results = $conn
        ->execute(
                $sql,
                [
                        'yx_lista_id' => $yx_lista_id
                ]);
        
        foreach($results as $result) { $resultado = $result[0]; break;}
        $conn = ConnectionManager::get('default');
        return (intVal($resultado)>0)?true:false;
    }
    
    public function phoneStatusDirect($task_id){
        
        $task = $this->get($task_id);
        $yx_lista_id = $task->yx_lista_id;
        
        $sql = "/*(SELECT
        `yx_sms_report`.`yx_sms_report_fono` as fono_destino
        ,`yx_sms_report`.`yx_sms_report_msg` as mensaje
        ,`yx_sms_report`.`yx_sms_report_estado` as estado_entrega
        ,`yx_sms_report`.`yx_sms_report_fecha_envio` as fecha_envio_mensaje
        ,`yx_sms_report`.`yx_sms_report_fecha_status`  as fecha_respuesta_estado_mensaje_plataforma
        ,`yx_sms_report`.`yx_sms_report_status` as respuesta_estado_mensaje_plataforma
        ,`yx_sms_report`.`yx_sms_report_messageId` as rut_mensaje
        ,`yx_sms_report`.`yx_sms_report_desc` as descripcion_respuesta_estado_mensaje_plataforma
        ,`yx_sms_report`.`yx_sms_report_channel` as fono_salida_mensaje
        ,`yx_sms_report`.`yx_sms_report_counter` as reintentos_envio
        ,`yx_sms_report`.`yx_sms_report_fecha_update` as fecha_actualizacion
        ,`yx_sms_report`.`yx_sms_report_uniqueid` as identificador_unico_plataforma_mensaje
        ,`yx_sms_report`.`yx_sms_report_delivery_estado` as estado_entrega
        ,`yx_sms_report`.`yx_sms_report_delivery_fecha` as fecha_entrega
        ,`yx_sms_report`.`yx_sms_report_delivery_estado_desc` as descripcion_estado_entrega
        FROM yx_sms_report
        WHERE `yx_sms_report`.`yx_lista_id` = '$yx_lista_id')
        UNION*/ (SELECT
        `yx_sms_report_hist`.`yx_sms_report_fono` as fono_destino
        ,`yx_sms_report_hist`.`yx_sms_report_msg` as mensaje
        ,`yx_sms_report_hist`.`yx_sms_report_estado` as estado_entrega
        ,`yx_sms_report_hist`.`yx_sms_report_fecha_envio` as fecha_envio_mensaje
        ,`yx_sms_report_hist`.`yx_sms_report_fecha_status`  as fecha_respuesta_estado_mensaje_plataforma
        ,`yx_sms_report_hist`.`yx_sms_report_status` as respuesta_estado_mensaje_plataforma
        ,`yx_sms_report_hist`.`yx_sms_report_messageId` as rut_mensaje
        ,`yx_sms_report_hist`.`yx_sms_report_desc` as descripcion_respuesta_estado_mensaje_plataforma
        ,`yx_sms_report_hist`.`yx_sms_report_channel` as fono_salida_mensaje
        ,`yx_sms_report_hist`.`yx_sms_report_counter` as reintentos_envio
        ,`yx_sms_report_hist`.`yx_sms_report_fecha_update` as fecha_actualizacion
        ,`yx_sms_report_hist`.`yx_sms_report_hist_uniqueid` as identificador_unico_plataforma_mensaje
        ,`yx_sms_report_hist`.`yx_sms_report_delivery_estado` as estado_entrega
        ,`yx_sms_report_hist`.`yx_sms_report_delivery_fecha` as fecha_entrega
        ,`yx_sms_report_hist`.`yx_sms_report_delivery_estado_desc` as descripcion_estado_entrega
        FROM yx_sms_report_hist
        WHERE  `yx_sms_report_hist`.`yx_lista_id` = '$yx_lista_id'
        )";
        
        $conn = ConnectionManager::get('phonesms');
        $results = $conn
        ->execute(
                $sql,
                [
                        'yx_lista_id' => $yx_lista_id
                ]);
        $conn = ConnectionManager::get('default');
        return $results;
    }
    
}
