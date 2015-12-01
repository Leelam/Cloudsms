<?php namespace Leelam\Cloudsms\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Leelam\Cloudsms\Libraries\Contracts\CloudsmsInterface;

class CloudsmsController extends BaseController {

    /**
     * @var CloudsmsInterface
     */
    protected $cloudsmsInterface;

    public function __construct(CloudsmsInterface $cloudsmsInterface)
    {
        $this->cloudsmsInterface = $cloudsmsInterface;
    }

    public function show($mobiles)
    {
     $printOutput =  $this->cloudsmsInterface
            ->message('This message testing purpose only. Gopal Krishna mosali :: 9949990991 ')
            ->numbers($mobiles)
            ->senderId('Leelam')
            ->route(4)
            ->send();

        Log::info( ' Service : Cloudsms - Message : ' . $this->cloudsmsInterface->message . ' Sender Id : ' . $this->cloudsmsInterface->senderId .  ' Mobiles : ' .  $this->cloudsmsInterface->numbers .  ' Route : ' .$this->cloudsmsInterface->route  ) ;
        return $printOutput;

    }

    public function index()
    {
         return view('cloudsms::cloudsms.index');
    }

    public function sendCloudsms(Request $request)
    {

        echo $this->show($request->mobiles);
    }

}