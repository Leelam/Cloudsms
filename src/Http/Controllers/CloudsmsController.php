<?php namespace Leelam\Cloudsms\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Leelam\Cloudsms\Libraries\Contracts\CloudsmsInterface;
use Leelam\Cloudsms\Libraries\Gateways\SMS\Cloudsms;

class CloudsmsController extends BaseController {

    /**
     * @var CloudsmsInterface
     */
    protected $cloudsmsInterface;

    public function __construct(CloudsmsInterface $cloudsmsInterface)
    {
        $this->cloudsmsInterface = $cloudsmsInterface;
    }

    public function show()
    {
     $printOutput =  $this->cloudsmsInterface
            ->message('This message testing purpose only. Gopal Krishna mosali :: 9949990991 ')
            ->numbers('9949990991,223223665')
            ->senderId('Leelam')
            ->route(4)
            ->send();

        Log::info( ' Service : Cloudsms - Message : ' . $this->cloudsmsInterface->message . ' Sender Id : ' . $this->cloudsmsInterface->senderId .  ' Sender Mobiles : ' .  $this->cloudsmsInterface->numbers .  ' Route : ' .$this->cloudsmsInterface->route  ) ;
        return $printOutput;

    }

    public function index()
    {


       echo $this->show();

        // return view('cloudsms::cloudsmses.index');
    }

}