<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Files Model
 *
 * @property \Cake\ORM\Association\HasMany $Workloads
 *
 * @method \App\Model\Entity\File get($primaryKey, $options = [])
 * @method \App\Model\Entity\File newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\File[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\File|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\File patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\File[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\File findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class FilesTable extends Table
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

        $this->setTable('files');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Workloads', [
            'foreignKey' => 'file_id'
        ]);
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
            ->allowEmpty('name');

        return $validator;
    }
    
    /**
     * Obtiene el contenido del archivo cargado $file_id
     * @param int $file_id
     * @return NULL|StdClass
     */
    public function getFileData($file_id){
        set_time_limit(0);
    	//Query File. Leeremos los datos de archivo desde la BBDD.
    	$queryFile 		= $this->find();
    	$queryFile->select(['data']);
    	$queryFile->where(['Files.id'=>$file_id]);
    	$fileRs 		= $queryFile->first();
    	/*
    	 * Si no pilla el archivo, da error.
    	 */
    	if(is_null($fileRs)){
    		return null;
    	}
    	//El archivo está comprimido.
    	
    	$fileContents 	= gzuncompress(stream_get_contents($fileRs->data));
		$fileContents   = utf8_decode(utf8_encode(trim($fileContents)));
		
    	//@#%#@ es solo una marca.
    	//$fileContents = trim(preg_replace('/\s\s+/', '@#%#@', $fileContents));
		$fileContents = trim(preg_replace("/(\s*[\r\n]+\s*)/", '@#%#@', $fileContents));
    	
    	$fileContents = explode('@#%#@',$fileContents);
    	return (object)$fileContents;
    
    }
}
