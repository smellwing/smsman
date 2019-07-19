<?php
namespace App\Controller\Backend;

use App\Controller\AppController;

/**
 * Workloads Controller
 *
 * @property \App\Model\Table\WorkloadsTable $Workloads
 */
class WorkloadsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Files']
        ];
        $workloads = $this->paginate($this->Workloads);

        $this->set(compact('workloads'));
        $this->set('_serialize', ['workloads']);
    }

    /**
     * View method
     *
     * @param string|null $id Workload id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $workload = $this->Workloads->get($id, [
            'contain' => ['Files']
        ]);

        $this->set('workload', $workload);
        $this->set('_serialize', ['workload']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $workload = $this->Workloads->newEntity();
        if ($this->request->is('post')) {
            $workload = $this->Workloads->patchEntity($workload, $this->request->getData());
            if ($this->Workloads->save($workload)) {
                $this->Flash->success(__('The workload has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The workload could not be saved. Please, try again.'));
        }
        $files = $this->Workloads->Files->find('list', ['limit' => 200]);
        $this->set(compact('workload', 'files'));
        $this->set('_serialize', ['workload']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Workload id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $workload = $this->Workloads->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $workload = $this->Workloads->patchEntity($workload, $this->request->getData());
            if ($this->Workloads->save($workload)) {
                $this->Flash->success(__('The workload has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The workload could not be saved. Please, try again.'));
        }
        $files = $this->Workloads->Files->find('list', ['limit' => 200]);
        $this->set(compact('workload', 'files'));
        $this->set('_serialize', ['workload']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Workload id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $workload = $this->Workloads->get($id);
        if ($this->Workloads->delete($workload)) {
            $this->Flash->success(__('The workload has been deleted.'));
        } else {
            $this->Flash->error(__('The workload could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
