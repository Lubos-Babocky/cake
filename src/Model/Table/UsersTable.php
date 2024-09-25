<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model.
 *
 * @method \App\Model\Entity\User                                                                             newEmptyEntity()
 * @method \App\Model\Entity\User                                                                             newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\User>                                                                      newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User                                                                             get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\User                                                                             findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\User                                                                             patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\User>                                                                      patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\User|false                                                                       save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\User                                                                             saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\User>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\User>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\User>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\User>       saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\User>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\User>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\User>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\User>       deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class UsersTable extends Table
{
    /**
     * Initialize method.
     *
     * @param array<string, mixed> $config the configuration for the Table
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('users');
        $this->setDisplayField('login');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
    }

    /**
     * Default validation rules.
     *
     * @param Validator $validator validator instance
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('login')
            ->maxLength('login', 255)
            ->requirePresence('login', 'create')
            ->notEmptyString('login', 'Login is required!')
            ->add('login', 'unique', ['rule' => 'validateUnique', 'provider' => 'table', 'message' => 'Login already exists!']);

        $validator
            ->scalar('password')
            ->maxLength('password', 255)
            ->requirePresence('password', 'create')
            ->notEmptyString('password', 'Password is required!')
            ->add('password', 'minLength', [
                'rule' => ['minLength', 8],
                'message' => 'Password must contain at least 8 characters!',
            ])
            ->add('password', 'custom', [
                'rule' => function ($value, $context) {
                    return (bool) \preg_match('/^(?=.*[A-Z])(?=.*\d).+$/', $value);
                },
                'message' => 'The password must contain at least one number and one uppercase letter!']);
        $validator
            ->notEmptyString('password_confirm', 'The password confirmation is mandatory!')
            ->add('password_confirm', 'custom', [
                'rule' => function ($value, $context) {
                    return $value === $context['data']['password'];
                },
                'message' => 'The password and its confirmation must match!']);

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param RulesChecker $rules the rules object to be modified
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['login']), ['errorField' => 'login']);

        return $rules;
    }
}
