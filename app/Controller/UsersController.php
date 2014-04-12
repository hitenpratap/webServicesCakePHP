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

        $servPassword = $this->request->header('servPassword');
        if ($servPassword == 'prabhathitenatish') {
            $users = $this->User->find('all');
            $this->set(compact('users'));
        } else {
            $statusCode = 415;
            $message = "Api key must be set in Request Header.";
            $this->set(compact('statusCode','message'));
        }
    }

    public function checkUserAuthenticate(){
        $servPassword=$this->request->header('servPassword');
        if($servPassword=='prabhathitenatish'){
            if($this->request->is('POST')){
                $username=$this->request->data('username');
                $password=$this->request->data('password');
                $users=$this->User->find('all',array('conditions'=>array('User.email'=>$username,'User.password' => md5($password))));
                if($users){
                    $statusCode=200;
                    $message='user is available';
                    $firstName=$users[0]['User']['first_name'];
                    $lastName=$users[0]['User']['last_name'];
                    $email=$users[0]['User']['email'];
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

    public function view($id){
        $servPassword = $this->request->header('servPassword');
        if ($servPassword == 'prabhathitenatish') {
            $result=$this->User->find('all',array('conditions'=>array('User.id'=>$id)));
            if($result){
                $firstName= $result[0]['User']['first_name'];
                $lastName= $result[0]['User']['last_name'];
                $mobile= $result[0]['User']['mobile'];
                $email= $result[0]['User']['email'];
                $statusCode=200;
                $this->set(compact('statusCode','firstName','lastName','mobile','email'));
            }else{
                $statusCode=204;
                $message="User is not valid";
                $this->set(compact('statusCode','message'));
            }
        } else {
            $statusCode = 415;
            $message = "Api key must be set in Request Header.";
            $this->set(compact('statusCode','message'));
        }
    }

    public function add()
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
                    $data = array(
                        'User' => array(
                            'username' => $usernameForm,
                            'firstname' => $firstNameForm,
                            'lastname' => $lastNameForm,
                            'mobile' => $mobileForm,
                            'password' => md5($passwordForm)
                        )
                    );
                    $this->User->save($data);
                    $statusCode = 200;
                    $userName = $usernameForm;
//                    $passWord = $passwordForm;
                    $firstName = $firstNameForm;
                    $lastName = $lastNameForm;
                    $mobile = $mobileForm;
                    $serverId=$this->User->getLastInsertId();
                    $message = "user save successful";
                    $this->set(compact('statusCode','message','firstName','lastName','userName','mobile','serverId'));
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

    public function delete($id)
    {
        $servPassword = $this->request->header('servPassword');
        if ($servPassword == 'prabhathitenatish') {
            $result=$this->User->find('all',array('fields'=>array('id','email'),'conditions'=>array('User.id'=>$id)));
            if($result){
                $id= $result[0]['User']['id'];
                if($this->User->delete($id)){
                    $statusCode=200;
                    $message='User with username '.$result[0]['User']['email'].' is successfully deleted from record';
                    $this->set(compact('statusCode','message'));
                }
            }else{
                $statusCode=204;
                $message="User is not valid";
                $this->set(compact('statusCode','message'));
            }
            } else {
                $statusCode = 415;
                $message = "Api key must be set in Request Header.";
                $this->set(compact('statusCode','message'));
        }
    }


}

?>
