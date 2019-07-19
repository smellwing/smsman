<?php
namespace App\Controller;

use App\Controller\AppController;
//use Cake\Mailer\Email;

/**
 * Listas Controller
 *
 * @property \App\Model\Table\ListasTable $Listas
 */
class ListasController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
    	
        #$rol=$this->Auth->user();
        #debug($rol);
        $conditions = null;
    	if(!$this->isAdmin)
    		$conditions = ['Listas.client_id IN'=>$this->clienteId];
        $this->paginate = [
            'contain' => ['Users', 'Clients','Workloads','Tasks'],
        	'order'		=>['Listas.created'=>'DESC'],
        	'conditions' => $conditions 
        ];
        
        /*
        $email = new Email();
        $email->setFrom(['bi@intelet.cl' => 'SMS Front'])
        ->setTo('juan.valenzuela@intelet.cl')
        ->setSubject('About')
        ->send('My message');
        */
        
        $user = $this->Auth->user();
        $this->loadModel('Roles');
        $role = $this->Roles->get($user['role_id']);
        
        $cuota_diaria = null;
        $cuota_listas = null;
        $tasks_ids = [];
        if(($user['cuota']!=0)||($role->name!='Administrador')){
            $qry_listas = $this->Listas->find();
            $qry_listas->select(['id']);            
            $qry_listas->where(['Listas.user_id'=>$user['id'],'Listas.created > CURDATE()']);
            
            if($qry_listas->count() >0){
                foreach ($qry_listas as $lista){
                    
                    $listas_ids[]=$lista->id;
                };
                
                $qry_wls = $this->Listas->Workloads->find();
                $qry_wls->select(['count' => $qry_wls->func()->count('*')]);
                if(!empty($listas_ids))
                    $qry_wls->where(['Workloads.lista_id IN'=>$listas_ids,'DATE(Workloads.created) LIKE DATE(NOW())']);
                    else $qry_wls->where(['DATE(Workloads.created) LIKE DATE(NOW())']);
                $wls = $qry_wls->first();                
                $cuota_diaria = $user['cuota'] - $wls->count;               
            if($cuota_diaria<0) $cuota_diaria = 0;
            if(is_null($cuota_diaria)) $cuota_diaria = 0;
        }}
        $this->loadModel('Tips');
        $tip_qry = $this->Tips->find();
        $tip_qry->select(['id','tip']);        
        $tips = $tip_qry->toArray();
        
        if ($tip_qry->count()) {            
            $tip = $tips[rand(0, $tip_qry->count()-1)]['tip'];
        }
        $listas = $this->paginate($this->Listas);        
        $isAdmin = $this->isAdmin;
        $this->set(compact('listas', 'cuota_diaria', 'tip', 'isAdmin'));
        $this->set('_serialize', ['listas']);    
        
        
    }

    /**
     * View method
     *
     * @param string|null $id Lista id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $lista = $this->Listas->get($id, [
            'contain' => ['Users', 'Clients', 'Tasks', 'Workloads']
        ]);

        $this->set('lista', $lista);
        $this->set('_serialize', ['lista']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $lista = $this->Listas->newEntity();
        if ($this->request->is('post')) {
            $lista = $this->Listas->patchEntity($lista, $this->request->getData());
            if ($this->Listas->save($lista)) {
                $this->Flash->success(__('Su lista fue creada.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Error, intente de nuevo.'));
        }
        $conditions_client=null;
        $conditions_users=null;
        if(!$this->isAdmin){
        	$conditions_client = ['Clients.id IN'=>$this->clienteId];
        	$conditions_users = ['Users.id'=>$this->Auth->user('id')];
        }        
        $clients = $this->Listas->Clients->find('list', [
        			'conditions'=>$conditions_client ,
        			'limit' => 200 
        ]);
        
        $users = $this->Listas->Users->find('list', [
        		'limit' => 200,        		
        		'conditions'=>$conditions_users
        ]);
        
        $this->set(compact('lista', 'users', 'clients'));
        $this->set('_serialize', ['lista']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Lista id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $lista = $this->Listas->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $lista = $this->Listas->patchEntity($lista, $this->request->getData());
            if ($this->Listas->save($lista)) {
                $this->Flash->success(__('Los cambios fueron guardados.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Error, intente de nuevo.'));
        }
        $conditions_client=null;
        $conditions_users=null;
        
        if(!$this->isAdmin){
        	$conditions_client = ['Clients.id IN'=>$this->clienteId];
        	$conditions_users = ['Users.id'=>$this->Auth->user('id')];
        }
        $clients = $this->Listas->Clients->find('list', [
        		'conditions'=>$conditions_client ,
        		'limit' => 200
        ]);
        
        $users = $this->Listas->Users->find('list', [
        		'limit' => 200,
        		'conditions'=>$conditions_users
        ]);
        
        $this->set(compact('lista', 'users', 'clients'));
        $this->set('_serialize', ['lista']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Lista id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $lista = $this->Listas->get($id);
        if( ($lista->user_id==$this->Auth->user('id'))||($this->isAdmin) ){
        if ($this->Listas->delete($lista)) {
            $this->Flash->success(__('La lista se ha borrado.'));
        } else {
            $this->Flash->error(__('Error, intente de nuevo.'));
        }} else $this->Flash->error(__('Error. Sin permisos para esta acci�n.'));

        return $this->redirect(['action' => 'index']);
    }
    
}
