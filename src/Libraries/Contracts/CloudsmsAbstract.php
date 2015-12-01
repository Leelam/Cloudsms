<?php
namespace Leelam\Cloudsms\Libraries\Contracts;


abstract class CloudsmsAbstract {

    /**
     *
     * CloudsmsAbstract constructor.
     */

    public function __construct()
    {
        if (!env('CLOUDSMS_ENABLE')){
            return exit("Cloudsms service is not enabled");
        }

        $this->authkey = config('cloudsms.connections.cloudsms.authkey');
        $this->url = config('cloudsms.connections.cloudsms.send_url');
        $this->senderId = config('cloudsms.connections.cloudsms.sender');
        $this->route = config('cloudsms.connections.cloudsms.route');
     //   $this->connection = "default";
        $this->ch = curl_init();
    }

    public function senderId($id)
    {
        $this->senderId = $id;

        if (strlen($this->senderId) != 6)
            return exit("Invalid sender ID");

        return $this;
    }

    public function route($route)
    {
        $this->route = $route;
        return $this;
    }



    // DANGER ZONE

}