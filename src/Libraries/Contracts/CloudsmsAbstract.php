<?php
    namespace Leelam\Cloudsms\Libraries\Contracts;


    abstract class CloudsmsAbstract
    {

        /**
         *
         * CloudsmsAbstract constructor.
         */

        public function __construct ()
        {
            if ( ! env ( 'CLOUDSMS_ENABLE' ) ) {
                return exit( "Cloudsms service is not enabled" );
            }

            $this->authkey = env ( 'CLOUDSMS_AUTH_KEY', 'GetKeyFromCloudSMS.IN' );
            $this->route = env ( 'CLOUDSMS_ROUTE', 4 );
            $this->senderId = env ( 'CLOUDSMS_SENDER_ID', 'LEELAM' );
            $this->url = env ( 'CLOUDSMS_SEND_URL', 'http://api.cloudsms.in/api/sendhttp.php' );
            //   $this->connection = "default";
            $this->ch = curl_init ();
        }

        public function senderId ( $id )
        {
            $this->senderId = $id;

            if ( strlen ( $this->senderId ) != 6 )
                return exit( "Invalid sender ID" );

            return $this;
        }

        public function route ( $route )
        {

            $this->route = $route;

            return $this;
        }


        // DANGER ZONE

    }