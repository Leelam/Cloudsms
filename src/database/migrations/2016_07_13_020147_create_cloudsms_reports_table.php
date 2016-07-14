<?php

    use \Illuminate\Database\Schema\Blueprint;
    use \Illuminate\Database\Migrations\Migration;

    class CreateCloudsmsReportsTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up ()
        {
            Schema::create ( 'cloudsms_reports', function ( Blueprint $table ) {
                $table->increments ( 'id' );
                $table->unsignedInteger ( 'user_id' );// basically User::id
                $table->string ( 'senderid', 10 );
                $table->string ( 'request_id', 24 )->unique ()->index (); // unique
                $table->tinyInteger ( 'request_route' );
                $table->string ( 'message' );
                $table->ipAddress ( 'sender_ip' );
                $table->json ( 'data' ); // combination of mobile:status like "8008008322":"sent"
                $table->tinyInteger ( 'status' )->default ( 1 );
                $table->timestamps ();
            } );
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down ()
        {
            Schema::drop ( 'cloudsms_reports' );
        }
    }
