<?php namespace Leelam\Cloudsms\Libraries\Gateways;


use Leelam\Cloudsms\Libraries\Contracts\{
    CloudsmsAbstract, CloudsmsInterface
};

class Cloudsms extends CloudsmsAbstract implements CloudsmsInterface
{


    /**
     * @param $mobiles
     * @return $this
     */
    public function numbers ( $mobiles )
    {
        $this->numbers = $mobiles;

        if ( is_array ( $mobiles ) ) {
            $this->numbers = implode ( ",", $mobiles );
        }

        return $this;
    }

    /**
     * @param $content
     * @return $this
     */
    public function message ( $content )
    {
        $this->message = $content;

        return $this;
    }


    /**
     * @param $data
     * @return $this
     */
    public function send ( $data = null )
    {
        // $this->send = $data;

        // init the resource
        $postData = [
            'authkey' => $this->authkey,
            'mobiles' => $this->numbers,
            'message' => $this->message,
            'sender'  => $this->senderId,
            'route'   => $this->route,
        ];
        curl_setopt_array ( $this->ch, [
            CURLOPT_URL            => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $postData
            //,CURLOPT_FOLLOWLOCATION => true
        ] );

        //Ignore SSL certificate verification
        curl_setopt ( $this->ch, CURLOPT_SSL_VERIFYHOST, 0 );
        curl_setopt ( $this->ch, CURLOPT_SSL_VERIFYPEER, 0 );

        //get response
        // var_dump(curl_getinfo($this->ch));
        if ( env ( 'CLOUDSMS_ENV' ) == 'production' ) {
            $output = curl_exec ( $this->ch );
            \Log::info ( 'Success ' . $output );
        } else {
            $output = 'In order to send cloudsms add CLOUDSMS_ENV=production in .env file';
            \Log::debug ( $output );
        }

        //Print error if any
        if ( curl_errno ( $this->ch ) )
            echo 'error:' . curl_error ( $this->ch );

        curl_close ( $this->ch );

        return $output;
    }


}