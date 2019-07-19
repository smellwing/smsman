<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Blacklists Controller
 *
 * @property \App\Model\Table\BlacklistsTable $Blacklists
 */
class BlacklistsController extends AppController
{

	
    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
    	$conditions=null;
    	if(!$this->isAdmin)
    		$conditions = ['Blacklists.client_id IN'=>$this->clienteId];
    		 
    		
        $this->paginate = [
            'contain' => ['Clients'],
        	'order'	=> ['Blacklists.id'=>'DESC'],
        	'conditions'=> $conditions
        ];
        $blacklists = $this->paginate($this->Blacklists);

        
        $this->set(compact('blacklists'));
        $this->set('_serialize', ['blacklists']);
    }
 

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
    	$conditions=null;
    	if(!$this->isAdmin){
    	    $clientes = $this->Blacklists->Clients->Users->find(); 
    		$conditions = ['Clients.id IN'=>$this->clienteId];
    	}
    		 
        $blacklist = $this->Blacklists->newEntity();
        if ($this->request->is('post')) {
        	$data = $this->request->getData();            
            //Buscamos el ID cliente de phoneSMS
            $query_client = $this->Blacklists->Clients->find();
            $query_client->select(['yx_login_id']);
            $query_client->where(['id'=>$data['client_id']]);
            $client = $query_client->first();
            //Seteamos datos
            $data['phone'] 	= empty($data['phone'])?null:$data['phone'];            
            $phone 			= $data['phone'];            
            
            $data['dni']	= empty($data['dni'])?null:$data['dni'];
            $rut			= empty($data['dni'])?null:$data['dni'].$data['dni_dv'];
            $phonesms_cliente_id = $client->yx_login_id;
            //Ahora insertamos en PhoneSMS
            $lista_negra_id = $this->Blacklists->insertBlacklist($phone, $rut, $phonesms_cliente_id);
            //Verificamos si se guarda
            if(empty($lista_negra_id )) return false;
            
            $data['lista_negra_id'] = $lista_negra_id;
            //Ahor si validamos para guardar ac�
            $blacklist = $this->Blacklists->patchEntity($blacklist, $data);            
            if ($this->Blacklists->save($blacklist)) {
                $this->Flash->success(__('Se ha guardado el dato.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The blacklist could not be saved. Please, try again.'));
        }
        $clients = $this->Blacklists->Clients->find('list', [
        		'limit' => 200,
        		'conditions'=>$conditions 
        ]);

        $this->set(compact('blacklist', 'clients', 'listaNegras'));
        $this->set('_serialize', ['blacklist']);
    }

    
    /**
     * Delete method
     *
     * @param string|null $id Blacklist id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $blacklist = $this->Blacklists->get($id);
        $lista_negra_id = $blacklist->lista_negra_id;
        
        if ($this->Blacklists->delete($blacklist)) {
        	$borrar = $this->Blacklists->deleteBlacklist($lista_negra_id);
        	if($borrar)
            	$this->Flash->success(__('Se ha quitado de la lista negra.'));
        } else {
            $this->Flash->error(__('Error. Intente de nuevo.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    
    
    
}
