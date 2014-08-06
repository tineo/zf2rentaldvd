<?php
/**
 * Created by PhpStorm.
 * User: tineo
 * Date: 05/08/14
 * Time: 02:45 AM
 */

namespace Application\Form;


use Zend\Form\Form;

class RentalForm  extends Form{

    public function __construct()
    {
        parent::__construct();

        $this->add(array(
            'name' => 'rentalId',
            'options' => array(
                'label' => 'Rental ID : ',
            ),
            'type'  => 'Text',
        ));

        $this->add(array(
            'name' => 'submit',
            'type'  => 'Submit',
            'attributes' => array(
                'value' => 'Get Rental',
            ),
        ));
    }

} 