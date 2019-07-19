<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Lista Entity
 *
 * @property int $id
 * @property string $name
 * @property int $user_id
 * @property int $client_id
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Client $client
 * @property \App\Model\Entity\Task[] $tasks
 * @property \App\Model\Entity\Workload[] $workloads
 */
class Lista extends Entity
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
