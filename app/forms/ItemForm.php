<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\File;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\PresenceOf;


class ItemForm extends Form
{
    public function initialize($entity = null, $options = null)
    {
         // In edition the id is hidden
        if (isset($options['edit']) && $options['edit']) {
            $id = new Hidden('id');
            $this->add($id);
        } /*else {
            //$id = new Text('id');
        }*/  
        
        $title = new Text('title', array(
            'placeholder' => 'Document Title', 
            "class"=>"form-control", 
            "id"=>"titleInput"
        ));

        $title->setLabel("Document Title");

        $title->addValidators(array(
            new PresenceOf(array(
                'message' => 'The title of the document is required'
            ))
        ));

        $this->add($title);

        $desc = new TextArea('description', array(
            'placeholder' => 'Document description', 
            "class"=>"form-control", 
            "id"=>"descriptionInput"
        ));

        $desc->setLabel("Document description");

        $desc->addValidators(array(
            new PresenceOf(array(
                'message' => 'The description of the document is required'
            ))
        ));

        $this->add($desc);

        /*
        $doc = new File('doc', array("id"=>"documentFile"));

        $doc->setLabel("File Input");
        
        $this->add($doc);
        */

        

        /*
        $this->add(new Submit('go', array(
            'class' => 'btn btn-success'
        )));
        */
    }
}
