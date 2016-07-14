<?php

    namespace Leelam\Cloudsms\Libraries;

    use Illuminate\Database\Eloquent\Model;

    class CloudsmsReports extends Model
    {

        protected $casts = [
            'data' => 'array',
        ];

        protected $fillable = [

            'user_id',
            'senderid',
            'message',
            'request_id',
            'sender_ip',
            'data',
            'status',
            'request_route',
        ];


        public static function createInstantReport ( $message, $senderid, $requestid, $route, array $response )
        {
            // Request id
            $dataToInsert[ 'user_id' ] = 1; // active user id
            $dataToInsert[ 'request_id' ] = $requestid;
            $dataToInsert[ 'request_route' ] = $route;
            $dataToInsert[ 'senderid' ] = $senderid;
            $dataToInsert[ 'message' ] = $message;
            $dataToInsert[ 'data' ] = $response; //json
            $dataToInsert[ 'sender_ip' ] = getClientIP ();

            CloudsmsReports::create ( $dataToInsert );
        }
    }
