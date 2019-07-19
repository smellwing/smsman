<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Files Controller
 *
 * @property \App\Model\Table\FilesTable $Files
 */
class FilesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $files = $this->paginate($this->Files);

        $this->set(compact('files'));
        $this->set('_serialize', ['files']);
    }

    /**
     * View method
     *
     * @param string|null $id File id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $file = $this->Files->get($id, [
            'contain' => ['Workloads']
        ]);

        $this->set('file', $file);
        $this->set('_serialize', ['file']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($lista_id=null)
    {
        set_time_limit(0);
        $file = $this->Files->newEntity();
        //Primero Guardamos el Archivo y luego su contenido.
        if ($this->request->is('post')) {
        	$datos = $this->request->getData();

        	$fileContents = file_get_contents($datos['filename']['tmp_name']);
        	$fileContents = utf8_encode($fileContents);
        	//$fileContents = mb_strtolower($fileContents);
        	$fileContents = gzcompress($fileContents,9);
        	$archivo = [
        			'name'=>$datos['filename']['name'],
        			'size'=>$datos['filename']['size'],
        			'type'=>$datos['filename']['type'],
        			'data'=>$fileContents
        	];
            $file = $this->Files->patchEntity($file, $archivo);

            $guardar = $this->Files->save($file);

            if ($guardar) {
                //$this->Flash->success(__('The file has been saved.'));
                //Redirigimos el tráfico a WORKLOAD para cargar el archivo.
                return $this->redirect(['controller'=>'workloads','action' => 'add',$guardar->id,$lista_id]);
            }
            $this->Flash->error(__('Error. Intente d enuevo.'));
        }
        $this->set(compact('file','lista_id'));
        $this->set('_serialize', ['file']);
    }


    /**
     * Delete method
     *
     * @param string|null $id File id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $file = $this->Files->get($id);
        if ($this->Files->delete($file)) {
            $this->Flash->success(__('The file has been deleted.'));
        } else {
            $this->Flash->error(__('Error. Intente d enuevo.'));
        }

        return $this->redirect(['action' => 'index']);
    }
    
    public function descargar($id) {
    	if($id==1)
    		$path = DS.'Template/Files/ejemplo.csv';    	
    	$this->response->file($path, array(
    			'download' => true,
    			'name' => 'ejemplo.csv',
    	));
    	return $this->response;
    }
}
