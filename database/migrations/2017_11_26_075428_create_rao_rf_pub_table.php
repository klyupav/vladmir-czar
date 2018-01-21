<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRaoRfPubTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rao_rf_pub', function(Blueprint $table){
            $table->increments('id');
            $table->string('STATUS')->nullable()->comment('Статус');
            $table->string('CERT_NUM')->unique()->comment('Номер аттестата аккредитации');
            $table->string('REG_DATE')->comment('Дата внесения в реестр сведений об аккредитованном лице');
            $table->string('END_DATE')->nullable()->comment('Срок действия');
            $table->string('STATUS_DATE')->nullable()->comment('Дата приостановления действия');

            $table->string('NUM_DECISION')->nullable()->comment('Номер решения об аккредитации');
            $table->string('DATE_DECISION')->nullable()->comment('Дата решения об аккредитации');

            $table->text('TYPE_DECLARANT')->nullable()->comment('Тип заявителя');

            $table->text('FULL_NAME')->nullable()->comment('Полное наименование юридического лица');
            $table->text('FIO')->nullable()->comment('ФИО руководителя юридического лица');
            $table->text('ADDRESS')->nullable()->comment('Адреса места нахождения юридического лица ');

            $table->string('PHONE')->nullable()->comment('Номер телефона');
            $table->string('FAX')->nullable()->comment('Номер факса');
            $table->string('EMAIL')->nullable()->comment('Адрес электронной почты');
            $table->string('GOS_REG_YR')->nullable()->comment('Государственный регистрационный номер записи о регистрации юридического лица');
            $table->string('INN')->nullable()->comment('Идентификационный номер налогоплательщика');

            $table->string('FIO_RUC_ACC_LICA')->nullable()->comment('ФИО руководителя аккредитованного лица');
            $table->text('ADRESS_ACC_AREA')->nullable()->comment('Адрес мест осуществления деятельности в области аккредитации');

            $table->text('ACC_AREA')->nullable()->comment('Область аккредитации');
            $table->text('TR')->nullable()->comment('Технический регламент ТС');
            $table->text('OKPD')->nullable()->comment('Коды ОКПД');
            $table->text('OKP')->nullable()->comment('Коды ОКП');
            $table->text('OKUN')->nullable()->comment('Коды ОКУН');
            $table->text('TN_VAD')->nullable()->comment('Коды ТН ВЭД');
            $table->text('SKAN')->nullable()->comment('Скан-копия области аккредитации');

            $table->text('NC_TN_VAD_EAS')->nullable()->comment('Национальная часть-Коды ТН ВЭД ЕАЭС');
            $table->text('NC_TR_EAS')->nullable()->comment('Национальная часть-Технический регламент ЕАЭС');
            $table->text('NC_ACC_AREA')->nullable()->comment('Национальная часть-Область аккредитации');

            $table->string('NUM_DECISION_INCR_ACC_AREA')->nullable()->comment('Номер решения о расширении области аккредитации');
            $table->string('DATE_DECISION_INCR_ACC_AREA')->nullable()->comment('Дата решения о расширении области аккредитации');
            $table->text('TR_INCR_ACC_AREA')->nullable()->comment('Технический регламент ТС расширяемой области аккредитации');
            $table->text('TN_VAD_INCR_ACC_AREA')->nullable()->comment('Коды ТН ВЭД расширяемой области аккредитации');
            $table->text('DESC_ACC_AREA')->nullable()->comment('Описание области аккредитации');

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
        Schema::drop('rao_rf_pub');
    }
}
