<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;

/**
 * Blacklists Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Clients
 * @property \Cake\ORM\Association\BelongsTo $ListaNegras
 *
 * @method \App\Model\Entity\Blacklist get($primaryKey, $options = [])
 * @method \App\Model\Entity\Blacklist newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Blacklist[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Blacklist|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Blacklist patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Blacklist[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Blacklist findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BlacklistsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('blacklists');
        $this->setDisplayField('id');
        $this->setPrimaryKey(['id']);

        $this->addBehavior('Timestamp');

        $this->belongsTo('Clients', [
            'foreignKey' => 'client_id',
            'joinType' => 'INNER'
        ]);/*
        $this->belongsTo('ListaNegras', [
            'foreignKey' => 'lista_negra_id',
            'joinType' => 'INNER'
        ]);*/
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
        $rules->add($rules->existsIn(['client_id'], 'Clients'));
//        $rules->add($rules->existsIn(['lista_negra_id'], 'ListaNegras'));

        return $rules;
    }
    
    /**
     * Obtiene las listas negras de PhoneSMS
     * Filtradas segun clientes creados
     * en FrontSMS o del cliente indicado si 
     * se incluye
     * @param NULL|int $client_id
     * @return \Cake\Database\Query
     */
    public function getListasNegrasPhoneSMS($client_id = null){
    	$query = $this->Clients->find();
    	$query->select(['yx_login_id','codcli']);
    	if(!is_null($client_id))
    		$query->where(['Clients.id'=>$client_id]);
    	foreach ($query as $rs){
    		$yx_login_id_arr[] = $rs->yx_login_id;
    	}
    	$yx_login_id = implode(',',$yx_login_id_arr);
    	
    	$conn = ConnectionManager::get('phonesms');
    	$results = $conn
    	->execute("SELECT NULL AS id	
					,LEFT(`lista_negra_rut`, LENGTH(`lista_negra_rut`) - 1) AS dni
					,RIGHT(`lista_negra_rut`, 1) AS dni_dv
					,RIGHT(`lista_negra_fono`, 9) AS phone
					,`cliente_id` AS yx_login_id
					,`yx_login_codcli` AS codcli
					,`lista_negra_id`
					,`created`
					,`created` AS modified
				FROM `lista_negra`
				INNER JOIN yx_login ON (yx_login.yx_login_id = lista_negra.cliente_id)
    			WHERE lista_negra.cliente_id IN ($yx_login_id);");
    			//->fetchAll('assoc');
    	
		$resultado = $results;
        $conn = ConnectionManager::get('default');
        return $resultado;
    }

    public function insertBlacklist($phone,$rut,$phonesms_cliente_id){
    	   	
    	$conn = ConnectionManager::get('phonesms');
    	//Quitamos lo que no es valido
    	$rut = preg_replace("/[^Kk0-9]/",'',$rut);
    	$phone = preg_replace("/[^0-9]/",'',$phone);
    	
    	//Ahora
    	$now = date("Y-m-d H:i:s");
    	//Un hash para reconocer lo que insertamos
    	//Imposible rescatar el ultimo insert por le
    	//modo de este modelo (externo)
    	$hash = md5(rand().$now);
    	//Insertamos un hash unico
    	$results = $conn
    	->execute("INSERT INTO lista_negra 
    					(lista_negra_fono,created)
    			VALUES (:hash,'$now');",['hash'=>$hash]);
    	
    	//Ahora buscamos el insert id
    	$results = $conn
    	->execute("SELECT 
    			lista_negra_id
    			FROM `lista_negra`
    			WHERE lista_negra.lista_negra_fono LIKE :hash;",['hash'=>$hash])
    	->fetchAll('assoc');
    	$lista_negra_id = $results[0]['lista_negra_id'];
    	//Si esta todo ok...
    	if(!empty($lista_negra_id)){
    	$results = $conn
    	->execute("UPDATE lista_negra SET 
    			lista_negra_fono=:phone,lista_negra_rut=:rut,cliente_id=$phonesms_cliente_id
    			WHERE lista_negra_id=:lista_negra_id;",[
    					'phone'=>$phone,'rut'=>$rut,'lista_negra_id'=>$lista_negra_id]);
    	$conn = ConnectionManager::get('default');
    	 if($results)
    	 	return $lista_negra_id;
    	}
    	$conn = ConnectionManager::get('default');
    	return false;
    }
    
    public function deleteBlacklist($lista_negra_id){
    	if(empty($lista_negra_id)) return false;
    	$conn = ConnectionManager::get('phonesms');
    	$results = $conn
    	->execute("DELETE    			
    			FROM `lista_negra`
    			WHERE lista_negra.lista_negra_id = :lista_negra_id;",['lista_negra_id'=>$lista_negra_id]);
    	return $results;
    }
    
       
    
    
    
}
