<?php

use Msieprawski\EloquentPhotos\Models\Photo;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEloquentPhotosTable extends Migration
{

    /**
     * @var string
     */
    private $table;

    /**
     * CreateEloquentPhotosTable constructor.
     */
    public function __construct()
    {
        $photoModel = new Photo();
        $this->table = $photoModel->getTable();
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function(Blueprint $table)
        {
            $table->increments('id')->unsigned();
            $table->integer('eloquent_id')->unsigned();
            $table->string('eloquent_type');
            $table->string('photo_original_name');
            $table->string('photo_path');
            $table->decimal('photo_size')->comment('In kilobytes.');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->table);
    }

}