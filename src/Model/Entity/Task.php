<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Task Entity
 *
 * @property int $id
 * @property string $name
 * @property \Cake\I18n\Time $datetime_start
 * @property \Cake\I18n\Time $datetime_end
 * @property int $lista_id
 * @property int $message_id
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\Lista $lista
 * @property \App\Model\Entity\Message $message
 */
class Task extends Entity
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
	
	
	protected $_virtual = ['inbox'];
	
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];    
    
    
}
