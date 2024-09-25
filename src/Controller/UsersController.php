<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Response;

/**
 * Users Controller.
 *
 * @property \App\Model\Table\UsersTable                                  $Users
 * @property \Authentication\Controller\Component\AuthenticationComponent $Authentication
 */
class UsersController extends AppController
{
    /**
     * Initialize controller.
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->Authentication->allowUnauthenticated(['login', 'add']);
    }

    /**
     * Index method.
     *
     * @return Response|void|null Renders view
     */
    public function index()
    {
        $query = $this->Users->find();
        $users = $this->paginate($query);
        $this->set(\compact('users'));
    }

    /**
     * View method.
     *
     * @param string|null $id user id
     *
     * @throws \Cake\Datasource\Exception\RecordNotFoundException when record not found
     *
     * @return Response|void|null Renders view
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, contain: []);
        $this->set(\compact('user'));
    }

    /**
     * Add method.
     *
     * @return Response|void|null
     */
    public function add()
    {
        $user = $this->Users->newEmptyEntity();

        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());

            if ($this->Users->save($user)) {
                $this->Flash->success(__('Registrácia bola úspešná.'));

                return $this->redirect(['action' => 'login']);
            }
            $this->Flash->error(__('Nepodarilo sa uložiť používateľa. Skúste znova.'));
        }
        $this->set(\compact('user'));
    }

    /**
     * Edit method.
     *
     * @param string|null $id user id
     *
     * @throws \Cake\Datasource\Exception\RecordNotFoundException when record not found
     *
     * @return Response|void|null redirects on successful edit, renders view otherwise
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, contain: []);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());

            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(\compact('user'));
    }

    /**
     * Delete method.
     *
     * @param string|null $id user id
     *
     * @throws \Cake\Datasource\Exception\RecordNotFoundException when record not found
     *
     * @return Response|null redirects to index
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);

        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Login method.
     */
    public function login(): ?Response
    {
        $result = $this->Authentication->getResult();

        if ($result !== null && $result->isValid()) {
            return $this->redirect($this->Authentication->getLoginRedirect() ?? '/');
        }

        if ($this->request->is('post') && $result !== null && !$result->isValid()) {
            $this->Flash->error('Nesprávne prihlasovacie údaje.');
        }

        return null;
    }

    /**
     * Logout method.
     */
    public function logout(): ?Response
    {
        $this->Authentication->logout();

        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
    }
}
