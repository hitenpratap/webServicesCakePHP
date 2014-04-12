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
            $this->set(compact('statusCode', 'message'));
        }
    }

    public function view($id)
    {
        $servPassword = $this->request->header('servPassword');
        if ($servPassword == 'prabhathitenatish') {
            $result = $this->Customer->find('all', array('conditions' => array('Customer.id' => $id)));
            if ($result) {
                $fullName = $result[0]['Customer']['full_name'];
                $email = $result[0]['Customer']['email'];
                $mobile = $result[0]['Customer']['mobile'];
                $statusCode = 200;
                $this->set(compact('statusCode', 'fullName', 'mobile', 'email'));
            } else {
                $statusCode = 204;
                $message = "Customer is not valid";
                $this->set(compact('statusCode', 'message'));
            }
        } else {
            $statusCode = 415;
            $message = "Api key must be set in Request Header.";
            $this->set(compact('statusCode', 'message'));
        }
    }


    public function add()
    {
        $failed = array();
        $inserted=0;
        $servPassword = $this->request->header('servPassword');
        if ($servPassword == 'prabhathitenatish') {
            $json = $this->request->data('customersJSON');
            $json2array = json_decode($json, true);
            foreach ($json2array['customers'] as $key => $value) {
                if ($value['name'] == '' || $value['mobile'] == '') {
                        array_push($failed,$value['id']);
                } else {
                    $data = array(
                        'Customer' => array(
                            'full_name' => $value['name'],
                            'email' => $value['email'],
                            'mobile' => $value['mobile'],
                            'address' => $value['address'],
                            'deviceId' => $value['id']
                        )
                    );
                    $this->Customer->saveMany($data);
                    $inserted++;
                }
            }
            $statusCode = 200;
            $message='Customers saved successfully.';
            $numberOfRecordsInserted = $inserted;
            $numberOfRecordsFailed = sizeof($failed);
            $failedRecordsDeviceIds = $failed;
            $this->set(compact('statusCode', 'message','failed','numberOfRecordsInserted','numberOfRecordsFailed','failedRecordsDeviceIds'));
        } else {
            $statusCode = 415;
            $message = "Api key must be set in Request Header.";
            $this->set(compact('statusCode', 'message'));
        }

    }


    public function delete($id)
    {
        $servPassword = $this->request->header('servPassword');
        if ($servPassword == 'prabhathitenatish') {
            $result = $this->Customer->find('all', array('fields' => array('id', 'full_name'), 'conditions' => array('Customer.id' => $id)));
            if ($result) {
                $id = $result[0]['Customer']['id'];
                if ($this->Customer->delete($id)) {
                    $statusCode = 200;
                    $message = 'Customer with name ' . $result[0]['Customer']['full_name'] . ' is successfully deleted from record';
                    $this->set(compact('statusCode', 'message'));
                }
            } else {
                $statusCode = 204;
                $message = "Customer is not valid";
                $this->set(compact('statusCode', 'message'));
            }
        } else {
            $statusCode = 415;
            $message = "Api key must be set in Request Header.";
            $this->set(compact('statusCode', 'message'));
        }
    }


}

?>
