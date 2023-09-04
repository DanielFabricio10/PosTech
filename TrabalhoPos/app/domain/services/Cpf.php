<?php

namespace domain\services;

class Cpf {

    private string $cpfNumber;

    public function __construct(string $cpfNumber) {
        $this->setCpfNumber($cpfNumber);
    }

    private function setCpfNumber(string $number) {
        
        $options = [
            'options' => [
                'regexp' => '/\d{3}\.\d{3}\.\d{3}\-\d{2}/'
            ]
        ];

        if(filter_var($number, FILTER_VALIDATE_REGEXP, $options) === false) {
            throw new \InvalidArgumentException('CPF in invalid format');
        }

        echo 'aaa';
    }


}