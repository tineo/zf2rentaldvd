<?php
/**
 * Created by PhpStorm.
 * User: tineo
 * Date: 03/08/14
 * Time: 11:23 AM
 */

namespace Application\Form;


use Zend\Form\Form;

class CustomerForm extends Form
{
    public function __construct()
    {
        parent::__construct();

        $this->add(array(
            'name' => 'customerId',
            'options' => array(
                'label' => 'Customer ID : ',
            ),
            'type'  => 'Text',
        ));

        $this->add(array(
            'name' => 'submit',
            'type'  => 'Submit',
            'attributes' => array(
                'value' => 'Get Customer',
            ),
        ));
    }

} 