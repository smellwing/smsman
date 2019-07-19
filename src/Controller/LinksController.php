<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use GoogleUrlApi\GoogleUrlApi;
use Cake\Network\Exception\NotFoundException;

/**
 * Links Controller
 *
 * @property \App\Model\Table\LinksTable $Links
 */
class LinksController extends AppController
{

	
	//PERMISOS
	public function beforeFilter(Event $event)
	{
		parent::beforeFilter($event);
		// Allow users to register and logout.
		// You should not add the "login" action to allow list. Doing so would
		// cause problems with normal functioning of AuthComponent.
		$this->Auth->allow(['go']);
	}
	
    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index($id=null)
    {
        if($id!=null)
        	$links = $this->paginate($this->Links, 
        			[
        				'conditions'=>['Links.id'=>$id],	
        				'contain'=>'linksIps'
        					
        			]);
    	else 
        	$links = $this->paginate($this->Links, ['contain'=>'linksIps']);
        
        
        $uri = $this->request->getUri()->getScheme()
        		.'://'.$this->request->getUri()->getHost()
        		.$this->request->getUri()->base
        		.$this->request->getUri()->getPath().'/go/';
        
        $this->set(compact('links'));
        $this->set(compact('uri'));
        $this->set('_serialize', ['links']);
    }

    /**
     * View method
     *
     * @param string|null $id Link id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($hash = null)
    {
        $link = $this->Links->findByHash($hash, [
            'contain' => []
        ])->first();
		
		echo "redirecting to $link->url";
		//return $this->redirect($link->url);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
    	
    	//Carga la clase googl url api
    	//require_once(ROOT .DS. "vendor/DavidWalsh" . DS  . "GoogleUrlApi" . DS . "GoogleUrlApi.php");
    	require_once(ROOT .DS. "vendor" . DS  . "GoogleUrlApi" . DS . "GoogleUrlApi.php");    	
    	//
    	$googer = new GoogleURLAPI();
    	//Por alguna razón, el constructor no anda en Cake
    	$this->loadModel('Workloads');
    	$googer->GoogleURLAPI($this->Workloads->key);
    	
    	
        $link = $this->Links->newEntity();
        if ($this->request->is('post')) {
        	$data = $this->request->getData();
        	$data['hash'] = hash('crc32',$data['url']);
        	$uri = $this->request->getUri()->getScheme()
        	.'://'.$this->request->getUri()->getHost()
        	.$this->request->getUri()->base
        	.$this->request->getUri()->getPath().'/go/'.$data['hash'];
        	
        	$data['url_google'] =  $googer->shorten($uri);
            $link = $this->Links->patchEntity($link, $data);
            if ($this->Links->save($link)) {
                $this->Flash->success(__('The link has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The link could not be saved. Please, try again.'));
        }
        $this->set(compact('link'));
        $this->set('_serialize', ['link']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Link id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $link = $this->Links->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $link = $this->Links->patchEntity($link, $this->request->getData());
            if ($this->Links->save($link)) {
                $this->Flash->success(__('The link has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The link could not be saved. Please, try again.'));
        }
        $this->set(compact('link'));
        $this->set('_serialize', ['link']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Link id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $link = $this->Links->get($id);
        if ($this->Links->delete($link)) {
            $this->Flash->success(__('The link has been deleted.'));
        } else {
            $this->Flash->error(__('The link could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
 
    }
    
    public function go($hash){
    	$link = $this->Links->findByHash($hash, [
    			'contain' => []
    	])->first();
    	if($link==null ) 
    	{
    		throw new NotFoundException(__('Link ya no existe'));   		
    		
    		return null;
    	}
    	date_default_timezone_set('America/Santiago');
    	
    	$datetime = date('Y-m-d H:i:s', time());
    	$link->visited = $datetime;
    	$link->visits++;
    	$linksIp = $this->Links->LinksIps->newEntity();
    	
    	$data['ip'] = $this->request->clientIp();
    	$data['link_id']=$link->id;    	
    	$data['http_user_agent']=$this->request->env('HTTP_USER_AGENT');
    	
    	$linksIp = $this->Links->LinksIps->patchEntity($linksIp, $data);
    	
    	$linksIp = $this->Links->LinksIps->save($linksIp);
    	
    	$link = $this->Links->save($link);

    	
//    	echo "redirecting to $link->url";
    	return $this->redirect($link->url);
    }
}
