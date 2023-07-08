<?php

namespace domain\services;

Class AdressGadget {

    function validateZipCode($zipCode){

        if(empty($zipCode)){
            return false;
        }

        $zipCode = preg_replace('/[^0-9]/', '', $zipCode);

        if(strlen($zipCode) === 8){
            if($this->verifyZipCode($zipCode) !== false){
                return true;
            }
        }else{
            return false;
        }
    }

    public function verifyZipCode($zipCode){

        $curl = curl_init();

        $url = 'viacep.com.br/ws/'.$zipCode.'/json/';

        curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        $response = json_decode($response);

        curl_close($curl);

        if(in_array($httpCode,[200,201,204])){
            return (Object)['status' => true, 'response' => $response];
        }else{
            return false;
        }
    }

    function validateUf($uf){

        if(empty($uf)){
            return false;
        }

        $uf = strtoupper($uf); // Converter para letras maiúsculas

        // Array com as siglas das UF do Brasil
        $ufValidas = array(
            'AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA',
            'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN',
            'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'
        );

        return in_array($uf, $ufValidas);
    }
}