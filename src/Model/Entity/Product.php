<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Product Entity.
 *
 * @property int                          $id
 * @property string                       $name
 * @property string|null                  $description
 * @property string                       $price
 * @property string                       $vat_rate
 * @property string|null                  $image
 * @property \Cake\I18n\DateTime          $created
 * @property \Cake\I18n\DateTime|null     $modified
 * @property \App\Model\Entity\Category[] $categories
 */
class Product extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'name' => true,
        'description' => true,
        'price' => true,
        'vat_rate' => true,
        'image' => true,
        'created' => true,
        'modified' => true,
        'categories' => true,
    ];
}
