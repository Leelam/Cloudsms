<?php
namespace Leelam\Cloudsms\Libraries\Contracts;

    /**
     * Main gateway interface for all the SMS communication
     *
     * Interface CloudsmsInterface
     */
interface CloudsmsInterface {

    /**
     *  Every Sub/child class Requires number numbers
     *
     * @param $mobiles
     * @return mixed
     */
    public function numbers($mobiles);

    /**
     * Every Sub/Child class Requires Message
     *
     * @param $content
     * @return mixed
     */
    public function message ( $content ) : string;

    /**
     * Send/Execute
     * @param $data
     * @return mixed
     * @internal param $data
     */
    public function send($data = null);
}