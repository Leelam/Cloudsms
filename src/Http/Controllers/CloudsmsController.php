<?php namespace Leelam\Cloudsms\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Leelam\Cloudsms\Libraries\Contracts\cloudsms;
use Leelam\Cloudsms\Libraries\Contracts\CloudsmsInterface;

class CloudsmsController extends Controller
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

    public function sendCloudsms ( Request $request )
    {

        echo $this->show ( $request->mobiles );
    }

}