<?php
$arrFields = array("pic", "Name", "Surname", "gender", "playingage", "email", "password", "password", "mobile", "country", "city", "ethnicity", "nationality", "languages", "category", "minrate" , "website", "cvlink", "showreellink", "height", "waist", "collar", "chest_breast", "shoes", "hips", "hair", "hairlength", "eyes", "weight", "build", "skin", "nude", "newsletter", "contact");

$action = @$_REQUEST['action'];

if ($action != null){
    switch ($action){
        case "table":
                $tbl_fields = array(
                'id' => array(
                                'type' => 'int',
                                'constraint' => 11, 
                                'null' => false,
                                'auto_increment' => true
                          ),
                'fromEmail' => array(
                                'type' => 'varchar',
                                'constraint' => 50,
                                'null' => false
                          ),
                'toEmail' => array(
                                'type' => 'varchar',
                                'constraint' => 50,
                                'null' => false
                          ),                
                'text' => array(
                                'type' => 'text',
                                'null' => false
                          ),
                'time' => array(
                                'type' => 'varchar',
                                'constraint' => 50, 
                                'null' => false
                          )
            );
                    
            $db->add_field($tbl_fields);
            $db->add_key('id', true);
            $db->create_table(TBL_MESSAGE, true);

            break;
        case "create":        
            $db->where('email', $_REQUEST['email']);
            $db->from(TBL_NAME);
            $intRows = $db->count_all_results();
            
            if ($intRows > 0){
                die("{$_REQUEST['email']} was already registered.");
            }
                    
            $data = array();
                
            foreach ($arrFields as $strFieldNamd){
                $strReceivedValue = @$_REQUEST[$strFieldNamd];
                if ($strReceivedValue != null){
                    $data[$strFieldNamd] = $strReceivedValue; 
                }
            }  

            if ($db->insert(TBL_NAME, $data)){
                echo "Congratulations!\nYou suceed in creating profile.";
            } else {
                echo "Unfortunately, you are failed in creating profile.";    
            }
            
            if (!isset($_FILES['userPhoto']))break;
            
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES['userPhoto']['name']);            

            $uploadOK = 1;
            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
            
            //Chek it image file is a actual image or fake image
            $check = getimagesize($_FILES['userPhoto']['tmp_name']);
            if ($check !== false){
//                echo "File is an image - " . $check['mime'] . ".";
                $uploadOK = 1;
            } else {
                echo "File is not an image.";
                $uploadOK = 0;
            }
            
            //Check if file already exists
            if (file_exists($target_file)){
                echo "Sorry, file already exists.";
                $uploadOK = 0;
            }
            
            //Check file size is more than 10 MB
            if ($_FILES['userPhoto']['size'] > 10000000){
                echo "Sorry, your file is too large.";
                $uploadOK = 0;
            }
            
            //Allow certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif"){
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOK = 0;
            }
            
            //Check if $uploadOK is set to 0 by an error
            if ($uploadOK == 0){
                echo "Sorry, your file was not uploaded.";
            //if everyting is OK, try to upload file
            } else {
                if (move_uploaded_file($_FILES['userPhoto']['tmp_name'], $target_file)){
//                    echo "The file ". basename($_FILES['userPhoto']['name']) . " has been uploaded.";
                } else {
                    echo "Sorry, there was an error uploading your photo.";
                }
            }
            
            break;
        case "update":
            $user = $_REQUEST['user'];            
            
            $data = array();
            
            foreach ($arrFields as $strFieldNamd){
                $strReceivedValue = @$_REQUEST[$strFieldNamd];
                $data[$strFieldNamd] = $strReceivedValue; 
            }
            
            $db->where('email', $user);
            if ($db->update(TBL_NAME, $data)){
                echo "Congratulations!\nYou suceed in updating profile.";
            } else {
                echo "Unfortunately, you are failed in updating profile.";    
            }
            
            if (!isset($_FILES['userPhoto']))break;
            
            $target_dir = "uploads/";
            $original_photo = $target_dir . $user . ".png";
            $target_file = $target_dir . basename($_FILES['userPhoto']['name']);            

            $uploadOK = 1;
            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
            
            //Chek it image file is a actual image or fake image
            $check = getimagesize($_FILES['userPhoto']['tmp_name']);
            if ($check !== false){
//                echo "File is an image - " . $check['mime'] . ".";
                $uploadOK = 1;
            } else {
                echo "File is not an image.";
                $uploadOK = 0;
            }
            
            //Check file size is more than 10 MB
            if ($_FILES['userPhoto']['size'] > 10000000){
                echo "Sorry, your file is too large.";
                $uploadOK = 0;
            }
            
            //Allow certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif"){
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                $uploadOK = 0;
            }
            
            //Check if $uploadOK is set to 0 by an error
            if ($uploadOK == 0){
                echo "Sorry, your file was not uploaded.";
            //if everyting is OK, try to upload file
            } else {
                unlink($original_photo);

                if (move_uploaded_file($_FILES['userPhoto']['tmp_name'], $target_file)){
//                    echo "The file ". basename($_FILES['userPhoto']['name']) . " has been uploaded.";
                } else {
                    echo "Sorry, there was an error uploading your photo.";
                }
            }                        
            break;
        case "login":
            $email = $_REQUEST['email'];
            $password = $_REQUEST['password'];
            
            $db->where('email', $email);
            $db->from(TBL_NAME);
            $intRows = $db->count_all_results();
            
            if ($intRows == 0)die("{$email} does not exist.\nEnter a different email address.");
            
            $db->where('email', $email);
            $db->where('password', $password);
            $db->from(TBL_NAME);
            $intRows = $db->count_all_results();
            
            if ($intRows == 0)die("That password is incorrect.");
            
            echo "success!";
            
            break;
        case "retrieve":
            $param = $_REQUEST['param'];
            $email = $_REQUEST['email'];
            
            $db->where('email', $email);
            $db->from(TBL_NAME);
            $db->select($param);
            $row = $db->get()->first_row();
            
            echo $row->$param;
            
            break;
        case "check":
            $email = $_REQUEST['email'];
            $Name = @$_REQUEST['Surname'];
            $password = @$_REQUEST['password'];
            
            $db->where('email', $email);
            $db->from(TBL_NAME);
                        
            if(isset($Name)){
                $db->where('Surname', $Name);
                $intRows = $db->count_all_results();
                if ($intRows == 1){
                    $db->where('email', $email);
                    $db->select('password');
                    $row = $db->get(TBL_NAME)->first_row();
                    $password = $row->password;
                    echo substr($password, strlen($password) - 5);
                } else echo "failed!";
            } else if (isset($password)){
                $db->where('password', $password);
                $intRows = $db->count_all_results();
                if ($intRows == 1)echo "success!"; else "wrong!";
            } else {     
                $intRows = $db->count_all_results();
                if ($intRows == 1)echo "success!"; else "That email doesn't exist!";           
            }
            break;
        case "search":
            $key = $_REQUEST['key'];
            $value = @$_REQUEST['value'];
            $field = @$_REQUEST['field'];
            
            if (isset($field)){
                $db->where($field, $key);
            } else {   
                if ($key == "")$key = "Name";
                $db->like($key, $value);         
                $db->order_by('Name', 'asc');
            }
            
            $query = $db->get(TBL_NAME);            
                        
            $data = array();
            
            $email = @$_REQUEST['email'];
            
            foreach ($query->result() as $row){
                if ($row->email == $email)continue;
                
                $data1 = array();
                foreach($arrFields as $strField){                    
                    $data1[$strField] = $row->$strField;
                }
                
                $data[] = implode("[;]", $data1);
            }
            
            $strReturn = implode("[:]", $data);
            echo $strReturn;
            
            break;
        case "send":
            $db->set('fromEmail', $_REQUEST['fromEmail']);
            $db->set('toEmail', $_REQUEST['toEmail']);
            $db->set('text', $_REQUEST['text']);
            $db->set('time', time());
            $db->set('status', "sealed");
            
            if ($db->insert(TBL_MESSAGE)){
                echo "New message sent!";
            } else {
                echo "Sending message failed!";
            }
            
            break;
        case "find":
            $status = $_REQUEST['status'];

            
            if ($status == "delete"){
                $db->where('id', $_REQUEST['email']);
                $db->set('status', 'deleted');
                $db->update(TBL_MESSAGE);
                break;
            }
            
            $db->where('toEmail', $_REQUEST['email']);
            $db->where('status', $status);
            
            $db->order_by('time', 'desc');
            
            $query = $db->get(TBL_MESSAGE);
            
            $data = array();
            
            foreach($query->result() as $row){
                $data[] = $row->id . "\n" . $row->fromEmail . "\n" . $row->text;
                
                if ($status == "sealed"){
                    $db->where('id', $row->id);
                    $db->set('status', 'opened');
                    $db->update(TBL_MESSAGE);
                }
                
            }
            
            echo implode("\n\n", $data);
            
            break;
        case "filter":
            extract($_REQUEST, EXTR_OVERWRITE, "");
            $db->like("category", $category);
            $db->like("playingage", $playingage);
            $db->like("gender", $gender);
            $db->like("country", $country);
            $db->like("city", $city);
            $db->like("ethnicity", $ethnicity);
            $db->like("languages", $languages);
            $db->like("hair", $hair);
            $db->like("eyes", $eyes);
            $db->like("build", $build);
            $db->like("skin", $skin);
            $db->like("nude", $nude);
            
            $query = $db->get(TBL_NAME);
            
            $data = array();
           
            foreach ($query->result() as $row){
                if ($row->email == $email)continue;
                if((floatval($row->chest_breast) < floatval($min_chest) && $min_chest != "") || (floatval($row->chest_breast) > floatval($max_chest) && $max_chest != ""))continue;            
                if((floatval($row->waist) < floatval($min_waist) && $min_waist != "") || (floatval($row->waist) > floatval($max_waist) && $max_waist != ""))continue;

               
                if((floatHeight($row->height) < floatHeight($min_height) && $min_height != "") || (floatHeight($row->height) > floatHeight($max_height) && $max_height != ""))continue;
                
                if((floatWeight($row->weight) < floatWeight($min_weight) && $min_weight != "") || (floatWeight($row->weight) > floatWeight($max_weight) && $max_weight != ""))continue;
                
                $data1 = array();
                foreach($arrFields as $strField){                    
                    $data1[$strField] = $row->$strField;
                }
                
                $data[] = implode("[;]", $data1);
            }
                        
            $strReturn = implode("[:]", $data);
            echo $strReturn;
            if (count($data) == 0)echo "no data";
            
            break;
        default:
            break;
    }
}

function floatHeight($strHeight){
    if ($strHeight == "")return 0.0;
    $first_splited_height = explode("ft,", $strHeight);
    $second_splited_height = explode("in", $first_splited_height[1]);
    
    return 12 * floatval($first_splited_height[0]) + floatval($second_splited_height[0]);
}

function floatWeight($strWeight){
    if($strWeight == "")return 0.0;
    $first_splited_height = explode("st,", $strWeight);
    $second_splited_height = explode("lb", $first_splited_height[1]);
    
    return 14 * floatval($first_splited_height[0]) + floatval($second_splited_height[0]);
}

?>
