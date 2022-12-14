<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Allstations Model
 *
 * @method \App\Model\Entity\Allstation newEmptyEntity()
 * @method \App\Model\Entity\Allstation newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Allstation[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Allstation get($primaryKey, $options = [])
 * @method \App\Model\Entity\Allstation findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Allstation patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Allstation[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Allstation|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Allstation saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Allstation[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Allstation[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Allstation[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Allstation[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class AllstationsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('allstations');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('brand')
            ->maxLength('brand', 255)
            ->allowEmptyString('brand');

        $validator
            ->scalar('code')
            ->maxLength('code', 64)
            ->allowEmptyString('code')
            ->add('code', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->allowEmptyString('name');

        $validator
            ->scalar('address')
            ->allowEmptyString('address');

        $validator
            ->scalar('suburb')
            ->maxLength('suburb', 255)
            ->allowEmptyString('suburb');

        $validator
            ->scalar('state')
            ->maxLength('state', 5)
            ->allowEmptyString('state');

        $validator
            ->scalar('postcode')
            ->maxLength('postcode', 6)
            ->allowEmptyString('postcode');

        $validator
            ->numeric('loc_lat')
            ->allowEmptyString('loc_lat');

        $validator
            ->numeric('loc_lng')
            ->allowEmptyString('loc_lng');

        $validator
            ->numeric('U91')
            ->allowEmptyString('U91');

        $validator
            ->numeric('P95')
            ->allowEmptyString('P95');

        $validator
            ->numeric('P98')
            ->allowEmptyString('P98');

        $validator
            ->numeric('DL')
            ->allowEmptyString('DL');

        $validator
            ->numeric('PDL')
            ->allowEmptyString('PDL');

        $validator
            ->numeric('LPG')
            ->allowEmptyString('LPG');

        $validator
            ->numeric('EV')
            ->allowEmptyString('EV');

        $validator
            ->numeric('E10')
            ->allowEmptyString('E10');

        $validator
            ->numeric('E85')
            ->allowEmptyString('E85');

        $validator
            ->numeric('B20')
            ->allowEmptyString('B20');

        $validator
            ->numeric('adblue')
            ->allowEmptyString('adblue');

        $validator
            ->numeric('LAF')
            ->allowEmptyString('LAF');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['code'], ['allowMultipleNulls' => true]), ['errorField' => 'code']);

        return $rules;
    }
}
