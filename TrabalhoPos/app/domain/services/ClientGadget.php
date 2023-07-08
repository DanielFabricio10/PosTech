<?php

namespace domain\services;

Class ClientGadget {

    function validateCPF($cpf){

        if(empty($cpf)){
            return false;
        }

        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        if (strlen($cpf) !== 11) {
            return false;
        }

        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        for ($i = 9; $i < 11; $i++) {
            $soma = 0;
            for ($j = 0; $j < $i; $j++) {
                $soma += intval($cpf[$j]) * (($i + 1) - $j);
            }
            $digito = (($soma % 11) < 2) ? 0 : 11 - ($soma % 11);
            if (intval($cpf[$i]) !== $digito) {
                return false;
            }
        }



        return true;
    }

    function rectifyDate($date){

        if(empty($date)){
            return false;
        }
        $newDate = date('Y-m-d H:i:s', strtotime($date));
        return $newDate;
    }

    function validatePhone($phone){

        if(empty($phone)){
            return false;
        }

        $phone = preg_replace('/[^0-9]/', '', $phone);

        if(preg_match('/^(?:\d{10}|\d{11})$/', $phone)){ // tem que ter 11 digitos ddd+numero
            return true;
        }else{
            return false;
        }
    }

    function validateEmail($email) {

        $email = trim($email);

        if(preg_match('/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)){
            return true;
        }else{
            return false;
        }
    }
}