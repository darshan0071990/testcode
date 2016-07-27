<?php

class ChatController extends Controller
{

    public function actionNearby(){

        if(isset($_GET)){
            $latitude = $_GET['latitude'];
            $longitude = $_GET['longitude'];
            $user_id = $_GET['user_id'];

            $criteria = new CDbCriteria();
            $criteria->select = "user_id,display_name,role, (3959 * ACOS(COS( RADIANS('".$latitude."')) * COS(RADIANS(`latitude`)) * COS(RADIANS(`longitude`) - RADIANS('".$longitude."')) + SIN( RADIANS('".$latitude."')) * SIN(RADIANS(`latitude`)))) AS distance";
            $criteria->having = "distance < 20";
            $criteria->condition = "role = 'App' and t.user_id != '$user_id'";
            $criteria->order = "distance ASC";
            $criteria->with = array('user'=>array('with'=>array('userAlbums')));

            $model = UserLogin::model()->findAll($criteria);
            $response = array();
            if(!empty($model)){
                $count = 0;
                foreach($model as $r){

                    $response['status'] = "Success";
                    $response['user'][$count]['name']  = $r->display_name;
                    $response['user'][$count]['id']  = $r->user_id;
                    $response['user'][$count]['sex']  = $r->user->sex;
                    $response['user'][$count]['dob']  = $r->user->dob;
                    $response['user'][$count]['about_me']  = $r->user->about_me;
                    if(!empty($r->user)){
                        if(!empty($r->user->userAlbums)){
                            $counter = 0;
                            foreach($r->user->userAlbums as $a){
                                $response['user'][$count]['images'][$counter]  = Yii::app()->getBaseUrl(true) . "/images/users/" . $r->user_id . "/" . $a->image_url;
                                $counter++;
                            }
                        }
                    }
                    $count++;
                }
            }else{
                $response['status'] = "Failure";
                $response['message'] = "No Users Nearby";
            }
            echo json_encode($response);
            exit;
        }
    }

    public function actionChat_request(){

        $response = array();

        if(isset($_GET)){
            $userone = $_GET['user_one'];
            $usertwo = $_GET['user_two'];
            $model = new ChatRequest();
            $model->user_one = $userone;
            $model->user_two = $usertwo;
            $model->created_date = date('Y-m-d H:i:s');
            if($model->save()){
                $response['status'] = "success";
                $response["message"] = "Successfully Sent Request";
            }else{
                $response['status'] = "failure";
                $response["message"] = "Failed to Sent Request";
            }
        }
        echo json_encode($response);
    }

    public function actionChat_accept(){

        $response = array();
        if(isset($_GET)){
            $userone = $_GET['user_one'];
            $usertwo = $_GET['user_two'];
            $request_id = $_GET['request_id'];
            $model = new ChatAccept();
            $model->user_one = $userone;
            $model->user_two = $usertwo;
            $model->created_date = date('Y-m-d H:i:s');

            if($model->save()){
                $delete=ChatRequest::model()->findByPk($request_id); // assuming there is a post whose ID is 10
                $delete->delete(); // delete the row from the database table
                $response['status'] = "success";
                $response["message"] = "Successfully Accepted Request";
                $response["conv_id"] = $model->id;
            }else{
                $response['status'] = "failure";
                $response["message"] = "Failed to Accept Request";
            }
        }

        echo json_encode($response);
    }

    public function actionChat_Reject(){

        $response = array();
        if(isset($_GET)){
            $userone = $_GET['user_one'];
            $usertwo = $_GET['user_two'];
            $request_id = $_GET['request_id'];
            $model = new ChatReject();
            $model->user_one = $userone;
            $model->user_two = $usertwo;
            $model->created_date = date('Y-m-d H:i:s');
            if($model->save()){
                $delete=ChatRequest::model()->findByPk($request_id); // assuming there is a post whose ID is 10
                $delete->delete(); // delete the row from the database table
                $response['status'] = "success";
                $response["message"] = "Successfully Rejected";
            }else{
                $response['status'] = "failure";
                $response["message"] = "Failed to Reject";
            }
        }

        echo json_encode($response);
    }

    public function actionConversation(){

        $response = array();
        if(isset($_GET)){
            $user = $_GET["user"];
            $reply = $_GET["reply"];
            $conversation_id = $_GET["conv_id"];
            $model = new ConversationReply();
            $model->reply = $reply;
            $model->user_id = $user;
            $model->conversation_id = $conversation_id;
            $model->time = date('Y-m-d H:i:s');
            if($model->save()){
                $response["status"] = "success";
            }else{
                $response["status"] = "failure";
            }
        }

        echo json_encode($response);
    }

    public function actionChat_list(){
        header('Content-Type: application/json');
        $response = array();
        if(isset($_GET)){
            $user_id = $_GET['user_id'];
            $query = Yii::app()->db->createCommand('SELECT users.id, users.name, chat_accept.id as conv_id, conversation_reply.reply, conversation_reply.time FROM users, chat_accept LEFT JOIN conversation_reply ON chat_accept.id = conversation_reply.conversation_id WHERE CASE WHEN chat_accept.user_one = "'.$user_id.'" THEN chat_accept.user_two = users.id WHEN chat_accept.user_two = "'.$user_id.'" THEN chat_accept.user_one = users.id END AND ( chat_accept.user_one = "'.$user_id.'" OR chat_accept.user_two = "'.$user_id.'" ) GROUP BY conversation_reply.conversation_id');

            $data = $query->queryAll();

            if(!empty($data)){
                $ctr = 0;
                $response["status"] = "success";
                $response["message"] = "chat history present";
                foreach($data as $r){
                    $image = UserAlbum::model()->findByAttributes(array('user_id'=>$r['id'],"is_profile"=>"1"));

                    $response['data'][$ctr]['conv_id'] = $r['conv_id'];
                    $response['data'][$ctr]['user_id'] = $r['id'];
                    $response['data'][$ctr]['name'] = $r['name'];
                    if(!empty($image)){
                        $response['data'][$ctr]['profile_pic'] = Yii::app()->getBaseUrl(true) . "/images/users/" . $r->id . "/" . $image->image_url;
                    }else{
                        $response['data'][$ctr]['profile_pic'] = "not set";
                    }

                    $response['data'][$ctr]['message'] = $r['reply'];
                    $response['data'][$ctr]['message_time'] = $r['time'];
                    $ctr++;
                }
            }else{
                $response["status"] = "failure";
                $response["message"] = "no chat history present";
            }
        }
        else{
            $response["status"] = "failure";
            $response["message"] = "Please post data";
        }

        echo json_encode($response);
    }

    public function actionRequest_list(){

        $response = array();
        if(isset($_GET)){
            $user_id = $_GET["user_id"];
            $query = Yii::app()->db->createCommand("SELECT users.id, users.name,chat_request.id as request_id FROM chat_request, users WHERE CASE WHEN chat_request.user_one = '".$user_id."' THEN chat_request.user_two = users.id WHEN chat_request.user_two = '".$user_id."' THEN chat_request.user_one = users.id END AND ( chat_request.user_one = '".$user_id."' OR chat_request.user_two = '".$user_id."' ) ORDER BY chat_request.id");

            $data = $query->queryAll();
            if(!empty($data)){
                $ctr = 0;
                $response["status"] = "success";
                $response["message"] = "chat history present";
                foreach($data as $r){
                    $image = UserAlbum::model()->findByAttributes(array('user_id'=>$r['id'],"is_profile"=>"1"));

                    $response['data'][$ctr]['request_id'] = $r['request_id'];
                    $response['data'][$ctr]['user_id'] = $r['id'];
                    $response['data'][$ctr]['name'] = $r['name'];
                    if(!empty($image)){
                        $response['data'][$ctr]['profile_pic'] = Yii::app()->getBaseUrl(true) . "/images/users/" . $r['id'] . "/" . $image->image_url;
                    }else{
                        $response['data'][$ctr]['profile_pic'] = "not set";
                    }

                    $ctr++;
                }

            }else{
                $response["status"] = "failure";
                $response["message"] = "no chat history present";
            }
        }else{
            $response['status'] = "failure";
            $response['message'] = "please pass data";
        }

        echo json_encode($response);

    }
}
