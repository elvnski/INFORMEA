<?php

class ItemController extends BaseController
{
    public function initialize()
    {
        $this->view->auth = $this->session->get('auth');
    }



    public function detailAction($itemID)
    {

        $url = "items/". $itemID ."/?expand=all";
        $token = $this->session->get('token');


        $res = $this->dspace->get($url, ['verify' => false]);

        $data = json_decode($res->getBody());
        $result = json_decode($res->getBody(), true);

        // echo "<pre>";print_r($result);die;

        $collection = $result['parentCollection']['id'];

        switch ($collection) {
            case 4:
                $action = "meeting";
                break;
            case 3:
                $action =  "decision";
                break;
            case 2:
                $action =  "contact";
                break;
            case 1:
                $action =  "report";
                break;
            default:
                $action =  "meeting";
        }

        $this->view->title = "Document Details";

        $this->view->doc = $data->name;
        $this->view->itemID = $itemID;

        $this->view->data = $data;
        $this->view->action = $action;
        $this->view->token = $token;

        $custom_js = [
            'partial'=>'item/detail_javascript',
            'params'=>[]
        ];

        $this->addJavaScript($custom_js);
    }




    public function meetingAction($id = "")
    {
        if ($this->request->isPost())
        {

            $token = $this->session->get('token');

            // Get the data from the user
            $title    = $this->request->getPost('title');
            $author = $this->request->getPost('author');
            $keywords = $this->request->getPost('keywords');
            $extrakeywords = $this->request->getPost('extrakeywords');
            $treaty = $this->request->getPost('treaty');
            $description = $this->request->getPost('description');
            $url = $this->request->getPost('url');
            $meeting_dates = $this->request->getPost('meeting-dates');
            $repetition = $this->request->getPost('repetition');
            $kind = $this->request->getPost('kind');
            $languages = $this->request->getPost('languages');
            $extralanguages = $this->request->getPost('extralanguages');
            $type = $this->request->getPost('document_type');
            $rights = $this->request->getPost('rights');
            $status = $this->request->getPost('status');
            $imageurl = $this->request->getPost('imageurl');
            $imagecopyright = $this->request->getPost('imagecopyright');
            $location = $this->request->getPost('location');
            $city = $this->request->getPost('city');
            $country = $this->request->getPost('country');
            $latitude = $this->request->getPost('latitude');
            $longitude = $this->request->getPost('longitude');

            $existingkeywords = rtrim($existingkeywords, ",");
            $existinglanguages = rtrim($existinglanguages, ",");

            $meetingdates = explode(" - ", $meeting_dates);
            $start = $meetingdates[0];
            $end = $meetingdates[1];
            if($extrakeywords != '')
            {
                $extrakeywords = explode(",", $extrakeywords);
                $keyworddata = '[';
                for($i = 0; $i<= sizeof($extrakeywords)-2; $i ++ )
                {
                    $keyworddata .= '{"key":"dc.subject","value":"'.$extrakeywords[$i].'"},';
                }
                $keyworddata = rtrim($keyworddata, ",");

                $keyworddata .= ']';

            }
            if($extralanguages != '')
            {
                $extralanguages = explode(",", $extralanguages);
                $languagedata = '[';
                for($i = 0; $i<= sizeof($extralanguages)-2; $i ++ )
                {
                    $languagedata .= '{"key":"dc.language","value":"'.$extralanguages[$i].'"},';
                }
                $languagedata = rtrim($languagedata, ",");

                $languagedata .= ']';

            }
            $fileOneName = "";
            $fileOneTmp = "";
            $fileOneDesc = "";

            //Check if the user has uploaded files
            if ($this->request->hasFiles() == true) {
                //Print the real file names and their sizes
                foreach ($this->request->getUploadedFiles() as $file)
                {
                    //echo $file->getName(), " ", $file->getSize(), "\n";
                    $fileOneName = str_replace(" ", "_", $file->getName());
                    $fileOneTmp = $file->getTempName();

                }
            }

            if($token != NULL)
            {
                $headers = [
                    'Content-Type'=>'application/json',
                    'Accept'=>'application/json',
                    'rest-dspace-token' => $token
                ];

                $data = [
                    [
                        "key"=>"dc.title",
                        "value"=>$title
                    ],
                    [
                        "key"=>"dc.contributor.author",
                        "value"=>$author
                    ],
                    /*[
                        "key"=>"dc.subject.keywords",
                        "value"=>$keywords
                    ],*/
                    [
                        "key"=>"informea.identifier.treaty",
                        "value"=>$treaty
                    ],
                    [
                        "key"=>"dc.description",
                        "value"=>$description
                    ],
                    [
                        "key"=>"dc.identifier.uri",
                        "value"=>$url
                    ],
                    [
                        "key"=>"informea.identifier.date-start",
                        "value"=>$start
                    ],
                    [
                        "key"=>"informea.identifier.date-end",
                        "value"=>$end
                    ],
                    [
                        "key"=>"informea.identifier.repetition",
                        "value"=>$repetition
                    ],
                    [
                        "key"=>"informea.identifier.kind",
                        "value"=>$kind
                    ],
                    /*[
                        "key"=>"dc.identifier.languages",
                        "value"=>$languages
                    ],*/
                    [
                        "key"=>"dc.type",
                        "value"=>$type
                    ],
                    [
                        "key"=>"informea.identifier.rights",
                        "value"=>$rights
                    ],
                    [
                        "key"=>"informea.identifier.status",
                        "value"=>$status
                    ],
                    [
                        "key"=>"informea.identifier.imageurl",
                        "value"=>$imageurl
                    ],
                    [
                        "key"=>"informea.identifier.imagecopyright",
                        "value"=>$imagecopyright
                    ],
                    [
                        "key"=>"informea.identifier.location",
                        "value"=>$location
                    ],
                    [
                        "key"=>"informea.identifier.city",
                        "value"=>$city
                    ],
                    [
                        "key"=>"informea.identifier.country",
                        "value"=>$country
                    ],
                    [
                        "key"=>"informea.identifier.latitude",
                        "value"=>$latitude
                    ],
                    [
                        "key"=>"informea.identifier.longitude",
                        "value"=>$longitude
                    ],



                ];

                $metadata = ["metadata"=>$data];

                $addUrl = "https://stg-wedocs.unep.org/rest/collections/6/items";
                $metadata = json_encode($metadata);


                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $addUrl,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => $metadata,
                    CURLOPT_HTTPHEADER => array(
                        "cache-control: no-cache",
                        "content-type: application/json",
                        "postman-token: 642f2082-ffc1-c830-252c-1b85b18d0a6e",
                        "rest-dspace-token: ".$token

                    ),
                ));
                $response = curl_exec($curl);
                $err = curl_error($curl);
                if ($err) {
                    echo "cURL Error #:" . $err;
                } else {
                    echo $response;


                }
                curl_close($curl);
                $item = new SimpleXMLElement($response);

                if($item->id != null)
                {
                    $itemID = $item->id;
                    $editUrl = "items/".$itemID;
                    $editUrl = "https://stg-wedocs.unep.org/rest/items/".$itemID."/metadata";


                    if($keyworddata != '')
                    {

                        $curl2 = curl_init();
                        curl_setopt_array($curl2, array(
                            CURLOPT_URL => $editUrl,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => "",
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 30,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => "POST",
                            CURLOPT_POSTFIELDS => $keyworddata,
                            CURLOPT_HTTPHEADER => array(
                                "cache-control: no-cache",
                                "content-type: application/json",
                                "rest-dspace-token: ".$token
                            ),
                        ));

                        $response = curl_exec($curl2);
                        $err = curl_error($curl2);
                        if ($err) {
                            echo "cURL Error #:" . $err;
                        } else {
                            echo $response;
                        }
                        curl_close($curl2);
                        echo $err;

                    }

                    if($languagedata != '')
                    {

                        $curl2 = curl_init();
                        curl_setopt_array($curl2, array(
                            CURLOPT_URL => $editUrl,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => "",
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 30,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => "POST",
                            CURLOPT_POSTFIELDS => $languagedata,
                            CURLOPT_HTTPHEADER => array(
                                "cache-control: no-cache",
                                "content-type: application/json",
                                "rest-dspace-token: ".$token
                            ),
                        ));

                        $response = curl_exec($curl2);
                        $err = curl_error($curl2);
                        if ($err) {
                            echo "cURL Error #:" . $err;
                        } else {
                            echo $response;
                        }
                        curl_close($curl2);
                        echo $err;

                    }

                    $headers = [
                        'Content-Type'=>'application/pdf',
                        'rest-dspace-token' => $token
                    ];

                    $url = "items/".$item->id."/bitstreams" . "?name=" . $fileOneName . "&description=" . $fileOneDesc;

                    if(fopen($fileOneTmp, 'r'))
                    {
                        $postFileRes = $this->dspace->post($url,
                            [
                                'verify' => false,
                                'headers'=>$headers,
                                'multipart' => [
                                    [
                                        'contents' => fopen($fileOneTmp, 'r'),
                                    ],
                                ]
                            ]
                        );
                    }
                }


                $meeting = new Meetings();

                $meeting->id 				= 	$item->id;
                $meeting->treaty			=	$treaty;
                $meeting->url 				=	$post_url;
                $meeting->start 			=	$start;
                $meeting->end 				=	$end;
                $meeting->repetition 		=	$repetition;
                $meeting->kind 				=	$kind;
                $meeting->type 				=	$type;
                $meeting->access 			=	$access;
                $meeting->status 			=	$status;
                $meeting->imageurl 			=	$imageurl;
                $meeting->imageCopyright	=	$imageCopyright;
                $meeting->location 			=	$location;
                $meeting->city 				=	$city;
                $meeting->country 			=	$country;
                $meeting->latitude			=	$latitude;
                $meeting->longitude			=	$longitude;
                $meeting->updated 			=	$updated;
                $meeting->author 			=	$author;
                $meeting->updated 			=	date("Y-m-d h:i:s");

                $meeting->save();

                $meeting_title = new MeetingTitle();

                $meeting_title->meeting_id = $item->id;
                $meeting_title->title = $title;
                $meeting_title->language = "English";

                $meeting_title->save();

                $meeting_description = new MeetingsDescription();
                $meeting_description->meeting_id = $item->id;
                $meeting_description->language = "English";
                $meeting_description->description = $description;

                $meeting_description->save();

                // Forward to the 'category' controller list
                $this->response->redirect("category/list/6");
                $this->view->disable();

                return;

            }
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

        $this->view->title = "Meeting Document";
        $this->view->id = $id;

        $this->view->treaties_select = $this->createTreatiesSelect();
        $this->view->repetition_select = $this->createRepetitonsSelect();
        $this->view->kinds_select = $this->createKindSelect();
        $this->view->types_select = $this->createMeetingTypeSelect();
        $this->view->rights_select = $this->createRightsSelect();
        $this->view->status_select = $this->createStatusSelect();
        $this->view->countries_select = $this->createCountrySelect();

        $custom_js = [
            'partial'=>'item/meeting_javascript',
            'params'=>[]
        ];

        $this->addJavaScript($custom_js);
    }

    public function contactAction($id = "")
    {
        if ($this->request->isPost()) {

            $token = $this->session->get('token');

            /// Get the data from the user
            $author = $this->request->getPost('author');
            $title    = $this->request->getPost('title');
            $treaty = $this->request->getPost('treaty');
            $country = $this->request->getPost('country');
            $prefix = $this->request->getPost('prefix');
            $firstname = $this->request->getPost('firstname');
            $lastname = $this->request->getPost('lastname');
            $type = $this->request->getPost('type');
            $position = $this->request->getPost('position');
            $organizations = $this->request->getPost('organizations');
            $department = $this->request->getPost('department');
            $location = $this->request->getPost('location');
            $email = $this->request->getPost('email');
            $phone = $this->request->getPost('phone');
            $fax = $this->request->getPost('fax');
            $status = $this->request->getPost('status');





            $fileOneName = "";
            $fileOneTmp = "";
            $fileOneDesc = "";

            //Check if the user has uploaded files
            if ($this->request->hasFiles() == true) {
                //Print the real file names and their sizes
                foreach ($this->request->getUploadedFiles() as $file)
                {
                    //echo $file->getName(), " ", $file->getSize(), "\n";
                    $fileOneName = $file->getName();
                    $fileOneTmp = $file->getTempName();
                }
            }

            if($token != NULL)
            {
                $headers = [
                    'Content-Type'=>'application/json',
                    'Accept'=>'application/json',
                    'rest-dspace-token' => $token
                ];

                $author = $this->request->getPost('author');
                $title    = $this->request->getPost('title');
                $treaty = $this->request->getPost('treaty');
                $department = $this->request->getPost('department');
                $email = $this->request->getPost('email');
                $firstname = $this->request->getPost('firstname');
                $lastname = $this->request->getPost('lastname');
                $organizations = $this->request->getPost('organizations');
                $phone = $this->request->getPost('phone');
                $position = $this->request->getPost('position');
                $prefix = $this->request->getPost('prefix');
                $type = $this->request->getPost('type');
                $address = $this->request->getPost('address');
                $country = $this->request->getPost('country');
                $fax = $this->request->getPost('fax');
                $location = $this->request->getPost('location');
                $status = $this->request->getPost('status');



                $fileOneName = "";
                $fileOneTmp = "";
                $fileOneDesc = "";


                $data = [
                    [
                        "key"=>"dc.contributor.author",
                        "value"=>$author
                    ],
                    [
                        "key"=>"dc.title",
                        "value"=>$title
                    ],
                    [
                        "key"=>"informea.identifier.treaty",
                        "value"=>$treaty
                    ],
                    [
                        "key"=>"informea.contact.department",
                        "value"=>$department
                    ],
                    [
                        "key"=>"informea.contact.email",
                        "value"=>$email
                    ],
                    [
                        "key"=>"informea.contact.firstname",
                        "value"=>$firstname
                    ],
                    [
                        "key"=>"informea.contact.lastname",
                        "value"=>$lastname
                    ],
                    [
                        "key"=>"informea.contact.organizations",
                        "value"=>$organizations
                    ],
                    [
                        "key"=>"informea.contact.phone",
                        "value"=>$phone
                    ],
                    [
                        "key"=>"informea.contact.position",
                        "value"=>$position
                    ],
                    [
                        "key"=>"informea.contact.prefix",
                        "value"=>$prefix
                    ],
                    [
                        "key"=>"informea.contact.type",
                        "value"=>$type
                    ],
                    [
                        "key"=>"informea.contact.address",
                        "value"=>$address
                    ],
                    [
                        "key"=>"informea.contact.country",
                        "value"=>$country
                    ],
                    /*[
                        "key"=>"informea.contact.location",
                        "value"=>$location
                    ],
                    [
                        "key"=>"informea.contact.status",
                        "value"=>$status
                    ],*/
                    [
                        "key"=>"informea.contact.fax",
                        "value"=>$fax
                    ]

                ];


                $metadata = ["metadata"=>$data];
                $datapost = json_encode($data);


                $addUrl = "https://stg-wedocs.unep.org/rest/collections/12/items";
                $metadata = json_encode($metadata);


                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $addUrl,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => $metadata,
                    CURLOPT_HTTPHEADER => array(
                        "cache-control: no-cache",
                        "content-type: application/json",
                        "postman-token: 642f2082-ffc1-c830-252c-1b85b18d0a6e",
                        "rest-dspace-token: ".$token

                    ),
                ));
                $response = curl_exec($curl);
                $err = curl_error($curl);
                if ($err) {
                    echo "cURL Error #:" . $err;
                } else {
                    //echo $response;


                }
                curl_close($curl);
                $item = new SimpleXMLElement($response);

                if($item->id != null)
                {
                    $itemID = $item->id;
                    $editUrl = "items/".$itemID;
                    $editUrl = "https://stg-wedocs.unep.org/rest/items/".$itemID."/metadata";

                    $contact = new Contacts();

                    $contact->id = $itemID;
                    $contact->firstName = $firstname;
                    $contact->lastName = $lastname;
                    $contact->prefix = $prefix;
                    $contact->country = $country;
                    $contact->position = $position;
                    $contact->institution = $organizations;
                    $contact->department = $department;
                    $contact->type = $type;
                    $contact->address = $address;
                    $contact->email = $email;
                    $contact->phoneNumber = $phone;
                    $contact->fax = $fax;
                    $contact->author = $author;
                    $contact->updated = date("Y-m-d H:i:s");

                    $contact->save();

                    $contact_treaty = new ContactTreaty();

                    $contact_treaty->contact_id = $itemID;
                    $contact_treaty->treaty = $treaty;

                    $contact_treaty->save();
                }


                // Forward to the 'item' detail
                $this->response->redirect("item/detail/".$itemID);
                $this->view->disable();

                return;

            }
        }

        $this->view->message = $message;

        $this->view->title = "Contact Document";
        $this->view->id = $id;

        $custom_js = [
            'partial'=>'item/contact_javascript',
            'params'=>[]
        ];

        $this->addJavaScript($custom_js);
    }
    public function decisionAction($id = "")
    {
        if ($this->request->isPost()) {

            // echo "<pre>";print_r($this->request->getPost());die;
            $token = $this->session->get('token');

            /// Get the data from the user
            $author = $this->request->getPost('author');
            $date_issued = $this->request->getPost('date_issued');
            $url = $this->request->getPost('url');
            $description = $this->request->getPost('description');
            $existinglanguages = $this->request->getPost('existinglanguages');
            $extralanguages = $this->request->getPost('extralanguages');
            $publisher = $this->request->getPost('publisher');
            $existingkeywords = $this->request->getPost('existingkeywords');
            $extrakeywords = $this->request->getPost('extrakeywords');
            $title    = $this->request->getPost('title');
            $type = $this->request->getPost('type');
            $treaty = $this->request->getPost('treaty');
            $rights = $this->request->getPost('rights');
            $status = $this->request->getPost('status');
            $meeting_id = $this->request->getPost('meeting_id');
            $number = $this->request->getPost('number');
            $displayorder = $this->request->getPost('displayorder');
            $language = strtolower($this->request->getPost('language'));



            $existingkeywords = rtrim($existingkeywords, ",");
            $existinglanguages = rtrim($existinglanguages, ",");

            if($extrakeywords != '')
            {
                $extrakeywords = explode(",", $extrakeywords);
                $keyworddata = '[';
                for($i = 0; $i<= sizeof($extrakeywords)-2; $i ++ )
                {
                    $keyworddata .= '{"key":"dc.subject","value":"'.$extrakeywords[$i].'"},';
                }
                $keyworddata = rtrim($keyworddata, ",");

                $keyworddata .= ']';

            }
            if($extralanguages != '')
            {
                $extralanguages = explode(",", $extralanguages);
                $languagedata = '[';
                for($i = 0; $i<= sizeof($extralanguages)-2; $i ++ )
                {
                    $languagedata .= '{"key":"dc.language","value":"'.$extralanguages[$i].'"},';
                }
                $languagedata = rtrim($languagedata, ",");

                $languagedata .= ']';

            }

            $fileOneName = "";
            $fileOneTmp = "";
            $fileOneDesc = $language;

            //Check if the user has uploaded files
            if ($this->request->hasFiles() == true) {

                /*// Check if array is empty
                if (count($this->request->getUploadedFiles() ) == 0) {
                    return false;
                }
                //Print the real file names and their sizes
                //foreach($_FILES as $name => $file) {
                foreach ($this->request->getUploadedFiles() as $file)
                {
                    if (!empty($file->getTempName())) {
                        $fileOneName = $file->getName();
                        $fileOneTmp = $file->getTempName();
                        //echo $file->getName(), " ", $file->getSize(), "\n";
                        return true;
                    }
                }*/
            }

            if($token != NULL)
            {
                $headers = [
                    'Content-Type'=>'application/json',
                    'Accept'=>'application/json',
                    'rest-dspace-token' => $token
                ];

                $data = [

                    [
                        "key"=>"dc.contributor.author",
                        "value"=>$author
                    ],
                    [
                        "key"=>"dc.date.issued",
                        "value"=>$date_issued
                    ],
                    [
                        "key"=>"dc.identifier.uri",
                        "value"=>$url
                    ],
                    [
                        "key"=>"dc.description",
                        "value"=>$description
                    ],
                    [
                        "key"=>"dc.publisher",
                        "value"=>$publisher
                    ],
                    [
                        "key"=>"dc.title",
                        "value"=>$title
                    ],
                    [
                        "key"=>"dc.type",
                        "value"=>$type
                    ],
                    [
                        "key"=>"informea.identifier.treaty",
                        "value"=>$treaty
                    ],
                    [
                        "key"=>"informea.identifier.rights",
                        "value"=>$rights
                    ],
                    [
                        "key"=>"informea.identifier.status",
                        "value"=>$status
                    ],
                    [
                        "key"=>"informea.identifier.meeting-id",
                        "value"=>$meeting_id
                    ],
                    [
                        "key"=>"informea.identifier.number",
                        "value"=>$number
                    ],
                    [
                        "key"=>"informea.identifier.display-order",
                        "value"=>$displayorder
                    ]

                ];


                $metadata = ["metadata"=>$data];


                $addUrl = "https://stg-wedocs.unep.org/rest/collections/7/items";
                $metadata = json_encode($metadata);


                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $addUrl,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => $metadata,
                    CURLOPT_HTTPHEADER => array(
                        "cache-control: no-cache",
                        "content-type: application/json",
                        "postman-token: 642f2082-ffc1-c830-252c-1b85b18d0a6e",
                        "rest-dspace-token: ".$token

                    ),
                ));
                $response = curl_exec($curl);
                $err = curl_error($curl);
                if ($err) {
                    echo "cURL Error #:" . $err;
                } else {
                    //echo $response;


                }
                curl_close($curl);
                $item = new SimpleXMLElement($response);

                if($item->id != null)
                {
                    $itemID = $item->id;
                    $editUrl = "items/".$itemID;
                    $editUrl = "https://stg-wedocs.unep.org/rest/items/".$itemID."/metadata";


                    if($keyworddata != '')
                    {

                        $curl2 = curl_init();
                        curl_setopt_array($curl2, array(
                            CURLOPT_URL => $editUrl,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => "",
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 30,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => "POST",
                            CURLOPT_POSTFIELDS => $keyworddata,
                            CURLOPT_HTTPHEADER => array(
                                "cache-control: no-cache",
                                "content-type: application/json",
                                "rest-dspace-token: ".$token
                            ),
                        ));

                        $response = curl_exec($curl2);
                        $err = curl_error($curl2);
                        if ($err) {
                            echo "cURL Error #:" . $err;
                        } else {
                            echo $response;
                        }
                        curl_close($curl2);
                        echo $err;

                    }

                    if($languagedata != '')
                    {

                        $curl2 = curl_init();
                        curl_setopt_array($curl2, array(
                            CURLOPT_URL => $editUrl,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => "",
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 30,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => "POST",
                            CURLOPT_POSTFIELDS => $languagedata,
                            CURLOPT_HTTPHEADER => array(
                                "cache-control: no-cache",
                                "content-type: application/json",
                                "rest-dspace-token: ".$token
                            ),
                        ));

                        $response = curl_exec($curl2);
                        $err = curl_error($curl2);
                        if ($err) {
                            echo "cURL Error #:" . $err;
                        } else {
                            echo $response;
                        }
                        curl_close($curl2);
                        echo $err;

                    }
                }

                $decision = new Decisions();

                $decision->id = $item->id;
                $decision->type = $type;
                $decision->status = $status;
                $decision->number = $number;
                $decision->treaty = $treaty;
                $decision->published = date('Y-m-d', strtotime($date_issued));
                $decision->meetingId = $meeting_id;
                $decision->displayOrder = $display_order;
                $decision->author = $author;
                $decision->updated = date("Y-m-d H:i:s");

                $decision->save();

                $decision_content = new DecisionsContent();

                $decision_content->decision_id = $item->id;
                $decision_content->language = "Not Available";
                $decision_content->content = "Not Available";

                $decision_content->save();



                foreach ($this->request->getPost('exkeywords') as $keyword) {
                    $decision_keyword = new DecisionsKeywords();

                    $decision_keyword->decision_id = $item->id;
                    $decision_keyword->namespace = "Not Defined";
                    $decision_keyword->term = $keyword;

                    $decision_keyword->save();
                }

                $decision_summary = new DecisionsSummary();

                $decision_summary->decision_id = $item->id;
                $decision_summary->language = "Not Available";
                $decision_summary->summary = "Not Available";

                $decision_summary->save();

                $decision_title = new DecisionsTitle();

                $decision_title->decision_id = $item->id;
                $decision_title->language = "English";
                $decision_title->title = $title;

                $decision_title->save();

                $decision_long_title = new DecisionsLongtitle();

                $decision_long_title->decision_id = $item->id;
                $decision_long_title->language = "Not Available";
                $decision_long_title->long_title = "Not Available";



                //Check if the user has uploaded files
                if ($this->request->hasFiles() == true)
                {
                    $fileOneName = "";
                    $fileOneTmp = "";
                    $fileOneDesc = $language;

                    switch ($language) {
                        case 'ar':
                            $fileOneDesc = 'arabic';
                            break;
                        case 'zh':
                            $fileOneDesc = 'chinese';
                            break;
                        case 'en':
                            $fileOneDesc = 'english';
                            break;
                        case 'fr':
                            $fileOneDesc = 'french';
                            break;
                        case 'ru':
                            $fileOneDesc = 'russian';
                            break;
                        case 'es':
                            $fileOneDesc = 'spanish';
                            break;
                        default:
                            $fileOneDesc = 'english';
                    }
                    // Check if array is empty
                    if (count($this->request->getUploadedFiles() ) == 0) {
                        return false;
                    }
                    else
                    {
                        foreach ($this->request->getUploadedFiles() as $file)
                        {
                            $fileOneName = $file->getName();
                            $fileOneTmp = $file->getTempName();
                            if($fileOneName != null)
                            {
                                if($itemID != null)
                                {

                                    $headers = [
                                        'Content-Type'=>'application/pdf',
                                        'rest-dspace-token' => $token
                                    ];

                                    $url = "items/".$itemID."/bitstreams?name=" . $fileOneName . "&description=" . $fileOneDesc;

                                    $url = "https://stg-wedocs.unep.org/rest/items/".$itemID."/bitstreams" . "?name=" . $fileOneName . "&description=" . $fileOneDesc;


                                    $file_name_with_full_path = realpath($fileOneTmp);
                                    /* curl will accept an array here too.
                                     * Many examples I found showed a url-encoded string instead.
                                     * Take note that the 'key' in the array will be the key that shows up in the
                                     * $_FILES array of the accept script. and the at sign '@' is required before the
                                     * file name.
                                     */
                                    $filepost = array('extra_info' => '123456','file_contents'=>'@'.$file_name_with_full_path);


                                    $curl = curl_init();
                                    curl_setopt_array($curl, array(
                                        CURLOPT_URL => $url,
                                        CURLOPT_RETURNTRANSFER => true,
                                        CURLOPT_ENCODING => "",
                                        CURLOPT_MAXREDIRS => 10,
                                        CURLOPT_TIMEOUT => 30,
                                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                        CURLOPT_CUSTOMREQUEST => "POST",
                                        CURLOPT_POST => 1,
                                        CURLOPT_POSTFIELDS => $filepost,
                                        CURLOPT_HTTPHEADER => array(
                                            "cache-control: no-cache",
                                            "content-type: multipart/form-data",
                                            "contents : fopen($fileOneTmp, 'r')",
                                            "rest-dspace-token: ".$token
                                        ),
                                    ));

                                    $response = curl_exec($curl);
                                    $err = curl_error($curl);
                                    if ($err) {
                                        echo "cURL Error #:" . $err;
                                    } else {

                                        $response_item = new SimpleXMLElement($response);
                                        $file_id = $response_item->id;

                                        $decision_document = new DecisionsDocuments();

                                        $decision_document->id = "" + $file_id;
                                        $decision_document->url = "https://stg-wedocs.unep.org/rest". $response_item->retrieveLink;
                                        $decision_document->mimeType = "" . $response_item->mimeType;
                                        $decision_document->filename = "" . $response_item->name;					$decision_document->language = $language;

                                        $decision_document->save();
                                    }
                                    curl_close($curl);
                                }
                            }
                        }
                    }
                }




                // $decision->
                // Forward to the 'items' controller list
                $this->response->redirect("item/detail/".$itemID);
                $this->view->disable();


                return;


            }
        }

        $this->view->message = $message;

        $meetings = Meetings::find();
        $meetings_data = array();

        if ($meetings) {
            foreach ($meetings as $key => $value) {
                $meeting_title = MeetingTitle::findFirstByMeetingId($value->id);

                $meetings_data[] = [
                    'id'	=>	$value->id,
                    'text'	=>	$meeting_title->title
                ];
            }
        }

        $meetings_json = json_encode($meetings_data, JSON_NUMERIC_CHECK);
        $this->assets
            ->addCss('plugins/daterangepicker/daterangepicker-bs3.css')
            ->addCss('plugins/select2/select2.min.css');

        $this->assets
            ->addJs('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js', false)
            ->addJs('plugins/daterangepicker/daterangepicker.js')
            ->addJs('plugins/select2/select2.min.js');

        $this->view->title = "Decision Document";
        $this->view->id = $id;
        $this->view->rights_select = $this->createRightsSelect();
        $this->view->status_select = $this->createStatusSelect(NULL, "decision");
        $this->view->treaty_select = $this->createTreatiesSelect();
        $this->view->decision_types_select = $this->createDecisionTypeSelect();

        $custom_js = [
            'partial'=>'item/decision_javascript',
            'params'=>[
                'meetings' => $meetings_json
            ]
        ];

        $this->addJavaScript($custom_js);
    }

    public function editmeetingAction($itemID)
    {
        // echo $itemID;die;
        if ($this->request->isPost())
        {
            ini_set('max_execution_time', 120);
            $token = $this->session->get('token');

            // Get the data from the user
            $itemID    = $this->request->getPost('itemID');
            $title    = $this->request->getPost('title');
            $author = $this->request->getPost('author');
            $keywords = $this->request->getPost('keywords');
            $existingkeywords = $this->request->getPost('existingkeywords');
            $extrakeywords = $this->request->getPost('extrakeywords');
            $treaty = $this->request->getPost('treaty');
            $description = $this->request->getPost('description');
            $url = $this->request->getPost('url');
            $meeting_dates = $this->request->getPost('meeting-dates');
            $repetition = $this->request->getPost('repetition');
            $kind = $this->request->getPost('kind');
            $languages = $this->request->getPost('languages');
            $existinglanguages = $this->request->getPost('existinglanguages');
            $extralanguages = $this->request->getPost('extralanguages');
            $type = $this->request->getPost('document_type');
            $rights = $this->request->getPost('rights');
            $status = $this->request->getPost('status');
            $imageurl = $this->request->getPost('imageurl');
            $imagecopyright = $this->request->getPost('imagecopyright');
            $location = $this->request->getPost('location');
            $city = $this->request->getPost('city');
            $country = $this->request->getPost('country');
            $latitude = $this->request->getPost('latitude');
            $longitude = $this->request->getPost('longitude');



            $existingkeywords = rtrim($existingkeywords, ",");
            $existinglanguages = rtrim($existinglanguages, ",");

            $meetingdates = explode(" - ", $meeting_dates);
            $start = $meetingdates[0];
            $end = $meetingdates[1];
            if($existingkeywords != '')
            {
                $existingkeywords = explode(",", $existingkeywords);
                $existingkeyworddata = '[';
                for($i = 0; $i<= sizeof($existingkeywords)-1; $i ++ )
                {
                    $existingkeyworddata .= '{"key":"dc.subject","value":"'.$existingkeywords[$i].'"},';
                }
                $existingkeyworddata = rtrim($existingkeyworddata, ",");

                $existingkeyworddata .= ']';

            }
            if($extrakeywords != '')
            {
                $extrakeywords = explode(",", $extrakeywords);
                $keyworddata = '[';
                for($i = 0; $i<= sizeof($extrakeywords)-2; $i ++ )
                {
                    $keyworddata .= '{"key":"dc.subject","value":"'.$extrakeywords[$i].'"},';
                }
                $keyworddata = rtrim($keyworddata, ",");

                $keyworddata .= ']';

            }
            if($existinglanguages != '')
            {
                $existinglanguages = explode(",", $existinglanguages);
                $existinglanguagedata = '[';
                for($i = 0; $i<= sizeof($existinglanguages)-1; $i ++ )
                {
                    $existinglanguagedata .= '{"key":"dc.language","value":"'.$existinglanguages[$i].'"},';
                }
                $existinglanguagedata = rtrim($existinglanguagedata, ",");

                $existinglanguagedata .= ']';

            }
            if($extralanguages != '')
            {
                $extralanguages = explode(",", $extralanguages);
                $languagedata = '[';
                for($i = 0; $i<= sizeof($extralanguages)-2; $i ++ )
                {
                    $languagedata .= '{"key":"dc.language","value":"'.$extralanguages[$i].'"},';
                }
                $languagedata = rtrim($languagedata, ",");

                $languagedata .= ']';

            }
            $fileOneName = "";
            $fileOneTmp = "";
            $fileOneDesc = "";
            //Check if the user has uploaded files
            if ($this->request->hasFiles() == true) {
                //Print the real file names and their sizes
                foreach ($this->request->getUploadedFiles() as $file)
                {
                    //echo $file->getName(), " ", $file->getSize(), "\n";
                    $fileOneName = str_replace(" ", "_", $file->getName());
                    $fileOneTmp = $file->getTempName();

                }
            }

            if($token != NULL)
            {
                $headers = [
                    'Content-Type'=>'application/json',
                    'Accept'=>'application/json',
                    'rest-dspace-token' => $token
                ];

                $data = [
                    [
                        "key"=>"dc.title",
                        "value"=>$title
                    ],
                    [
                        "key"=>"dc.contributor.author",
                        "value"=>$author
                    ],
                    /*[
                        "key"=>"dc.subject.keywords", 
                        "value"=>$keywords
                    ],*/
                    [
                        "key"=>"informea.identifier.treaty",
                        "value"=>$treaty
                    ],
                    [
                        "key"=>"dc.description",
                        "value"=>$description
                    ],
                    [
                        "key"=>"dc.identifier.uri",
                        "value"=>$url
                    ],
                    [
                        "key"=>"informea.identifier.date-start",
                        "value"=>$start
                    ],
                    [
                        "key"=>"informea.identifier.date-end",
                        "value"=>$end
                    ],
                    [
                        "key"=>"informea.identifier.repetition",
                        "value"=>$repetition
                    ],
                    [
                        "key"=>"informea.identifier.kind",
                        "value"=>$kind
                    ],
                    /*[
                        "key"=>"dc.identifier.languages", 
                        "value"=>$languages
                    ],*/
                    [
                        "key"=>"dc.type",
                        "value"=>$type
                    ],
                    [
                        "key"=>"informea.identifier.rights",
                        "value"=>$rights
                    ],
                    [
                        "key"=>"informea.identifier.status",
                        "value"=>$status
                    ],
                    [
                        "key"=>"informea.identifier.imageurl",
                        "value"=>$imageurl
                    ],
                    [
                        "key"=>"informea.identifier.imagecopyright",
                        "value"=>$imagecopyright
                    ],
                    [
                        "key"=>"informea.identifier.location",
                        "value"=>$location
                    ],
                    [
                        "key"=>"informea.identifier.city",
                        "value"=>$city
                    ],
                    [
                        "key"=>"informea.identifier.country",
                        "value"=>$country
                    ],
                    [
                        "key"=>"informea.identifier.latitude",
                        "value"=>$latitude
                    ],
                    [
                        "key"=>"informea.identifier.longitude",
                        "value"=>$longitude
                    ],



                ];


                $metadata = ["metadata"=>$data];


                $editUrl = "items/".$itemID;
                $editUrl = "https://stg-wedocs.unep.org/rest/items/".$itemID."/metadata";

                $datapost = json_encode($data);



                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $editUrl,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "PUT",
                    CURLOPT_POSTFIELDS => $datapost,
                    CURLOPT_HTTPHEADER => array(
                        "cache-control: no-cache",
                        "content-type: application/json",
                        "rest-dspace-token: ".$token
                    ),
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);
                if ($err) {
                    echo "cURL Error #:" . $err;
                } else {
                    echo $response;
                }
                curl_close($curl);


                if($existingkeyworddata != '')
                {


                    $curl2 = curl_init();
                    curl_setopt_array($curl2, array(
                        CURLOPT_URL => $editUrl,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "PUT",
                        CURLOPT_POSTFIELDS => $existingkeyworddata,
                        CURLOPT_HTTPHEADER => array(
                            "cache-control: no-cache",
                            "content-type: application/json",
                            "rest-dspace-token: ".$token
                        ),
                    ));

                    $response = curl_exec($curl2);
                    $err = curl_error($curl2);
                    if ($err) {
                        echo "cURL Error #:" . $err;
                    } else {
                        echo $response;
                    }
                    curl_close($curl2);
                    echo $err;

                }
                if($keyworddata != '')
                {

                    $curl2 = curl_init();
                    curl_setopt_array($curl2, array(
                        CURLOPT_URL => $editUrl,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => $keyworddata,
                        CURLOPT_HTTPHEADER => array(
                            "cache-control: no-cache",
                            "content-type: application/json",
                            "rest-dspace-token: ".$token
                        ),
                    ));

                    $response = curl_exec($curl2);
                    $err = curl_error($curl2);
                    if ($err) {
                        echo "cURL Error #:" . $err;
                    } else {
                        echo $response;
                    }
                    curl_close($curl2);
                    echo $err;

                }


                if($existinglanguagedata != '')
                {

                    $curl2 = curl_init();
                    curl_setopt_array($curl2, array(
                        CURLOPT_URL => $editUrl,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "PUT",
                        CURLOPT_POSTFIELDS => $existinglanguagedata,
                        CURLOPT_HTTPHEADER => array(
                            "cache-control: no-cache",
                            "content-type: application/json",
                            "rest-dspace-token: ".$token
                        ),
                    ));

                    $response = curl_exec($curl2);
                    $err = curl_error($curl2);
                    if ($err) {
                        echo "cURL Error #:" . $err;
                    } else {
                        echo $response;
                    }
                    curl_close($curl2);
                    echo $err;

                }

                if($languagedata != '')
                {

                    $curl2 = curl_init();
                    curl_setopt_array($curl2, array(
                        CURLOPT_URL => $editUrl,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => $languagedata,
                        CURLOPT_HTTPHEADER => array(
                            "cache-control: no-cache",
                            "content-type: application/json",
                            "rest-dspace-token: ".$token
                        ),
                    ));

                    $response = curl_exec($curl2);
                    $err = curl_error($curl2);
                    if ($err) {
                        echo "cURL Error #:" . $err;
                    } else {
                        echo $response;
                    }
                    curl_close($curl2);
                    echo $err;

                }


                if($itemID != null){

                    $headers = [
                        'Content-Type'=>'application/pdf',
                        'rest-dspace-token' => $token
                    ];

                    $url = "items/".$itemID."/bitstreams" . "?name=" . $fileOneName . "&description=" . $fileOneDesc;
                    $url = "https://stg-wedocs.unep.org/rest/items/".$itemID."/bitstreams" . "?name=" . $fileOneName . "&description=" . $fileOneDesc;


                    $file_name_with_full_path = realpath($fileOneTmp);
                    /* curl will accept an array here too.
                     * Many examples I found showed a url-encoded string instead.
                     * Take note that the 'key' in the array will be the key that shows up in the
                     * $_FILES array of the accept script. and the at sign '@' is required before the
                     * file name.
                     */
                    $filepost = array('extra_info' => '123456','file_contents'=>'@'.$file_name_with_full_path);


                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => $url,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POST => 1,
                        CURLOPT_POSTFIELDS => $filepost,
                        CURLOPT_HTTPHEADER => array(
                            "cache-control: no-cache",
                            "content-type: multipart/form-data",
                            "contents : fopen($fileOneTmp, 'r')",
                            "rest-dspace-token: ".$token
                        ),
                    ));

                    $response = curl_exec($curl);
                    $err = curl_error($curl);
                    if ($err) {
                        echo "cURL Error #:" . $err;
                    } else {
                        echo $response;
                    }
                    curl_close($curl);
                }

                $meeting = Meetings::findFirstById($itemID);

                $updated = date('Y-m-d h:i:s');

                $meeting->treaty			=	$treaty;
                $meeting->url 				=	$post_url;
                $meeting->start 			=	$start;
                $meeting->end 				=	$end;
                $meeting->repetition 		=	$repetition;
                $meeting->kind 				=	$kind;
                $meeting->type 				=	$type;
                $meeting->access 			=	$rights;
                $meeting->status 			=	$status;
                $meeting->imageurl 			=	$imageurl;
                $meeting->imageCopyright	=	$imageCopyright;
                $meeting->location 			=	$location;
                $meeting->city 				=	$city;
                $meeting->country 			=	$country;
                $meeting->latitude			=	$latitude;
                $meeting->longitude			=	$longitude;
                $meeting->updated 			=	$updated;
                $meeting->author 			=	$author;

                // echo "<pre>";print_r($meeting);die;

                $meeting->update();

                // die;

                $meeting_title = MeetingTitle::findFirstByMeetingId($itemID);

                if ($meeting_title) {
                    $meeting_title->title = $title;
                }
                else
                {
                    $meeting_title = new MeetingTitle();

                    $meeting_title->meeting_id = $itemID;
                    $meeting_title->title = $title;
                    $meeting_title->language = "English";
                }

                // echo "<pre>";print_r($meeting_title);die;
                $meeting_title->save();

                // die;

                $meeting_description = MeetingsDescription::findFirstByMeetingId($itemID);

                if($meeting_description)
                {
                    $meeting_description->description = $description;
                }
                else
                {
                    $meeting_description = new MeetingsDescription();
                    $meeting_description->meeting_id = $itemID;
                    $meeting_description->language = "English";
                    $meeting_description->description = $description;
                }

                $meeting_description->save();

                // echo "<pre>";print_r($meeting_title);die;
                // Forward to the 'items' controller list
                $this->response->redirect("item/detail/".$itemID);
                $this->view->disable();


                return;

            }
        }
        else
        {
            $url = "items/". $itemID."?expand=all";

            $res = $this->dspace->get($url, ['verify' => false]);

            $data = json_decode($res->getBody());

            $languages = $keywords = $treaty = $repetition = $kind = $type = $rights = $status = $country = "";

            foreach ($data as $key => $value) {
                if ($key == "metadata") {
                    foreach ($value as $k => $v) {
                        if ($v->key == "dc.language") {
                            $languages .= "<li>" . $v->value . "</li>";
                        }

                        if ($v->key == "dc.subject") {
                            $keywords.= "<li>" . $v->value . "</li>";
                        }

                        if ($v->key == "informea.identifier.treaty") {
                            $treaty = $v->value;
                        }

                        if($v->key == "informea.identifier.repetition")
                        {
                            $repetition = $v->value;
                        }

                        if($v->key == "informea.identifier.kind")
                        {
                            $kind = $v->value;
                        }

                        if ($v->key == "dc.type") {
                            $type = $v->value;
                        }

                        if ($v->key == "informea.identifier.rights") {
                            $rights = $v->value;
                        }

                        if ($v->key == "informea.identifier.status") {
                            $status = $v->value;
                        }

                        if ($v->key == "informea.identifier.country") {
                            $country = $v->value;
                        }

                    }
                }
            }

            $value = get_object_vars($data);

            // echo "<pre>";print_r($value);die;

            $this->view->title = "Item Details";
            $this->view->subject_keywords = $keywords;
            $this->view->languages_list = $languages;
            $this->view->doc = $data->name;
            $this->view->itemID = $itemID;

            $this->view->data = $value;
            $this->view->values = $data;
            $this->view->treaties_select = $this->createTreatiesSelect($treaty);
            $this->view->repetition_select = $this->createRepetitonsSelect($repetition);
            $this->view->kinds_select = $this->createKindSelect($kind);
            $this->view->types_select = $this->createMeetingTypeSelect($type);
            $this->view->rights_select = $this->createRightsSelect($rights);
            $this->view->status_select = $this->createStatusSelect($status);
            $this->view->countries_select = $this->createCountrySelect($country);

            // Add some local CSS resources
            $this->assets
                ->addCss('plugins/select2/select2.min.css')
                ->addCss('plugins/daterangepicker/daterangepicker-bs3.css');

            // And some local JavaScript resources
            $this->assets
                ->addJs('plugins/input-mask/jquery.inputmask.js')
                ->addJs('plugins/input-mask/jquery.inputmask.date.extensions.js')
                ->addJs('plugins/input-mask/jquery.inputmask.extensions.js')
                ->addJs('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js', false)
                ->addJs('plugins/daterangepicker/daterangepicker.js')
                ->addJs('plugins/select2/select2.min.js');



            $custom_js = [
                'partial'=>'item/meeting_javascript',
                'params'=>[]
            ];

            $this->addJavaScript($custom_js);
        }
    }

    public function editcontactAction($itemID)
    {
        // echo $token;die;
        if ($this->request->isPost())
        {
            ini_set('max_execution_time', 120);
            $token = $this->session->get('token');

            // Get the data from the user
            $itemID    = $this->request->getPost('itemID');
            $author = $this->request->getPost('author');
            $title    = $this->request->getPost('title');
            $treaty = $this->request->getPost('treaty');
            $department = $this->request->getPost('department');
            $email = $this->request->getPost('email');
            $firstname = $this->request->getPost('firstname');
            $lastname = $this->request->getPost('lastname');
            $organizations = $this->request->getPost('organizations');
            $phone = $this->request->getPost('phone');
            $position = $this->request->getPost('position');
            $prefix = $this->request->getPost('prefix');
            $type = $this->request->getPost('type');
            $address = $this->request->getPost('address');
            $country = $this->request->getPost('country');
            $fax = $this->request->getPost('fax');
            $location = $this->request->getPost('location');
            $status = $this->request->getPost('status');



            $fileOneName = "";
            $fileOneTmp = "";
            $fileOneDesc = "";

            //Check if the user has uploaded files
            if ($this->request->hasFiles() == true) {
                //Print the real file names and their sizes
                foreach ($this->request->getUploadedFiles() as $file)
                {
                    //echo $file->getName(), " ", $file->getSize(), "\n";
                    $fileOneName = $file->getName();
                    $fileOneTmp = $file->getTempName();
                }
            }

            if($token != NULL)
            {
                $headers = [
                    'Content-Type'=>'application/json',
                    'Accept'=>'application/json',
                    'rest-dspace-token' => $token
                ];

                $data = [
                    [
                        "key"=>"dc.contributor.author",
                        "value"=>$author
                    ],
                    [
                        "key"=>"dc.title",
                        "value"=>$title
                    ],
                    [
                        "key"=>"informea.identifier.treaty",
                        "value"=>$treaty
                    ],
                    [
                        "key"=>"informea.contact.department",
                        "value"=>$department
                    ],
                    [
                        "key"=>"informea.contact.email",
                        "value"=>$email
                    ],
                    [
                        "key"=>"informea.contact.firstname",
                        "value"=>$firstname
                    ],
                    [
                        "key"=>"informea.contact.lastname",
                        "value"=>$lastname
                    ],
                    [
                        "key"=>"informea.contact.organizations",
                        "value"=>$organizations
                    ],
                    [
                        "key"=>"informea.contact.phone",
                        "value"=>$phone
                    ],
                    [
                        "key"=>"informea.contact.position",
                        "value"=>$position
                    ],
                    [
                        "key"=>"informea.contact.prefix",
                        "value"=>$prefix
                    ],
                    [
                        "key"=>"informea.contact.type",
                        "value"=>$type
                    ],
                    [
                        "key"=>"informea.contact.address",
                        "value"=>$address
                    ],
                    [
                        "key"=>"informea.contact.country",
                        "value"=>$country
                    ],
                    /*[
                        "key"=>"informea.contact.location", 
                        "value"=>$location
                    ],
                    [
                        "key"=>"informea.contact.status", 
                        "value"=>$status
                    ],*/
                    [
                        "key"=>"informea.contact.fax",
                        "value"=>$fax
                    ]

                ];


                $metadata = ["metadata"=>$data];


                $editUrl = "items/".$itemID;
                $editUrl = "https://stg-wedocs.unep.org/rest/items/".$itemID."/metadata";

                $datapost = json_encode($data);


                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $editUrl,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "PUT",
                    CURLOPT_POSTFIELDS => $datapost,
                    CURLOPT_HTTPHEADER => array(
                        "cache-control: no-cache",
                        "content-type: application/json",
                        "rest-dspace-token: ".$token
                    ),
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);
                if ($err) {
                    echo "cURL Error #:" . $err;
                } else {
                    echo $response;
                }
                curl_close($curl);



                // Forward to the 'items' controller list

                $contact = Contacts::findFirst($itemID);

                $contact->firstName = $firstname;
                $contact->lastName = $lastname;
                $contact->prefix = $prefix;
                $contact->country = $country;
                $contact->position = $position;
                $contact->institution = $organizations;
                $contact->department = $department;
                $contact->type = $type;
                $contact->address = $address;
                $contact->email = $email;
                $contact->phoneNumber = $phone;
                $contact->fax = $fax;
                $contact->author = $author;

                $contact->update();

                $contact_treaty = ContactTreaty::findFirstByContactId($itemID);

                $contact_treaty->contact_id = $itemID;
                $contact_treaty->treaty = $treaty;

                $contact_treaty->update();
                $this->response->redirect("item/detail/".$itemID);
                $this->view->disable();


                return;

            }
        }
        else
        {
            $url = "items/". $itemID."?expand=all";

            $res = $this->dspace->get($url, ['verify' => false]);

            $data = json_decode($res->getBody());

            $value = get_object_vars($data);

            $this->view->title = "Item Details";

            $this->view->doc = $data->name;
            $this->view->itemID = $itemID;

            $this->view->data = $value;
            $this->view->values = $data;

            // Add some local CSS resources
            $this->assets
                ->addCss('plugins/daterangepicker/daterangepicker-bs3.css');

            // And some local JavaScript resources
            $this->assets
                ->addJs('plugins/input-mask/jquery.inputmask.js')
                ->addJs('plugins/input-mask/jquery.inputmask.date.extensions.js')
                ->addJs('plugins/input-mask/jquery.inputmask.extensions.js')
                ->addJs('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js', false)
                ->addJs('plugins/daterangepicker/daterangepicker.js');



            $custom_js = [
                'partial'=>'item/contact_javascript',
                'params'=>[]
            ];

            $this->addJavaScript($custom_js);
        }
    }

    public function editdecisionAction($itemID)
    {
        if ($this->request->isPost())
        {
            ini_set('max_execution_time', 120);
            $token = $this->session->get('token');

            // Get the data from the user
            $author = $this->request->getPost('author');
            $date_issued = $this->request->getPost('date_issued');
            $url = $this->request->getPost('url');
            $description = $this->request->getPost('description');
            $existinglanguages = $this->request->getPost('existinglanguages');
            $extralanguages = $this->request->getPost('extralanguages');
            $publisher = $this->request->getPost('publisher');
            $existingkeywords = $this->request->getPost('existingkeywords');
            $extrakeywords = $this->request->getPost('extrakeywords');
            $title    = $this->request->getPost('title');
            $type = $this->request->getPost('type');
            $treaty = $this->request->getPost('treaty');
            $rights = $this->request->getPost('rights');
            $status = $this->request->getPost('status');
            $meeting_id = $this->request->getPost('meeting_id');
            $number = $this->request->getPost('number');
            $displayorder = $this->request->getPost('displayorder');
            $language = strtolower($this->request->getPost('language'));


            $existingkeywords = rtrim($existingkeywords, ",");
            $existinglanguages = rtrim($existinglanguages, ",");

            $meetingdates = explode(" - ", $meeting_dates);
            $start = $meetingdates[0];
            $end = $meetingdates[1];
            if($existingkeywords != '')
            {
                $existingkeywords = explode(",", $existingkeywords);
                $existingkeyworddata = '[';
                for($i = 0; $i<= sizeof($existingkeywords)-1; $i ++ )
                {
                    $existingkeyworddata .= '{"key":"dc.subject","value":"'.$existingkeywords[$i].'"},';
                }
                $existingkeyworddata = rtrim($existingkeyworddata, ",");

                $existingkeyworddata .= ']';

            }
            if($extrakeywords != '')
            {
                $extrakeywords = explode(",", $extrakeywords);
                $keyworddata = '[';
                for($i = 0; $i<= sizeof($extrakeywords)-2; $i ++ )
                {
                    $keyworddata .= '{"key":"dc.subject","value":"'.$extrakeywords[$i].'"},';
                }
                $keyworddata = rtrim($keyworddata, ",");

                $keyworddata .= ']';

            }
            if($existinglanguages != '')
            {
                $existinglanguages = explode(",", $existinglanguages);
                $existinglanguagedata = '[';
                for($i = 0; $i<= sizeof($existinglanguages)-1; $i ++ )
                {
                    $existinglanguagedata .= '{"key":"dc.language","value":"'.$existinglanguages[$i].'"},';
                }
                $existinglanguagedata = rtrim($existinglanguagedata, ",");

                $existinglanguagedata .= ']';

            }
            if($extralanguages != '')
            {
                $extralanguages = explode(",", $extralanguages);
                $languagedata = '[';
                for($i = 0; $i<= sizeof($extralanguages)-2; $i ++ )
                {
                    $languagedata .= '{"key":"dc.language","value":"'.$extralanguages[$i].'"},';
                }
                $languagedata = rtrim($languagedata, ",");

                $languagedata .= ']';

            }

            $fileOneName = "";
            $fileOneTmp = "";
            $fileOneDesc = $language;

            //Check if the user has uploaded files
            if ($this->request->hasFiles() == true) {
                //Print the real file names and their sizes
                foreach ($this->request->getUploadedFiles() as $file)
                {
                    //echo $file->getName(), " ", $file->getSize(), "\n";
                    $fileOneName = $file->getName();
                    $fileOneTmp = $file->getTempName();
                }
            }

            if($token != NULL)
            {
                $headers = [
                    'Content-Type'=>'application/json',
                    'Accept'=>'application/json',
                    'rest-dspace-token' => $token
                ];

                $data = [

                    [
                        "key"=>"dc.contributor.author",
                        "value"=>$author
                    ],
                    [
                        "key"=>"dc.date.issued",
                        "value"=>$date_issued
                    ],
                    [
                        "key"=>"dc.identifier.uri",
                        "value"=>$url
                    ],
                    [
                        "key"=>"dc.description",
                        "value"=>$description
                    ],
                    [
                        "key"=>"dc.publisher",
                        "value"=>$publisher
                    ],
                    [
                        "key"=>"dc.title",
                        "value"=>$title
                    ],
                    [
                        "key"=>"dc.type",
                        "value"=>$type
                    ],
                    [
                        "key"=>"informea.identifier.treaty",
                        "value"=>$treaty
                    ],
                    [
                        "key"=>"informea.identifier.rights",
                        "value"=>$rights
                    ],
                    [
                        "key"=>"informea.identifier.status",
                        "value"=>$status
                    ],
                    [
                        "key"=>"informea.identifier.meeting-id",
                        "value"=>$meeting_id
                    ],
                    [
                        "key"=>"informea.identifier.number",
                        "value"=>$number
                    ],
                    [
                        "key"=>"informea.identifier.display-order",
                        "value"=>$displayorder
                    ]

                ];


                $metadata = ["metadata"=>$data];


                $editUrl = "items/".$itemID;
                $editUrl = "https://stg-wedocs.unep.org/rest/items/".$itemID."/metadata";

                $datapost = json_encode($data);


                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $editUrl,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "PUT",
                    CURLOPT_POSTFIELDS => $datapost,
                    CURLOPT_HTTPHEADER => array(
                        "cache-control: no-cache",
                        "content-type: application/json",
                        "rest-dspace-token: ".$token
                    ),
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);
                if ($err) {
                    echo "cURL Error #:" . $err;
                } else {
                    echo $response;
                }
                curl_close($curl);

                if($keyworddata != '')
                {

                    $curl2 = curl_init();
                    curl_setopt_array($curl2, array(
                        CURLOPT_URL => $editUrl,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => $keyworddata,
                        CURLOPT_HTTPHEADER => array(
                            "cache-control: no-cache",
                            "content-type: application/json",
                            "rest-dspace-token: ".$token
                        ),
                    ));

                    $response = curl_exec($curl2);
                    $err = curl_error($curl2);
                    if ($err) {
                        echo "cURL Error #:" . $err;
                    } else {
                        echo $response;
                    }
                    curl_close($curl2);
                    echo $err;

                }

                if($existingkeyworddata != '')
                {

                    $curl2 = curl_init();
                    curl_setopt_array($curl2, array(
                        CURLOPT_URL => $editUrl,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "PUT",
                        CURLOPT_POSTFIELDS => $existingkeyworddata,
                        CURLOPT_HTTPHEADER => array(
                            "cache-control: no-cache",
                            "content-type: application/json",
                            "rest-dspace-token: ".$token
                        ),
                    ));

                    $response = curl_exec($curl2);
                    $err = curl_error($curl2);
                    if ($err) {
                        echo "cURL Error #:" . $err;
                    } else {
                        echo $response;
                    }
                    curl_close($curl2);
                    echo $err;

                }

                if($languagedata != '')
                {

                    $curl2 = curl_init();
                    curl_setopt_array($curl2, array(
                        CURLOPT_URL => $editUrl,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => $languagedata,
                        CURLOPT_HTTPHEADER => array(
                            "cache-control: no-cache",
                            "content-type: application/json",
                            "rest-dspace-token: ".$token
                        ),
                    ));

                    $response = curl_exec($curl2);
                    $err = curl_error($curl2);
                    if ($err) {
                        echo "cURL Error #:" . $err;
                    } else {
                        echo $response;
                    }
                    curl_close($curl2);
                    echo $err;

                }


                if($existinglanguagedata != '')
                {

                    $curl2 = curl_init();
                    curl_setopt_array($curl2, array(
                        CURLOPT_URL => $editUrl,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "PUT",
                        CURLOPT_POSTFIELDS => $existinglanguagedata,
                        CURLOPT_HTTPHEADER => array(
                            "cache-control: no-cache",
                            "content-type: application/json",
                            "rest-dspace-token: ".$token
                        ),
                    ));

                    $response = curl_exec($curl2);
                    $err = curl_error($curl2);
                    if ($err) {
                        echo "cURL Error #:" . $err;
                    } else {
                        echo $response;
                    }
                    curl_close($curl2);
                    echo $err;

                }

                //Check if the user has uploaded files

                if ($this->request->hasFiles() == true) {

                    $fileOneName = "";
                    $fileOneTmp = "";
                    $fileOneDesc = $language;
                    switch ($language) {
                        case 'ar':
                            $fileOneDesc = 'arabic';
                            break;
                        case 'zh':
                            $fileOneDesc = 'chinese';
                            break;
                        case 'en':
                            $fileOneDesc = 'english';
                            break;
                        case 'fr':
                            $fileOneDesc = 'french';
                            break;
                        case 'ru':
                            $fileOneDesc = 'russian';
                            break;
                        case 'es':
                            $fileOneDesc = 'spanish';
                            break;
                        default:
                            $fileOneDesc = 'english';
                    }
                    foreach ($this->request->getUploadedFiles() as $file)
                    {

                        $fileOneName = $file->getName();
                        $fileOneTmp = $file->getTempName();
                        if($fileOneName != null)
                        {
                            if($itemID != null)
                            {

                                $headers = [
                                    'Content-Type'=>'application/pdf',
                                    'rest-dspace-token' => $token
                                ];

                                $url = "items/".$itemID."/bitstreams?name=" . $fileOneName . "&description=" . $fileOneDesc;

                                $url = "https://stg-wedocs.unep.org/rest/items/".$itemID."/bitstreams" . "?name=" . $fileOneName . "&description=" . $fileOneDesc;


                                $file_name_with_full_path = realpath($fileOneTmp);
                                /* curl will accept an array here too.
                                 * Many examples I found showed a url-encoded string instead.
                                 * Take note that the 'key' in the array will be the key that shows up in the
                                 * $_FILES array of the accept script. and the at sign '@' is required before the
                                 * file name.
                                 */
                                $filepost = array('extra_info' => '123456','file_contents'=>'@'.$file_name_with_full_path);


                                $curl = curl_init();
                                curl_setopt_array($curl, array(
                                    CURLOPT_URL => $url,
                                    CURLOPT_RETURNTRANSFER => true,
                                    CURLOPT_ENCODING => "",
                                    CURLOPT_MAXREDIRS => 10,
                                    CURLOPT_TIMEOUT => 30,
                                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                    CURLOPT_CUSTOMREQUEST => "POST",
                                    CURLOPT_POST => 1,
                                    CURLOPT_POSTFIELDS => $filepost,
                                    CURLOPT_HTTPHEADER => array(
                                        "cache-control: no-cache",
                                        "content-type: multipart/form-data",
                                        "contents : fopen($fileOneTmp, 'r')",
                                        "rest-dspace-token: ".$token
                                    ),
                                ));

                                $response = curl_exec($curl);
                                $err = curl_error($curl);
                                if ($err) {
                                    echo "cURL Error #:" . $err;
                                } else {

                                    $response_item = new SimpleXMLElement($response);
                                    $file_id = $response_item->id;
                                    $decision_document = new DecisionsDocuments();

                                    $decision_document->id = "" + $file_id;
                                    $decision_document->decision_id = "" + $itemID;
                                    $decision_document->url = "https://stg-wedocs.unep.org/rest". $response_item->retrieveLink;
                                    $decision_document->mimeType = "" . $response_item->mimeType;
                                    $decision_document->filename = "" . $response_item->name;					$decision_document->language = $language;

                                    $decision_document->save();
                                }
                                curl_close($curl);
                            }
                        }

                    }
                }

                // Forward to the 'items' controller list

                $decisions = Decisions::findFirstById($itemID);

                $decisions->type = $type;
                $decisions->treaty = $treaty;
                $decisions->status = $status;
                $decisions->number = $number;
                $decisions->published = $date_issued;
                $decisions->meetingId = $meeting_id;
                $decisions->updated = date('Y-m-d h:i:s');
                $decisions->author = $author;

                $decisions->update();

                $decisions_title = DecisionsTitle::findFirstByDecisionId($itemID);

                if ($decisions_title) {
                    $decisions_title->title = $title;
                }
                else{
                    $decisions_title->language = "English";
                    $decisions_title->title = $title;
                    $decisions_title->decision_id = $itemID;
                }

                $decisions_title->save();

                $extrakeywords = explode(',', $keywords);

                for ($i=0; $i < count($extrakeywords); $i++) {
                    $decision_keywords = new DecisionsKeywords();
                    $decision_keywords->decision_id = $itemID;
                    $decision_keywords->namespace = "Not Defined";
                    $decision_keywords->term = $extrakeywords[$i];

                    $decision_keywords->save();
                }

                $this->response->redirect("item/detail/".$itemID);


                return;

            }
        }
        else
        {
            $url = "items/". $itemID."?expand=all";
            $res = $this->dspace->get($url, ['verify' => false]);

            $data = json_decode($res->getBody());

            $value = get_object_vars($data);
            $metadata = $value['metadata'];
            $multi_data = array('languages' => [], 'subject' => []);

            $rights = $status = $treaty = $type = "";
            foreach ($metadata as $key => $v) {

                if ($v->key == "dc.language") {
                    $multi_data['languages'][] = $v->value;
                }
                else if($v->key == "dc.subject"){
                    $multi_data['subject'][] = $v->value;
                }

                if($v->key == "informea.identifier.rights")
                {
                    $rights = $v->value;
                }

                if($v->key == "informea.identifier.status")
                {
                    $status = $v->value;
                }

                if($v->key == "informea.identifier.treaty")
                {
                    $treaty = $v->value;
                }

                if ($v->key == "dc.type") {
                    $type = $v->value;
                }
            }
            $this->view->multi_data = $multi_data;

            $this->view->title = "Item Details";

            $this->view->doc = $data->name;
            $this->view->itemID = $itemID;

            $this->view->data = $value;
            $this->view->values = $data;
            $this->view->rights_select = $this->createRightsSelect($rights);
            $this->view->status_select = $this->createStatusSelect($status, "decision");
            $this->view->treaty_select = $this->createTreatiesSelect($treaty);
            $this->view->decision_types_select = $this->createDecisionTypeSelect($type);

            // Add some local CSS resources
            $this->assets
                ->addCss('plugins/daterangepicker/daterangepicker-bs3.css')
                ->addCss('plugins/select2/select2.min.css');

            // And some local JavaScript resources
            $this->assets
                ->addJs('plugins/input-mask/jquery.inputmask.js')
                ->addJs('plugins/input-mask/jquery.inputmask.date.extensions.js')
                ->addJs('plugins/input-mask/jquery.inputmask.extensions.js')
                ->addJs('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js', false)
                ->addJs('plugins/daterangepicker/daterangepicker.js')
                ->addJs('plugins/select2/select2.min.js');


            $author = $this->session->get("auth")["user_type"];



            $meetings = Meetings::findByAuthor($author['name']);
            $meeting_array = array();

            $array =  (array) $meetings;
            //echo '<pre>'; print_r($array);

            if ($meetings)
            {
                /*foreach ($meetings as $meeting)
                {
                    $meeting_title = MeetingTitle::findFirstByMeetingId($meeting->id);
                    $meeting_array[]  = ['id' => $meeting->id, 'text' => $meeting_title->title];

                }

                foreach($array as $key => $value)
                {
                    print "$key => $value\n"; echo 'meetings_json';
                }*/
            }
            $meetings_json = json_encode($meeting_array, JSON_NUMERIC_CHECK);
            /*echo $meetings_json;
             print_r($meeting_array);
             die;*/
            $custom_js = [
                'partial'=>'item/decision_javascript',
                'params'=>['meetings' => $meetings_json]
            ];

            $this->addJavaScript($custom_js);
        }
    }


    public function deleteAction($itemID)
    {

        ini_set('max_execution_time', 120);
        $token = $this->session->get('token');

        $url = "items/". $itemID ."/?expand=all";

        $res = $this->dspace->get($url, ['verify' => false]);

        $result = json_decode($res->getBody(), true);

        $collection = $result['parentCollection']['id'];

        if($token != NULL)
        {
            $url = "https://stg-wedocs.unep.org/rest/items/".$itemID;

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "DELETE",
                CURLOPT_POSTFIELDS => "",
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache",
                    "content-type: application/json",
                    "postman-token: c9ab95bc-c2f6-ed28-8843-86957de25697",
                    "rest-dspace-token: " .$token
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                echo $response;
                switch ($collection) {
                    case 6:
                        $action = "meeting";
                        $meeting_title = MeetingTitle::findByMeetingId($itemID);

                        if($meeting_title){
                            if($meeting_title->delete() == false)
                            {
                                foreach ($meeting_title->getMessages() as $message) {
                                    echo $message . "\n";
                                }die;
                            }
                        }

                        $meeting_description = MeetingsDescription::findByMeetingId($itemID);
                        if($meeting_description){
                            if($meeting_description->delete() == false)
                            {
                                foreach ($meeting_description->getMessages() as $message) {
                                    echo $message . "\n";
                                }die;
                            }
                        }

                        $meeting = Meetings::findFirst($itemID);

                        if($meeting->delete() == false)
                        {
                            foreach ($meeting->getMessages() as $message) {
                                echo $message . "\n";
                            }die;
                        }
                        break;
                    case 7:
                        $action =  "decision";

                        $decision = Decisions::findFirst($itemID);

                        $decision_content = DecisionsContent::findFirstByDecisionId($itemID);

                        $decision_keywords = DecisionsKeywords::findFirstByDecisionId($itemID);

                        $decision_long_title = DecisionsLongtitle::findFirstByDecisionId($itemID);

                        $decision_summary = DecisionsSummary::findFirstByDecisionId($itemID);

                        $decisions_title = DecisionsTitle::findFirstByDecisionId($itemID);

                        if($decision){
                            $decision->delete();
                        }

                        if($decision_content){
                            $decision_content->delete();
                        }

                        if($decision_long_title){
                            $decision_long_title->delete();
                        }

                        if($decision_summary){
                            $decision_summary->delete();
                        }

                        if($decision_title){
                            $decision_title->delete();
                        }

                        if($decision_keywords){
                            $decision_keywords->delete();
                        }

                        break;
                    case 12:
                        $action =  "contact";

                        $contact = Contacts::findFirst($itemID);

                        if($contact)
                        {
                            $contact->delete();
                        }
                        break;
                    case 11:

                        $country_report = CountryReports::findFirst($itemID);

                        $country_report->delete();

                        $country_reports_title = CountryReportsTitle::findFirstByCountryReportId($itemID);

                        $country_reports_title->delete();
                        break;
                    default:
                        $action =  "meeting";
                }
            }

            // Forward to the 'items' controller list
            $this->response->redirect("category/list/".$collection);
            $this->view->disable();

            return;

        }
    }

    public function filedeleteAction($itemID)
    {

        ini_set('max_execution_time', 120);
        $token = $this->session->get('token');
        $fileid = explode("-", $itemID);
        $id = $fileid[0];
        $itemID = $fileid[1];

        try
        {
            $decision_document = DecisionsDocuments::findFirstById($id);
        }
        catch (Exception $e)
        {
            $msg = array(
                'type'=>'Error',
                'code'=>0,
                'text'=>$e->getMessage());
        }


        if($token != NULL)
        {
            $url = "https://stg-wedocs.unep.org/rest/bitstreams/".$id;

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "DELETE",
                CURLOPT_POSTFIELDS => "",
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache",
                    "content-type: application/json",
                    "postman-token: c9ab95bc-c2f6-ed28-8843-86957de25697",
                    "rest-dspace-token: " .$token
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                echo "cURL Error #:" . $err;
            } else {
                echo $response;
            }



            if($decision_document){
                $decision_document->delete();
            }

            // Forward to the 'items' controller list
            $this->response->redirect("item/detail/".$itemID);
            $this->view->disable();

            return;

        }

    }


    public function publicationAction($id = "")
    {
        if ($this->request->isPost()) {

            $token = $this->session->get('token');

            // Get the data from the user
            $title    = $this->request->getPost('title');
            $number    = $this->request->getPost('year');
            $keywords    = $this->request->getPost('keywords');
            $description = $this->request->getPost('description');

            if($token != NULL)
            {
                $headers = [
                    'Content-Type'=>'application/json',
                    'Accept'=>'application/json',
                    'rest-dspace-token' => $token
                ];

                $data = [
                    [
                        "key"=>"dc.contributor.author",
                        "value"=>"UNEP MAP"
                    ],
                    [
                        "key"=>"dc.title",
                        "value"=>$title
                    ],
                    [
                        "key"=>"dc.subject",
                        "value"=>$keywords
                    ],
                    [
                        "key"=>"dc.identifier.publication-year",
                        "value"=>$number
                    ],
                    [
                        "key"=>"dc.description",
                        "value"=>$description
                    ],
                ];

                $metadata = ["metadata"=>$data];


                $postMetadataRes = $this->dspace->post('collections/8/items', [
                    'verify' => false,
                    'headers'=>$headers,
                    'json' => $metadata
                ]);

                $item = json_decode($postMetadataRes->getBody());


                //Check if the user has uploaded files

                if ($this->request->hasFiles() == true) {

                    $fileOneName = "";
                    $fileOneTmp = "";
                    $fileOneDesc = "";

                    foreach ($this->request->getUploadedFiles() as $file)
                    {
                        $fileOneName = $file->getName();
                        $fileOneTmp = $file->getTempName();


                        if($item != null && $item->id != null){

                            $headers = [
                                'Content-Type'=>'application/pdf',
                                'rest-dspace-token' => $token
                            ];

                            $url = "items/".$item->id."/bitstreams?name=" . $fileOneName . "&description=" . $fileOneDesc;

                            $postFileRes = $this->dspace->post($url,
                                [
                                    'verify' => false,
                                    'headers'=>$headers,
                                    'multipart' => [
                                        [
                                            'name'     => $fileOneName,
                                            'contents' => fopen($fileOneTmp, 'r'),
                                        ],
                                    ]
                                ]
                            );

                        }
                    }
                }




                // Forward to the 'category' controller list
                $this->response->redirect("category/list/8");
                $this->view->disable();

                return;

            }
        }

        $this->view->message = $message;

        $this->view->title = "Publication Document";
        $this->view->id = $id;

        $custom_js = [
            'partial'=>'item/decision_javascript',
            'params'=>[]
        ];

        $this->addJavaScript($custom_js);
    }

    public function reportAction($id = "")
    {
        if ($this->request->isPost()) {

            $token = $this->session->get('token');

            /// Get the data from the user		
            $author = $this->request->getPost('author');
            $date_issued = $this->request->getPost('date_issued');
            $url = $this->request->getPost('url');
            $description = $this->request->getPost('description');
            $existinglanguages = $this->request->getPost('existinglanguages');
            $extralanguages = $this->request->getPost('extralanguages');
            $publisher = $this->request->getPost('publisher');
            $existingkeywords = $this->request->getPost('existingkeywords');
            $extrakeywords = $this->request->getPost('extrakeywords');
            $title    = $this->request->getPost('title');
            $type = $this->request->getPost('type');
            $existingtreaties = $this->request->getPost('existingtreaties');
            $extratreaties = $this->request->getPost('extratreaties');
            $rights = $this->request->getPost('rights');
            $extracountries = $this->request->getPost('extracountries');
            $existingcountries = $this->request->getPost('existingcountries');



            $existingkeywords = rtrim($existingkeywords, ",");
            $existinglanguages = rtrim($existinglanguages, ",");
            $existingtreaties = rtrim($existingtreaties, ",");
            $existingcountries = rtrim($existingcountries, ",");

            if($extrakeywords != '')
            {
                $extrakeywords = explode(",", $extrakeywords);
                $keyworddata = '[';
                for($i = 0; $i<= sizeof($extrakeywords)-2; $i ++ )
                {
                    $keyworddata .= '{"key":"dc.subject","value":"'.$extrakeywords[$i].'"},';
                }
                $keyworddata = rtrim($keyworddata, ",");

                $keyworddata .= ']';

            }
            if($extralanguages != '')
            {
                $extralanguages = explode(",", $extralanguages);
                $languagedata = '[';
                for($i = 0; $i<= sizeof($extralanguages)-2; $i ++ )
                {
                    $languagedata .= '{"key":"dc.language","value":"'.$extralanguages[$i].'"},';
                }
                $languagedata = rtrim($languagedata, ",");

                $languagedata .= ']';

            }
            if($extratreaties != '')
            {
                $extratreaties = explode(",", $extratreaties);
                $treatydata = '[';
                for($i = 0; $i<= sizeof($extratreaties)-2; $i ++ )
                {
                    $treatydata .= '{"key":"informea.identifier.treaty","value":"'.$extratreaties[$i].'"},';
                }
                $treatydata = rtrim($treatydata, ",");

                $treatydata .= ']';

            }
            if($extracountries != '')
            {
                $extracountries = explode(",", $extracountries);
                $countrydata = '[';
                for($i = 0; $i<= sizeof($extracountries)-2; $i ++ )
                {
                    $countrydata .= '{"key":"informea.identifier.country","value":"'.$extracountries[$i].'"},';
                }
                $countrydata = rtrim($countrydata, ",");

                $countrydata .= ']';

            }

            $fileOneName = "";
            $fileOneTmp = "";
            $fileOneDesc = "";

            //Check if the user has uploaded files
            if ($this->request->hasFiles() == true) {
                /* //Print the real file names and their sizes
                 foreach ($this->request->getUploadedFiles() as $file)
                 {
                     //echo $file->getName(), " ", $file->getSize(), "\n";
                     $fileOneName = $file->getName();
                     $fileOneTmp = $file->getTempName();
                 }*/
            }

            if($token != NULL)
            {
                $headers = [
                    'Content-Type'=>'application/json',
                    'Accept'=>'application/json',
                    'rest-dspace-token' => $token
                ];

                $data = [

                    [
                        "key"=>"dc.contributor.author",
                        "value"=>$author
                    ],
                    [
                        "key"=>"dc.date.issued",
                        "value"=>$date_issued
                    ],
                    [
                        "key"=>"dc.description",
                        "value"=>$description
                    ],
                    [
                        "key"=>"dc.publisher",
                        "value"=>$publisher
                    ],
                    [
                        "key"=>"dc.title",
                        "value"=>$title
                    ],
                    [
                        "key"=>"dc.type",
                        "value"=>$type
                    ],
                    [
                        "key"=>"informea.identifier.rights",
                        "value"=>$rights
                    ]

                ];


                $metadata = ["metadata"=>$data];

                $addUrl = "https://stg-wedocs.unep.org/rest/collections/11/items";
                $metadata = json_encode($metadata);

                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $addUrl,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => $metadata,
                    CURLOPT_HTTPHEADER => array(
                        "cache-control: no-cache",
                        "content-type: application/json",
                        "postman-token: 642f2082-ffc1-c830-252c-1b85b18d0a6e",
                        "rest-dspace-token: ".$token

                    ),
                ));
                $response = curl_exec($curl);
                $err = curl_error($curl);
                if ($err) {
                    echo "cURL Error #:" . $err;
                } else {
                    //echo $response;


                }
                curl_close($curl);

                $item = new SimpleXMLElement($response);

                $main_item_id = $item->id;

                if($item->id != null)
                {
                    $itemID = $item->id;
                    $editUrl = "items/".$itemID;
                    $editUrl = "https://stg-wedocs.unep.org/rest/items/".$itemID."/metadata";


                    if($keyworddata != '')
                    {

                        $curl2 = curl_init();
                        curl_setopt_array($curl2, array(
                            CURLOPT_URL => $editUrl,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => "",
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 30,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => "POST",
                            CURLOPT_POSTFIELDS => $keyworddata,
                            CURLOPT_HTTPHEADER => array(
                                "cache-control: no-cache",
                                "content-type: application/json",
                                "rest-dspace-token: ".$token
                            ),
                        ));

                        $response = curl_exec($curl2);
                        $err = curl_error($curl2);
                        if ($err) {
                            echo "cURL Error #:" . $err;
                        } else {
                            //echo $response;
                        }
                        curl_close($curl2);
                        echo $err;

                    }

                    if($languagedata != '')
                    {

                        $curl2 = curl_init();
                        curl_setopt_array($curl2, array(
                            CURLOPT_URL => $editUrl,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => "",
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 30,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => "POST",
                            CURLOPT_POSTFIELDS => $languagedata,
                            CURLOPT_HTTPHEADER => array(
                                "cache-control: no-cache",
                                "content-type: application/json",
                                "rest-dspace-token: ".$token
                            ),
                        ));

                        $response = curl_exec($curl2);
                        $err = curl_error($curl2);
                        if ($err) {
                            echo "cURL Error #:" . $err;
                        } else {
                            //echo $response;
                        }
                        curl_close($curl2);
                        echo $err;

                    }

                    if($treatydata != '')
                    {

                        $curl3 = curl_init();
                        curl_setopt_array($curl3, array(
                            CURLOPT_URL => $editUrl,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => "",
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 30,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => "POST",
                            CURLOPT_POSTFIELDS => $treatydata,
                            CURLOPT_HTTPHEADER => array(
                                "cache-control: no-cache",
                                "content-type: application/json",
                                "rest-dspace-token: ".$token
                            ),
                        ));

                        $response = curl_exec($curl3);
                        $err = curl_error($curl3);
                        if ($err) {
                            echo "cURL Error #:" . $err;
                        } else {
                            //echo $response;
                        }
                        curl_close($curl3);
                        echo $err;

                    }

                    if($countrydata != '')
                    {

                        $curl4 = curl_init();
                        curl_setopt_array($curl4, array(
                            CURLOPT_URL => $editUrl,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => "",
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 30,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => "POST",
                            CURLOPT_POSTFIELDS => $countrydata,
                            CURLOPT_HTTPHEADER => array(
                                "cache-control: no-cache",
                                "content-type: application/json",
                                "rest-dspace-token: ".$token
                            ),
                        ));

                        $response = curl_exec($curl4);
                        $err = curl_error($curl4);
                        if ($err) {
                            echo "cURL Error #:" . $err;
                        } else {
                            // echo $response;
                        }
                        curl_close($curl4);
                        echo $err;

                    }
                }



                //Check if the user has uploaded files

                if ($this->request->hasFiles() == true) {

                    $fileOneName = "";
                    $fileOneTmp = "";
                    $fileOneDesc = "";
                    // Check if array is empty
                    if (count($this->request->getUploadedFiles() ) == 0) {
                        return false;
                    }
                    else
                    {
                        foreach ($this->request->getUploadedFiles() as $file)
                        {
                            $country_report_document = new CountryReportsDocuments();
                            $country_report_document->country_report_id = "" . $itemID;
                            $fileOneName = $file->getName();
                            $fileOneTmp = $file->getTempName();
                            if($fileOneName != null)
                            {
                                if($itemID != null)
                                {

                                    $headers = [
                                        'Content-Type'=>'application/pdf',
                                        'rest-dspace-token' => $token
                                    ];

                                    $url = "items/".$itemID."/bitstreams?name=" . $fileOneName . "&description=" . $fileOneDesc;

                                    $url = "https://stg-wedocs.unep.org/rest/items/".$itemID."/bitstreams" . "?name=" . $fileOneName . "&description=" . $fileOneDesc;


                                    $file_name_with_full_path = realpath($fileOneTmp);
                                    /* curl will accept an array here too.
                                     * Many examples I found showed a url-encoded string instead.
                                     * Take note that the 'key' in the array will be the key that shows up in the
                                     * $_FILES array of the accept script. and the at sign '@' is required before the
                                     * file name.
                                     */
                                    $filepost = array('extra_info' => '123456','file_contents'=>'@'.$file_name_with_full_path);


                                    $curl = curl_init();
                                    curl_setopt_array($curl, array(
                                        CURLOPT_URL => $url,
                                        CURLOPT_RETURNTRANSFER => true,
                                        CURLOPT_ENCODING => "",
                                        CURLOPT_MAXREDIRS => 10,
                                        CURLOPT_TIMEOUT => 30,
                                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                        CURLOPT_CUSTOMREQUEST => "POST",
                                        CURLOPT_POST => 1,
                                        CURLOPT_POSTFIELDS => $filepost,
                                        CURLOPT_HTTPHEADER => array(
                                            "cache-control: no-cache",
                                            "content-type: multipart/form-data",
                                            "contents : fopen($fileOneTmp, 'r')",
                                            "rest-dspace-token: ".$token
                                        ),
                                    ));

                                    $response = curl_exec($curl);
                                    $err = curl_error($curl);
                                    if ($err) {
                                        echo "cURL Error #:" . $err;
                                    } else {

                                        $file_item = new SimpleXMLElement($response);
                                        $file_item_json = json_encode($file_item);
                                        $file_item_obj = json_decode($file_item_json);

                                        // $itemID = json_encode($itemID);
                                        // $itemID = (array)json_decode($itemID);



                                        $country_report_document->url = "https://stg-wedocs.unep.org/rest".$file_item_obj->retrieveLink;
                                        $country_report_document->mimeType = $file_item_obj->mimeType;
                                        $country_report_document->filename = $file_item_obj->name;
                                        $country_report_document->language = "English";

                                        $country_report_document->save();
                                    }
                                    curl_close($curl);

                                }
                            }
                        }
                    }
                }



                $country_report = new CountryReports();


                $country_report->id = $item->id;
                $country_report->treaty = rtrim(implode(',', $extratreaties), ",");
                $country_report->country = rtrim(implode(',', $extracountries), ",");
                $country_report->submission = date('Y-m-d', strtotime($date_issued));
                if($url != null)
                {
                    $country_report->url = $url;
                }
                else
                {
                    $country_report->url = $addUrl;
                }
                $country_report->updated = date("Y-m-d H:i:s");
                $country_report->author = $author;

                $country_report->save();


                $country_reports_title = new CountryReportsTitle();

                $country_reports_title->country_report_id = $item->id;
                $country_reports_title->language = "English";
                $country_reports_title->title = $title;

                $country_reports_title->save();

                // Forward to the 'items' controller list
                $this->response->redirect("item/detail/".$itemID);
                $this->view->disable();

                return;


            }
        }

        $this->view->message = $message;
        $this->view->rights_select = $this->createRightsSelect();
        $this->view->types_select = $this->createDecisionTypeSelect();

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

        $this->view->title = "Technical Report Document";
        $this->view->id = $id;

        $custom_js = [
            'partial'=>'item/report_javascript',
            'params'=>[]
        ];

        $this->addJavaScript($custom_js);
    }

    public function editreportAction($itemID)
    {
        if ($this->request->isPost())
        {
            ini_set('max_execution_time', 120);
            $token = $this->session->get('token');

            $author = $this->request->getPost('author');
            $date_issued = $this->request->getPost('date_issued');
            $url = $this->request->getPost('url');
            $description = $this->request->getPost('description');
            $existinglanguages = $this->request->getPost('existinglanguages');
            $extralanguages = $this->request->getPost('extralanguages');
            $publisher = $this->request->getPost('publisher');
            $existingkeywords = $this->request->getPost('existingkeywords');
            $extrakeywords = $this->request->getPost('extrakeywords');
            $title    = $this->request->getPost('title');
            $type = $this->request->getPost('type');
            $existingtreaties = $this->request->getPost('existingtreaties');
            $extratreaties = $this->request->getPost('extratreaties');
            $rights = $this->request->getPost('rights');
            $extracountries = $this->request->getPost('extracountries');
            $existingcountries = $this->request->getPost('existingcountries');

            $existingkeywords = rtrim($existingkeywords, ",");
            $existinglanguages = rtrim($existinglanguages, ",");
            $existingtreaties = rtrim($existingtreaties, ",");
            $existingcountries = rtrim($existingcountries, ",");

            if($existingkeywords != '')
            {
                $existingkeywords = explode(",", $existingkeywords);
                $existingkeyworddata = '[';
                for($i = 0; $i<= sizeof($existingkeywords)-1; $i ++ )
                {
                    $existingkeyworddata .= '{"key":"dc.subject","value":"'.$existingkeywords[$i].'"},';
                }
                $existingkeyworddata = rtrim($existingkeyworddata, ",");

                $existingkeyworddata .= ']';

            }
            if($extrakeywords != '')
            {
                $extrakeywords = explode(",", $extrakeywords);
                $keyworddata = '[';
                for($i = 0; $i<= sizeof($extrakeywords)-2; $i ++ )
                {
                    $keyworddata .= '{"key":"dc.subject","value":"'.$extrakeywords[$i].'"},';
                }
                $keyworddata = rtrim($keyworddata, ",");

                $keyworddata .= ']';

            }
            if($existinglanguages != '')
            {
                $existinglanguages = explode(",", $existinglanguages);
                $existinglanguagedata = '[';
                for($i = 0; $i<= sizeof($existinglanguages)-1; $i ++ )
                {
                    $existinglanguagedata .= '{"key":"dc.language","value":"'.$existinglanguages[$i].'"},';
                }
                $existinglanguagedata = rtrim($existinglanguagedata, ",");

                $existinglanguagedata .= ']';

            }
            if($extralanguages != '')
            {
                $extralanguages = explode(",", $extralanguages);
                $languagedata = '[';
                for($i = 0; $i<= sizeof($extralanguages)-2; $i ++ )
                {
                    $languagedata .= '{"key":"dc.language","value":"'.$extralanguages[$i].'"},';
                }
                $languagedata = rtrim($languagedata, ",");

                $languagedata .= ']';

            }

            if($existingtreaties != '')
            {
                $existingtreaties = explode(",", $existingtreaties);
                $existingtreatiesdata = '[';
                for($i = 0; $i<= sizeof($existingtreaties)-1; $i ++ )
                {
                    $existingtreatiesdata .= '{"key":"informea.identifier.treaty","value":"'.$existingtreaties[$i].'"},';
                }
                $existingtreatiesdata = rtrim($existingtreatiesdata, ",");

                $existingtreatiesdata .= ']';

            }

            if($extratreaties != '')
            {
                $extratreaties = explode(",", $extratreaties);
                $treatydata = '[';
                for($i = 0; $i<= sizeof($extratreaties)-2; $i ++ )
                {
                    $treatydata .= '{"key":"informea.identifier.treaty","value":"'.$extratreaties[$i].'"},';
                }
                $treatydata = rtrim($treatydata, ",");

                $treatydata .= ']';

            }
            if($existingcountries != '')
            {
                $existingcountries = explode(",", $existingcountries);
                $existingcountriesdata = '[';
                for($i = 0; $i<= sizeof($existingcountries)-1; $i ++ )
                {
                    $existingcountriesdata .= '{"key":"informea.identifier.country","value":"'.$existingcountries[$i].'"},';
                }
                $existingcountriesdata = rtrim($existingcountriesdata, ",");

                $existingtreatiesdata .= ']';

            }
            if($extracountries != '')
            {
                $extracountries = explode(",", $extracountries);
                $countrydata = '[';
                for($i = 0; $i<= sizeof($extracountries)-2; $i ++ )
                {
                    $countrydata .= '{"key":"informea.identifier.country","value":"'.$extracountries[$i].'"},';
                }
                $countrydata = rtrim($countrydata, ",");

                $countrydata .= ']';

            }


            $fileOneName = "";
            $fileOneTmp = "";
            $fileOneDesc = "";

            //Check if the user has uploaded files
            if ($this->request->hasFiles() == true) {
                //Print the real file names and their sizes
                foreach ($this->request->getUploadedFiles() as $file)
                {
                    //echo $file->getName(), " ", $file->getSize(), "\n";
                    $fileOneName = $file->getName();
                    $fileOneTmp = $file->getTempName();
                }
            }

            if($token != NULL)
            {
                $headers = [
                    'Content-Type'=>'application/json',
                    'Accept'=>'application/json',
                    'rest-dspace-token' => $token
                ];

                $data = [

                    [
                        "key"=>"dc.contributor.author",
                        "value"=>$author
                    ],
                    [
                        "key"=>"dc.date.issued",
                        "value"=>$date_issued
                    ],
                    [
                        "key"=>"dc.description",
                        "value"=>$description
                    ],
                    [
                        "key"=>"dc.publisher",
                        "value"=>$publisher
                    ],
                    [
                        "key"=>"dc.title",
                        "value"=>$title
                    ],
                    [
                        "key"=>"dc.type",
                        "value"=>$type
                    ],
                    [
                        "key"=>"informea.identifier.rights",
                        "value"=>$rights
                    ]

                ];


                $metadata = ["metadata"=>$data];


                $editUrl = "items/".$itemID;
                $editUrl = "https://stg-wedocs.unep.org/rest/items/".$itemID."/metadata";

                $datapost = json_encode($data);


                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $editUrl,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "PUT",
                    CURLOPT_POSTFIELDS => $datapost,
                    CURLOPT_HTTPHEADER => array(
                        "cache-control: no-cache",
                        "content-type: application/json",
                        "rest-dspace-token: ".$token
                    ),
                ));

                $response = curl_exec($curl);
                $err = curl_error($curl);
                if ($err) {
                    echo "cURL Error #:" . $err;
                } else {
                    echo $response;
                }
                curl_close($curl);



                if($existingkeyworddata != '')
                {

                    $curl3 = curl_init();
                    curl_setopt_array($curl3, array(
                        CURLOPT_URL => $editUrl,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "PUT",
                        CURLOPT_POSTFIELDS => $existingkeyworddata,
                        CURLOPT_HTTPHEADER => array(
                            "cache-control: no-cache",
                            "content-type: application/json",
                            "rest-dspace-token: ".$token
                        ),
                    ));

                    $response = curl_exec($curl3);
                    $err = curl_error($curl3);
                    if ($err) {
                        echo "cURL Error #:" . $err;
                    } else {
                        echo $response;
                    }
                    curl_close($curl3);
                    echo $err;

                }

                if($languagedata != '')
                {

                    $curl4 = curl_init();
                    curl_setopt_array($curl4, array(
                        CURLOPT_URL => $editUrl,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => $languagedata,
                        CURLOPT_HTTPHEADER => array(
                            "cache-control: no-cache",
                            "content-type: application/json",
                            "rest-dspace-token: ".$token
                        ),
                    ));

                    $response = curl_exec($curl4);
                    $err = curl_error($curl4);
                    if ($err) {
                        echo "cURL Error #:" . $err;
                    } else {
                        echo $response;
                    }
                    curl_close($curl4);
                    echo $err;

                }

                if($existinglanguagedata != '')
                {

                    $curl5 = curl_init();
                    curl_setopt_array($curl5, array(
                        CURLOPT_URL => $editUrl,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "PUT",
                        CURLOPT_POSTFIELDS => $existinglanguagedata,
                        CURLOPT_HTTPHEADER => array(
                            "cache-control: no-cache",
                            "content-type: application/json",
                            "rest-dspace-token: ".$token
                        ),
                    ));

                    $response = curl_exec($curl5);
                    $err = curl_error($curl5);
                    if ($err) {
                        echo "cURL Error #:" . $err;
                    } else {
                        echo $response;
                    }
                    curl_close($curl5);
                    echo $err;

                }

                if($treatydata != '')
                {

                    $curl6 = curl_init();
                    curl_setopt_array($curl6, array(
                        CURLOPT_URL => $editUrl,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => $treatydata,
                        CURLOPT_HTTPHEADER => array(
                            "cache-control: no-cache",
                            "content-type: application/json",
                            "rest-dspace-token: ".$token
                        ),
                    ));

                    $response = curl_exec($curl6);
                    $err = curl_error($curl6);
                    if ($err) {
                        echo "cURL Error #:" . $err;
                    } else {
                        echo $response;
                    }
                    curl_close($curl6);
                    echo $err;

                }

                if($existingtreatiesdata != '')
                {

                    $curl7 = curl_init();
                    curl_setopt_array($curl7, array(
                        CURLOPT_URL => $editUrl,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "PUT",
                        CURLOPT_POSTFIELDS => $existingtreatiesdata,
                        CURLOPT_HTTPHEADER => array(
                            "cache-control: no-cache",
                            "content-type: application/json",
                            "rest-dspace-token: ".$token
                        ),
                    ));

                    $response = curl_exec($curl7);
                    $err = curl_error($curl7);
                    if ($err) {
                        echo "cURL Error #:" . $err;
                    } else {
                        echo $response;
                    }
                    curl_close($curl7);
                    echo $err;

                }

                if($countrydata != '')
                {

                    $curl8 = curl_init();
                    curl_setopt_array($curl8, array(
                        CURLOPT_URL => $editUrl,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => $countrydata,
                        CURLOPT_HTTPHEADER => array(
                            "cache-control: no-cache",
                            "content-type: application/json",
                            "rest-dspace-token: ".$token
                        ),
                    ));

                    $response = curl_exec($curl8);
                    $err = curl_error($curl8);
                    if ($err) {
                        echo "cURL Error #:" . $err;
                    } else {
                        echo $response;
                    }
                    curl_close($curl8);
                    echo $err;

                }
                if($existingcountriesdata != '')
                {

                    $curl9 = curl_init();
                    curl_setopt_array($curl9, array(
                        CURLOPT_URL => $editUrl,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "PUT",
                        CURLOPT_POSTFIELDS => $existingcountriesdata,
                        CURLOPT_HTTPHEADER => array(
                            "cache-control: no-cache",
                            "content-type: application/json",
                            "rest-dspace-token: ".$token
                        ),
                    ));

                    $response = curl_exec($curl9);
                    $err = curl_error($curl9);
                    if ($err) {
                        echo "cURL Error #:" . $err;
                    } else {
                        echo $response;
                    }
                    curl_close($curl9);
                    echo $err;

                }

                if($keyworddata != '')
                {

                    $curl2 = curl_init();
                    curl_setopt_array($curl2, array(
                        CURLOPT_URL => $editUrl,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 30,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_POSTFIELDS => $keyworddata,
                        CURLOPT_HTTPHEADER => array(
                            "cache-control: no-cache",
                            "content-type: application/json",
                            "rest-dspace-token: ".$token
                        ),
                    ));

                    $response = curl_exec($curl2);
                    $err = curl_error($curl2);
                    if ($err) {
                        echo "cURL Error #:" . $err;
                    } else {
                        echo $response;
                    }
                    curl_close($curl2);
                    echo $err;

                }
            }


            //Check if the user has uploaded files

            if ($this->request->hasFiles() == true) {

                $fileOneName = "";
                $fileOneTmp = "";
                $fileOneDesc = "";

                foreach ($this->request->getUploadedFiles() as $file)
                {
                    $fileOneName = $file->getName();
                    $fileOneTmp = $file->getTempName();

                    if($itemID != null){

                        $headers = [
                            'Content-Type'=>'application/pdf',
                            'rest-dspace-token' => $token
                        ];

                        $url = "items/".$itemID."/bitstreams?name=" . $fileOneName . "&description=" . $fileOneDesc;

                        $url = "https://stg-wedocs.unep.org/rest/items/".$itemID."/bitstreams" . "?name=" . $fileOneName . "&description=" . $fileOneDesc;


                        $file_name_with_full_path = realpath($fileOneTmp);
                        /* curl will accept an array here too.
                         * Many examples I found showed a url-encoded string instead.
                         * Take note that the 'key' in the array will be the key that shows up in the
                         * $_FILES array of the accept script. and the at sign '@' is required before the
                         * file name.
                         */
                        $filepost = array('extra_info' => '123456','file_contents'=>'@'.$file_name_with_full_path);


                        $curl = curl_init();
                        curl_setopt_array($curl, array(
                            CURLOPT_URL => $url,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => "",
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 30,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => "POST",
                            CURLOPT_POST => 1,
                            CURLOPT_POSTFIELDS => $filepost,
                            CURLOPT_HTTPHEADER => array(
                                "cache-control: no-cache",
                                "content-type: multipart/form-data",
                                "contents : fopen($fileOneTmp, 'r')",
                                "rest-dspace-token: ".$token
                            ),
                        ));

                        $response = curl_exec($curl);
                        $err = curl_error($curl);
                        if ($err) {
                            echo "cURL Error #:" . $err;
                        } else {
                            echo $response;
                        }
                        curl_close($curl);

                    }

                }
            }
            // Forward to the 'items' controller list

            if($extracountries){
                array_push($existingcountries, $extracountries);
            }

            if ($extratreaties) {
                array_push($existingtreaties, $extratreaties);
            }

            $country_report = CountryReports::findFirstById($itemID);

            $date_issued = date("Y-m-d", strtotime($date_issued));

            $country_report->treaty = rtrim(implode(',', $existingtreaties), ",");
            $country_report->country = rtrim(implode(',', $existingcountries), ",");
            $country_report->submission = $date_issued;
            $country_report->url = $url;
            $country_report->updated = date("Y-m-d H:i:s");
            $country_report->author = $author;

            $country_report->update();

            $country_reports_title = CountryReportsTitle::findFirstByCountryReportId($itemID);

            $country_reports_title->country_report_id = $itemID;
            $country_reports_title->language = "English";
            $country_reports_title->title = $title;

            $country_reports_title->update();
            $this->response->redirect("item/detail/".$itemID);
            $this->view->disable();


            return;


        }
        else
        {
            $url = "items/". $itemID."?expand=all";

            $res = $this->dspace->get($url, ['verify' => false]);

            $data = json_decode($res->getBody());

            $value = get_object_vars($data);

            $this->view->title = "Item Details";

            $this->view->doc = $data->name;
            $this->view->itemID = $itemID;

            $this->view->data = $value;
            $this->view->values = $data;

            // Add some local CSS resources
            $this->assets
                ->addCss('plugins/daterangepicker/daterangepicker-bs3.css');

            // And some local JavaScript resources
            $this->assets
                ->addJs('plugins/input-mask/jquery.inputmask.js')
                ->addJs('plugins/input-mask/jquery.inputmask.date.extensions.js')
                ->addJs('plugins/input-mask/jquery.inputmask.extensions.js')
                ->addJs('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js', false)
                ->addJs('plugins/daterangepicker/daterangepicker.js');



            $custom_js = [
                'partial'=>'item/report_javascript',
                'params'=>[]
            ];

            $this->addJavaScript($custom_js);
        }
    }


    private function createTreatiesSelect($selected_treaty = NULL)
    {
        $treaties = Treaties::find();
        $treaties_select = "";
        foreach ($treaties as $value) {
            $selected = "";
            if(isset($selected_treaty) && $value->treaty == $selected_treaty)
            {
                $selected = "selected = 'selected'";
            }
            $treaties_select .= "<option value = '{$value->treaty}' {$selected}>{$value->treaty}</option>";
        }

        return $treaties_select;
    }

    private function createRepetitonsSelect($selected_repetition = NULL)
    {
        $repetitions = [
            'Weekly',
            'Monthly',
            'Yearly',
            'Not Specified'
        ];
        $repetition_select = "";

        foreach ($repetitions as $rep) {
            $selected = "";
            if(isset($selected_repetition) && $rep == $selected_repetition)
            {
                $selected = "selected = 'selected'";
            }
            $repetition_select .= "<option value = '{$rep}' {$selected}>{$rep}</option>";
        }

        return $repetition_select;
    }

    private function createKindSelect($selected_kind = NULL)
    {
        $kinds = [
            "Official",
            "Partner",
            "Interest",
            "Not Specified"
        ];

        $kinds_select = "";

        foreach ($kinds as $kind) {
            $selected = "";

            if (isset($selected_kind) && $kind == $selected_kind) {
                $selected = "selected = 'selected'";
            }

            $kinds_select .= "<option value = '{$kind}' {$selected}>{$kind}</option>";
        }

        return $kinds_select;
    }

    private function createMeetingTypeSelect($selected_type = NULL)
    {
        $meeting_types = [
            "COP",
            "MOP",
            "Subsidiary",
            "Expert",
            "Mission",
            "Symposium",
            "Conference",
            "Workshop",
            "Informal",
            "SCC",
            "STC",
            "Technical Meeting",
            "Negotiation Meeting"
        ];

        $meeting_types_select = "";

        foreach ($meeting_types as $type) {
            $selected = "";
            if (isset($selected_type) && $type == $selected_type) {
                $selected = "selected = 'selected'";
            }

            $meeting_types_select .= "<option value = '{$type}' {$selected}>{$type}</option>";
        }

        return $meeting_types_select;
    }

    private function createDecisionTypeSelect($selected_type = NULL)
    {
        $decision_types = [
            "Agendas Meeting documents and minutes of meetings",
            "Amm 2013 Presentations",
            "Communication Material",
            "Contribution Trends",
            "Corporate agreements and partnerships",
            "Corporate Guideline",
            "Corporate Policies and Agreements",
            "Corporate policies, instructions and procedures",
            "Correspondence Guidelines and Templates.",
            "Country Profile",
            "Country Report",
            "Decision",
            "Delegation of Authority",
            "Donor Agreements",
            "Donor Funding Priorities",
            "Evaluation Reports",
            "Financial Guidelines",
            "Funding Statistics and Reports",
            "GEAS Newsletters",
            "GEF Templates and Guidelines",
            "Legal Agreements",
            "Legal Templates",
            "Manuals and templates",
            "Medium Term Strategy",
            "Memos",
            "Memos and letters",
            "Newsletter",
            "Official Documents",
            "OIOS Audit Documents",
            "Outreach Materials",
            "Partnerships Committee Meeting Minutes",
            "Partnerships Legal Instruments",
            "Partnerships Policy Due diligence dossiers",
            "Partnerships Policy: Evaluations of partners approved under the Partnership Policy",
            "PoW/PPR",
            "Procurement Documents",
            "Programme Manual",
            "Programme of Work",
            "Programme/Project Performance",
            "Programme/Project Review",
            "Progress Reports",
            "Publications, analysis, literature reviews (NON-UNEP)",
            "Quality of Project Management",
            "Regional Newsletters",
            "Signed Legal Documents",
            "Standard Operating Procedures",
            "Strategic Framework/Programme of Work",
            "Umoja Templates",
            "UNEP Funding Strategies"
        ];

        $decision_types_select = "";

        foreach ($decision_types as $type) {
            $selected = "";
            if (isset($selected_type) && $type == $selected_type) {
                $selected = "selected = 'selected'";
            }

            $decision_types_select .= "<option value = '{$type}' {$selected}>{$type}</option>";
        }

        return $decision_types_select;
    }

    private function createRightsSelect($selected_rights = NULL)
    {
        $rights = [
            "Public",
            "Private"
        ];

        $rights_select = "";

        foreach ($rights as $right) {
            $selected = "";
            if (isset($selected_rights) && $right == $selected_rights) {
                $selected = "selected = 'selected'";
            }

            $rights_select .= "<option value = '{$right}' {$selected}>{$right}</option>";
        }

        return $rights_select;
    }

    private function createStatusSelect($selected_status = NULL, $type = "meeting")
    {
        if($type == "decision")
        {
            $status_arr = [
                "Draft",
                "Active",
                "Amended",
                "Retired",
                "Revised",
                "Adopted"
            ];
        }
        else
        {
            $status_arr = [
                "Tentative",
                "Confirmed",
                "Postponed",
                "Cancelled",
                "No Date",
                "Over",
                "Not Specified"
            ];
        }


        $status_select = "";

        foreach ($status_arr as $value) {
            $selected = "";
            if (isset($selected_status) && $selected_status == $value) {
                $selected = "selected = 'selected'";
            }

            $status_select .= "<option value = '{$value}' {$selected}>{$value}</option>";
        }

        return $status_select;
    }

    private function createCountrySelect($selected_country = NULL)
    {
        $countries = Country::find();

        $countries_select = "";

        foreach ($countries as $country) {
            $selected = "";
            if(isset($selected_country) && $country->name == $selected_country)
            {
                $selected = "selected = 'selected'";
            }

            $countries_select .= "<option value = '{$country->name}' {$selected}>{$country->name}</option>";
        }

        return $countries_select;
    }
}