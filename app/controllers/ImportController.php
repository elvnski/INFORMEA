<?php

class ImportController extends BaseController{


    public function initialize()
    {
        $this->view->auth = $this->session->get('auth');
        $this->token = $this->session->get('token');
        $auth = $this->session->get('auth');
        if($auth === null){
            $this->response->redirect("user/");
            $this->view->disable();
        }
    }


    public function indexAction()
    {
        echo 'This is ImportController';
    }

    public function MapDspaceAction($collectionID){

        date_default_timezone_set("Africa/Nairobi");

        $coll_name = Collections::findFirst($collectionID)->collection;

        set_time_limit(0);
        header('Content-type: text/plain; charset=utf-8');
        $this->view->disable();

        $this->getDspaceData($collectionID);

        $MappedData = $this->getColumnMapping($collectionID);


        if($coll_name === "Meeting Documents"){

            $Model = new Meetings();
            $records = $Model::find();
            $records->delete();

            $M2 = new MeetingsDescription();
            $records2 = $M2::find();
            $records2->delete();

            $Model3 = new MeetingTitle();
            $records3 = $Model3::find();
            $records3->delete();

            for ($i = 0, $iMax = count($MappedData); $i < $iMax; $i++) {
                $M_Model = new Meetings();

                $M_Model->setId(array_key_exists("id", $MappedData[$i]['meetings']) ? $MappedData[$i]['meetings']['id'] : "");
                $M_Model->setTreaty(array_key_exists("treaty", $MappedData[$i]['meetings']) ? $MappedData[$i]['meetings']['treaty'] : "");
                $M_Model->setUrl(array_key_exists("url", $MappedData[$i]['meetings']) ? $MappedData[$i]['meetings']['url'] : "");
                $M_Model->setStart(array_key_exists("start", $MappedData[$i]['meetings']) ? $MappedData[$i]['meetings']['start'] : "");
                $M_Model->setEnd(array_key_exists("end", $MappedData[$i]['meetings']) ? $MappedData[$i]['meetings']['end'] : "");
                $M_Model->setRepetition(array_key_exists("repetition", $MappedData[$i]['meetings']) ? $MappedData[$i]['meetings']['repetition'] : "");
                $M_Model->setKind(array_key_exists("kind", $MappedData[$i]['meetings']) ? $MappedData[$i]['meetings']['kind'] : "");
                $M_Model->setType(array_key_exists("type", $MappedData[$i]['meetings']) ? $MappedData[$i]['meetings']['type'] : "");
                $M_Model->setAccess(array_key_exists("access", $MappedData[$i]['meetings']) ? $MappedData[$i]['meetings']['access'] : "");
                $M_Model->setStatus(array_key_exists("status", $MappedData[$i]['meetings']) ? $MappedData[$i]['meetings']['status'] : "");
                $M_Model->setImageUrl(array_key_exists("imageUrl", $MappedData[$i]['meetings']) ? $MappedData[$i]['meetings']['imageUrl'] : "");
                $M_Model->setImageCopyright(array_key_exists("imageCopyright", $MappedData[$i]['meetings']) ? $MappedData[$i]['meetings']['imageCopyright'] : "");
                $M_Model->setLocation(array_key_exists("location", $MappedData[$i]['meetings']) ? $MappedData[$i]['meetings']['location'] : "");
                $M_Model->setCity(array_key_exists("city", $MappedData[$i]['meetings']) ? $MappedData[$i]['meetings']['city'] : "");
                $M_Model->setCountry(array_key_exists("country", $MappedData[$i]['meetings']) ? $MappedData[$i]['meetings']['country'] : "");
                $M_Model->setLatitude(array_key_exists("latitude", $MappedData[$i]['meetings']) ? $MappedData[$i]['meetings']['latitude'] : "");
                $M_Model->setLongitude(array_key_exists("longitude", $MappedData[$i]['meetings']) ? $MappedData[$i]['meetings']['longitude'] : "");
                $M_Model->setAuthor(array_key_exists("author", $MappedData[$i]['meetings']) ? $MappedData[$i]['meetings']['author'] : "");
                $M_Model->setUpdated(array_key_exists("updated", $MappedData[$i]['meetings']) ? $MappedData[$i]['meetings']['updated'] : "");

                $M_Model->save();


                $MD_Model = new MeetingsDescription();

                $MD_Model->setId(array_key_exists("id", $MappedData[$i]['meetings_description']) ? $MappedData[$i]['meetings_description']['id'] : "");
                $MD_Model->setMeetingId(array_key_exists("meeting_id", $MappedData[$i]['meetings_description']) ? $MappedData[$i]['meetings_description']['meeting_id'] : "");
                $MD_Model->setLanguage(array_key_exists("language", $MappedData[$i]['meetings_description']) ? $MappedData[$i]['meetings_description']['language'] : "");
                $MD_Model->setDescription(array_key_exists("description", $MappedData[$i]['meetings_description']) ? $MappedData[$i]['meetings_description']['description'] : "");

                $MD_Model->save();


                $MT_Model = new MeetingTitle();

                $MT_Model->setId(array_key_exists("id", $MappedData[$i]['meeting_title']) ? $MappedData[$i]['meeting_title']['id'] : "");
                $MT_Model->setMeetingId(array_key_exists("meeting_title", $MappedData[$i]['meeting_title']) ? $MappedData[$i]['meeting_title']['meeting_id'] : "");
                $MT_Model->setLanguage(array_key_exists("language", $MappedData[$i]['meeting_title']) ? $MappedData[$i]['meeting_title']['language'] : "");
                $MT_Model->setTitle(array_key_exists("title", $MappedData[$i]['meeting_title']) ? $MappedData[$i]['meeting_title']['title'] : "");

                $MT_Model->save();

            }

            echo "\n\n MEETING DOCUMENTS COLLECTION DATA UPDATED! \n\n";

        }


        //Saving to contacts table
        else if($coll_name === "Contacts"){
            $Model = new Contacts();
            $records = $Model::find();
            $records->delete();

            $Model2 = new ContactTreaty();
            $records2 = $Model2::find();
            $records2->delete();

            for ($i = 0, $iMax = count($MappedData); $i < $iMax; $i++) {

                $C_Model = new Contacts();

                $C_Model->setId(array_key_exists("id", $MappedData[$i]['contacts']) ? $MappedData[$i]['contacts']['id'] : "");
                $C_Model->setCountry(array_key_exists("country", $MappedData[$i]['contacts']) ? $MappedData[$i]['contacts']['country'] : "");
                $C_Model->setPrefix(array_key_exists("prefix", $MappedData[$i]['contacts']) ? $MappedData[$i]['contacts']['prefix'] : "");
                $C_Model->setFirstName(array_key_exists("firstName", $MappedData[$i]['contacts']) ? $MappedData[$i]['contacts']['firstName'] : "");
                $C_Model->setLastName(array_key_exists("lastName", $MappedData[$i]['contacts']) ? $MappedData[$i]['contacts']['lastName'] : "");
                $C_Model->setPosition(array_key_exists("position", $MappedData[$i]['contacts']) ? $MappedData[$i]['contacts']['position'] : "");
                $C_Model->setInstitution(array_key_exists("institution", $MappedData[$i]['contacts']) ? $MappedData[$i]['contacts']['institution'] : "");
                $C_Model->setDepartment(array_key_exists("department", $MappedData[$i]['contacts']) ? $MappedData[$i]['contacts']['department'] : "");
                $C_Model->setType(array_key_exists("type", $MappedData[$i]['contacts']) ? $MappedData[$i]['contacts']['type'] : "");
                $C_Model->setAddress(array_key_exists("address", $MappedData[$i]['contacts']) ? $MappedData[$i]['contacts']['address'] : "");
                $C_Model->setEmail(array_key_exists("email", $MappedData[$i]['contacts']) ? $MappedData[$i]['contacts']['email'] : "");
                $C_Model->setPhoneNumber(array_key_exists("phoneNumber", $MappedData[$i]['contacts']) ? $MappedData[$i]['contacts']['phoneNumber'] : "");
                $C_Model->setFax(array_key_exists("fax", $MappedData[$i]['contacts']) ? $MappedData[$i]['contacts']['fax'] : "");
                $C_Model->setPrimary(array_key_exists("primary", $MappedData[$i]['contacts']) ? $MappedData[$i]['contacts']['primary'] : "");
                $C_Model->setAuthor(array_key_exists("author", $MappedData[$i]['contacts']) ? $MappedData[$i]['contacts']['author'] : "");
                $C_Model->setUpdated(array_key_exists("updated", $MappedData[$i]['contacts']) ? $MappedData[$i]['contacts']['updated'] : "");

                $C_Model->save();


                $CT_Model = new ContactTreaty();

                $CT_Model->setId(array_key_exists("id", $MappedData[$i]['contact_treaty']) ? $MappedData[$i]['contact_treaty']['id'] : "");
                $CT_Model->setContactId(array_key_exists("contact_id", $MappedData[$i]['contact_treaty']) ? $MappedData[$i]['contact_treaty']['contact_id'] : "");
                $CT_Model->setTreaty(array_key_exists("treaty", $MappedData[$i]['contact_treaty']) ? $MappedData[$i]['contact_treaty']['treaty'] : "");

                $CT_Model->save();

            }

            echo "\n\n CONTACTS COLLECTION DATA UPDATED! \n\n";


        }

        else if($coll_name === "Decisions") {
            $Model = new Decisions();
            $records = $Model::find();
            $records->delete();

            $Model2 = new DecisionsContent();
            $records2 = $Model2::find();
            $records2->delete();

            $Model3 = new DecisionsDocuments();
            $records3 = $Model3::find();
            $records3->delete();

            $Model4 = new DecisionsKeywords();
            $records4 = $Model4::find();
            $records4->delete();

            $Model5 = new DecisionsLongtitle();
            $records5 = $Model5::find();
            $records5->delete();

            $Model6 = new DecisionsSummary();
            $records6 = $Model6::find();
            $records6->delete();

            $Model7 = new DecisionsTitle();
            $records7 = $Model7::find();
            $records7->delete();



            for ($i = 0, $iMax = count($MappedData); $i < $iMax; $i++) {
                $D_Model = new Decisions();

                $D_Model->setId(array_key_exists("id", $MappedData[$i]['decisions']) ? $MappedData[$i]['decisions']['id'] : "");
                $D_Model->setLink(array_key_exists("link", $MappedData[$i]['decisions']) ? $MappedData[$i]['decisions']['link'] : "");
                $D_Model->setType(array_key_exists("type", $MappedData[$i]['decisions']) ? $MappedData[$i]['decisions']['type'] : "");
                $D_Model->setStatus(array_key_exists("status", $MappedData[$i]['decisions']) ? $MappedData[$i]['decisions']['status'] : "");
                $D_Model->setNumber(array_key_exists("number", $MappedData[$i]['decisions']) ? $MappedData[$i]['decisions']['number'] : "");
                $D_Model->setTreaty(array_key_exists("treaty", $MappedData[$i]['decisions']) ? $MappedData[$i]['decisions']['treaty'] : "");
                $D_Model->setPublished(array_key_exists("published", $MappedData[$i]['decisions']) ? $MappedData[$i]['decisions']['published'] : "");
                $D_Model->setMeetingId(array_key_exists("meetingId", $MappedData[$i]['decisions']) ? $MappedData[$i]['decisions']['meetingId'] : "");
                $D_Model->setMeetingTitle(array_key_exists("meetingTitle", $MappedData[$i]['decisions']) ? $MappedData[$i]['decisions']['meetingTitle'] : "");
                $D_Model->setMeetingUrl(array_key_exists("meetingUrl", $MappedData[$i]['decisions']) ? $MappedData[$i]['decisions']['meetingUrl'] : "");
                $D_Model->setDisplayOrder(array_key_exists("displayOrder", $MappedData[$i]['decisions']) ? $MappedData[$i]['decisions']['displayOrder'] : "");
                $D_Model->setAuthor(array_key_exists("author", $MappedData[$i]['decisions']) ? $MappedData[$i]['decisions']['author'] : "");
                $D_Model->setUpdated(array_key_exists("updated", $MappedData[$i]['decisions']) ? $MappedData[$i]['decisions']['updated'] : "");

                $D_Model->save();


                $DC_Model = new DecisionsContent();

                $DC_Model->setId(array_key_exists("id", $MappedData[$i]['decisions_content']) ? $MappedData[$i]['decisions_content']['id'] : "");
                $DC_Model->setDecisionId(array_key_exists("decision_id", $MappedData[$i]['decisions_content']) ? $MappedData[$i]['decisions_content']['decision_id'] : "");
                $DC_Model->setLanguage(array_key_exists("language", $MappedData[$i]['decisions_content']) ? $MappedData[$i]['decisions_content']['language'] : "");
                $DC_Model->setContent(array_key_exists("content", $MappedData[$i]['decisions_content']) ? $MappedData[$i]['decisions_content']['content'] : "");

                $DC_Model->save();


                $DD_Model = new DecisionsDocuments();

                $DD_Model->setId(array_key_exists("id", $MappedData[$i]['decisions_documents']) ? $MappedData[$i]['decisions_documents']['id'] : "");
                $DD_Model->setDecisionId(array_key_exists("decision_id", $MappedData[$i]['decisions_documents']) ? $MappedData[$i]['decisions_documents']['decision_id'] : "");
                $DD_Model->setDiskPath(array_key_exists("diskPath", $MappedData[$i]['decisions_documents']) ? $MappedData[$i]['decisions_documents']['diskPath'] : "");
                $DD_Model->setUrl(array_key_exists("url", $MappedData[$i]['decisions_documents']) ? $MappedData[$i]['decisions_documents']['url'] : "");
                $DD_Model->setMimeType(array_key_exists("mimeType", $MappedData[$i]['decisions_documents']) ? $MappedData[$i]['decisions_documents']['mimeType'] : "");
                $DD_Model->setLanguage(array_key_exists("language", $MappedData[$i]['decisions_documents']) ? $MappedData[$i]['decisions_documents']['language'] : "");
                $DD_Model->setFilename(array_key_exists("language", $MappedData[$i]['decisions_documents']) ? $MappedData[$i]['decisions_documents']['filename'] : "");

                $DD_Model->save();


                $DK_Model = new DecisionsKeywords();

                $DK_Model->setId(array_key_exists("id", $MappedData[$i]['decisions_keywords']) ? $MappedData[$i]['decisions_keywords']['id'] : "");
                $DK_Model->setDecisionId(array_key_exists("decision_id", $MappedData[$i]['decisions_keywords']) ? $MappedData[$i]['decisions_keywords']['decision_id'] : "");
                $DK_Model->setNamespace(array_key_exists("namespace", $MappedData[$i]['decisions_keywords']) ? $MappedData[$i]['decisions_keywords']['namespace'] : "");
                $DK_Model->setTerm(array_key_exists("term", $MappedData[$i]['decisions_keywords']) ? $MappedData[$i]['decisions_keywords']['term'] : "");

                $DK_Model->save();


                $DL_Model = new DecisionsLongtitle();

                $DL_Model->setId(array_key_exists("id", $MappedData[$i]['decisions_longtitle']) ? $MappedData[$i]['decisions_longtitle']['id'] : "");
                $DL_Model->setDecisionId(array_key_exists("decision_id", $MappedData[$i]['decisions_longtitle']) ? $MappedData[$i]['decisions_longtitle']['decision_id'] : "");
                $DL_Model->setLanguage(array_key_exists("language", $MappedData[$i]['decisions_longtitle']) ? $MappedData[$i]['decisions_longtitle']['language'] : "");
                $DL_Model->setLongTitle(array_key_exists("long_title", $MappedData[$i]['decisions_longtitle']) ? $MappedData[$i]['decisions_longtitle']['long_title'] : "");

                $DL_Model->save();


                $DS_Model = new DecisionsSummary();

                $DS_Model->setId(array_key_exists("id", $MappedData[$i]['decisions_summary']) ? $MappedData[$i]['decisions_summary']['id'] : "");
                $DS_Model->setDecisionId(array_key_exists("decision_id", $MappedData[$i]['decisions_summary']) ? $MappedData[$i]['decisions_summary']['decision_id'] : "");
                $DS_Model->setLanguage(array_key_exists("language", $MappedData[$i]['decisions_summary']) ? $MappedData[$i]['decisions_summary']['language'] : "");
                $DS_Model->setSummary(array_key_exists("summary", $MappedData[$i]['decisions_summary']) ? $MappedData[$i]['decisions_summary']['summary'] : "");

                $DS_Model->save();


                $DT_Model = new DecisionsTitle();

                $DT_Model->setId(array_key_exists("id", $MappedData[$i]['decisions_title']) ? $MappedData[$i]['decisions_title']['id'] : "");
                $DT_Model->setDecisionId(array_key_exists("decision_id", $MappedData[$i]['decisions_title']) ? $MappedData[$i]['decisions_title']['decision_id'] : "");
                $DT_Model->setLanguage(array_key_exists("language", $MappedData[$i]['decisions_title']) ? $MappedData[$i]['decisions_title']['language'] : "");
                $DT_Model->setTitle(array_key_exists("title", $MappedData[$i]['decisions_title']) ? $MappedData[$i]['decisions_title']['title'] : "");

                $DT_Model->save();

            }

            echo "\n\n DECISIONS COLLECTION DATA UPDATED! \n\n";

        }


        else if($coll_name === "Country Reports") {

            $Model = new CountryReports();
            $records = $Model::find();
            $records->delete();

            $Model2 = new CountryReportsDocuments();
            $records2 = $Model2::find();
            $records2->delete();


            for ($i = 0, $iMax = count($MappedData); $i < $iMax; $i++) {

                $CR_Model = new CountryReports();

                $CR_Model->setId(array_key_exists("id", $MappedData[$i]['country_reports']) ? $MappedData[$i]['country_reports']['id'] : "");
                $CR_Model->setTreaty(array_key_exists("treaty", $MappedData[$i]['country_reports']) ? $MappedData[$i]['country_reports']['treaty'] : "");
                $CR_Model->setCountry(array_key_exists("country", $MappedData[$i]['country_reports']) ? $MappedData[$i]['country_reports']['country'] : "");
                $CR_Model->setSubmission(array_key_exists("submission", $MappedData[$i]['country_reports']) ? $MappedData[$i]['country_reports']['submission'] : "");
                $CR_Model->setUrl(array_key_exists("url", $MappedData[$i]['country_reports']) ? $MappedData[$i]['country_reports']['url'] : "");
                $CR_Model->setAuthor(array_key_exists("author", $MappedData[$i]['country_reports']) ? $MappedData[$i]['country_reports']['author'] : "");
                $CR_Model->setUpdated(array_key_exists("updated", $MappedData[$i]['country_reports']) ? $MappedData[$i]['country_reports']['updated'] : "");

                $CR_Model->save();


                $CRD_Model = new CountryReportsDocuments();

                $CRD_Model->setId(array_key_exists("id", $MappedData[$i]['country_reports_documents']) ? $MappedData[$i]['country_reports_documents']['id'] : "");
                $CRD_Model->setCountryReportId(array_key_exists("country_report_id", $MappedData[$i]['country_reports_documents']) ? $MappedData[$i]['country_reports_documents']['country_report_id'] : "");
                $CRD_Model->setDiskPath(array_key_exists("diskPath", $MappedData[$i]['country_reports_documents']) ? $MappedData[$i]['country_reports_documents']['diskPath'] : "");
                $CRD_Model->setUrl(array_key_exists("url", $MappedData[$i]['country_reports_documents']) ? $MappedData[$i]['country_reports_documents']['url'] : "");
                $CRD_Model->setMimeType(array_key_exists("mimeType", $MappedData[$i]['country_reports_documents']) ? $MappedData[$i]['country_reports_documents']['mimeType'] : "");
                $CRD_Model->setLanguage(array_key_exists("language", $MappedData[$i]['country_reports_documents']) ? $MappedData[$i]['country_reports_documents']['language'] : "");
                $CRD_Model->setFileName(array_key_exists("filename", $MappedData[$i]['country_reports_documents']) ? $MappedData[$i]['country_reports_documents']['filename'] : "");

                $CRD_Model->save();


                $CRT_Model = new CountryReportsTitle();

                $CRT_Model->setId(array_key_exists("id", $MappedData[$i]['country_reports_title']) ? $MappedData[$i]['country_reports_title']['id'] : "");
                $CRT_Model->setCountryReportId(array_key_exists("country_report_id", $MappedData[$i]['country_reports_title']) ? $MappedData[$i]['country_reports_title']['country_report_id'] : "");
                $CRT_Model->setLanguage(array_key_exists("language", $MappedData[$i]['country_reports_title']) ? $MappedData[$i]['country_reports_title']['language'] : "");
                $CRT_Model->setTitle(array_key_exists("title", $MappedData[$i]['country_reports_title']) ? $MappedData[$i]['country_reports_title']['title'] : "");

                $CRT_Model->save();

            }

            echo "\n\n COUNTRY REPORTS COLLECTION DATA UPDATED! \n\n";

        }



        else if($coll_name === "General Documents") {
            $Model = new GeneralDocuments();
            $records = $Model::find();
            $records->delete();


            for ($i = 0, $iMax = count($MappedData); $i < $iMax; $i++) {

                $GD_Model = new GeneralDocuments();

                $GD_Model->setId(array_key_exists("id", $MappedData[$i]['general_documents']) ? $MappedData[$i]['general_documents']['id'] : "");
                $GD_Model->setTitle(array_key_exists("title", $MappedData[$i]['general_documents']) ? $MappedData[$i]['general_documents']['title'] : "");
                $GD_Model->setAuthor(array_key_exists("author", $MappedData[$i]['general_documents']) ? $MappedData[$i]['general_documents']['author'] : "");
                $GD_Model->setPublisher(array_key_exists("publisher", $MappedData[$i]['general_documents']) ? $MappedData[$i]['general_documents']['publisher'] : "");
                $GD_Model->setUrl(array_key_exists("url", $MappedData[$i]['general_documents']) ? $MappedData[$i]['general_documents']['url'] : "");
                $GD_Model->setDateIssued(array_key_exists("date_issued", $MappedData[$i]['general_documents']) ? $MappedData[$i]['general_documents']['date_issued'] : "");
                $GD_Model->setDateAvailable(array_key_exists("date_available", $MappedData[$i]['general_documents']) ? $MappedData[$i]['general_documents']['date_available'] : "");
                $GD_Model->setDateAccessioned(array_key_exists("available", $MappedData[$i]['general_documents']) ? $MappedData[$i]['general_documents']['available'] : "");
                $GD_Model->setLanguageIso(array_key_exists("language_iso", $MappedData[$i]['general_documents']) ? $MappedData[$i]['general_documents']['language_iso'] : "");
                $GD_Model->setUpdated(array_key_exists("updated", $MappedData[$i]['general_documents']) ? $MappedData[$i]['general_documents']['updated'] : "");

                $GD_Model->save();

            }

            echo "\n\n GENERAL DOCUMENTS COLLECTION DATA UPDATED! \n\n";


        }


        else if($coll_name === "Library Catalog") {
            $Model = new LibraryCatalog();
            $records = $Model::find();
            $records->delete();

            for ($i = 0, $iMax = count($MappedData); $i < $iMax; $i++) {

                $LC_Model = new LibraryCatalog();

                $LC_Model->setId(array_key_exists("id", $MappedData[$i]['library_catalog']) ? $MappedData[$i]['library_catalog']['id'] : "");
                $LC_Model->setTitle(array_key_exists("title", $MappedData[$i]['library_catalog']) ? $MappedData[$i]['library_catalog']['title'] : "");
                $LC_Model->setAuthor(array_key_exists("author", $MappedData[$i]['library_catalog']) ? $MappedData[$i]['library_catalog']['author'] : "");
                $LC_Model->setPublisher(array_key_exists("publisher", $MappedData[$i]['library_catalog']) ? $MappedData[$i]['library_catalog']['publisher'] : "");
                $LC_Model->setContributor(array_key_exists("contributor", $MappedData[$i]['library_catalog']) ? $MappedData[$i]['library_catalog']['contributor'] : "");
                $LC_Model->setDateIssued(array_key_exists("date_issued", $MappedData[$i]['library_catalog']) ? $MappedData[$i]['library_catalog']['date_issued'] : "");
                $LC_Model->setDateAccessioned(array_key_exists("date_accessioned", $MappedData[$i]['library_catalog']) ? $MappedData[$i]['library_catalog']['date_accessioned'] : "");
                $LC_Model->setDateAvailable(array_key_exists("date_available", $MappedData[$i]['library_catalog']) ? $MappedData[$i]['library_catalog']['date_available'] : "");
                $LC_Model->setDate(array_key_exists("date", $MappedData[$i]['library_catalog']) ? $MappedData[$i]['library_catalog']['date'] : "");
                $LC_Model->setVersion(array_key_exists("version", $MappedData[$i]['library_catalog']) ? $MappedData[$i]['library_catalog']['version'] : "");
                $LC_Model->setLanguage(array_key_exists("language", $MappedData[$i]['library_catalog']) ? $MappedData[$i]['library_catalog']['language'] : "");
                $LC_Model->setCategory(array_key_exists("category", $MappedData[$i]['library_catalog']) ? $MappedData[$i]['library_catalog']['category'] : "");
                $LC_Model->setClass(array_key_exists("class", $MappedData[$i]['library_catalog']) ? $MappedData[$i]['library_catalog']['class'] : "");
                $LC_Model->setWebsite(array_key_exists("website", $MappedData[$i]['library_catalog']) ? $MappedData[$i]['library_catalog']['website'] : "");
                $LC_Model->setUri(array_key_exists("uri", $MappedData[$i]['library_catalog']) ? $MappedData[$i]['library_catalog']['uri'] : "");
                $LC_Model->setPlace(array_key_exists("place", $MappedData[$i]['library_catalog']) ? $MappedData[$i]['library_catalog']['place'] : "");
                $LC_Model->setNotes(array_key_exists("notes", $MappedData[$i]['library_catalog']) ? $MappedData[$i]['library_catalog']['notes'] : "");
                $LC_Model->setInmagic(array_key_exists("inmagic", $MappedData[$i]['library_catalog']) ? $MappedData[$i]['library_catalog']['inmagic'] : "");
                $LC_Model->setUpdated(array_key_exists("updated", $MappedData[$i]['library_catalog']) ? $MappedData[$i]['library_catalog']['updated'] : "");

                $LC_Model->save();


            }

            echo "\n\n LIBRARY CATALOG COLLECTION DATA UPDATED! \n\n";


        }


        else if($coll_name === "Publications") {
            $Model = new Publications();
            $records = $Model::find();
            $records->delete();

            for ($i = 0, $iMax = count($MappedData); $i < $iMax; $i++) {

                $Pub_Model = new Publications();

                $Pub_Model->setId(array_key_exists("id", $MappedData[$i]['publications']) ? $MappedData[$i]['publications']['id'] : "");
                $Pub_Model->setTitle(array_key_exists("title", $MappedData[$i]['publications']) ? $MappedData[$i]['publications']['title'] : "");
                $Pub_Model->setTitleEs(array_key_exists("title_es", $MappedData[$i]['publications']) ? $MappedData[$i]['publications']['title_es'] : "");
                $Pub_Model->setTitleFr(array_key_exists("title_fr", $MappedData[$i]['publications']) ? $MappedData[$i]['publications']['title_fr'] : "");
                $Pub_Model->setAuthor(array_key_exists("author", $MappedData[$i]['publications']) ? $MappedData[$i]['publications']['author'] : "");
                $Pub_Model->setPublisher(array_key_exists("publisher", $MappedData[$i]['publications']) ? $MappedData[$i]['publications']['publisher'] : "");
                $Pub_Model->setContributor(array_key_exists("contributor", $MappedData[$i]['publications']) ? $MappedData[$i]['publications']['contributor'] : "");
                $Pub_Model->setVersion(array_key_exists("version", $MappedData[$i]['publications']) ? $MappedData[$i]['publications']['version'] : "");
                $Pub_Model->setLanguage(array_key_exists("language", $MappedData[$i]['publications']) ? $MappedData[$i]['publications']['language'] : "");
                $Pub_Model->setDescriptors(array_key_exists("descriptors", $MappedData[$i]['publications']) ? $MappedData[$i]['publications']['descriptors'] : "");
                $Pub_Model->setCategory(array_key_exists("category", $MappedData[$i]['publications']) ? $MappedData[$i]['publications']['category'] : "");
                $Pub_Model->setClass(array_key_exists("class", $MappedData[$i]['publications']) ? $MappedData[$i]['publications']['class'] : "");
                $Pub_Model->setIsPartOfSeries(array_key_exists("is_part_of_series", $MappedData[$i]['publications']) ? $MappedData[$i]['publications']['is_part_of_series'] : "");
                $Pub_Model->setSeries(array_key_exists("series", $MappedData[$i]['publications']) ? $MappedData[$i]['publications']['series'] : "");
                $Pub_Model->setType(array_key_exists("type", $MappedData[$i]['publications']) ? $MappedData[$i]['publications']['type'] : "");
                $Pub_Model->setIsbn(array_key_exists("isbn", $MappedData[$i]['publications']) ? $MappedData[$i]['publications']['isbn'] : "");
                $Pub_Model->setRights(array_key_exists("rights", $MappedData[$i]['publications']) ? $MappedData[$i]['publications']['rights'] : "");
                $Pub_Model->setPlace(array_key_exists("place", $MappedData[$i]['publications']) ? $MappedData[$i]['publications']['place'] : "");
                $Pub_Model->setWebsite(array_key_exists("website", $MappedData[$i]['publications']) ? $MappedData[$i]['publications']['website'] : "");
                $Pub_Model->setUri(array_key_exists("uri", $MappedData[$i]['publications']) ? $MappedData[$i]['publications']['uri'] : "");
                $Pub_Model->setDate(array_key_exists("date", $MappedData[$i]['publications']) ? $MappedData[$i]['publications']['date'] : "");
                $Pub_Model->setDateIssued(array_key_exists("date_issued", $MappedData[$i]['publications']) ? $MappedData[$i]['publications']['date_issued'] : "");
                $Pub_Model->setDateAvailable(array_key_exists("date_available", $MappedData[$i]['publications']) ? $MappedData[$i]['publications']['date_available'] : "");
                $Pub_Model->setDateAccessioned(array_key_exists("date_accessioned", $MappedData[$i]['publications']) ? $MappedData[$i]['publications']['date_accessioned'] : "");
                $Pub_Model->setUpdated(array_key_exists("updated", $MappedData[$i]['publications']) ? $MappedData[$i]['publications']['updated'] : "");

                $Pub_Model->save();

            }

            echo "\n\n PUBLICATIONS COLLECTION DATA UPDATED! \n\n";


        }

    }




    public function getDspaceData($collectionID){
        date_default_timezone_set("Africa/Nairobi");

        $coll_name = Collections::findFirst($collectionID)->collection;

        switch ($collectionID) {
            case 1:
                $file = "meetings";
                break;
            case 2;
                $file = "contacts";
                break;
            case 3:
                $file = "decisions";
                break;
            case 4:
                $file = "reports";
                break;
            case 5:
                $file = "general_documents";
                break;
            case 6:
                $file = "library_catalog";
                break;
            case 7:
                $file = "publications";
                break;
        }

        echo "COLLECTION NAME : " . $coll_name . "\n\n";

        $token = $this->session->get('token');

        $headers = ['Content-Type' => 'application/json', 'rest-dspace-token' => $token];

        //Getting all collection names from WeDocs
        $url1 = "collections/";
        $res1 = $this->dspace->get($url1, ['verify' => false, 'headers' => $headers]);
        $coll_data = json_decode($res1->getBody(), true);

//        echo ">>>>>>>> THIS IS ALL COLLECTIONS DATA <<<<<<<<<<<\n\n";
//        print_r($coll_data);
//        echo "\n\n";

        //Writing all collections data to its own .json file
        $coll_data_json = json_encode($coll_data);
        $coll_file = fopen("collections.json", 'wb');
        fwrite($coll_file, $coll_data_json);
        fclose($coll_file);


        //Finding the collection names
        $CollectionDetails = array();
        $j = 0;
        for ($i = 0, $iMax = count($coll_data); $i < $iMax; $i++) {
            if ($coll_data[$i]['name'] === $coll_name) {
                $CollectionDetails[$j]['name'] = $coll_name . " no. " . $i;
                $CollectionDetails[$j]['uuid'] = $coll_data[$i]['uuid'];
                echo "\n\n Found the collection name on index " . $i . " !!!! \n";
                echo "\n The collection has uuid " . $CollectionDetails[$j]['uuid'] . "\n\n";
                $j++;

            }
        }

//        echo "\n\n COLLECTION DETAILS FOR " . $coll_name;
//        echo "\n";
//        print_r($CollectionDetails);
//        echo "\n\n";

        $data = array();
        for ($i = 0, $iMax = count($CollectionDetails); $i < $iMax; $i++) {
            $Collection_UUID = $CollectionDetails[$i]['uuid'];

            if($coll_name === "Library Catalog"){

                $lc_data = array();
                $LCD = array();

                // looping with offsets
                $offset = 0;
                $retrieved = 223;
                while ($retrieved <= 2230) {
                    $url4 = "collections/" . $Collection_UUID . "/items?limit=223&offset=" . $offset;

                    $res4 = $this->dspace->get($url4, ['verify' => false, 'headers' => $headers]);
                    $lc_data = json_decode($res4->getBody(), true);
                    if (!$lc_data) {
                        echo "\n\n" . $Collection_UUID . " is empty.\n\n";
                    }

                    $LCD = array_merge($LCD, $lc_data);

                    $data[$i] = $LCD;

                    $retrieved += 223;
                    $offset += 223;

                }

//                echo "\n\n >>>>>>>> THIS IS SPECIFIC LIBRARY CATALOG DATA FOR " . $coll_name . " entry no. " . $i . " with uuid " . $Collection_UUID . " <<<<<<<<<<<\n\n";
//                print_r($data[$i]);
//                echo "\n\n";

            }


            else if($coll_name === "Meeting Documents"){
                $md_data = array();
                $MDD = array();

                // looping with offsets
                $offset = 0;
                $retrieved = 522;
                while ($retrieved <= 7308) {
                    $url3 = "collections/" . $Collection_UUID . "/items?limit=522&offset=" . $offset;

                    $res3 = $this->dspace->get($url3, ['verify' => false, 'headers' => $headers]);
                    $md_data = json_decode($res3->getBody(), true);
                    if (!$md_data) {
                        echo "\n\n" . $Collection_UUID . " is empty.\n\n";
                    }


                    $MDD = array_merge($MDD, $md_data);

                    $data[$i] = $MDD;

                    $retrieved += 522;
                    $offset += 522;

                }


//                echo "\n\n >>>>>>>> THIS IS SPECIFIC MEETING DOCUMENTS DATA FOR " . $coll_name . " entry no. " . $i . " with uuid " . $Collection_UUID . " <<<<<<<<<<<\n\n";
//                print_r($data[$i]);
//                echo "\n\n";
            }

            else {
                $url2 = "collections/" . $Collection_UUID . "/items?limit=100000";

                $res2 = $this->dspace->get($url2, ['verify' => false, 'headers' => $headers]);
                $data[$i] = json_decode($res2->getBody(), true);
                if (!$data[$i]) {
                    echo "\n\n" . $Collection_UUID . " is empty.\n\n";
                } else {
                    echo "\n>>>>>>>> FOUND THE SPECIFIC COLLECTION DATA FOR " . $coll_name . " entry no. " . $i . " with uuid " . $Collection_UUID . " <<<<<<<<<<<\n\n";

//                    print_r($data[$i]);
//                    echo "\n\n";
                }

            }

            //Skipping the unofficial meeting documents on index 30
            if ($coll_name === "Meeting Documents") {
                $i++;
//                echo "\n\n SKIPPED THE UNOFFICIAL MEETING DOCUMENTS \n\n";
            }
        }

//        echo "\n\n";
//        echo "THIS IS THE FULL DATA FOR " . $coll_name;
//        echo "\n";
//        print_r($data);

        //Getting the uuid for each item in the collection
        $meta_id = array();
        for ($i = 0, $iMax = count($data); $i < $iMax; $i++) {
            for ($j = 0, $jMax = count($data[$i]); $j < $jMax; $j++) {
                $meta_id[$j] = $data[$i][$j]['uuid'];
            }
        }

        echo "\n\n ALL METADATA UUIDS FOR " . $coll_name . "\n";
        print_r($meta_id);
        echo "\n\n";


        //Getting the metadata for every uuid in the collection
        $metadata = array();
        for ($i = 0, $iMax = count($meta_id); $i < $iMax; $i++) {
            $url3 = "items/" . $meta_id[$i] . "/metadata";
            $res3 = $this->dspace->get($url3, ['verify' => false, 'headers' => $headers]);
            $metadata[$i] = json_decode($res3->getBody(), true);
        }

//        echo "\n\n >>>>>>>>> THE COMPLETE METADATA FOR THE COLLECTION : " . $coll_name . " <<<<<<<<<<< \n\n";
//        print_r($metadata);
//        echo "\n\n";

        $data_json = json_encode($metadata);
        $dataFile = fopen("{$file}.json", "wb");
        fwrite($dataFile, $data_json);
        fclose($dataFile);


        return $metadata;
    }




    function getColumnMapping($collectionID){
        date_default_timezone_set("Africa/Nairobi");

        $MappedData = array();

        $coll_name = Collections::findFirst($collectionID)->collection;
        $Meetings_data = array();
        $Contacts_data = array();
        $Decisions_data = array();
        $CReports_data = array();
        $GenDocs_data = array();
        $LibCat_data = array();
        $Publications_data = array();


        if ($coll_name === "Meeting Documents") {
            //opening and reading .json file for the data
            $datastring = file_get_contents("meetings.json");
            $meets_array = json_decode($datastring, true);


            for ($i = 0, $iMax = count($meets_array); $i < $iMax; $i++) {
                for ($j = 0, $jMax = count($meets_array[$i]); $j < $jMax; $j++) {

                    //SETTING blank columns
                    $Meetings_data[$i]['meetings']['repetition'] = "N/A";
                    $Meetings_data[$i]['meetings']['kind'] = "N/A";
                    $Meetings_data[$i]['meetings']['access'] = "N/A";
                    $Meetings_data[$i]['meetings']['status'] = "N/A";
                    $Meetings_data[$i]['meetings']['imageUrl'] = "N/A";
                    $Meetings_data[$i]['meetings']['imageCopyright'] = "N/A";
                    $Meetings_data[$i]['meetings']['latitude'] = "";
                    $Meetings_data[$i]['meetings']['longitude'] = "";



                    //SETTING id and updated
                    $Meetings_data[$i]['meetings']['id'] = ($i + 1);
                    $Meetings_data[$i]['meetings']['updated'] = date("Y/m/d H:i:s");

                    $Meetings_data[$i]['meetings_description']['id'] = ($i + 1);
                    $Meetings_data[$i]['meetings_description']['meeting_id'] = ($i + 1);

                    $Meetings_data[$i]['meeting_title']['id'] = ($i + 1);
                    $Meetings_data[$i]['meeting_title']['meeting_id'] = ($i + 1);


                    if ($meets_array[$i][$j]['key'] === "wd.meeting.treaty"){
                        $Meetings_data[$i]['meetings']['treaty'] = $meets_array[$i][$j]['value'];
                    }
                    else if ($meets_array[$i][$j]['key'] === "dc.identifier.uri"){
                        $Meetings_data[$i]['meetings']['url'] = $meets_array[$i][$j]['value'];
                    }
                    else if ($meets_array[$i][$j]['key'] === "wd.meeting.startdate"){
                        $Meetings_data[$i]['meetings']['start'] = $meets_array[$i][$j]['value'];
                    }
                    else if ($meets_array[$i][$j]['key'] === "wd.meeting.enddate"){
                        $Meetings_data[$i]['meetings']['end'] = $meets_array[$i][$j]['value'];
                    }
                    else if ($meets_array[$i][$j]['key'] === "dc.type"){
                        $Meetings_data[$i]['meetings']['type'] = $meets_array[$i][$j]['value'];
                    }
                    else if ($meets_array[$i][$j]['key'] === "wd.meeting.location"){
                        $Meetings_data[$i]['meetings']['location'] = $meets_array[$i][$j]['value'];
                        $Meetings_data[$i]['meetings']['city'] = explode(", ", $meets_array[$i][$j]['value'], 2)[0];
                        $Meetings_data[$i]['meetings']['country'] = explode(", ", $meets_array[$i][$j]['value'], 2)[1];
                    }
                    else if ($meets_array[$i][$j]['key'] === "dc.contributor.author"){
                        $Meetings_data[$i]['meetings']['author'] = $meets_array[$i][$j]['value'];
                    }


                    else if ($meets_array[$i][$j]['key'] === "dc.language"){
                        //FOR meetings_description
                        $Meetings_data[$i]['meetings_description']['language'] = $meets_array[$i][$j]['value'];

                        //FOR meeting_title
                        $Meetings_data[$i]['meeting_title']['language'] = $meets_array[$i][$j]['value'];


                    }
                    else if ($meets_array[$i][$j]['key'] === "dc.description"){
                        //FOR meetings_description
                        $Meetings_data[$i]['meetings_description']['description'] = $meets_array[$i][$j]['value'];
                    }
                    else if ($meets_array[$i][$j]['key'] === "dc.title"){
                        //FOR meeting_title
                        $Meetings_data[$i]['meeting_title']['title'] = $meets_array[$i][$j]['value'];

                    }

                }
            }

//            echo "\n\n THE MAPPED DATA ARRAY FOR " . $coll_name . "\n\n";
//            print_r($Meetings_data);

            $MappedData = $Meetings_data;

        }


        else if ($coll_name === "Contacts") {
            //opening and reading .json file for the data
            $datastring = file_get_contents("contacts.json");
            $contacts_array = json_decode($datastring, true);


            for ($i = 0, $iMax = count($contacts_array); $i < $iMax; $i++) {
                for ($j = 0, $jMax = count($contacts_array[$i]); $j < $jMax; $j++) {

                    //Leaving the 'primary' column blank coz it's not in metadata
                    $Contacts_data[$i]['contacts']['primary'] = "";

                    //SETTING contact_id AND updated
                    $Contacts_data[$i]['contacts']['id'] = ($i + 1);
                    $Contacts_data[$i]['contacts']['updated'] = date("Y/m/d H:i:s");
                    $Contacts_data[$i]['contact_treaty']['contact_id'] = ($i + 1);


                    if ($contacts_array[$i][$j]['key'] === "unepmap.contact.country"){
                        $Contacts_data[$i]['contacts']['country'] = $contacts_array[$i][$j]['value'];
                    } else if ($contacts_array[$i][$j]['key'] === "unepmap.contact.prefix"){
                        $Contacts_data[$i]['contacts']['prefix'] = $contacts_array[$i][$j]['value'];
                    } else if ($contacts_array[$i][$j]['key'] === "unepmap.contact.firstname"){
                        $Contacts_data[$i]['contacts']['firstName'] = $contacts_array[$i][$j]['value'];
                    } else if ($contacts_array[$i][$j]['key'] === "unepmap.contact.lastname"){
                        $Contacts_data[$i]['contacts']['lastName'] = $contacts_array[$i][$j]['value'];
                    } else if ($contacts_array[$i][$j]['key'] === "unepmap.contact.position"){
                        $Contacts_data[$i]['contacts']['position'] = $contacts_array[$i][$j]['value'];
                    } else if ($contacts_array[$i][$j]['key'] === "unepmap.contact.organizations"){
                        $Contacts_data[$i]['contacts']['institution'] = $contacts_array[$i][$j]['value'];
                    } else if ($contacts_array[$i][$j]['key'] === "unepmap.contact.department"){
                        $Contacts_data[$i]['contacts']['department'] = $contacts_array[$i][$j]['value'];
                    } else if ($contacts_array[$i][$j]['key'] === "unepmap.contact.type"){
                        $Contacts_data[$i]['contacts']['type'] = $contacts_array[$i][$j]['value'];
                    } else if ($contacts_array[$i][$j]['key'] === "unepmap.contact.address"){
                        $Contacts_data[$i]['contacts']['address'] = $contacts_array[$i][$j]['value'];
                    } else if ($contacts_array[$i][$j]['key'] === "unepmap.contact.email"){
                        $Contacts_data[$i]['contacts']['email'] = $contacts_array[$i][$j]['value'];
                    } else if ($contacts_array[$i][$j]['key'] === "unepmap.contact.phone"){
                        $Contacts_data[$i]['contacts']['phoneNumber'] = $contacts_array[$i][$j]['value'];
                    } else if ($contacts_array[$i][$j]['key'] === "unepmap.contact.fax"){
                        $Contacts_data[$i]['contacts']['fax'] = $contacts_array[$i][$j]['value'];
                    } else if ($contacts_array[$i][$j]['key'] === "dc.contributor.author"){
                        $Contacts_data[$i]['contacts']['author'] = $contacts_array[$i][$j]['value'];
                    }
                    //For contact_treaty table
                    else if ($contacts_array[$i][$j]['key'] === "unepmap.identifier.treaty") {
                        $Contacts_data[$i]['contact_treaty']['treaty'] = $contacts_array[$i][$j]['value'];
                    }

                }

            }

//            echo "\n\n THE MAPPED DATA ARRAY FOR " . $coll_name . "\n\n";
//            print_r($Contacts_data);

            $MappedData = $Contacts_data;

        }


        else if ($coll_name === "Decisions") {
            //opening and reading .json file for the data
            $datastring = file_get_contents("decisions.json");
            $decisions_array = json_decode($datastring, true);


            for ($i = 0, $iMax = count($decisions_array); $i < $iMax; $i++) {
                for ($j = 0, $jMax = count($decisions_array[$i]); $j < $jMax; $j++) {
                    //blank columns FOR decisions table
                    $Decisions_data[$i]['decisions']['link'] = "N/A";
                    $Decisions_data[$i]['decisions']['published'] = "N/A";

                    //blank columns FOR decisions_content table
                    $Decisions_data[$i]['decisions_content']['content'] = "N/A";

                    //blank columns for decisions_documents
                    $Decisions_data[$i]['decisions_documents']['diskPath'] = "N/A";
                    $Decisions_data[$i]['decisions_documents']['mimeType'] = "N/A";
                    $Decisions_data[$i]['decisions_documents']['filename'] = "N/A";

                    //blank columns for decisions_keywords
                    $Decisions_data[$i]['decisions_keywords']['namespace'] = "N/A";
                    $Decisions_data[$i]['decisions_keywords']['term'] = "N/A";

                    //blank columns for decisions_summary
                    $Decisions_data[$i]['decisions_summary']['summary'] = "N/A";


                    //>>>>> SETTING decision_id AND updated<<<<<<<
                    $Decisions_data[$i]['decisions']['id'] = ($i + 1);
                    $Decisions_data[$i]['decisions']['updated'] = date("Y/m/d H:i:s");

                    $Decisions_data[$i]['decisions_documents']['id'] = ($i + 1);
                    $Decisions_data[$i]['decisions_documents']['decision_id'] = ($i + 1);//Adding 1 coz counter is zero-based

                    $Decisions_data[$i]['decisions_content']['id'] = ($i + 1);
                    $Decisions_data[$i]['decisions_content']['decision_id'] = ($i + 1);

                    $Decisions_data[$i]['decisions_keywords']['id'] = ($i + 1);
                    $Decisions_data[$i]['decisions_keywords']['decision_id'] = ($i + 1);

                    $Decisions_data[$i]['decisions_longtitle']['id'] = ($i + 1);
                    $Decisions_data[$i]['decisions_longtitle']['decision_id'] = ($i + 1);

                    $Decisions_data[$i]['decisions_summary']['id'] = ($i + 1);
                    $Decisions_data[$i]['decisions_summary']['decision_id'] = ($i + 1);

                    $Decisions_data[$i]['decisions_title']['id'] = ($i + 1);
                    $Decisions_data[$i]['decisions_title']['decision_id'] = ($i + 1);


                    if ($decisions_array[$i][$j]['key'] === "dc.type"){
                        //Here we concatenate the type because one decision may have more than one type
                        $Decisions_data[$i]['decisions']['type'] .= ", " . $decisions_array[$i][$j]['value'];
                    }

                    else if ($decisions_array[$i][$j]['key'] === "unepmap.identifier.status"){
                        $Decisions_data[$i]['decisions']['status'] = $decisions_array[$i][$j]['value'];
                    }

                    else if ($decisions_array[$i][$j]['key'] === "unepmap.identifier.number"){
                        $Decisions_data[$i]['decisions']['number'] = $decisions_array[$i][$j]['value'];
                    }

                    else if ($decisions_array[$i][$j]['key'] === "unepmap.identifier.treaty"){
                        $Decisions_data[$i]['decisions']['treaty'] = $decisions_array[$i][$j]['value'];
                    }

                    else if ($decisions_array[$i][$j]['key'] === "unepmap.identifier.meeting-id"){
                        $Decisions_data[$i]['decisions']['meetingId'] = $decisions_array[$i][$j]['value'];
                    }

                    else if ($decisions_array[$i][$j]['key'] === "dc.title"){
                        $Decisions_data[$i]['decisions']['meetingTitle'] = $decisions_array[$i][$j]['value'];
                        //FOR decisions_longtitle
                        $Decisions_data[$i]['decisions_longtitle']['long_title'] = $decisions_array[$i][$j]['value'];
                        //FOR decisions_title
                        $Decisions_data[$i]['decisions_title']['title'] = $decisions_array[$i][$j]['value'];
                    }

                    else if ($decisions_array[$i][$j]['key'] === "unepmap.identifier.url"){
                        $Decisions_data[$i]['decisions']['meetingUrl'] = $decisions_array[$i][$j]['value'];
                        //FOR decisions_documents
                        $Decisions_data[$i]['decisions_documents']['url'] =  $decisions_array[$i][$j]['value'];
                    }

                    else if ($decisions_array[$i][$j]['key'] === "unepmap.identifier.display-order"){
                        $Decisions_data[$i]['decisions']['displayOrder'] = $decisions_array[$i][$j]['value'];
                    }

                    else if ($decisions_array[$i][$j]['key'] === "dc.contributor.author"){
                        $Decisions_data[$i]['decisions']['author'] = $decisions_array[$i][$j]['value'];
                    }

                    else if ($decisions_array[$i][$j]['key'] === "dc.language"){
                        //FOR decisions_content table
                        $Decisions_data[$i]['decisions_content']['language'] .= " ," . $decisions_array[$i][$j]['value'];
                        //FOR decisions_documents
                        $Decisions_data[$i]['decisions_documents']['language'] .= " ," . $decisions_array[$i][$j]['value'];
                        //FOR decisions_longtitle
                        $Decisions_data[$i]['decisions_longtitle']['language'] .= " ," . $decisions_array[$i][$j]['value'];
                        //FOR decisions_summary
                        $Decisions_data[$i]['decisions_summary']['language'] .= " ," . $decisions_array[$i][$j]['value'];
                        //FOR decisions_title
                        $Decisions_data[$i]['decisions_title']['language'] .= " ," . $decisions_array[$i][$j]['value'];

                    }

                }
            }

//            echo "\n\n THE MAPPED DATA ARRAY FOR " . $coll_name . "\n\n";
//            print_r($Decisions_data);

            $MappedData = $Decisions_data;
        }

        else if ($coll_name === "Country Reports"){
            //opening and reading .json file for the data
            $datastring = file_get_contents("reports.json");
            $reports_array = json_decode($datastring, true);

            for ($i = 0, $iMax = count($reports_array); $i < $iMax; $i++) {
                for ($j = 0, $jMax = count($reports_array[$i]); $j < $jMax; $j++) {

                    //SETTING blank columns
                    $CReports_data[$i]['country_reports_documents']['diskPath'] = "N/A";
                    $CReports_data[$i]['country_reports_documents']['mimeType'] = "N/A";
                    $CReports_data[$i]['country_reports_documents']['filename'] = "N/A";


                    //SETTING id's AND updated
                    $CReports_data[$i]['country_reports']['id'] = ($i + 1);
                    $CReports_data[$i]['country_reports']['updated'] = date("Y/m/d H:i:s");

                    $CReports_data[$i]['country_reports_documents']['id'] = ($i + 1);
                    $CReports_data[$i]['country_reports_documents']['country_report_id'] = ($i + 1);

                    $CReports_data[$i]['country_reports_title']['id'] = ($i + 1);
                    $CReports_data[$i]['country_reports_title']['country_report_id'] = ($i + 1);


                    if ($reports_array[$i][$j]['key'] === "unepmap.identifier.treaty"){
                        $CReports_data[$i]['country_reports']['treaty'] .= ", " . $reports_array[$i][$j]['value'];
                    }
                    else if ($reports_array[$i][$j]['key'] === "unepmap.identifier.country"){
                        $CReports_data[$i]['country_reports']['country'] .= ", " . $reports_array[$i][$j]['value'];
                    }
                    //Using dc.date.issued as 'submission'
                    else if ($reports_array[$i][$j]['key'] === "dc.date.issued"){
                        $CReports_data[$i]['country_reports']['submission'] = $reports_array[$i][$j]['value'];
                    }
                    else if ($reports_array[$i][$j]['key'] === "unepmap.identifier.url"){
                        $CReports_data[$i]['country_reports']['url'] = $reports_array[$i][$j]['value'];
                        //FOR country_reports_documents
                        $CReports_data[$i]['country_reports_documents']['url'] = $reports_array[$i][$j]['value'];

                    }
                    else if ($reports_array[$i][$j]['key'] === "dc.contributor.author"){
                        $CReports_data[$i]['country_reports']['author'] = $reports_array[$i][$j]['value'];
                    }

                    //FOR country_reports_documents
                    else if ($reports_array[$i][$j]['key'] === "dc.contributor.author"){
                        $CReports_data[$i]['country_reports_documents']['author'] = $reports_array[$i][$j]['value'];
                    }
                    else if ($reports_array[$i][$j]['key'] === "dc.language"){
                        $CReports_data[$i]['country_reports_documents']['language'] .= ", " . $reports_array[$i][$j]['value'];

                        //FOR country_reports_title
                        $CReports_data[$i]['country_reports_title']['language'] .= ", " . $reports_array[$i][$j]['value'];

                    }

                    //FOR country_reports_title
                    else if ($reports_array[$i][$j]['key'] === "dc.title"){
                        $CReports_data[$i]['country_reports_title']['title'] = $reports_array[$i][$j]['value'];
                    }

                }
            }

//            echo "\n\n THE MAPPED DATA ARRAY FOR " . $coll_name . "\n\n";
//            print_r($CReports_data);

            $MappedData = $CReports_data;

        }


        else if ($coll_name === "General Documents") {

            //opening and reading .json file for the data
            $datastring = file_get_contents("general_documents.json");
            $gendocs_array = json_decode($datastring, true);


            for ($i = 0, $iMax = count($gendocs_array); $i < $iMax; $i++) {
                for ($j = 0, $jMax = count($gendocs_array[$i]); $j < $jMax; $j++) {

                    //SETTING id AND updated
                    $GenDocs_data[$i]['general_documents']['id'] = ($i + 1);
                    $GenDocs_data[$i]['general_documents']['updated'] = date("Y/m/d H:i:s");


                    if ($gendocs_array[$i][$j]['key'] === "dc.title"){
                        $GenDocs_data[$i]['general_documents']['title'] = $gendocs_array[$i][$j]['value'];
                    }
                    else if ($gendocs_array[$i][$j]['key'] === "dc.contributor.author"){
                        $GenDocs_data[$i]['general_documents']['author'] = $gendocs_array[$i][$j]['value'];
                    }
                    else if ($gendocs_array[$i][$j]['key'] === "dc.publisher"){
                        $GenDocs_data[$i]['general_documents']['publisher'] = $gendocs_array[$i][$j]['value'];
                    }
                    else if ($gendocs_array[$i][$j]['key'] === "dc.identifier.uri"){
                        $GenDocs_data[$i]['general_documents']['url'] = $gendocs_array[$i][$j]['value'];
                    }
                    else if ($gendocs_array[$i][$j]['key'] === "dc.date.issued"){
                        $GenDocs_data[$i]['general_documents']['date_issued'] = $gendocs_array[$i][$j]['value'];
                    }
                    else if ($gendocs_array[$i][$j]['key'] === "dc.date.accessioned"){
                        $GenDocs_data[$i]['general_documents']['date_accessioned'] = $gendocs_array[$i][$j]['value'];
                    }
                    else if ($gendocs_array[$i][$j]['key'] === "dc.date.available"){
                        $GenDocs_data[$i]['general_documents']['date_available'] = $gendocs_array[$i][$j]['value'];
                    }
                    else if ($gendocs_array[$i][$j]['key'] === "dc.language.iso"){
                        $GenDocs_data[$i]['general_documents']['language_iso'] = $gendocs_array[$i][$j]['value'];
                    }


                }
            }

//            echo "\n\n THE MAPPED DATA ARRAY FOR " . $coll_name . "\n\n";
//            print_r($GenDocs_data);

            $MappedData = $GenDocs_data;

        }


        else if ($coll_name === "Library Catalog") {

            //opening and reading .json file for the data
            $datastring = file_get_contents("library_catalog.json");
            $libcat_array = json_decode($datastring, true);


            for ($i = 0, $iMax = count($libcat_array); $i < $iMax; $i++) {
                for ($j = 0, $jMax = count($libcat_array[$i]); $j < $jMax; $j++) {

                    //SETTING id AND updated
                    $LibCat_data[$i]['library_catalog']['id'] = ($i + 1);
                    $LibCat_data[$i]['library_catalog']['updated'] = date("Y/m/d H:i:s");


                    if ($libcat_array[$i][$j]['key'] === "dc.title"){
                        $LibCat_data[$i]['library_catalog']['title'] = $libcat_array[$i][$j]['value'];
                    }
                    else if ($libcat_array[$i][$j]['key'] === "unepmap.identifier.author"){
                        $LibCat_data[$i]['library_catalog']['author'] = $libcat_array[$i][$j]['value'];
                    }
                    else if ($libcat_array[$i][$j]['key'] === "dc.publisher"){
                        $LibCat_data[$i]['library_catalog']['publisher'] = $libcat_array[$i][$j]['value'];
                    }
                    else if ($libcat_array[$i][$j]['key'] === "dc.publisher"){
                        $LibCat_data[$i]['library_catalog']['publisher'] = $libcat_array[$i][$j]['value'];
                    }
                    else if ($libcat_array[$i][$j]['key'] === "dc.contributor"){
                        $LibCat_data[$i]['library_catalog']['contributor'] = $libcat_array[$i][$j]['value'];
                    }
                    else if ($libcat_array[$i][$j]['key'] === "dc.date.issued"){
                        $LibCat_data[$i]['library_catalog']['date_issued'] = $libcat_array[$i][$j]['value'];
                    }
                    else if ($libcat_array[$i][$j]['key'] === "dc.date.accessioned"){
                        $LibCat_data[$i]['library_catalog']['date_accessioned'] = $libcat_array[$i][$j]['value'];
                    }
                    else if ($libcat_array[$i][$j]['key'] === "dc.date.available"){
                        $LibCat_data[$i]['library_catalog']['date_available'] = $libcat_array[$i][$j]['value'];
                    }
                    else if ($libcat_array[$i][$j]['key'] === "dc.date"){
                        $LibCat_data[$i]['library_catalog']['date'] = $libcat_array[$i][$j]['value'];
                    }
                    else if ($libcat_array[$i][$j]['key'] === "dc.description.version"){
                        $LibCat_data[$i]['library_catalog']['version'] = $libcat_array[$i][$j]['value'];
                    }
                    else if ($libcat_array[$i][$j]['key'] === "dc.language"){
                        $LibCat_data[$i]['library_catalog']['language'] = $libcat_array[$i][$j]['value'];
                    }
                    else if ($libcat_array[$i][$j]['key'] === "unepmap.identifier.category"){
                        $LibCat_data[$i]['library_catalog']['category'] = $libcat_array[$i][$j]['value'];
                    }
                    else if ($libcat_array[$i][$j]['key'] === "unepmap.identifier.class"){
                        $LibCat_data[$i]['library_catalog']['class'] = $libcat_array[$i][$j]['value'];
                    }
                    else if ($libcat_array[$i][$j]['key'] === "unepmap.identifier.website"){
                        $LibCat_data[$i]['library_catalog']['website'] = $libcat_array[$i][$j]['value'];
                    }
                    else if ($libcat_array[$i][$j]['key'] === "dc.identifier.uri"){
                        $LibCat_data[$i]['library_catalog']['uri'] = $libcat_array[$i][$j]['value'];
                    }
                    else if ($libcat_array[$i][$j]['key'] === "unepmap.identifier.place"){
                        $LibCat_data[$i]['library_catalog']['place'] = $libcat_array[$i][$j]['value'];
                    }
                    else if ($libcat_array[$i][$j]['key'] === "unepmap.notes"){
                        $LibCat_data[$i]['library_catalog']['notes'] = $libcat_array[$i][$j]['value'];
                    }
                    else if ($libcat_array[$i][$j]['key'] === "unepmap.identifier.inmagic"){
                        $LibCat_data[$i]['library_catalog']['inmagic'] = $libcat_array[$i][$j]['value'];
                    }


                }
            }


//            echo "\n\n THE MAPPED DATA ARRAY FOR " . $coll_name . "\n\n";
//            print_r($LibCat_data);

            $MappedData = $LibCat_data;

        }


        else if ($coll_name === "Publications") {

            //opening and reading .json file for the data
            $datastring = file_get_contents("publications.json");
            $pub_array = json_decode($datastring, true);


            for ($i = 0, $iMax = count($pub_array); $i < $iMax; $i++) {
                for ($j = 0, $jMax = count($pub_array[$i]); $j < $jMax; $j++) {

                    //SETTING the id and updated
                    $Publications_data[$i]['publications']['id'] = ($i + 1);
                    $Publications_data[$i]['publications']['updated'] = date("Y/m/d H:i:s");


                    if ($pub_array[$i][$j]['key'] === "dc.title"){
                        $Publications_data[$i]['publications']['title'] = $pub_array[$i][$j]['value'];
                    }
                    else if ($pub_array[$i][$j]['key'] === "wd.title.es"){
                        $Publications_data[$i]['publications']['title_es'] = $pub_array[$i][$j]['value'];
                    }
                    else if ($pub_array[$i][$j]['key'] === "wd.title.fr"){
                        $Publications_data[$i]['publications']['title_fr'] = $pub_array[$i][$j]['value'];
                    }
                    else if ($pub_array[$i][$j]['key'] === "unepmap.identifier.author"){
                        $Publications_data[$i]['publications']['author'] = $pub_array[$i][$j]['value'];
                    }
                    else if ($pub_array[$i][$j]['key'] === "dc.publisher"){
                        $Publications_data[$i]['publications']['publisher'] = $pub_array[$i][$j]['value'];
                    }
                    else if ($pub_array[$i][$j]['key'] === "dc.contributor"){
                        $Publications_data[$i]['publications']['contributor'] = $pub_array[$i][$j]['value'];
                    }
                    else if ($pub_array[$i][$j]['key'] === "dc.description.version"){
                        $Publications_data[$i]['publications']['version'] = $pub_array[$i][$j]['value'];
                    }
                    else if ($pub_array[$i][$j]['key'] === "dc.language"){
                        $Publications_data[$i]['publications']['language'] = $pub_array[$i][$j]['value'];
                    }
                    else if ($pub_array[$i][$j]['key'] === "dc.descriptors"){
                        $Publications_data[$i]['publications']['descriptors'] = $pub_array[$i][$j]['value'];
                    }
                    else if ($pub_array[$i][$j]['key'] === "unepmap.identifier.category"){
                        $Publications_data[$i]['publications']['category'] = $pub_array[$i][$j]['value'];
                    }
                    else if ($pub_array[$i][$j]['key'] === "unepmap.identifier.class"){
                        $Publications_data[$i]['publications']['class'] = $pub_array[$i][$j]['value'];
                    }
                    else if ($pub_array[$i][$j]['key'] === "dc.relation.ispartofseries"){
                        $Publications_data[$i]['publications']['is_part_of_series'] = $pub_array[$i][$j]['value'];
                    }
                    else if ($pub_array[$i][$j]['key'] === "unepmap.identifier.series"){
                        $Publications_data[$i]['publications']['series'] = $pub_array[$i][$j]['value'];
                    }
                    else if ($pub_array[$i][$j]['key'] === "dc.type"){
                        $Publications_data[$i]['publications']['type'] = $pub_array[$i][$j]['value'];
                    }
                    else if ($pub_array[$i][$j]['key'] === "dc.identifier.isbn"){
                        $Publications_data[$i]['publications']['isbn'] = $pub_array[$i][$j]['value'];
                    }
                    else if ($pub_array[$i][$j]['key'] === "dc.rights"){
                        $Publications_data[$i]['publications']['rights'] = $pub_array[$i][$j]['value'];
                    }
                    else if ($pub_array[$i][$j]['key'] === "unepmap.identifier.place"){
                        $Publications_data[$i]['publications']['place'] = $pub_array[$i][$j]['value'];
                    }
                    else if ($pub_array[$i][$j]['key'] === "unepmap.identifier.website"){
                        $Publications_data[$i]['publications']['website'] = $pub_array[$i][$j]['value'];
                    }
                    else if ($pub_array[$i][$j]['key'] === "dc.identifier.uri"){
                        $Publications_data[$i]['publications']['uri'] = $pub_array[$i][$j]['value'];
                    }
                    else if ($pub_array[$i][$j]['key'] === "dc.date"){
                        $Publications_data[$i]['publications']['date'] = $pub_array[$i][$j]['value'];
                    }
                    else if ($pub_array[$i][$j]['key'] === "dc.date.issued"){
                        $Publications_data[$i]['publications']['date_issued'] = $pub_array[$i][$j]['value'];
                    }
                    else if ($pub_array[$i][$j]['key'] === "dc.date.available"){
                        $Publications_data[$i]['publications']['date_available'] = $pub_array[$i][$j]['value'];
                    }
                    else if ($pub_array[$i][$j]['key'] === "dc.date.accessioned"){
                        $Publications_data[$i]['publications']['date_accessioned'] = $pub_array[$i][$j]['value'];
                    }


                }
            }

//            echo "\n\n THE MAPPED DATA ARRAY FOR " . $coll_name . "\n\n";
//            print_r($Publications_data);

            $MappedData = $Publications_data;

        }


        return $MappedData;
    }
}

