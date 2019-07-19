<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\I18n\Time;
/**
 * Sentlog Entity
 *
 * @property int $id
 * @property int $workload_id
 * @property string $result
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \App\Model\Entity\Workload $workload
 */
class Sentlog extends Entity
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
    protected $_virtual = [
        'recv_date_tz',
        'send_date_tz',        
        'delivery_date_tz',
        'estado_entrega',
    ];
    
    protected function _getRecvDateTz()
    {        
        if(!is_null($this->_properties['recv_date'])){
            $time = new Time($this->_properties['recv_date']);
            $time->modify('-3 hours');
            return $time->i18nFormat("yyyy-MM-dd HH:mm'", 'America/Santiago');
        } else return null;
    }
    protected function _getSendDateTz()
    {
        if(!is_null($this->_properties['send_date'])){
            $time = new Time($this->_properties['send_date']);
            $time->modify('-3 hours');
            return $time->i18nFormat("yyyy-MM-dd HH:mm'", 'America/Santiago');
        } else return null;
    }
    
    protected function _getDeliveryDateTz()
    {
        if(!is_null($this->_properties['delivery_date'])){
            $time = new Time($this->_properties['delivery_date']);
            $time->modify('-3 hours');
            return $time->i18nFormat("yyyy-MM-dd HH:mm'", 'America/Santiago');
        } else return null;
    }
    
    protected function _getEstadoEntrega()
    {
        $error_code = $this->_properties['error_code'];
        $delivery_date = $this->_properties['delivery_date'];
        $delivery_status = $this->_properties['delivery_status'];
        $send_date = $this->_properties['send_date'];
        $recv_date = $this->_properties['recv_date'];
        $success = $this->_properties['success'];
        if(!empty($error_code))
        {
            if($error_code == 'BLACKLIST'){
                $estado_entrega = 'LISTA NEGRA';
            }
             elseif($error_code == 'DatabaseProblemORIdNotFound'){
                    $estado_entrega = 'ERROR: SIN INFORMACION EN LIRYC';
            } 
           else $estado_entrega = 'ERROR DESCONOCIDO: '.$delivery_status ;
            
        } else {
            if(!is_null($success)){
                if(!empty($recv_date)){
                    if(!empty($send_date)){                
                        if(!empty($delivery_date)) {
                            if($delivery_status<3){
                                $estado_entrega = 'ENTREGADO';
                            } else {
                                $estado_entrega = 'ERROR '.$delivery_status;
                            }
                        }
                        else {
                            $estado_entrega = 'ENVIADO';
                        }
                    } 
                    else{
                        $estado_entrega = 'EN COLA';
                    }
                
                } else {
                    $estado_entrega = 'NO ENCOLADO';
            }   
         }
         else 
         {
             $estado_entrega = 'SIN PROCESAR';
         }
        }
        return $estado_entrega;
    }
}
