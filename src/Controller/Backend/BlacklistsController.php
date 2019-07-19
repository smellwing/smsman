<?php
namespace App\Controller\Backend;

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
        $this->paginate = [
            'contain' => ['Clients']
        ];
        $blacklists = $this->paginate($this->Blacklists);

        $this->set(compact('blacklists'));
        $this->set('_serialize', ['blacklists']);
    }

    /**
     * View method
     *
     * @param string|null $id Blacklist id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $blacklist = $this->Blacklists->get($id, [
            'contain' => ['Clients']
        ]);

        $this->set('blacklist', $blacklist);
        $this->set('_serialize', ['blacklist']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
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
    		//Ahor si validamos para guardar acá
    		$blacklist = $this->Blacklists->patchEntity($blacklist, $data);
    		if ($this->Blacklists->save($blacklist)) {
    			$this->Flash->success(__('Se ha guardado el dato.'));
    
    			return $this->redirect(['action' => 'index']);
    		}
    		$this->Flash->error(__('The blacklist could not be saved. Please, try again.'));
    	}
    	$clients = $this->Blacklists->Clients->find('list', ['limit' => 200]);
    
    	$this->set(compact('blacklist', 'clients', 'listaNegras'));
    	$this->set('_serialize', ['blacklist']);
    }
    
    /**
     * Edit method
     *
     * @param string|null $id Blacklist id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $blacklist = $this->Blacklists->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $blacklist = $this->Blacklists->patchEntity($blacklist, $this->request->getData());
            if ($this->Blacklists->save($blacklist)) {
                $this->Flash->success(__('The blacklist has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The blacklist could not be saved. Please, try again.'));
        }
        $clients = $this->Blacklists->Clients->find('list', ['limit' => 200]);
        $this->set(compact('blacklist', 'clients'));
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
        if ($this->Blacklists->delete($blacklist)) {
            $this->Flash->success(__('The blacklist has been deleted.'));
        } else {
            $this->Flash->error(__('The blacklist could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    

    public function sync_blacklists(){
    	//Rescatamos todas las listas negras de PhoneSMS
    	$listasNegras = $this->Blacklists->getListasNegrasPhoneSMS();
    	$insertados=0; $existentes=0;
    	foreach ($listasNegras as $row){
    		if($row['created']=='0000-00-00 00:00:00'||is_null($row['created'])) $row['created']=date('Y-m-d H:i:s');
    		if($row['modified']=='0000-00-00 00:00:00'||is_null($row['modified'])) $row['modified']=date('Y-m-d H:i:s');
    		$query_blacklisted = $this->Blacklists->find();
    		$query_blacklisted->select(['lista_negra_id']);
    		$query_blacklisted->where(['lista_negra_id'=>$row['lista_negra_id']]);
    		if(!is_null($row['phone']))
    			$query_blacklisted->orWhere(['phone'=>$row['phone']]);
    			if(!is_null($row['dni']))
    				$query_blacklisted->orWhere(['dni'=>$row['dni']]);
    
    
    				if(is_null($query_blacklisted->first())){
    					 
    					$query_client = $this->Blacklists->Clients->find();
    					$query_client->select(['id']);
    					$query_client->where(['yx_login_id'=>$row['yx_login_id']]);
    					 
    					 
    					$client_id = $query_client->first()->id;
    					$blacklist = $this->Blacklists->newEntity();
    					$row['client_id']=$client_id;
    					
    					foreach ($row as $key=>$content){
    						
    						if(!is_numeric($key)) $data[$key] = $content;
    					}
    					unset($data['yx_login_id']);
    					unset($data['codcli']);
    					 
    					$blacklist = $this->Blacklists->patchEntity($blacklist, $data);
    					 
    					$save = $this->Blacklists->save($blacklist);
    					if($save) $insertados++;
    					else $existentes++;
    				} else $existentes++;
    	}
    	$this->set(compact('insertados', 'existentes'));
    }
    
}
