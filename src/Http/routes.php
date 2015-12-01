<?php
/**
 * Created by Leelam Consultancy Services Pvt. Ltd.
 * User: Gopal Krishna Mosali
 * Email : krishna@leelam.com
 * Date: 28/11/15
 * Time: 10:45 AM
 */

Route::group([ 'namespace' => 'Leelam\Cloudsms\Http\Controllers' ], function () {

    Route::get('/cloudsms', 'CloudsmsController@index');
    Route::post('/sendCloudsms', 'CloudsmsController@sendCloudsms');

});