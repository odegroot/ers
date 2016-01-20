<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ErsBase\Service;

use Zend\Session\Container;
use ErsBase\Entity;

/**
 * order service
 */
class OrderService
{
    protected $_sl;
    protected $order;

    public function __construct() {
        
    }
    
    /**
     * set ServiceLocator
     * 
     * @param ServiceLocator $sl
     */
    public function setServiceLocator($sl) {
        $this->_sl = $sl;
    }
    
    /**
     * get ServiceLocator
     * 
     * @return ServiceLocator
     */
    protected function getServiceLocator() {
        return $this->_sl;
    }
    
    public function getOrder() {
        if($this->order instanceof Entity\Order) {
            return $this->order;
        }
        $cartContainer = new Container('cart');
        $em = $this->getServiceLocator()
                ->get('Doctrine\ORM\EntityManager');
        if(isset($cartContainer->order_id) && is_numeric($cartContainer->order_id)) {
            $order = $em->getRepository('ErsBase\Entity\Order')
                    ->findOneBy(array('id' => $cartContainer->order_id));
            if(!$order) {
                error_log('Cannot find order with id '.$cartContainer->order_id.'. Creating new order...');
                $order = new Entity\Order();
                $status = $em->getRepository('ErsBase\Entity\Status')
                    ->findOneBy(array('value' => 'order pending'));
                $order->setStatus($status);
            
                $em->persist($order);
                $em->flush();

                $cartContainer->order_id = $order->getId();
            }
            
            $this->order = $order;
            $this->addLoggedInUser();
            return $order;
        } else {
            #error_log('reset cart');
            #$cartContainer->getManager()->getStorage()->clear('cart');
            
            error_log('order_id is not set or is not numeric: '.$cartContainer->order_id.'. Creating new order...');
            $order = new Entity\Order();
            $status = $em->getRepository('ErsBase\Entity\Status')
                ->findOneBy(array('value' => 'order pending'));
            $order->setStatus($status);
            
            $em->persist($order);
            $em->flush();
            
            $cartContainer->order_id = $order->getId();
            error_log($cartContainer->order_id);
            
            $this->order = $order;
            $this->addLoggedInUser();
            
            return $order;
        }
    }
    
    public function addLoggedInUser() {
        $auth = $this->getServiceLocator()
                ->get('zfcuser_auth_service');
        if ($auth->hasIdentity()) {
            $login_email = $auth->getIdentity()->getEmail();

            $order = $this->getOrder();
            $package = $order->getPackageByParticipantEmail($login_email);
            if(!$package) {
                $em = $this->getServiceLocator()
                    ->get('Doctrine\ORM\EntityManager');
                $user = $em->getRepository('ErsBase\Entity\User')
                        ->findOneBy(array('email' => $login_email));
                $this->addParticipant($user);
            }
        }
        
    }
    
    public function setCountryId($country_id) {
        $cartContainer = new Container('cart');
        $cartContainer->country_id = $country_id;
    }
    
    public function getCountryId() {
        $cartContainer = new Container('cart');
        return $cartContainer->country_id;
    }
    
    /**
     * Add Participant (add new Package and set participant)
     * 
     * @param \Entity\User $participant
     * @return \Entity\Order
     */
    
    public function addParticipant(Entity\User $participant) {
        $package = new Entity\Package();
        $em = $this->getServiceLocator()
                ->get('Doctrine\ORM\EntityManager');
        $status = $em->getRepository('ErsBase\Entity\Status')
                    ->findOneBy(array('value' => 'order pending'));
        if(!$status) {
            throw new \Exception('Please setup status "order pending"');
        }
        $package->setStatus($status);
        $status->addPackage($package);
        
        $package->setParticipant($participant);
        
        $this->getOrder()->addPackage($package);
        $package->setOrder($this->getOrder());
        
        return $this->getOrder();
    }
}
