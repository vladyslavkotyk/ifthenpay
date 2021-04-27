<?php

/*  
    Pagamentos MbWay

    Author: github.com/vladyslavkotyk/ifthenpay
    2021
*/

class MBway {

    private $mbway_key;
    private $channel;
    private $url_protocol;

    function __construct($mbway_key, $channel, $url_protocol) {

        $this->mbway_key    = $mbway_key;
        $this->channel    = $channel;
        $this->url_protocol = $url_protocol;
    }

    /* 
        Criar um pagamento MBWay com o número de telemóvel do client

        $phone_number       - Número de telemóvel do cliente
        $internal_reference - Referência interna para ser usada depois no callback e identificar o pagamento ( MAX 15 caráters )
        $order_value        - Valor da encomenda em €
        $customer_email     - Email do utilizador ( OPCIONAL )
        $description        - Descrição do pagamento ( MAX 50 caráters ) ( OPCIONAL )
    */

    function create($phone_number, $internal_reference, $order_value, $customer_email = "", $description = "") {

        $url  = $this->url_protocol . "://www.ifthenpay.com/mbwayws/IfthenPayMBW.asmx/SetPedidoJSON";

        // Dados que vão ser enviados para o Ifthenpay
        $post = http_build_query(array(
            'MbWayKey'      => $this->mbway_key,
            'canal'         => $this->channel,
            "referencia"    => $internal_reference,
            "valor"         => $order_value,
            "nrtlm"         => $phone_number,
            "email"         => $customer_email,
            "descricao"     => $description
        ));
                
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL,             $url);
        curl_setopt($curl, CURLOPT_POSTFIELDS,      $post);
        curl_setopt($curl, CURLOPT_POST,            true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,  true);

        $data = curl_exec($curl);

        curl_close($curl);

        var_dump($data);

        // Limpar e converter a resposta da api em JSON
        $data = str_replace('<?xml version="1.0" encoding="utf-8"?>', "", $data);
        $data = str_replace('<string xmlns="https://www.ifthenpay.com/">', "", $data);
        $data = str_replace("</string>", "", $data);
        
        $data = json_decode($data, true);

        return $data;
    }

    /* 
        Obter o status do pagamento com uma API call à ifthenpay
        ( sem usar o callback )
    */

    function status($payment_id) {

        $url  = $this->url_protocol . "://www.ifthenpay.com/mbwayws/IfthenPayMBW.asmx/EstadoPedidosJSON";
        $url .= "?MbWayKey=" . $this->mbway_key . "&canal=" . $this->channel . "&idspagamento=" . $payment_id;
                
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $data = curl_exec($curl);

        curl_close($curl);

        // Limpar e converter a resposta da api em JSON
        $data = str_replace('<?xml version="1.0" encoding="utf-8"?>', "", $data);
        $data = str_replace('<string xmlns="https://www.ifthenpay.com/">', "", $data);
        $data = str_replace("</string>", "", $data);
        
        $data = json_decode($data, true);

        return $data;
    }
}