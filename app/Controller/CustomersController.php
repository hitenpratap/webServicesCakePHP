<?php

class CustomersController extends AppController
{
    public $components = array('RequestHandler');

    public function index()
    {

        $servPassword = $this->request->header('servPassword');
        if ($servPassword == 'prabhathitenatish') {
            $customers = $this->Customer->find('all');
            $this->set(compact('customers'));
        } else {
            $statusCode = 415;
            $message = "Api key must be set in Request Header.";
            $this->set(compact('statusCode','message'));
        }
    }

    public function view($id){
        $servPassword = $this->request->header('servPassword');
        if ($servPassword == 'prabhathitenatish') {
            $result=$this->Customer->find('all',array('conditions'=>array('Customer.id'=>$id)));
            if($result){
                $fullName= $result[0]['Customer']['full_name'];
                $email= $result[0]['Customer']['email'];
                $mobile= $result[0]['Customer']['mobile'];
                $statusCode=200;
                $this->set(compact('statusCode','fullName','mobile','email'));
            }else{
                $statusCode=204;
                $message="Customer is not valid";
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
                $fullNameForm = $this->request->data('fullname');
                $emailForm = $this->request->data('email');
                $mobileForm = $this->request->data('mobile');
                $dateCreated = date('Y-m-d H:i:s');
                $lastUpdated = date('Y-m-d H:i:s');
                global $error;
                if( $fullNameForm=='' || $mobileForm==''){
                    $statusCode = 404;
                    $message="Parameters are missing.Please submit the form again.";
                    $this->set(compact('statusCode','message'));
                    $error=1;
                }
                else if($error!=1){
                    $data = array(
                        'Customer' => array(
                            'fullName' => $fullNameForm,
                            'email' => $emailForm,
                            'mobile' => $mobileForm
                        )
                    );
                    $this->Customer->save($data);
                    $statusCode = 200;
                    $fullName = $fullNameForm;
                    $email = $emailForm;
                    $mobile = $mobileForm;
                    $serverId=$this->Customer->getLastInsertId();
                    $message = "Customer save successful";
                    $this->set(compact('statusCode','message','fullName','email','mobile','serverId'));
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
            $result=$this->Customer->find('all',array('fields'=>array('id','full_name'),'conditions'=>array('Customer.id'=>$id)));
            if($result){
                $id= $result[0]['Customer']['id'];
                if($this->Customer->delete($id)){
                    $statusCode=200;
                    $message='Customer with name '.$result[0]['Customer']['full_name'].' is successfully deleted from record';
                    $this->set(compact('statusCode','message'));
                }
            }else{
                $statusCode=204;
                $message="Customer is not valid";
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
