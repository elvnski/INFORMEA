<?php
use Phalcon\Mvc\Model\Query;
set_time_limit (-1);
use Phalcon\Mvc\View;

class CategoryController extends BaseController{


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

    public function listAction($collectionID){
        // Setting the parameters for the multiple views
        $parameters = [
            "meetings"=>[
                "form"=>"meeting",
                "title"=>"Meetings",
                "button_title" => "<i class='fa fa-file-text-o'></i> Add New Meeting"
            ],
            "decisions"=>[
                "form"=>"decision",
                "title"=>"Decisions",
                "button_title" => "<i class='fa fa-file-text-o'></i> Add New Document"
            ],
            "contacts"=>[
                "form"=>"contact",
                "title"=>"Contacts",
                "button_title" => "<i class='fa ion-person-add'></i> Add a New Contact"
            ],
            "reports"=>[
                "form"=>"report",
                "title"=>"Country Reports",
                "button_title" => "<i class='fa fa-file-text-o'></i> Add New Document"
            ],
            "general_documents"=>[
                "form"=>"general_docs",
                "title"=>"General Documents",
                "button_title"=>"<i class='fa fa-file-text-o'></i> Add New General Document"
            ],
            "library_catalog"=>[
                "form"=>"library_catalog",
                "title"=>"Library Catalog",
                "button_title"=>"<i class='fa fa-file-text-o'></i> Add New Library Catalog"
            ],
            "publications"=>[
                "form"=>"publications",
                "title"=>"Publications",
                "button_title"=>"<i class='fa fa-file-text-o'></i> Add New Publication"
            ],


        ];


        //Add some local css resources
        $this->assets->addCss('plugins/datatables/dataTables.bootstrap.css');

        // And some local JavaScript resources
        $this->assets
            ->addJs('plugins/datatables/jquery.dataTables.min.js')
            ->addJs('plugins/datatables/dataTables.bootstrap.min.js');



        switch ($collectionID) {
            case 7:
                $parameter = $parameters["publications"];
                $category = "publications";
                break;
            case 6:
                $parameter = $parameters["library_catalog"];
                $category = "library_catalog";
                break;
            case 5:
                $parameter = $parameters["general_documents"];
                $category = "general_documents";
                break;
            case 4:
                $parameter = $parameters["meetings"];
                $category = "meetings";
                break;
            case 3:
                $parameter = $parameters["decisions"];
                $category = "decisions";
                break;
            case 2:
                $parameter = $parameters["contacts"];
                $category = "contacts";
                break;
            case 1:
                $parameter = $parameters["reports"];
                $category = "reports";
                break;
            default:
                $parameter = $parameters["meetings"];
                $category = "meetings";
        }

        $token = $this->session->get('token');


        //Used to set the data to be displayed
        if ($category == "meetings") {
            $data = Meetings::find();
        } elseif ($category == "decisions") {
            $data = Decisions::find();
        } elseif ($category == "contacts") {
            $data = Contacts::find();
        } elseif ($category == "reports") {
            $data = CountryReports::find();
        }elseif ($category == "general_documents"){
            $data = GeneralDocuments::find();
        }elseif ($category == "library_catalog"){
            $data = LibraryCatalog::find();
        }elseif ($category == "publications"){
            $data = Publications::find();
        }

        //This picks a string from the selected subset $parameter for either the meeting/report/...
        $this->view->categoryID = $collectionID;

        $this->view->form = $parameter["form"];

        $this->view->title = $parameter["title"];

        $this->view->button_title = $parameter["button_title"];

        $sorted_data = array();

        foreach ($data as $key => $value) {

            if ($category == "meetings") {
                $title = MeetingTitle::findFirst("meeting_id = {$value->id}");
                $sorted_data[] = [
                    'id' => $value->id,
                    'treaty' => $value->treaty,
                    'location' => $value->location,
                    'type'=> $value->type,
                    'author'=> $value->author,
                    'updated' => $value->updated
                ];
                $json = json_encode($sorted_data);
                $custom_js = array('partial'=>'category/meetings_javascript', 'params'=>array('data'=>$json));

            }

            else if ($category == "decisions") {
                $title = DecisionsTitle::findFirst("decision_id = {$value->id}");
                $sorted_data[] = [
                    'id' => $value->id,
                    'meetingTitle' => $value->meetingTitle,
                    'treaty' => $value->treaty,
                    'type' => $value->type,
                    'author' => $value->author,
                    'updated' => $value->updated,
                ];
                $json = json_encode($sorted_data);
                $custom_js = array('partial'=>'category/decisions_javascript', 'params'=>array('data'=>$json));

            }

            else if ($category == "contacts") {

                $sorted_data[] = [
                    'id' => $value->id,
                    'prefix' => $value->prefix,
                    'name' => $value->firstName . ' ' . $value->lastName,
                    'position' => $value->position,
                    'institution' => $value->institution,
                    'country' => $value->country,
                    'updated' => $value->updated,
                ];

                $json = json_encode($sorted_data);
                $custom_js = array('partial'=>'category/contacts_javascript', 'params'=>array('data'=>$json));


            }

            else if ($category == "reports") {
                $title = CountryReportsTitle::findFirst("country_report_id = {$value->id}");
                $sorted_data[] = [
                    'id' => $value->id,
                    'title' => $title->title,
                    'treaty' => $value->author,
                    'country' => $value->country,
                    'author' => $value->author,
                    'updated' => $value->updated,
                ];

                $json = json_encode($sorted_data);
                $custom_js = array('partial'=>'category/country_reports_javascript', 'params'=>array('data'=>$json));

            }

            else if ($category === "general_documents") {

                $sorted_data[] = [
                    'id' => $value->id,
                    'title' => $value->title,
                    'author' => $value->author,
                    'publisher' => $value->publisher,
                    'updated' => $value->updated,
                ];

                $json = json_encode($sorted_data);
                $custom_js = array('partial'=>'category/general_documents_javascript', 'params'=>array('data'=>$json));

            }

            else if ($category === "library_catalog") {

                $sorted_data[] = [
                    'id' => $value->id,
                    'title' => $value->title,
                    'author' => $value->author,
                    'publisher' => $value->publisher,
                    'version' => $value->version,
                    'updated' => $value->updated,
                ];

                $json = json_encode($sorted_data);
                $custom_js = array('partial'=>'category/library_catalog_javascript', 'params'=>array('data'=>$json));

            }

            else if ($category === "publications") {

                $sorted_data[] = [
                    'id' => $value->id,
                    'title' => $value->title,
                    'author' => $value->author,
                    'publisher' => $value->publisher,
                    'version' => $value->version,
                    'updated' => $value->updated,
                ];

                $json = json_encode($sorted_data);
                $custom_js = array('partial'=>'category/publications_javascript', 'params'=>array('data'=>$json));

            }





            else {
                echo "There is nothing mentioned";
                die;
            }

        }





        $this->addJavaScript($custom_js);
    }

    function get($collectionID){
        $file = "";
        switch ($collectionID) {
            case 6:
                $file = "meetings";
                break;
            case 7:
                $file = "decisions";
                break;
            case 12:
                $file = "contacts";
                break;
            case 9:
                $file = "reports";
                break;
            default:
                $file = "meetings";        }

        return file_get_contents($file .".json");
    }


    function addAuthorCountryReportAction()
    {

        $token = $this->session->get('token');

        $headers = ['Content-Type'=>'application/json', 'rest-dspace-token' => $token];

        $reports = file_get_contents('./reports.json');

        $reports = json_decode($reports);

        // echo "<pre>"; print_r($reports);die;

        foreach ($reports as $key => $value) {
            $country_reports = CountryReports::findFirst($value->id);

            foreach ($value->metadata as $metadata) {
                if ($metadata->key == "dc.contributor.author") {
                    $country_reports->author = $metadata->value;
                }
            }

            $country_reports->update();
        }
    }



}












