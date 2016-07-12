<?php
    /**
     * Created by Leelam Consultancy Services Pvt. Ltd.
     * User: Gopal Krishna Mosali
     * Email : krishna@leelam.com
     * Date: 28/11/15
     * Time: 10:45 AM
     */

    Route::group ( [ 'namespace' => 'Leelam\Cloudsms\Http\Controllers' ], function () {

        Route::get ( '/cloudsms/home', 'CloudsmsController@index' );

        Route::post ( '/cloudsms/sendCloudsms', [ 'as' => 'sendCloudsms', 'uses' => 'CloudsmsController@sendCloudsms' ] );

        // get tabled reports
        Route::get ( 'cloudsms/reports', [ 'as' => 'getDLR', 'uses' => 'CloudsmsController@getDlr' ] );

        // You must whitelist this
        Route::post ( 'cloudsms/reports', [ 'as' => 'postDLR', 'uses' => 'CloudsmsController@postDlr' ] );

    } );