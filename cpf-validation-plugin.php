<?php
/*
Plugin Name: CPF Validation Plugin
Description: Plugin to validate CPF using API before submitting Contact Form 7.
Version: 1.0
Author: Seu Nome
*/

// Adiciona a validação do CPF ao Contact Form 7
add_action('wpcf7_init', 'cpf_validation_init');

function cpf_validation_init()
{
    require_once(dirname(__FILE__) . '/cpf-validation.php');

    // Adiciona a validação do CPF ao Contact Form 7
    if (class_exists('WPCF7')) {
        $cpf_validation = new CPF_Validation();
        $cpf_validation->init();
    }
}
