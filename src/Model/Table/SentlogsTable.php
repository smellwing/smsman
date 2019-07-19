<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Sentlogs Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Workloads
 *
 * @method \App\Model\Entity\Sentlog get($primaryKey, $options = [])
 * @method \App\Model\Entity\Sentlog newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Sentlog[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Sentlog|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Sentlog patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Sentlog[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Sentlog findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SentlogsTable extends Table
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

        $this->setTable('sentlogs');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Workloads', [
            'foreignKey' => 'workload_id',
            'joinType' => 'INNER'
        ]);
        
        $this->belongsTo('Tasks', [
        		'foreignKey' => 'task_id',
        		'joinType' => 'INNER'
        ]);
        
        $this->belongsTo('Lyrics', [
            'foreignKey' => 'lyric_id',
            'joinType' => 'LEFT'
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

        /*$validator
            ->requirePresence('result', 'create')
            ->notEmpty('result');*/

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
        $rules->add($rules->existsIn(['workload_id'], 'Workloads'));
        $rules->add($rules->existsIn(['task_id'], 'Tasks'));
        
        return $rules;
    }
}
