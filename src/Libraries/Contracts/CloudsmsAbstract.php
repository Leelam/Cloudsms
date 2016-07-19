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
            $this->url = env ( 'CLOUDSMS_POST_URL', 'http://api.cloudsms.in/api/postsms.php' );
            //   $this->connection = "default";
            $this->ch = curl_init ();
        }

        public function url ( $url )
        {
            $this->url = $url;
            if ( $this->url == 'post' ) {
                $this->url = 'http://api.cloudsms.in/api/sendhttp.php';
            }

            return $this;
        }
        
        public function senderId ( $id )
        {
            $this->senderId = $id;
            if ( strlen ( $this->senderId ) != 6 ) {
                \Log::error ( "Invalid sender ID, and mapped to default sender id from ENV." );
                $this->senderId = env ( 'CLOUDSMS_SENDER_ID', 'LEELAM' );
                //return exit( "Invalid sender ID" );
            }

            return $this;
        }

        public function route ( $route )
        {
            $this->route = $route;
            return $this;
        }

        // DANGER ZONE
    }