<?php

# TODO: abstract away the encoding and decoding so this class
#       can be used with other kinds of APIs
class API_Client
{
    private $host = '';
    private $user = '';
    private $pass = '';
    private $assoc = TRUE;

    function __construct($hostname, $username, $password, $assoc = TRUE)
    {
        $this->host = $hostname;
        $this->user = $username;
        $this->pass = $password;
        $this->assoc = $assoc;

        set_exception_handler(array('Exception_Handler', 'api_exception_handler'));
    }

    public function call($path, $args = null, $decode=TRUE)
    {
        $postdata = $args ? json_encode($args) : '{}';
        $ch = curl_init($this->host . $path);
        curl_setopt_array($ch,  array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_USERPWD => $this->user . ':' . $this->pass,
            CURLOPT_HTTPAUTH => CURLAUTH_ANY,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postdata,
            CURLOPT_SSL_VERIFYHOST => false,
        ));
        $raw_result = curl_exec($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if(curl_errno($ch) != 0)
        {
            $errtxt = curl_error($ch);
            curl_close($ch);
            throw new Exception('cURL error: ' . $errtxt);
        }

        switch ($http_status)
        {
            case 200:
                //Everything is good.
                break;
            case 401:
                throw new Exception('Invalid API User. Please make sure your API user is configured properly.', $http_status);
            default:
                throw new Exception('Unknown API error', $http_status);
        }

        curl_close($ch);
        $result = $decode ? json_decode($raw_result, $this->assoc) : $raw_result;
        if ($result == null)
        {
            throw new Exception('Unable to decode server response: ' . $result);
        }
        return $result;
    }
}

?>
