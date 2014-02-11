<?php

/**
 * Created by PhpStorm.
 * User: hitenpratap
 * Date: 31/1/14
 * Time: 1:13 AM
 */
class UsersController extends AppController
{
    public $components = array('RequestHandler');

    public function index()
    {
        $users = $this->User->find('all');
        $this->set(compact('users'));
    }

    public function checkUserAuthenticate(){
        $response=array();
        $servPassword=$this->request->header('servPassword');
        if($servPassword=='prabhathitenatish'){
            if($this->request->is('POST')){
                $username=$this->request->data('username');
                $password=$this->request->data('password');;
                $users=$this->User->query("SELECT * FROM users where email='".$username."' AND password='".$password."'");
                if($users){
                    $response["statusCode"]=200;
                    $response["message"]='user is available';
                    $response['first_name']=$users[0]['users']['first_name'];
                    $response['last_name']=$users[0]['users']['last_name'];
                    $response['email']=$users[0]['users']['email'];
                    $this->set(compact('response'));
                }else{
                    $response["statusCode"]=204;
                    $response["message"]="User is not valid";
                    $this->set(compact('response'));
                }
            }else{
                $response["statusCode"]=405;
                $response["message"]="No other methods than Post are allowed.";
                $this->set(compact('response'));
            }
        }else{
            $response["statusCode"]=415;
            $response["message"]="Api key must be set in Request Header.";
            $this->set(compact('response'));
        }

    }

    public function saveUser()
    {
        $servPassword = $this->request->header('servPassword');
        if ($servPassword == 'prabhathitenatish') {
            if ($this->request->is("POST")) {
                $username = $this->request->data('username');
                $password = $this->request->data('password');
                $firstName = $this->request->data('firstName');
                $lastName = $this->request->data('lastName');
                $mobile = $this->request->data('mobile');
                $dateCreated = date('Y-m-d H:i:s');
                $lastUpdated = date('Y-m-d H:i:s');
                $response = array();
                if (empty($username)) {
                    $response["username"] = "Email field can't be left blank.";
                } else if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $username)) {
                    $response["username"] = "Please enter correct email id.";
                }
                if (empty($password)) {
                    $response["passwordErr"] = "Password field can't be left blank.";
                }
                if (empty($firstName)) {
                    $response["firstNameErr"] = "First Name field can't be left blank.";
                }
                if (empty($lastName)) {
                    $response["lastNameErr"] = "Last Name field can't be left blank.";
                }
                if (empty($mobile)) {
                    $response["mobileErr"] = "Mobile field can't be left blank.";
                }

                if (empty($response)) {
                    $this->User->query("insert into users values('" . "" . "','" . $username . "','" . $password . "','" . $firstName . "','" . $lastName . "','" . $mobile . "','" . $dateCreated . "','" . $lastUpdated . "')");
                    $response['statusCode'] = 200;
                    $response["username"] = $username;
                    $response["password"] = $password;
                    $response["firstName"] = $firstName;
                    $response["lastName"] = $lastName;
                    $response["mobile"] = $mobile;
                    $response['msg'] = "user save successful";
                    $this->set(compact('response'));
                } else {
                    //   $statusCode = 204;
                    // $this->set(compact('statusCode', 'errors'));
                    $errors["statuscode"] = 204;
                    echo json_encode($errors);
                }
            } else {
                $response = array();
                $response['statuscode'] = 405;
                $response['msg'] = "No other methods than POST are allowed.";
                echo json_encode($response);
            }
        } else {
            $response = array();
            $response["statuscode"] = 415;
            $response['msg'] = "Api key must be set in Request Header.";
            echo(json_encode($response));
        }
    }

    public function deleteUser()
    {
        $servPassword = $this->request->header('servPassword');
        if ($servPassword == 'prabhathitenatish') {
            if ($this->request->is("PUT")) {

                //TODO:username is found empty at all the time
                $username = $this->request->data('username');
                if (empty($username)) {
                    $response = array();
                    $response['statuscode'] = 408;
                    $response['msg'] = "Username can't be null";
                    echo json_encode($response);
                }
            } else {
                $response = array();
                $response["statuscode"] = 415;
                $response['msg'] = "Api key must be set in Request Header.";
                echo(json_encode($response));
            }

        }

    }


}

?>
