<?php
use Phalcon\Mvc\Model\Query;
set_time_limit (-1);
class MetadataController extends BaseController
{
	//private $dspaceClient;

    public function initialize()
    {
        $this->view->auth = $this->session->get('auth');
		 $this->token = $this->session->get('token'); 
		$auth = $this->session->get('auth');
		if($auth == null){
            $this->response->redirect("user/"); 
                $this->view->disable(); 
        } 
    }	

    public function listAction()
    {
 
    	// Add some local CSS resources
        $this->assets
            ->addCss('plugins/datatables/dataTables.bootstrap.css');

        // And some local JavaScript resources
        $this->assets
            ->addJs('plugins/datatables/jquery.dataTables.min.js')
            ->addJs('plugins/datatables/dataTables.bootstrap.min.js');            
         

		$token = $this->session->get('token');
 
        $data = Metadata::find("delete_flag =0");
		 
		$this->view->title = "MetaData";
	    $this->view->form = "Add";
		$this->view->button_title = "<i class='fa fa-users'></i> Add a new Metadata"; 
		$this->view->token = $token;
 
        $sorted_data = array();

        foreach ($data as $key => $value) { 
		  $sorted_data[] = [
				'id'            =>  $value->id,  
				'metadata_name'    =>  $value->metadata_name,
				'lastModified'  =>  $value->updated
			]; 
        }
		
        $json = json_encode($sorted_data); 
 
 
		$custom_js = array('partial'=>'Metadata/metadata_javascript', 'params'=>array('data'=>$json));		

		$this->addJavaScript($custom_js);

    }
 

    function addAction()
    {
        
        if ($this->request->isPost()) 
		{                     

            $token = $this->session->get('token');
			$metadata_name    = $this->request->getPost('metadata_name');		
			$metadata = new Metadata();

			
			$metadata->metadata 		=	$metadata_name;
			$metadata->delete_flag 		=	0;
			$metadata->updated 			=	date("Y-m-d h:i:s");

			$metadata->save();
			// Forward to the 'category' controller list
			$this->response->redirect("Metadata/list"); 
			$this->view->disable();

			return;
                
		}

		// Add some local CSS resources
        $this->assets
        	->addCss('plugins/select2/select2.min.css')
            ->addCss('plugins/daterangepicker/daterangepicker-bs3.css');

        // And some local JavaScript resources
        $this->assets
        	->addJs('plugins/select2/select2.min.js')            
            ->addJs('plugins/input-mask/jquery.inputmask.js')
            ->addJs('plugins/input-mask/jquery.inputmask.date.extensions.js')
            ->addJs('plugins/input-mask/jquery.inputmask.extensions.js')
            ->addJs('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js', false)
            ->addJs('plugins/daterangepicker/daterangepicker.js');     	

		$this->view->title = "New Collection";    	
    	$this->view->id = $id;

    	//$this->view->treaties_select = $this->createTreatiesSelect(); 
 
    }
	
	public function editAction($itemID)
    {
		 
		$data = Metadata::findFirstById($itemID);
		if ($this->request->isPost()) 
		{                     

            $token = $this->session->get('token');
			$metadata_name    = $this->request->getPost('metadata_name');		
			
			$metadata = new Metadata();

			$metadata->id 		=	$itemID;
			$metadata->metadata_name 	=	$metadata_name;
			$metadata->delete_flag 		=	0;
			$metadata->updated 			=	date("Y-m-d h:i:s");

			$metadata->update();
			// Forward to the 'category' controller list
			$this->response->redirect("Metadata/list"); 
			$this->view->disable();

			return;
                
		}
		 
		$this->view->title = "Edit Metadata";
		 
		$this->view->metadata_name = $metadata->metadata_name;
		$this->view->itemID = $itemID;
		
		$value = get_object_vars($data);  
		$this->view->data = $value; 
		$this->view->action = "Edit";
		$this->view->token = $token;
		
		  

		$custom_js = [
    	   'partial'=>'Metadata/detail_javascript',
    	   'params'=>[]
    	];	
		
        //$this->addJavaScript($custom_js);
    }
	
	public function deleteAction($itemID)
    {
		 
		$data = Metadata::findFirstById($itemID);
		
		$value = get_object_vars($data); 
			
		$metadata_name = $value['metadata']; 
		
		
		$metadata = new Metadata();
		$metadata->id 		=	$itemID; 
		$metadata->metadata 		=	$metadata_name;
		$metadata->delete_flag 		=	1;
		$metadata->updated 			=	date("Y-m-d h:i:s");

		$metadata->update();
		// Forward to the 'category' controller list
		$this->response->redirect("Metadata/list"); 
		 
    }

}