<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Model\Beer;
use Application\Model\BeerTableGateway;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $beers = $this->getServiceLocator()
                      ->get('Application\Model\BeerTableGateway')
                      ->fetchAll();
        return new ViewModel(array('beers' => $beers));
    }

    public function createAction()
    {
        $form = $this->getServiceLocator()->get('Application\Form\Beer');
        $form->setAttribute('action', '/insert');
        $form->get('send')->setAttribute('value', 'Salvar');

        return new ViewModel(['beerForm' => $form]);
    }
    public function editarAction()
    {
        $id = $this->params('id');
        $tableGateway = $this->getServiceLocator()->get('Application\Model\BeerTableGateway');
        $model = $tableGateway->get($id);
        $form = $this->getServiceLocator()->get('Application\Form\Beer');
        $form->setAttribute('action', '/insert');
        $form->bind($model);
        $form->get('send')->setAttribute('value', 'Editar');

        return new ViewModel(['beerForm' => $form]);
    }
    public function excluirAction()
    {
        $id = $this->params('id');
        $tableGateway = $this->getServiceLocator()->get('Application\Model\BeerTableGateway');
        $tableGateway->delete($id);
        return $this->redirect()->toUrl('/');        
    }
    public function insertAction()
    {
        $form = $this->getServiceLocator()->get('Application\Form\Beer');
        $form->setAttribute('action', '/insert');
        $tableGateway = $this->getServiceLocator()->get('Application\Model\BeerTableGateway');
        $beer = new \Application\Model\Beer;
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($beer->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                /* pega os dados validados e filtrados */
                $data = $form->getData();
                /* preenche os dados do objeto Post com os dados do formulário*/
                $beer->exchangeArray($data);
                /* salva o novo post*/
                $tableGateway->save($beer);
                /* redireciona para a página inicial que mostra todos os posts*/
                return $this->redirect()->toUrl('/');
            }
        }

        return new ViewModel(['beerForm' => $form]);
    }
}
