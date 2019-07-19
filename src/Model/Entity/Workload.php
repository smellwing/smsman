<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Workload Entity
 *
 * @property int $id
 * @property int $dni
 * @property string $dni_dv
 * @property int $phone
 * @property int $lista_id
 * @property int $file_id
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\File $file
 */
class Workload extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];
}
