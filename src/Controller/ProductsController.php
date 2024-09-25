<?php

declare(strict_types=1);

namespace App\Controller;

use Laminas\Diactoros\UploadedFile;

/**
 * Products Controller.
 *
 * @property \App\Model\Table\ProductsTable $Products
 */
class ProductsController extends AppController
{
    /**
     * Index method.
     *
     * @return \Cake\Http\Response|void|null Renders view
     */
    public function index()
    {
        $query = $this->Products->find();
        $products = $this->paginate($query);

        $this->set(\compact('products'));
    }

    /**
     * View method.
     *
     * @param string|null $id product id
     *
     * @throws \Cake\Datasource\Exception\RecordNotFoundException when record not found
     *
     * @return \Cake\Http\Response|void|null Renders view
     */
    public function view($id = null)
    {
        $product = $this->Products->get($id, contain: ['Categories']);
        $this->set(\compact('product'));
    }

    /**
     * Add method.
     *
     * @return \Cake\Http\Response|void|null redirects on successful add, renders view otherwise
     */
    public function add()
    {
        $product = $this->Products->newEmptyEntity();

        if ($this->request->is('post')) {
            $product = $this->Products->patchEntity($product, $this->request->getData());
            $imageFile = $this->request->getData('image');

            if ($imageFile instanceof UploadedFile && $imageFile->getError() === UPLOAD_ERR_OK && !empty($fileName = $imageFile->getClientFilename())) {
                $targetPath = '/img' . DS . 'products' . DS . \basename($fileName);
                $imageFile->moveTo(WWW_ROOT . $targetPath);
                $product->image = $targetPath;
            }

            if ($this->Products->save($product)) {
                $this->Flash->success(__('The product has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The product could not be saved. Please, try again.'));
        }
        $categories = $this->Products->Categories->find('list', limit: 200)->all();
        $this->set(\compact('product', 'categories'));
    }

    /**
     * Edit method.
     *
     * @param string|null $id product id
     *
     * @throws \Cake\Datasource\Exception\RecordNotFoundException when record not found
     *
     * @return \Cake\Http\Response|void|null redirects on successful edit, renders view otherwise
     */
    public function edit($id = null)
    {
        $product = $this->Products->get($id, contain: ['Categories']);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $imageFile = $this->request->getData('image');

            if ($imageFile instanceof UploadedFile && $imageFile->getError() === UPLOAD_ERR_OK && !empty($fileName = $imageFile->getClientFilename())) {
                $targetPath = '/img' . DS . 'products' . DS . \basename($fileName);
                $imageFile->moveTo(WWW_ROOT . $targetPath);
                $product->image = $targetPath;
            }

            if ($this->Products->save($product)) {
                return $this->redirect(['action' => 'index']);
            }
        }
        $product = $this->Products->patchEntity($product, $this->request->getData());
        $categories = $this->Products->Categories->find('list', limit: 200)->all();
        $this->set(\compact('product', 'categories'));
    }

    /**
     * Delete method.
     *
     * @param string|null $id product id
     *
     * @throws \Cake\Datasource\Exception\RecordNotFoundException when record not found
     *
     * @return \Cake\Http\Response|null redirects to index
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $product = $this->Products->get($id);

        if ($this->Products->delete($product)) {
            $this->Flash->success(__('The product has been deleted.'));
        } else {
            $this->Flash->error(__('The product could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
