<?php
namespace Application\Controller;

use Application\Entity\Payment;
use Application\Entity\Rental;
use Application\Form\CustomerForm;
use Application\Form\PaymentForm;
use Application\Form\RentalForm;
use Application\Form\ReturnForm;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Zend\Form\Element;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\Query\ResultSetMapping;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {

        $filmsPerPage = 15;
        $page = $this->params()->fromQuery('page',null);
        if(empty( $page )) $page = 1;
        $store = new Container('store');
        $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $query = $em->createQuery('SELECT f FROM Application\Entity\Film f')
            ->setFirstResult($filmsPerPage*($page-1))
            ->setMaxResults($filmsPerPage);
        $query->setHydrationMode(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        $films = new Paginator($query, $fetchJoinCollection = true);
        $pages = round(count($films)/$filmsPerPage);
        //$films = $query->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        foreach($films as $film){
                $connection = $em->getConnection()->getWrappedConnection();
                $stmt = $connection->prepare('CALL film_in_stock(:p_film_id, :p_store_id, @STOCK)');
                $stmt->bindValue('p_film_id', $film["filmId"]);
                $stmt->bindValue('p_store_id', $store->storeId);
                $stmt->execute();
                $stmt->closeCursor();

                $stmt = $connection->query("SELECT @STOCK");
                while (($row = $stmt->fetchColumn()) !== false) {
                    $pelas[] = array("film" =>$film,"store" =>$store->storeId,"stock" =>$row);
                }
        }

        $view = new ViewModel();
        $view->setVariable("pages",$pages);
        $view->setVariable("store",$store->storeId);
        $view->setVariable("pelas",$pelas);
        return $view;
    }
    public function rentalAction()
    {
        $store = new Container('store');
        $filmId = $this->params()->fromRoute('id');
        $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $query = $em
            ->createQuery('SELECT f FROM Application\Entity\Film f WHERE f.filmId = :filmId')
            ->setParameter(":filmId",$filmId);
        $film = $query->getOneOrNullResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        $query = $em
            ->createQuery('SELECT i,f,s FROM Application\Entity\Inventory i JOIN i.film f JOIN i.store s WHERE s.storeId = :storeId  AND f.filmId = :filmId')
            ->setParameter(":filmId",$filmId)
            ->setParameter(":storeId",$store->storeId);
        $inventories = $query->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        foreach($inventories as $inventory){
            $connection = $em->getConnection()->getWrappedConnection();
            $stmt = $connection->prepare('SELECT inventory_in_stock(:p_inventory_id)');
            $stmt->bindValue('p_inventory_id', $inventory["inventoryId"]);
            $stmt->execute();
            if (($row = $stmt->fetchColumn()) !== false) {
                $available[$inventory["inventoryId"]] = $row;
            }
        }
        $film_data = array("film" =>$film,"store" =>$store->storeId,"stock" =>count($inventories));
        $view = new ViewModel();
        $view->setVariable("store",$store->storeId);
        $view->setVariable("filmdata",$film_data);
        $view->setVariable("inventory",$inventories);
        $view->setVariable("avaliable",$available);
        return $view;
    }
    public function checkoutAction()
    {
        $store = new Container('store');
        $inventoryId = $this->params()->fromRoute('id');
        $view = new ViewModel();
        $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $query = $em
            ->createQuery('SELECT f,i FROM Application\Entity\Inventory i JOIN i.film f  WHERE i.inventoryId = :inventoryId')
            ->setParameter(":inventoryId",$inventoryId);
        $inventory = $query->getOneOrNullResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        $form = new CustomerForm();
        $form->prepare();
        $request = $this->getRequest();
        $form->setData($request->getPost());
        if ($request->isPost()) {
            if ($form->isValid()) {
                $formData = $form->getData();
                $query = $em
                    ->createQuery('SELECT c FROM Application\Entity\Customer c WHERE c.customerId = :customerId')
                    ->setParameter(":customerId",$formData["customerId"]);
                $customer = $query->getOneOrNullResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

                if(!empty($customer)){
                    $formPayment = new PaymentForm($inventoryId,$customer["customerId"]);
                    $formPayment->prepare();
                    $view->setVariable("formPayment",$formPayment);
                    $view->setVariable("customer",$customer);
                }
            }
        }
        $view->setVariable("form",$form);
        $view->setVariable("store",$store->storeId);
        $view->setVariable("inventory",$inventory);
        $view->setVariable("inventoryId",$inventoryId);
        return $view;

    }
    function paymentAction(){

        $view = new ViewModel();

        $staff = new Container('user');
        $inventoryId = $this->params()->fromPost('inventoryId',null);
        $customerId = $this->params()->fromPost('customerId',null);

        $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');

        $inventory = $em->find("Application\Entity\Inventory", $inventoryId);
        $costumer = $em->find("Application\Entity\Customer", $customerId);
        $staff = $em->find("Application\Entity\Staff", $staff->staffId);

        $rental = new Rental();
        $rental->setCustomer($costumer);
        $rental->setInventory($inventory);
        $now =new \DateTime("NOW");
        $days = $inventory->getFilm()->getRentalDuration();
        $rental->setRentalDate($now);
        $returnDate = $now->add(new \DateInterval('P'.$days.'D'));
        $rental->setStaff($staff);
        $em->persist($rental);
        $em->flush();

        $payment =  new Payment();
        $payment->setCustomer($costumer);
        $payment->setStaff($staff);
        $payment->setRental($rental);
        $payment->setAmount($inventory->getFilm()->getRentalRate());
        $payment->setPaymentDate(new \DateTime("now"));
        $em->persist($payment);
        $em->flush();

        $view->setVariable("film",$inventory->getFilm());
        $view->setVariable("rental",$rental);
        $view->setVariable("payment",$payment);
        $view->setVariable("returnDate",$returnDate);

        return $view;

    }

    public function returningAction(){

        $view = new ViewModel();
        $formRental = new RentalForm();
        $formRental->prepare();

        $request = $this->getRequest();
        $formRental->setData($request->getPost());
        if ($request->isPost()) {
            if ($formRental->isValid()) {
                $formData = $formRental->getData();
                $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
                $rental = $em->find("Application\Entity\Rental", $formData["rentalId"]);
                $film = $rental->getInventory()->getFilm();
                if(!empty($rental)){
                    $formReturn = new ReturnForm($rental->getRentalId());
                    $formReturn->prepare();
                    $view->setVariable("rental",$rental);
                    $view->setVariable("film", $film);
                    $view->setVariable("formReturn",$formReturn);
                }

            }
        }

        $view->setVariable("formRental",$formRental);

        return $view;

    }

    public function returnAction(){

        $rentalId = $this->params()->fromPost('rentalId',null);
        $formReturn = new ReturnForm($rentalId);
        $formReturn->prepare();

        $request = $this->getRequest();
        $formReturn->setData($request->getPost());
        if ($request->isPost()) {
            if ($formReturn->isValid()) {
                $formData = $formReturn->getData();
                $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
                $rental = $em->find("Application\Entity\Rental", $formData["rentalId"]);
                $rental->setReturnDate(new \DateTime());
                $em->persist($rental);
                $em->flush();
            }
        }
        return $this->redirect()->toRoute('application', array(
            'action' => "returning"));
    }
    public function overdueAction(){

        $view = new ViewModel();
        $em = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $rsm = new ResultSetMapping;
        $rsm->addEntityResult('Application\Entity\Rental', 'r');
        $rsm->addFieldResult('r', 'rental_id', 'rentalId');
        $rsm->addJoinedEntityResult('Application\Entity\Inventory' , 'i', 'r', 'inventory');
        $rsm->addFieldResult('i', 'inventory_id', 'inventoryId');

        $sql = 'SELECT r.rental_id
                ,r.inventory_id
                FROM
                rental r
                JOIN inventory i
                ON r.inventory_id = i.inventory_id
                JOIN film f
                ON i.film_id = f.film_id
                WHERE inventory_held_by_customer(r.inventory_id) is not null
                AND r.return_date is null
                AND ADDDATE(r.rental_date,f.rental_duration) < NOW()';
        $query = $em->createNativeQuery($sql, $rsm);
        $rentals = $query->getResult();

        foreach($rentals as $rental){
            $em->refresh($rental);
            $em->refresh($rental->getInventory());
        }
        $view->setVariable("rentals",$rentals);
        return $view;

    }
}
