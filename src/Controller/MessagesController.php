<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Messages Controller
 *
 * @property \App\Model\Table\MessagesTable $Messages
 */
class MessagesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index($lista_id=null)
    {
    	$conditions=null;
    	if(!$this->isAdmin)
    		$conditions = [
    		    'Messages.client_id IN'=>$this->clienteId,
    		    'Messages.hidden'=>0    		    
    		];
    	else	 
    	    $conditions = [    	      
    	        'Messages.hidden'=>0
    	    ];
        $this->paginate = [
            'contain' => ['Clients'],
        	'conditions'=>$conditions 
        ];
        $messages = $this->paginate($this->Messages);

        $this->set(compact('messages','lista_id'));
        $this->set('_serialize', ['messages']);
    }

    /**
     * View method
     *
     * @param string|null $id Message id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null,$lista_id=null)
    {
        $message = $this->Messages->get($id, [
            'contain' => ['Clients']
        ]);

        $this->set('message', $message);
        $this->set('lista_id', $lista_id);
        $this->set('_serialize', ['message']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($lista_id=null)
    {
    	
    	$conditions = null;
        $message = $this->Messages->newEntity();
        if ($this->request->is('post')) {
            $message = $this->Messages->patchEntity($message, $this->request->getData());
            if ($this->Messages->save($message)) {
                $this->Flash->success(__('El mensaje se ha guardado.'));

                return $this->redirect(['action' => 'index',$lista_id]);
            }
            $this->Flash->error(__('Error. Intente de nuevo.'));
        }
        if(!$this->isAdmin)
        	$conditions = ['Clients.id IN'=>$this->clienteId];
        	 
        $clients = $this->Messages->Clients->find('list', [
        		'conditions'=>$conditions,
        		/*'limit' => 200*/]);
        $this->set(compact('message', 'clients','lista_id'));
        $this->set('_serialize', ['message']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Message id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null, $lista_id = null)
    {
    	$conditions=null;
        $message = $this->Messages->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $message = $this->Messages->patchEntity($message, $this->request->getData());
            if ($this->Messages->save($message)) {
                $this->Flash->success(__('El mensaje se ha guardado.'));

                return $this->redirect(['action' => 'index',$lista_id]);
            }
            $this->Flash->error(__('Error. Intente de nuevo.'));
        }
        if(!$this->isAdmin)
        	$conditions = ['Clients.id IN'=>$this->clienteId];
        
        $clients = $this->Messages->Clients->find('list', [
        			'conditions'=>$conditions,
        			'limit' => 200]);
        
        $this->set(compact('message', 'clients','lista_id'));
        $this->set('_serialize', ['message']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Message id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null,$lista_id=null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $message = $this->Messages->get($id);
        if ($this->Messages->delete($message)) {
            $this->Flash->success(__('El mensaje se ha eliminado.'));
        } else {
            $this->Flash->error(__('Error. Intente de nuevo.'));
        }

        return $this->redirect(['action' => 'index',$lista_id]);
    }
}
