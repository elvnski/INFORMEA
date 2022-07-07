<?php
use Phalcon\Mvc\Model\Query;
set_time_limit (-1);
class CollectionsController extends BaseController
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
 
        $data = Collections::find("delete_flag = 0");
		 
		$this->view->title = "Collections";
	    $this->view->form = "Add";
		$this->view->button_title = "<i class='fa fa-users'></i> Add a new Collection"; 
		$this->view->token = $token;
 
        $sorted_data = array();

        foreach ($data as $key => $value) { 
		  $sorted_data[] = [
				'id'            =>  $value->id,  
				'collection'    =>  $value->collection,
				'lastModified'  =>  $value->updated
			]; 
        }
		
        $json = json_encode($sorted_data); 
 
 
		$custom_js = array('partial'=>'Collections/collections_javascript', 'params'=>array('data'=>$json));		

		$this->addJavaScript($custom_js);

    }
 

    function addAction()
    {
        
        if ($this->request->isPost()) 
		{                     

            $token = $this->session->get('token');
			$collection_name    = $this->request->getPost('collection_name');		
			$collection = new Collections();

			
			$collection->collection 		=	$collection_name;
			$collection->delete_flag 		=	0;
			$collection->updated 			=	date("Y-m-d h:i:s");

			$collection->save();
			// Forward to the 'category' controller list
			$this->response->redirect("Collections/list"); 
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
		 
		$data = Collections::findFirstById($itemID);
		if ($this->request->isPost()) 
		{                     

            $token = $this->session->get('token');
			$collection_name    = $this->request->getPost('collection_name');		
			
			$collection = new Collections();

			$collection->id 		=	$itemID;
			$collection->collection 		=	$collection_name;
			$collection->delete_flag 		=	0;
			$collection->updated 			=	date("Y-m-d h:i:s");

			$collection->update();
			// Forward to the 'category' controller list
			$this->response->redirect("Collections/list"); 
			$this->view->disable();

			return;
                
		}
		 
		$this->view->title = "Edit Collection";
		
		$this->view->collection_name = $collection->collection;
		$this->view->itemID = $itemID;
		
		$value = get_object_vars($data); 
		
		$this->view->data = $value; 
		$this->view->action = "Edit";
		$this->view->token = $token;

		$custom_js = [
    	   'partial'=>'Collections/detail_javascript',
    	   'params'=>[]
    	];	
		
        //$this->addJavaScript($custom_js);
    }
	
	public function deleteAction($itemID)
    {
		 
		$data = Collections::findFirstById($itemID);
		
		$value = get_object_vars($data); 
			
		$collection_name = $value['collection']; 
		
		
		$collection = new Collections();
		$collection->id 		=	$itemID; 
		$collection->collection 		=	$collection_name;
		$collection->delete_flag 		=	1;
		$collection->updated 			=	date("Y-m-d h:i:s");

		$collection->update();
		// Forward to the 'category' controller list
		$this->response->redirect("Collections/list"); 
		 
    }

}