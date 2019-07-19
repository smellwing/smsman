<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Lyrics Model
 *
 * @method \App\Model\Entity\Lyric get($primaryKey, $options = [])
 * @method \App\Model\Entity\Lyric newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Lyric[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Lyric|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Lyric patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Lyric[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Lyric findOrCreate($search, callable $callback = null, $options = [])
 */
class LyricsTable extends Table
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

        $this->setTable('lyrics');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        
        $this->hasOne('Sentlogs');
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
            ->allowEmpty('ip');

        $validator
            ->allowEmpty('usuario');

        $validator
            ->allowEmpty('clave');

        $validator
            ->allowEmpty('usuarioweb');

        $validator
            ->allowEmpty('claveweb');

        $validator
            ->allowEmpty('api');

        return $validator;
    }
}
