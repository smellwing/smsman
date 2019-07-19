<?php
namespace App\Controller\Backend;

use App\Controller\AppController;

/**
 * Sentlogs Controller
 *
 * @property \App\Model\Table\SentlogsTable $Sentlogs
 */
class SentlogsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Workloads']
        ];
        $sentlogs = $this->paginate($this->Sentlogs);

        $this->set(compact('sentlogs'));
        $this->set('_serialize', ['sentlogs']);
    }

    /**
     * View method
     *
     * @param string|null $id Sentlog id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $sentlog = $this->Sentlogs->get($id, [
            'contain' => ['Workloads']
        ]);

        $this->set('sentlog', $sentlog);
        $this->set('_serialize', ['sentlog']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $sentlog = $this->Sentlogs->newEntity();
        if ($this->request->is('post')) {
            $sentlog = $this->Sentlogs->patchEntity($sentlog, $this->request->getData());
            if ($this->Sentlogs->save($sentlog)) {
                $this->Flash->success(__('The sentlog has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sentlog could not be saved. Please, try again.'));
        }
        $workloads = $this->Sentlogs->Workloads->find('list', ['limit' => 200]);
        $this->set(compact('sentlog', 'workloads'));
        $this->set('_serialize', ['sentlog']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Sentlog id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sentlog = $this->Sentlogs->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $sentlog = $this->Sentlogs->patchEntity($sentlog, $this->request->getData());
            if ($this->Sentlogs->save($sentlog)) {
                $this->Flash->success(__('The sentlog has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The sentlog could not be saved. Please, try again.'));
        }
        $workloads = $this->Sentlogs->Workloads->find('list', ['limit' => 200]);
        $this->set(compact('sentlog', 'workloads'));
        $this->set('_serialize', ['sentlog']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Sentlog id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $sentlog = $this->Sentlogs->get($id);
        if ($this->Sentlogs->delete($sentlog)) {
            $this->Flash->success(__('The sentlog has been deleted.'));
        } else {
            $this->Flash->error(__('The sentlog could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
