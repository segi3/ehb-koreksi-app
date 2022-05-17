<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ujian_siswa', function (Blueprint $table) {

            $table->uuid('id');
            $table->primary('id');

            $table->text('random_soal')->nullable();
            $table->text('kunci_jawaban')->nullable();
            $table->text('random_jawaban')->nullable();
            $table->text('jawaban_siswa')->nullable();

            $table->dateTime('waktu_mulai')->nullable();
            $table->dateTime('waktu_selesai')->nullable();

            $table->text('predikat')->nullable();
            $table->text('jumlah_benar')->nullable();
            $table->text('jumlah_salah')->nullable();
            $table->text('jumlah_kosong')->nullable();
            $table->text('nilai_uraian')->nullable();

            // pilgan
            $table->double('pg_benar')->nullable();
            // pilgan l1
            $table->double('pgk_l1_benar')->nullable();
            // pilgan bs1
            $table->double('pg_bs1_benar')->nullable();
            // pilgan bsl1
            $table->double('pg_bsl1_benar')->nullable();
            // mjdk
            $table->double('mjdk_benar')->nullable();
            // isian
            $table->double('ijs_benar')->nullable();

            $table->string('status')->default(0);
            $table->string('keterangan')->default('Lancar');

            $table->char('paket_id', 36);
            $table->char('user_id', 36);
            $table->char('jadwal_ujian_id', 36);
            // $table->integer('is_done')->default('0');

            // set index
            $table->index(["user_id"]);
            $table->index(["jadwal_ujian_id"]);
            $table->index(["paket_id"]);

            $table->text('soal_1')->nullable();
            $table->text('soal_2')->nullable();
            $table->text('soal_3')->nullable();
            $table->text('soal_4')->nullable();
            $table->text('soal_5')->nullable();
            $table->text('soal_6')->nullable();
            $table->text('soal_7')->nullable();
            $table->text('soal_8')->nullable();
            $table->text('soal_9')->nullable();
            $table->text('soal_10')->nullable();

            $table->text('soal_11')->nullable();
            $table->text('soal_12')->nullable();
            $table->text('soal_13')->nullable();
            $table->text('soal_14')->nullable();
            $table->text('soal_15')->nullable();
            $table->text('soal_16')->nullable();
            $table->text('soal_17')->nullable();
            $table->text('soal_18')->nullable();
            $table->text('soal_19')->nullable();
            $table->text('soal_20')->nullable();

            $table->text('soal_21')->nullable();
            $table->text('soal_22')->nullable();
            $table->text('soal_23')->nullable();
            $table->text('soal_24')->nullable();
            $table->text('soal_25')->nullable();
            $table->text('soal_26')->nullable();
            $table->text('soal_27')->nullable();
            $table->text('soal_28')->nullable();
            $table->text('soal_29')->nullable();
            $table->text('soal_30')->nullable();

            $table->text('soal_31')->nullable();
            $table->text('soal_32')->nullable();
            $table->text('soal_33')->nullable();
            $table->text('soal_34')->nullable();
            $table->text('soal_35')->nullable();
            $table->text('soal_36')->nullable();
            $table->text('soal_37')->nullable();
            $table->text('soal_38')->nullable();
            $table->text('soal_39')->nullable();
            $table->text('soal_40')->nullable();

            $table->text('soal_41')->nullable();
            $table->text('soal_42')->nullable();
            $table->text('soal_43')->nullable();
            $table->text('soal_44')->nullable();
            $table->text('soal_45')->nullable();
            $table->text('soal_46')->nullable();
            $table->text('soal_47')->nullable();
            $table->text('soal_48')->nullable();
            $table->text('soal_49')->nullable();
            $table->text('soal_50')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ujian_siswa');
    }
};
