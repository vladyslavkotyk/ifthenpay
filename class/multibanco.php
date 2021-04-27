<?php

/* 
    COMO FUNCIONA UMA REFERÊNCIA

    Os 9 dígitos da referência multibanco são sempre construídos da
    seguinte forma: os 3 primeiros dígitos são obrigatoriamente os 3 dígitos
    da subentidade; os 4 dígitos seguintes são o ID; 
    os 2 últimos são os check-digits calculados;

    Author: github.com/vladyslavkotyk/ifthenpay
    2021
*/

class Multibanco {

    private $entity;
    private $sub_entity;
    private $url_protocol;

    function __construct($entity, $sub_entity, $url_protocol) {

        if (strlen($entity) !== 5) {

            echo json_encode(array("success" => false, "message" => "Entidade inválida, tente novamente"));
            exit();
        }

        if (strlen($sub_entity) !== 3) {

            echo json_encode(array("success" => false, "message" => "Sub-Entidade inválida, tente novamente"));
            exit();
        }

        $this->entity       = $entity;
        $this->sub_entity   = $sub_entity;

        $this->url_protocol = $url_protocol;
    }

    /* 
        Gerar uma referencia multibanco

        $order_identification - ID interno da compra do cliente
        $order_value          - Valor da compra
    */
    
    function generate($order_identification, $order_value) {

        $chk_val = 0;

        // Validação do valor da compra
        if (!is_numeric($order_value) || $order_value > 99999.99 || $order_value < 1) {

            return array("success" => false, "message" => "Valor da compra inválido");
        }
        
        // Formatar o ID da compra, caseo seja demasiado pequeno ( adicionamos 4 zeros à frente )
        $order_identification = "0000" . $order_identification;

        // Obter os ultimos 4 números do ID da compra
        $order_identification = substr($order_identification, (strlen($order_identification) - 4), strlen($order_identification));

        // Formatar o valor da compra ( remover decimal e preencher com zeros à esquerda para que tenha 8 caráteres )
        $order_value_formatted = str_pad(number_format($order_value, 2, "", ""), 8, '0', STR_PAD_LEFT);

        // Número identificador da encomenda
        $chk_str = sprintf('%05u%03u%04u%08u', $this->entity, $this->sub_entity, $order_identification, round($order_value * 100));
        
        // Cálculo dos check digits
        $chk_array = array(3, 30, 9, 90, 27, 76, 81, 34, 49, 5, 50, 15, 53, 45, 62, 38, 89, 17, 73, 51);
        
        for ($i = 0; $i < 20; $i++) {
            
            $chk_int = substr($chk_str, 19 - $i, 1);
            $chk_val += ($chk_int % 10) * $chk_array[$i];
        }
        
        $chk_val %= 97;
        
        $chk_digits = sprintf('%02u', 98 - $chk_val);

        // Return da referência multibanco criada
        return $this->sub_entity . " " . substr($chk_str, 8, 3) . " " . substr($chk_str, 11, 1) . $chk_digits;
    }

    /* 
        Obter o status do pagamento com uma API call à ifthenpay
        ( sem usar o callback )
    */

    function status($api_key, $reference) {

        // Limpar os espaços da referencia
        $reference = str_replace(" ", "", $reference);

        $url  = $this->url_protocol . "://www.ifthenpay.com/IfmbWS/WsIfmb.asmx/GetPaymentsJson";
        $url .= "?chavebackoffice=" . $api_key . "&entidade=" . $this->entity . "&subentidade=" . $this->sub_entity . "&referencia=" . $reference . "&sandbox=0&dtHrInicio=&dtHrFim=&valor=";
                
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL,             $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,  true);

        $data = curl_exec($curl);

        curl_close($curl);

        // Limpar e converter a resposta da api em JSON
        $data = str_replace('<?xml version="1.0" encoding="utf-8"?>', "", $data);
        $data = str_replace('<string xmlns="https://www.ifthenpay.com/">', "", $data);
        $data = str_replace("</string>", "", $data);
        
        $data = json_decode($data, true);

        return $data[0];
    }
}