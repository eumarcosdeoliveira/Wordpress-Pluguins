<?php
class CPF_Validation
{
    private $apiUrl;
    private $senha;

    public function __construct()
    {
        // Definir os valores das variáveis $apiUrl e $senha
        $this->apiUrl = '';
        $this->senha = '';
    }

    public function init()
    {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_custom_script'));
        add_filter('wpcf7_validate', array($this, 'validate_cpf'), 10, 2);
        add_filter('wpcf7_validate', array($this, 'submit_form'), 20, 2);
    }

    public function enqueue_custom_script()
    {
        // Adicionar o script e localizar as variáveis na fila de scripts do WordPress
        wp_enqueue_script('cpf-validation-script', plugin_dir_url(__FILE__) . 'cpf-validation.js', array('jquery'), null, true);
        wp_localize_script('cpf-validation-script', 'cpfValidation', array(
            'apiUrl' => $this->apiUrl,
            'senha' => $this->senha
        ));
    }

    public function validate_cpf($result, $tag)
    {
        $name = 'cpf'; // Nome do campo CPF no formulário
        $value = isset($_POST[$name]) ? sanitize_text_field($_POST[$name]) : '';

        // Validação do CPF
        if (!empty($value) && !$this->validate_cpf_api($value)) {
            $result->invalidate($tag, "CPF inválido.");
        }

        return $result;
    }

    public function submit_form($result, $tag)
    {
        // Se houver erros de validação, não envia o formulário
        if ($result['valid'] === false) {
            return $result;
        }

        // Obtém o valor do CPF submetido
        $cpf = isset($_POST['cpf']) ? sanitize_text_field($_POST['cpf']) : '';

        // Verifica se o CPF é válido
        if (!empty($cpf) && !$this->validate_cpf_api($cpf)) {
            // CPF inválido, então invalida o formulário
            $result->invalidate($tag, "CPF inválido.");
        }

        return $result;
    }

    public function validate_cpf_api($cpf)
    {
        // Parâmetros da solicitação para a API de validação de CPF
        $args = array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'senha' => $this->senha
            ),
            'body' => json_encode(array('cpf' => $cpf))
        );

        // URL da API de validação de CPF
        $url = $this->apiUrl;

        // Fazer a solicitação à API
        $response = wp_remote_post($url, $args);

        // Verificar se houve erro na solicitação
        if (!is_wp_error($response)) {
            $body = wp_remote_retrieve_body($response);
            $data = json_decode($body, true);

            // Verificar se a resposta da API foi bem-sucedida
            if (isset($data['Sucesso']) && $data['Sucesso']) {
                return true; // CPF válido
            }
        }

        return false; // CPF inválido ou erro na solicitação
    }
    
}

// Inicializar a classe CPF_Validation
$cpf_validation = new CPF_Validation();
$cpf_validation->init();
?>
