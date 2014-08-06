<?php
/**
 * Created by PhpStorm.
 * User: tineo
 * Date: 05/08/14
 * Time: 03:12 AM
 */

namespace Application\Form;


use Zend\Form\Form;

class ReturnForm extends Form {
    public function __construct($rentalId)
    {
        parent::__construct();

        $this->add(array(
            'type' => 'Zend\Form\Element\Hidden',
            'name' => 'rentalId',
            'attributes' => array(
                'value' => $rentalId
            )
        ));


        $this->add(array(
            'name' => 'submit',
            'type'  => 'Submit',
            'attributes' => array(
                'value' => 'Return',
            ),
        ));
    }

} 