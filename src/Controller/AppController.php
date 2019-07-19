<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{
	public $clienteId = array();
	public $isAdmin = false;

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');
        $this->loadComponent('Auth', [
                            'authorize' => ['Controller'],
                            'loginRedirect' => [
                            	'controller' => 'Listas',
                            	'action' => 'index'
                            ],
                            'logoutRedirect' => [
                            	'controller' => 'Pages',
                            	'action' => 'display',
                            	'home'  
        		],
                #'authorize' => array('Controller')
                //'authorize' => 'Controller',
        ]);

        /*
         * Enable the following components for recommended CakePHP security settings.
         * see http://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
        //$this->loadComponent('Csrf');
                
        //$this->clienteId = $this->Auth->user('client_id');
        $this->loadModel('Users'); 
        if (!is_null($this->Auth->user())) {
            $user = $this->Users->get($this->Auth->user('id'), ['contain'=>['Clients']]); 
            foreach ($user->clients as $client){
                $this->clienteId[$client['id']] = $client['id'];//$client['name'];
            }
            
            $this->isAdmin = ($this->Auth->user('role_id')==1);
        }
    }

    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return \Cake\Network\Response|null|void
     */
    public function beforeRender(Event $event)
    {
        if (!array_key_exists('_serialize', $this->viewVars) &&
            in_array($this->response->type(), ['application/json', 'application/xml'])
        ) {
            $this->set('_serialize', true);
        }
    }
    
    /**
     * Permite detectar un delimitador entre:
     *  ; , \t |
     * @param string $csvContentLine
     * @return string
     */
    public function getCSVDelimiter($csvContentLine){
    	$delimiters = array(
    			';' => 0,
    			/*',' => 0,*/
    			"\t" => 0,
    			"|" => 0
    	);
    	foreach ($delimiters as $delimiter => &$count) {
    		$count = count(str_getcsv($csvContentLine, $delimiter));
    	}
    	 
    	return(array_search(max($delimiters), $delimiters));    	     	
    }
    
    public function getRUT($dni){
    	$dni = preg_replace('/[^k0-9]/i', '', $dni);    	
    	return (object)['num'=>substr($dni, 0, strlen($dni)-1), 'dv'=>substr($dni, strlen($dni)-1)];
    }
    
    /**
     * Comprueba un RUT.
     * Adaptado de https://gist.github.com/rbarrigav/3881019
     * @param unknown $dni
     * @return boolean
     */
    public function isRUT($dni){
    	
    	$dni = $this->getRUT($dni);
    	$i = 2;
    	$suma = 0;
    	
    	if(!$dni->num||is_nan($dni->num)) return false;
    	foreach(array_reverse(str_split($dni->num)) as $v)
    	{
    		if($i==8)
    			$i = 2;
    			$suma += $v * $i;
    			++$i;
    	}
    	$dvr = 11 - ($suma % 11);
    	
    	if($dvr == 11)
    		$dvr = 0;
    		if($dvr == 10)
    			$dvr = 'K';
    			if($dvr == strtoupper($dni->dv))
    				return true;
    				else
    					return false;
    }

   /* public function isAuthorized($user) {
            // Admin can access every action
            if (isset($user['type']) && $user['type'] === 'admin') {
                return true;
            }
            // Default deny
            return false;
    }*/

    /*public function beforeFilter(Event $event)
    {
        #debug($this->isAdmin);
        parent::beforeFilter($event);
        $this->Auth->allow('index','view');
    }*/

    public function isAuthorized($user)
    {
    	//Cargamos la talba de permisos
		$this->Permisos = TableRegistry::get('Permissions');
		//Buscamos el permiso
		$qPermisos = $this->Permisos->find();
		$qPermisos->select(['id']);
		$qPermisos->where(['Permissions.controller'=>$this->request->getParam('controller')]);
		$qPermisos->where(['Permissions.action'=>$this->request->getParam('action')]);
		$permiso = (!is_null($qPermisos->first()));
		// Admin can access every action
    	if (isset($user['role_id']) && $user['role_id'] === 1) {
    		return true;
   		}
   		if($permiso) return true;
    
    	// Default deny
        $this->Flash->set('Usted no tiene acceso.', [
            'element' => 'error'
        ]);

        return $this->redirect('/listas');
    	#return false;
    }

}
