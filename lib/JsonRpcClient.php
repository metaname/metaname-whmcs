<?php

class JsonRpcFault extends Exception {}

class JsonRpcClient
{
    private $uri;

    public function __construct($uri)
    {
        $this->uri = $uri;
    }

    private function next_id()
    {
        $chars = array_merge(range('A', 'Z'), range('a', 'z'), range(0, 9));
        $id = '';
        for($c = 0; $c < 16; ++$c)
            $id .= $chars[mt_rand(0, count($chars) - 1)];
        return $id;
    }

    public function __call($name, $params)
    {
        $id = $this->next_id();

        $request = array(
            'jsonrpc' => '2.0',
            'id'      => $id,
            'method'  => $name,
            'params'  => $params
        );

        $jsonRequest = json_encode($request);

        $ctx = stream_context_create(array(
            'http' => array(
                'method'  => 'POST',
                'header'  => "User-Agent: PHP JsonRpcClient 2.0\r\nAccept: application/json\r\nContent-Type: application/json\r\n",
                'content' => $jsonRequest
            )
        ));
        $jsonResponse = file_get_contents($this->uri, false, $ctx);

        if ($jsonResponse === false)
            throw new JsonRpcFault('file_get_contents failed', -32603);

        $response = json_decode($jsonResponse);

        if ($response === null)
            throw new JsonRpcFault('JSON cannot be decoded', -32603);

        if ($response->id != $id)
            throw new JsonRpcFault('Mismatched JSON-RPC IDs', -32603);

        if (property_exists($response, 'error'))
            throw new JsonRpcFault($response->error->message, $response->error->code);

        else if (property_exists($response, 'result'))
            return $response->result;
        else
            throw new JsonRpcFault('Invalid JSON-RPC response', -32603);
    }
}

?>
