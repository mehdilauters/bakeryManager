<?php
App::uses('AppController', 'Controller');
/**
 * Products Controller
 *
 * @property Product $Product
 */
class ProductsController extends AppController {
  var $uses = array('Product');
/**
 * index method
 *
 * @return void
 */
  public function index() {
    $this->set('title_for_layout', 'Produits');
    $this->menu['Menu']['Produits']['active'] = true;
    $this->Product->recursive = 2;
    $this->set('products', $this->Product->find('all'));
  }



/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
  public function view($id = null) {
    if (!$this->Product->exists($id)) {
      throw new NotFoundException(__('Invalid product'));
    }
    
    $options = array('conditions' => array('Product.' . $this->Product->primaryKey => $id), 'recursive'=>2);
    $products = $this->Product->find('first', $options);
  
    $isToday = $this->requestAction(array('controller'=>'events', 'action'=>'isToday'), array( 'pass'=>array('event'=>$products)));
    $isCalendarAvailable = $this->requestAction(array('controller'=>'events', 'action'=>'eventsAvailable'));
    $this->set('isCalendarAvailable', $isCalendarAvailable);
    $this->set('produced', $isToday);
    $this->set('product', $products);
  }

/**
 * add method
 *
 * @return void
 */
  public function add() {
    if ($this->request->is('post')) {
      $this->Product->create();
      debug($this->request->data);
      if($this->request->data['Product']['media_id'] == '')
      {
	unset($this->request->data['Product']['media_id']);
      }
      if ($this->Product->save($this->request->data)) {
        $this->Session->setFlash(__('The product has been saved'));
         $this->redirect(array('action' => 'add'));
      } else {
        $this->Session->setFlash(__('The product could not be saved. Please, try again.'));
      }
    }
    $productTypes = $this->Product->ProductType->find('list');
    $media = $this->Product->Media->find('list');
    $media[''] = '';
    $this->set(compact('productTypes', 'media'));
  }

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
  public function edit($id = null) {
    if (!$this->Product->exists($id)) {
      throw new NotFoundException(__('Invalid product'));
    }
    if ($this->request->is('post') || $this->request->is('put')) {
      if($this->request->data['Product']['media_id'] == '')
      {
	unset($this->request->data['Product']['media_id']);
      }
      if ($this->Product->save($this->request->data)) {
        $this->Session->setFlash(__('The product has been saved'));
        $this->redirect(array('controller'=>'productTypes', 'action' => 'index'));
      } else {
        $this->Session->setFlash(__('The product could not be saved. Please, try again.'));
      }
    } else {
      $options = array('conditions' => array('Product.' . $this->Product->primaryKey => $id));
      $this->request->data = $this->Product->find('first', $options);
    }
    $productTypes = $this->Product->ProductType->find('list');
    $media = $this->Product->Media->find('list');
    $media[''] = '';
    $this->set(compact('productTypes', 'media'));
  }

/**
 * delete method
 *
 * @throws NotFoundException
 * @throws MethodNotAllowedException
 * @param string $id
 * @return void
 */
  public function delete($id = null) {
    $this->Product->id = $id;
    if (!$this->Product->exists()) {
      throw new NotFoundException(__('Invalid product'));
    }
    $this->request->onlyAllow('post', 'delete');
    if ($this->Product->delete()) {
      $this->Session->setFlash(__('Product deleted'));
      $this->redirect(array('action' => 'index'));
    }
    $this->Session->setFlash(__('Product was not deleted'));
    $this->redirect(array('action' => 'index'));
  }
}
