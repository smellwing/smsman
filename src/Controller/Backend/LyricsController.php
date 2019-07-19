<?php
namespace App\Controller\Backend;

use App\Controller\AppController;

/**
 * Lyrics Controller
 *
 * @property \App\Model\Table\LyricsTable $Lyrics
 *
 * @method \App\Model\Entity\Lyric[] paginate($object = null, array $settings = [])
 */
class LyricsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index($ip=null)
    {
        $lyrics = $this->Lyrics->find();
        $lyrics_ips_qry = $this->Lyrics->find();
        $lyrics_ips_qry->select(['Lyrics.ip']);
        $lyrics_ips_qry->group(['Lyrics.ip']);
        $lyrics_ips_qry->order(['Lyrics.ip']);
        
        if(!is_null($ip)) $lyrics->where(['Lyrics.ip'=>$ip]);
        $lyrics_ips[0]="ELEGIR";
        foreach($lyrics_ips_qry as $lip) $lyrics_ips[$lip->ip]=$lip->ip;
        
        $this->set(compact('lyrics','lyrics_ips','ip'));
       // $this->set('_serialize', ['lyrics']);
    }

    public function lyrics_group_index($ip){
        $this->viewBuilder()->setLayout('ajax');
        //$this->autoRender = false;
        
        $lyrics = $this->Lyrics->find();
        ;
        $lyrics->where(['Lyrics.ip'=>$ip]);
        //echo json_encode($lyrics->toArray());
        $this->set(compact('lyrics'));
    }
    
    
    /**
     * View method
     *
     * @param string|null $id Lyric id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null,$ip=null)
    {
        $lyric = $this->Lyrics->get($id, [
            'contain' => ['Sentlogs']
        ]);

        $this->set('lyric', $lyric);
        $this->set('ip', $ip);
        $this->set('_serialize', ['lyric']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add($ip=null)
    {
        $lyric = $this->Lyrics->newEntity();
        if ($this->request->is('post')) {
            $lyric = $this->Lyrics->patchEntity($lyric, $this->request->getData());
            if ($this->Lyrics->save($lyric)) {
                $this->Flash->success(__('The lyric has been saved.'));

                return $this->redirect(['action' => 'index',$ip]);
            }
            $this->Flash->error(__('The lyric could not be saved. Please, try again.'));
        }
        $this->set(compact('lyric','ip'));
        $this->set('_serialize', ['lyric']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Lyric id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null,$ip=null)
    {
        $lyric = $this->Lyrics->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $lyric = $this->Lyrics->patchEntity($lyric, $this->request->getData());
            if ($this->Lyrics->save($lyric)) {
                $this->Flash->success(__('The lyric has been saved.'));

                return $this->redirect(['action' => 'index',$ip]);
            }
            $this->Flash->error(__('The lyric could not be saved. Please, try again.'));
        }
        $this->set(compact('lyric','ip'));
        $this->set('_serialize', ['lyric']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Lyric id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null,$ip=null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $lyric = $this->Lyrics->get($id);
        if ($this->Lyrics->delete($lyric)) {
            $this->Flash->success(__('The lyric has been deleted.'));
        } else {
            $this->Flash->error(__('The lyric could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index',$ip]);
    }
    
    public function active($id,$ip=null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $lyric = $this->Lyrics->get($id);
        $lyric->active = !($lyric->active);
        if ($this->Lyrics->save($lyric)) {
            $this->Flash->success(($lyric->active)?'Canal activado':'Canal deshabilitado');
        } else {
            $this->Flash->error(__('Ocurrio un error.'));
        }
        
        return $this->redirect(['action' => 'index',$ip]);
    }
}
