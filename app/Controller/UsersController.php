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
        $servPassword=$this->request->header('servPassword');
        if($servPassword=='prabhathitenatish'){
            if($this->request->is('POST')){
                $username=$this->request->data('username');
                $password=$this->request->data('password');;
                $users=$this->User->query("SELECT * FROM users where email='".$username."' AND password='".$password."'");
                if($users){
                    $statusCode=200;
                    $message='user is available';
                    $firstName=$users[0]['users']['first_name'];
                    $lastName=$users[0]['users']['last_name'];
                    $email=$users[0]['users']['email'];
                    $this->set(compact('statusCode','message','firstName','lastName','email'));
                }else{
                    $statusCode=204;
                    $message="User is not valid";
                    $this->set(compact('statusCode','message'));
                }
            }else{
                $statusCode=405;
                $message="No other methods than Post are allowed.";
                $this->set(compact('statusCode','message'));
            }
        }else{
            $statusCode=415;
            $message="Api key must be set in Request Header.";
            $this->set(compact('statusCode','message'));
        }

    }

    public function saveUser()
    {
        $servPassword = $this->request->header('servPassword');
        if ($servPassword == 'prabhathitenatish') {
            if ($this->request->is("POST")) {
                $usernameForm = $this->request->data('username');
                $passwordForm = $this->request->data('password');
                $firstNameForm = $this->request->data('firstname');
                $lastNameForm = $this->request->data('lastname');
                $mobileForm = $this->request->data('mobile');
                $dateCreated = date('Y-m-d H:i:s');
                $lastUpdated = date('Y-m-d H:i:s');
                global $error;
                if($usernameForm=='' || $passwordForm=='' || $firstNameForm=='' || $lastNameForm=='' || $mobileForm==''){
                    $statusCode = 404;
                    $message="Parameters are missing.Please submit the form again.";
                    $this->set(compact('statusCode','message'));
                    $error=1;
                }
                else if($error!=1){
                    $this->User->query("insert into users values('" . "" . "','" . $usernameForm . "','" . md5($passwordForm) . "','" . $firstNameForm . "','" . $lastNameForm . "','" . $mobileForm . "','" . $dateCreated . "','" . $lastUpdated . "')");
                    $statusCode = 200;
                    $userName = $usernameForm;
//                    $passWord = $passwordForm;
                    $firstName = $firstNameForm;
                    $lastName = $lastNameForm;
                    $mobile = $mobileForm;
                    $message = "user save successful";
                    $this->set(compact('statusCode','message','firstName','lastName','userName','mobile'));
                } else {
                    $statusCode = 204;
                    $message="Something went wrong. Please try again later.";
                    $this->set(compact('statusCode','message'));
                }
            } else {
                $statusCode = 405;
                $message = "No other methods than POST are allowed.";
                $this->set(compact('statusCode','message'));
            }
        } else {
            $statusCode = 415;
            $message = "Api key must be set in Request Header.";
            $this->set(compact('statusCode','message'));
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
                    $statuscode = 408;
                    $msg = "Username can't be null";
                    echo json_encode($response);
                }
            } else {
                $response = array();
                $statuscode = 415;
                $msg = "Api key must be set in Request Header.";
                echo(json_encode($response));
            }

        }

    }


}

?>
