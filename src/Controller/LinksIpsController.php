<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * LinksIps Controller
 *
 * @property \App\Model\Table\LinksIpsTable $LinksIps
 */
class LinksIpsController extends AppController
{

	
    /**
     * View method
     *
     * @param string|null $id Links Ip id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
    	$link = $this->LinksIps->links->get($id); 
        $LinksIps = $this->LinksIps->findByLinkId($link->id, [
            'contain' => ['Links']
        ]);
		//$cuenta = $linksIp->count();
		 
        
        $this->paginate = [
        		'contain' => ['Links']
        ];
        $linksIps = $this->paginate($LinksIps);
        
        $this->set(compact('linksIps'));
        $this->set('_serialize', ['linksIps']);
    }

   
}
