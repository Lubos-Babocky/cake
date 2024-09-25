<?php

declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Categories Model.
 *
 * @property \App\Model\Table\ProductsTable&\Cake\ORM\Association\BelongsToMany $Products
 *
 * @method \App\Model\Entity\Category                                                                                 newEmptyEntity()
 * @method \App\Model\Entity\Category                                                                                 newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Category>                                                                          newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Category                                                                                 get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Category                                                                                 findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Category                                                                                 patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Category>                                                                          patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Category|false                                                                           save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Category                                                                                 saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Category>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Category>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Category>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Category>       saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Category>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Category>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Category>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Category>       deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CategoriesTable extends Table
{
    /**
     * Initialize method.
     *
     * @param array<string, mixed> $config the configuration for the Table
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('categories');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsToMany('Products', [
            'foreignKey' => 'category_id',
            'targetForeignKey' => 'product_id',
            'joinTable' => 'categories_products',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator validator instance
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

        return $validator;
    }
}
