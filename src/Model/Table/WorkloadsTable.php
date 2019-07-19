<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use PhpParser\Node\Expr\Cast\Object_;


/**
 * Workloads Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Listas
 * @property \Cake\ORM\Association\BelongsTo $Files
 *
 * @method \App\Model\Entity\Workload get($primaryKey, $options = [])
 * @method \App\Model\Entity\Workload newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Workload[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Workload|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Workload patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Workload[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Workload findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class WorkloadsTable extends Table
{

	
	//Cuando se hace una carga, guardamos mensajes.
	public $correcto = [];
	public $incorrecto = [];
	public $key = '';	
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('workloads');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Listas', [
            'foreignKey' => 'lista_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Files', [
            'foreignKey' => 'file_id',
            'joinType' => 'INNER'
        ]);
        
        $this->belongsTo('Messages', [
            'foreignKey' => 'message_id',
            'joinType' => 'LEFT'
        ]);
        $this->belongsTo('Links');
        $this->HasMany('Sentlogs');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->integer('dni')
            ->allowEmpty('dni');

        $validator
            ->allowEmpty('dni_dv');

        $validator
            ->integer('phone')
            ->allowEmpty('phone');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['lista_id'], 'Listas'));
        $rules->add($rules->existsIn(['file_id'], 'Files'));
        $rules->add($rules->existsIn(['link_id'], 'Links'));
        return $rules;
    }

    /**
     * Cuenta la carga de la lista $lista_id
     * @param int $lista_id
     * @return int
     */
    public function cuentaCargaLista($lista_id){
    	$queryCarga = $this->find();
    	$queryCarga->select(['count' => $queryCarga->func()->count('*')]);
    	$queryCarga->where(['Workloads.lista_id'=>$lista_id]);
    	$rsCarga = $queryCarga->first();
    	return $rsCarga->count;
    }
   
 	public function saveWorkload($csvData){
 	    set_time_limit(0);
 		foreach ($csvData as $data){
 		
 			//Creamos una nueva Carga.
 			$workload = $this->newEntity(); 			
 			$workload = $this->patchEntity($workload, (array)$data);
 			if ($this->save($workload)) {
 				$i = count($this->correcto);
 				$this->correcto[$i]['dni'] = $data->dni.'-'.$data->dni_dv;
 				$this->correcto[$i]['telefono'] = $data->phone;
 		
 				continue;
 			} else {
 				$i = count($this->incorrecto);
 				$this->incorrecto[$i]['dni'] = $data->dni.'-'.$data->dni_dv;
 				$this->incorrecto[$i]['telefono'] = $data->phone;
 				$this->incorrecto[$i]['error'] = 'El registro no se ha guardado.';
 		
 				continue;
 			}
 		
 		} 		
 	}
 	
 	public function saveWorkloadEntity($data){
 	    set_time_limit(0);
 	    
 	        
 	        //Creamos una nueva Carga.
 	        $workload = $this->newEntity();
 	        $workload = $this->patchEntity($workload, (array)$data);
 	        if ($this->save($workload)) {
 	            $i = count($this->correcto);
 	            $this->correcto[$i]['dni'] = $data->dni.'-'.$data->dni_dv;
 	            $this->correcto[$i]['telefono'] = $data->phone;
 	            
 	            //continue;
 	        } else {
 	            $i = count($this->incorrecto);
 	            $this->incorrecto[$i]['dni'] = $data->dni.'-'.$data->dni_dv;
 	            $this->incorrecto[$i]['telefono'] = $data->phone;
 	            $this->incorrecto[$i]['error'] = 'El registro no se ha guardado.';
 	            
 	            //continue;
 	        }
 	        
 	    
 	}
 	
    

}
