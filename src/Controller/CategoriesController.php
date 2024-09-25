<?php

declare(strict_types=1);

namespace App\Controller;

/**
 * Categories Controller.
 *
 * @property \App\Model\Table\CategoriesTable $Categories
 */
class CategoriesController extends AppController
{
    /**
     * Index method.
     *
     * @return \Cake\Http\Response|void|null Renders view
     */
    public function index()
    {
        $query = $this->Categories->find();
        $categories = $this->paginate($query);

        $this->set(\compact('categories'));
    }

    /**
     * View method.
     *
     * @param string|null $id category id
     *
     * @throws \Cake\Datasource\Exception\RecordNotFoundException when record not found
     *
     * @return \Cake\Http\Response|void|null Renders view
     */
    public function view($id = null)
    {
        $category = $this->Categories->get($id, contain: ['Products']);
        $this->set(\compact('category'));
    }

    /**
     * Add method.
     *
     * @return \Cake\Http\Response|void|null redirects on successful add, renders view otherwise
     */
    public function add()
    {
        $category = $this->Categories->newEmptyEntity();

        if ($this->request->is('post')) {
            $category = $this->Categories->patchEntity($category, $this->request->getData());

            if ($this->Categories->save($category)) {
                $this->Flash->success(__('The category has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The category could not be saved. Please, try again.'));
        }
        $products = $this->Categories->Products->find('list', limit: 200)->all();
        $this->set(\compact('category', 'products'));
    }

    /**
     * Edit method.
     *
     * @param string|null $id category id
     *
     * @throws \Cake\Datasource\Exception\RecordNotFoundException when record not found
     *
     * @return \Cake\Http\Response|void|null redirects on successful edit, renders view otherwise
     */
    public function edit($id = null)
    {
        $category = $this->Categories->get($id, contain: ['Products']);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $category = $this->Categories->patchEntity($category, $this->request->getData());

            if ($this->Categories->save($category)) {
                $this->Flash->success(__('The category has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The category could not be saved. Please, try again.'));
        }
        $products = $this->Categories->Products->find('list', limit: 200)->all();
        $this->set(\compact('category', 'products'));
    }

    /**
     * Delete method.
     *
     * @param string|null $id category id
     *
     * @throws \Cake\Datasource\Exception\RecordNotFoundException when record not found
     *
     * @return \Cake\Http\Response|null redirects to index
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $category = $this->Categories->get($id);

        if ($this->Categories->delete($category)) {
            $this->Flash->success(__('The category has been deleted.'));
        } else {
            $this->Flash->error(__('The category could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
