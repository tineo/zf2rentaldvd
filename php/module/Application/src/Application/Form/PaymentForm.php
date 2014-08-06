<?php
/**
 * Created by PhpStorm.
 * User: tineo
 * Date: 03/08/14
 * Time: 12:36 PM
 */

namespace Application\Form;


use Zend\Form\Form;

class PaymentForm extends Form {

    public function __construct($inventoryId, $customerId)
    {
        parent::__construct();

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'inventoryId',
            'attributes' => array(
                'value' => $inventoryId
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'customerId',
            'attributes' => array(
                'value' => $customerId
            )
        ));


        $this->add(array(
            'name' => 'submit',
            'type'  => 'Submit',
            'attributes' => array(
                'value' => 'Rental and Pay',
            ),
        ));
    }

} 