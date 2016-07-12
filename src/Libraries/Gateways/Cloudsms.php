<?php namespace Leelam\Cloudsms\Libraries\Gateways;


use Leelam\Cloudsms\Libraries\Contracts\{
    CloudsmsAbstract, CloudsmsInterface
};
use Leelam\CloudsmsReports;

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

    public function sendXML ()
    {
//dd($this->dataXML);
        if ( ! collect ( $this->dataXML )->isEmpty () ) {
            $xml = '<MESSAGE>';
            $xml .= '<AUTHKEY>' . $this->authkey . '</AUTHKEY>';
            $xml .= '<ROUTE>' . $this->route . '</ROUTE>';
            if ( $this->route == 4 OR $this->route == 'template' ) {
                $xml .= '<SENDER>' . $this->senderId . '</SENDER>';
            } else {
                $xml .= '<SENDER>Leelam</SENDER>';
            }

            // sending to multiple array
            if ( ! isset( $this->dataXML[ 'message' ] ) ) {

                // Customized sms with their respect mobile number
                $i = 0;
                foreach ( $this->dataXML as $textAndTo ) {

                    $forReport[ $i ] = [
                        'number' => $textAndTo[ 'mobile' ],
                        'desc'   => '',
                        'status' => '',
                        'date'   => '',
                    ];

                    $xml .= '<SMS TEXT="' . $textAndTo[ 'message' ] . '">';
                    $xml .= '<ADDRESS TO="' . $textAndTo[ 'mobile' ] . '"></ADDRESS>';
                    $xml .= '</SMS>';

                    $i++;
                }
                // send when there is only one sender, in technocal way single array of keys and values
            } elseif ( isset( $this->dataXML[ 'message' ] ) ) {
                $forReport = [
                    'number' => $this->dataXML[ 'mobile' ],
                    'desc'   => '',
                    'status' => '',
                    'date'   => '',
                ];
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
                        $report[ 'user_id' ] = 1;
                        $report[ 'message' ] = $this->dataXML[ 'message' ]??$this->dataXML[ 0 ][ 'message' ];
                        $report[ 'senderid' ] = $this->senderId;
                        $report[ 'sender_ip' ] = getClientIP ();
                        $report[ 'request_id' ] = $output;

                        $report[ 'data' ] = $forReport;

                        //dd($report);
                        CloudsmsReports::create ( $report );
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
}