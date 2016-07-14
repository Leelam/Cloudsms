<?php namespace Leelam\Cloudsms\Libraries\Gateways;


use Leelam\Cloudsms\Libraries\CloudsmsReports;
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
     *  Array of data
     *  [
     *      [
     *          'message'=>'Custome message 1',
     *          'mobile'=>'9949990991'
     *      ],
     *      [
     *          'message'=>'Custome message 2',
     *          'mobile'=>'8008008322'
     *      ]
     *  ]
     * @param $messagesAndMobiles
     * @return $this
     *
     */
    public function dataXML ( $messagesAndMobiles )
    {
        $this->dataXML = $messagesAndMobiles;

        return $this;
    }

    public function campaign ( $campaign )
    {
        $this->campaign = $campaign;

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

            $this->postInstantReports ( $this->message, $this->senderId, $output, $this->route, $this->numbers );

            \Log::info ( ' Response ' . $output );
        } else {
            $output = 'In order to send cloudsms add CLOUDSMS_ENV=production in .env file';
            \Log::debug ( $output );
        }
        //Print error if any
        if ( curl_errno ( $this->ch ) ) {
            echo 'error:' . curl_error ( $this->ch );
        }
        curl_close ( $this->ch );

        return $output;
    }

    /**
     * @return bool|mixed|string
     */
    public function sendXML ()
    {

        $collectedXMLData = collect ( $this->dataXML );
        if ( ! $collectedXMLData->isEmpty () ) {
            $xml = '<MESSAGE>';
            $xml .= '<AUTHKEY>' . $this->authkey . '</AUTHKEY>';
            $xml .= '<ROUTE>' . $this->route . '</ROUTE>';
            $xml .= '<SENDER>' . $this->senderId . '</SENDER>';

            // conforming dataXML is not single dimensional array
            if ( ! isset( $this->dataXML[ 'message' ] ) ) {
                // Customized sms with their respect mobile number
                foreach ( $collectedXMLData as $textAndTo ) {
                    $xml .= '<SMS TEXT="' . $textAndTo[ 'message' ] . '">';
                    $xml .= '<ADDRESS TO="' . $textAndTo[ 'mobile' ] . '"></ADDRESS>';
                    $xml .= '</SMS>';
                }
                // dataXML is single/single dimensional array
            } elseif ( isset( $this->dataXML[ 'message' ] ) ) {

                $xml .= '<SMS TEXT="' . $this->dataXML[ 'message' ] . '">';
                $xml .= '<ADDRESS TO="' . $this->dataXML[ 'mobile' ] . '"></ADDRESS>';
                $xml .= '</SMS>';

            } else {
                $xml .= 'Wrong in user data, Cloudsms Gateway';
            }
            $xml .= '</MESSAGE>';
//dd($xml);
            // init the resource
            $postData[ 'data' ] = $xml;
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

                if ( env ( 'CLOUDSMS_DLR' ) == 'enable' ) {
                    if ( strlen ( $output ) === 24 ) {

                        $report[ 'message' ] = $this->dataXML[ 'message' ]??$this->dataXML[ 0 ][ 'message' ];

                        $this->postInstantReports ( $report[ 'message' ], $this->senderId, $output, $this->route, $collectedXMLData );
                    }
                }
                //  curl_getinfo ($this->ch);
                \Log::info ( ' Response ' . $output );
            } else {
                $output = 'In order to send cloudsms add CLOUDSMS_ENV=production in .env file';
                \Log::debug ( $output );
            }
            //Print error if any
            if ( curl_errno ( $this->ch ) ) {
                echo 'error:' . curl_error ( $this->ch );
            }
            curl_close ( $this->ch );

            return $output;
        }

        \Log::error ( "No messages" );

        return false;
    }

    /**
     * @param $message
     * @param $senderid
     * @param $request_id
     * @param $request_route
     * @param $numbers
     */
    private function postInstantReports ( $message, $senderid, $request_id, $request_route, $numbers )
    {
        if ( env ( 'CLOUDSMS_DLR' ) == 'enable' ) {
            if ( strlen ( $request_id ) === 24 ) {

                if ( is_array ( $numbers ) ) {
                    $n = 0;
                    foreach ( $numbers as $number ) {
                        $numbersArray[ $n ] = $number[ 'mobile' ];
                        $n++;
                    }
                } elseif ( is_string ( $numbers ) ) {
                    $numbersArray = explode ( ",", $numbers );
                } else {
                    exit( "Error" );
                }
                $i = 0;
                foreach ( $numbersArray as $singlenumber ) {
                    $responseData[ $i ] = [
                        $dataReportmaker[ 'number' ] = $singlenumber, // Int
                        $dataReportmaker[ 'desc' ] = '', // Description
                        $dataReportmaker[ 'status' ] = '', // Int
                        $date[ 'date' ] = now ( 'Y-m-d h:m:s' ) //delivery report time
                    ];
                    $i++;
                }
                CloudsmsReports::createInstantReport ( $message, $senderid, $request_id, $request_route, $responseData );
            }

        }
    }
}