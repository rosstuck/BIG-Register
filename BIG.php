<?php
require 'nusoap.php';

class BIGRepository
{
    protected $client;
    protected $namespace = 'http://services.cibg.nl/ExternalUser';

    protected function getClient()
    {
        if ($this->client) {
            return $this->client;
        }
        
        return $this->client = new nusoap_client(
            'http://webservices.cibg.nl/Ribiz/Openbaar.asmx'
        );
    }
    
    protected function fetchByParameters($params)
    {
        $params['WebSite'] = 'Ribiz';
        $client = $this->getClient();

        $soapVals = array();
        foreach ($params as $key => $value) {
            $soapVals[] = new soapval($key, null, $value, $this->namespace);
        }
        
        $result = $client->call(
            'listHcpApproxRequest', 
            $soapVals,
            $this->namespace,
            'http://services.cibg.nl/ExternalUser/ListHcpApprox'
        );

        if (empty($result) || !isset($result['ListHcpApprox'])) {
            return;
        }
        
        return new BIGRecord($result['ListHcpApprox']);
    }

    public function fetchByRegistrationNumber($id)
    {
        return $this->fetchByParameters(array(
            'RegistrationNumber' => $id
        ));
    }
    
    public function fetchByNameAndBirthdate($name, Datetime $birthdate)
    {
        return $this->fetchByParameters(array(
            'Name'          => $name,
            'DateOfBirth'   => $birthdate->format('Y-m-d')
        ));
    }
}

class BIGRecord
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }
    
    public function getData()
    {
        return $this->data;
    }
    
    public function getFullName()
    {
        if (empty($this->data['Initial']) || empty($this->data['BirthSurname'])) {
            return;
        }
    
        return $this->data['Initial'].' '.$this->data['BirthSurname'];
    }
    
    public function getRegistrationNumber()
    {
        if (empty($this->data['ArticleRegistration']['ArticleRegistrationExtApp']['ArticleRegistrationNumber'])) {
            return;
        }
        
        return $this->data['ArticleRegistration']['ArticleRegistrationExtApp']['ArticleRegistrationNumber'];
    }
}
