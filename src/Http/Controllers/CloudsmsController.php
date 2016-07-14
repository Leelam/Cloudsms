<?php namespace Leelam\Cloudsms\Http\Controllers;

use Illuminate\Http\Request;
use Leelam\Cloudsms\Libraries\CloudsmsReports;
use Leelam\Cloudsms\Libraries\Contracts\cloudsms;
use Leelam\Cloudsms\Libraries\Contracts\CloudsmsInterface;


class CloudsmsController extends \Illuminate\Routing\Controller
{

    /**
     * @var cloudsms
     */
    protected $cloudsms;

    public function __construct ( CloudsmsInterface $cloudsms )
    {
        $this->cloudsms = $cloudsms;
    }

    public function show ( $mobiles, $senderID = null, $route = null )
    {
        $printOutput = $this->cloudsms
            ->message ( 'This message testing purpose only. Gopal Krishna mosali :: 9949990991 ' )
            ->numbers ( $mobiles )
            ->send ();
        if ( ! is_null ( $senderID ) ) {
            $this->cloudsms->senderId ( $senderID );
        }
        if ( ! is_null ( $route ) ) {
            $this->cloudsms->route ( $route );
        }

        \Log::info ( '##' . $printOutput . '## Service : Cloudsms - Message : ' . $this->cloudsms->message . ' Sender Id : ' . $this->cloudsms->senderId . ' Mobiles : ' . $this->cloudsms->numbers . ' Route : ' . $this->cloudsms->route );

        //return $printOutput;

    }

    public function index ()
    {
        return view ( 'cloudsms::cloudsms.index' );
    }

    public function sendCloudsms ( Request $request, CloudsmsInterface $cloudsmsInterface )
    {

        //return  $cloudsmsInterface->url;

        $data[ 'mobile' ] = $request->mobiles;

        $tenDigitNumber = ( strlen ( $data[ 'mobile' ] ) >= 10 ) ? substr ( $data[ 'mobile' ], -10 ) : 'Wrong number';

        if ( is_numeric ( $tenDigitNumber ) ) {

            $data[ 'message' ] = '2 Thank you for test India\'s fast and flexible SMS API. \n Call us on 8008008322 at  any time.';
            $senderid = 'CLDSMS';
            $route = 4;


            $cloudsmsInterface->dataXML ( $data )
                ->senderId ( $senderid )
                ->route ( $route )
                ->sendXML ();

            return back ()->with ( 'status', "That\s it" );
        }

        \Log::error ( "User has given wrong mobile number" );

        return back ()->with ( 'status', "Wrong mobile number" );

    }

    public function getDlr ()
    {
        $reports = [ ];

        return view ( 'cloudsms::cloudsms.reports', compact ( 'reports' ) );
    }

    public function postDlr ()
    {

        \Log::error ( "Cloudsms has started posting DLRs" );

        $jsonData = json_decode ( $_REQUEST[ "data" ], true );
        foreach ( $jsonData as $key => $value ) {
            $dataToInsert[ 'request_id' ] = $value[ 'requestId' ];
            $dataToInsert[ 'user_id' ] = $value[ 'userId' ];
            $dataToInsert[ 'senderid' ] = $value[ 'senderId' ];
            $dataToInsert[ 'data' ] = json_encode ( $value[ 'report' ], true ); //json

            $cloudsmsReports = CloudsmsReports::whereRequestId ( $value[ 'requestId' ] )->first ();

            if ( ! is_null ( $cloudsmsReports ) ) {
                $cloudsmsReports->update ( [ 'data' => $dataToInsert[ 'data' ] ] );
            } else {
                \Log::error ( "Error in posting DLRs. Cloudsms Package error." );
            }
        }
    }

}