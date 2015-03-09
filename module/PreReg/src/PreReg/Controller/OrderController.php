<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace PreReg\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use PreReg\Form;
use PreReg\InputFilter;
use PreReg\Service;
use Zend\Session\Container;
use ersEntity\Entity;

# for pdf generation
use DOMPDFModule\View\Model\PdfModel;

# for sending emails
use Zend\Mail\Message;
use Zend\Mail\Transport;
use Zend\Mime;

class OrderController extends AbstractActionController {
 
    /*
     * overview of this order
     */
    public function indexAction() {
        $forrest = new Service\BreadcrumbFactory();
        $forrest->reset();
        $forrest->set('product', 'order');
        $forrest->set('participant', 'order');
        $forrest->set('cart', 'order');
        
        $cartContainer = new Container('cart');
        
        return new ViewModel(array(
            'order' => $cartContainer->order,
        ));
    }
    public function overviewAction() {
        $view = $this->indexAction();
        $view->setTemplate('pre-reg/order/index.phtml');
        return $view;
    }
    
    /*
     * collect data for the purchaser
     */
    public function purchaserAction() {
        $form = new Form\Register();
        
        $cartContainer = new Container('cart');
        
        # even if it's not displayed, this is needed to recognize the possible 
        # values.
        if(count($cartContainer->order->getPackages()) > 1) {
            $users = $cartContainer->order->getParticipants();
            $purchaser = array();
            foreach($users as $participant) {
                $purchaser[] = array(
                    'value' => $participant->getSessionId(),
                    'label' => $participant->getFirstname().' '.$participant->getSurname().' ('.$participant->getEmail().')',
                );
            }
            $purchaser[] = array(
                'value' => 0,
                'label' => 'Add purchaser',
            );
            $form->get('purchaser_id')->setValueOptions($purchaser);
        }
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $inputFilter = new InputFilter\Register();
            $form->setInputFilter($inputFilter->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $data = $form->getData();
                
                foreach($cartContainer->order->getParticipants() as $participant) {
                    error_log($participant->getFirstname().' '.$participant->getSurname().' ('.$participant->getSessionId().')');
                }
                error_log('purchaser id: '.$data['purchaser_id']);
                
                $purchaser = $cartContainer->order->getParticipantBySessionId($data['purchaser_id']);
                        
                # add purchser to order
                $cartContainer->order->setPurchaser($purchaser);
                
                return $this->redirect()->toRoute('order', array('action' => 'payment'));
            } else {
                error_log(var_export($form->getMessages(), true));
            }
        }
       
        $forrest = new Service\BreadcrumbFactory();
        $forrest->set('participant', 'order', array('action' => 'purchaser'));
        $forrest->set('purchaser', 'order', array('action' => 'purchaser'));
        
        $participants = $cartContainer->order->getParticipants();
        
        return new ViewModel(array(
            'form' => $form,
            'order' => $cartContainer->order,
            'participants' => $participants,
        ));
    }
    
    /*
     * collect payment data and complete purchase
     */
    public function paymentAction() {
        $forrest = new Service\BreadcrumbFactory();
        $forrest->set('paymenttype', 'order', array('action' => 'payment'));
        
        $form = new Form\PaymentType();
        
        $em = $this
            ->getServiceLocator()
            ->get('Doctrine\ORM\EntityManager');
        
        $paymenttypes = $em->getRepository("ersEntity\Entity\PaymentType")->findBy(array(), array('ordering' => 'ASC'));
        $types = array();
        foreach($paymenttypes as $paymenttype) {
            $types[] = array(
                'value' => $paymenttype->getId(),
                'label' => $paymenttype->getName(),
            );
        }
        $form->get('paymenttype_id')->setValueOptions($types);
        
        $cartContainer = new Container('cart');
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $inputFilter = new InputFilter\PaymentType();
            $form->setInputFilter($inputFilter->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $data = $form->getData();
                
                $paymenttype = $em->getRepository("ersEntity\Entity\PaymentType")
                        ->findOneBy(array('id' => $data['paymenttype_id']));
                
                
                $cartContainer->order->setPaymentType($paymenttype);
                
                return $this->redirect()->toRoute('order', array('action' => 'checkout'));
            } else {
                error_log(var_export($form->getMessages(), true));
            }
        }
        
        
        return new ViewModel(array(
            'form' => $form,
            'order' => $cartContainer->order,
            'paymenttypes' => $paymenttypes,
        ));
    }
    
    /*
     * last check and checkout
     */
    public function checkoutAction() {
        $cartContainer = new Container('cart');
        
        $form = new Form\Checkout();
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            $inputFilter = new InputFilter\Checkout();
            $form->setInputFilter($inputFilter->getInputFilter());
            $form->setData($request->getPost());

            # check if the session cart container has all data to finish this order
            $em = $this
                ->getServiceLocator()
                ->get('Doctrine\ORM\EntityManager');
            
            $purchaser = $cartContainer->order->getPurchaser();
            $user = $em->getRepository("ersEntity\Entity\User")
                    ->findOneBy(array('email' => $purchaser->getEmail()));
            if($user instanceof Entity\User) {
                $cartContainer->order->setPurchaser($user);
                $cartContainer->order->setPurchaserId($user->getId());
            } else {
                $em->persist($purchaser);
                $cartContainer->order->setPurchaser($purchaser);
                $cartContainer->order->setPurchaserId($purchaser->getId());
            }
            
            $paymenttype = $em->getRepository("ersEntity\Entity\PaymentType")
                    ->findOneBy(array('id' => $cartContainer->order->getPaymentTypeId()));
            $cartContainer->order->setPaymentType($paymenttype);
            $cartContainer->order->setPaymentTypeId($paymenttype->getId());
            
            # get the order_id
            $code = new Entity\Code();
            $code->genCode();
            $codecheck = 1;
            while($codecheck != null) {
                $code->genCode();
                $codecheck = $em->getRepository("ersEntity\Entity\Code")
                    ->findOneBy(array('value' => $code->getValue()));
            }
            $em->persist($code);
            $cartContainer->order->setCode($code);
            $cartContainer->order->setCodeId($code->getId());
            
            $packages = $cartContainer->order->getPackages();
            foreach($packages as $package) {
                if(count($package->getItems()) <= 0) {
                    $cartContainer->order->removePackage($package);
                    continue;
                }
                $package->setOrder($cartContainer->order);
                $package->setOrderId($cartContainer->order->getId());
                
                $participant = $package->getParticipant();
                
                if($participant->getFirstname() == '' || $participant->getSurname() == '') {
                    $participant = $purchaser;
                }
                
                $user = $em->getRepository("ersEntity\Entity\User")
                        ->findOneBy(array('email' => $participant->getEmail()));
                $role = $em->getRepository("ersEntity\Entity\Role")
                        ->findOneBy(array('roleId' => 'participant'));
                if($user instanceof Entity\User) {
                    $package->setParticipant($user);
                    if(!$user->hasRole($role)) {
                        $user->addRole($role);
                        $em->persist($user);
                    }
                    $package->setParticipantId($user->getId());
                    
                } else {
                    $em->persist($participant);
                    $package->setParticipant($participant);
                    $package->setParticipantId($participant->getId());
                }
                
                $country = $em->getRepository("ersEntity\Entity\Country")
                        ->findOneBy(array('id' => $participant->getCountryId()));
                if(!$country) {
                    $participant->setCountry(null);
                } else {
                    $participant->setCountry($country);
                }
                
                $code = new Entity\Code();
                $code->genCode();
                $codecheck = 1;
                while($codecheck != null) {
                    $code->genCode();
                    $codecheck = $em->getRepository("ersEntity\Entity\Code")
                        ->findOneBy(array('value' => $code->getValue()));
                }
                $em->persist($code);
                $package->setCode($code);
                $package->setCodeId($code->getId());
                
                foreach($package->getItems() as $item) {
                    $product = $em->getRepository("ersEntity\Entity\Product")
                        ->findOneBy(array('id' => $item->getProductId()));
                    $item->setProduct($product);
                    $item->setPackage($package);
                    $code = new Entity\Code();
                    $code->genCode();
                    $codecheck = 1;
                    while($codecheck != null) {
                        $code->genCode();
                        $codecheck = $em->getRepository("ersEntity\Entity\Code")
                            ->findOneBy(array('value' => $code->getValue()));
                    }
                    $item->setCode($code);
                    foreach($item->getItemVariants() as $variant) {
                        $variant->setItem($item);
                    }
                }
                
                $em->persist($package);
            }
            
            $em->persist($cartContainer->order);
            $em->flush();
        
            $session_order = new Container('order');
            $session_order->order_id = $cartContainer->order->getId();
            
            $cartContainer->init = 0;
            
            switch(strtolower($cartContainer->order->getPaymentType()->getType())) {
                case 'banktransfer':
                    $this->sendBankTransferEmail();
                    return $this->redirect()->toRoute('payment', array('action' => 'banktransfer'));
                    break;
                case 'creditcard':
                    return $this->redirect()->toRoute('payment', array('action' => 'creditcard'));
                    break;
                case 'paypal':
                    break;
                default:
            }
            
        }
        
        return new ViewModel(array(
            'form' => $form,
            'order' => $cartContainer->order,
        ));
    }
    
    private function sendBankTransferEmail() {
        $em = $this
            ->getServiceLocator()
            ->get('Doctrine\ORM\EntityManager');
        
        $session_order = new Container('order');
        $order = $em->getRepository("ersEntity\Entity\Order")
                    ->findOneBy(array('id' => $session_order->order_id));
        $purchaser = $order->getPurchaser();
        
        $emailService = new Service\EmailFactory();
        #$emailService->setFrom('prereg@eja.net');
        $emailService->setFrom('prereg@inbaz.org');
        
        $emailService->addTo($purchaser);
        $emailService->setSubject('EJC Registration System: Order Confirmation');
        
        $viewModel = new ViewModel(array(
            'order' => $order,
        ));
        $viewModel->setTemplate('email/order-confirmation.phtml');
        $viewRender = $this->getServiceLocator()->get('ViewRenderer');
        $html = $viewRender->render($viewModel);
        
        $emailService->setHtmlMessage($html);
        #$emailService->setTextMessage('Testmail');
        $emailService->send();
        
        return true;
    }
    
    /*
     * say thank you after purchaser
     */
    public function thankyouAction() {
        $cartContainer = new Container('cart');
        #$cartContainer->getManager()->getStorage()->clear('cart');
        $cartContainer->init = 0;
        return new ViewModel(array(
            'order' => $cartContainer->order,
        ));
    }
    
    /*
     * collect data for the invoice
     */
    public function invoiceAction() {
        
    }
    
    /*
     * delete a Package of this Order
     */
    public function deleteAction() {
        
    }

    public function ccErrorAction() {
        return new ViewModel();
    }
}