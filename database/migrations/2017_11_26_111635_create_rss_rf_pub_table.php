<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRssRfPubTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rss_rf_pub', function(Blueprint $table){
            $table->increments('id');

            $table->string('STATUS')->nullable()->comment('Статус');

            $table->string('CERT_NUM')->unique()->comment('Номер сертификата');
            $table->text('CERF_RF')->nullable()->comment('Выбор раздела');

            $table->text('ORG_TYPE')->nullable()->comment('Тип заявителя');
            $table->text('MANUFACTURER_TYPE')->nullable()->comment('Тип изготовителя');

            $table->text('O_APPLICANT_TYPE')->nullable()->comment('Вид заявителя');
            $table->text('DECLARANT')->nullable()->comment('Заявитель');
            $table->text('O_DIRECTOR_NAME')->nullable()->comment('ФИО руководителя (заявитель)');
            $table->text('O_ADDRESS')->nullable()->comment('Адрес места нахождения (заявитель)');
            $table->text('O_PHONE')->nullable()->comment('Номер телефона (заявитель)');
            $table->text('O_FAX')->nullable()->comment('Номер факса (заявитель)');
            $table->text('O_EMAIL')->nullable()->comment('Адрес электронной почты (заявитель)');
            $table->text('O_OGRN')->nullable()->comment('Основной государственный регистрационный номер записи о государственной регистрации юридического лица (ОГРН) (заявитель)');

            $table->text('MANUFACTURER')->nullable()->comment('Изготовитель');
            $table->text('M_ADDRESS')->nullable()->comment('Адрес места нахождения (изготовитель)');
            $table->text('M_PHONE')->nullable()->comment('Номер телефона (изготовитель)');
            $table->text('M_FAX')->nullable()->comment('Номер факса (изготовитель)');
            $table->text('M_EMAIL')->nullable()->comment('Адрес электронной почты (изготовитель)');
            $table->text('M_OGRN')->nullable()->comment('Основной государственный регистрационный номер записи о государственной регистрации юридического лица (ОГРН) (изготовитель)');

            $table->text('DOCUMENT_INFO')->nullable()->comment('Представленные документы');
            $table->text('BASIC_FOR_CERTIFICATE')->nullable()->comment('Основание выдачи сертификата');

            $table->text('REG_NUMBER')->nullable()->comment('Регистрационный номер аттестата аккредитации');
            $table->text('TESTING_LAB_0_NAME')->nullable()->comment('Наименование испытательной лаборатории (центра)');
            $table->text('DATE_REG')->nullable()->comment('Дата регистрации аттестата');
            $table->text('OTHER_DOCUMENT_INFO')->nullable()->comment('Прочие документы, послужившие основанием выдачи сертификата');

            $table->text('OBJECT_TYPE_CERT')->nullable()->comment('«Тип объекта сертификации»: серийный выпуск, партия, единичное изделие');
            $table->text('PRODUCT_TYPE')->nullable()->comment('Вид продукции');
            $table->text('PRODUCTION')->nullable()->comment('Полное наименование продукции');
            $table->text('PRODUCTION_INFO')->nullable()->comment('Сведения о продукции (тип, марка, модель, сорт, артикул и др.), обеспечивающие ее идентификацию');
            $table->text('OKPD2')->nullable()->comment('Коды ОКПД 2');
            $table->text('TN_VED')->nullable()->comment('Код ТН ВЭД ЕАЭС');
            $table->text('PRODUCT_INFO_EXT')->nullable()->comment('Иная информация, идентифицирующая продукцию');

            $table->text('REGLAMENT')->nullable()->comment('Технический регламент');
            $table->text('PROD_DOC_ISSUED')->nullable()->comment('Дополнительные сведения о технических регламентах');

            $table->text('NOTATION_NATIONAL_STANDART')->nullable()->comment('Обозначение национального стандарта или свода правил');

            $table->text('a_expert-0-last_name')->nullable()->comment('Cведения об экспертах по сертификации, проводивших работы по сертификации. Фамилия');
            $table->text('a_expert-0-first_name')->nullable()->comment('Cведения об экспертах по сертификации, проводивших работы по сертификации. Имя');
            $table->text('a_expert-0-patr_name')->nullable()->comment('Cведения об экспертах по сертификации, проводивших работы по сертификации. Отчество');

            $table->text('organ_to_certification-name')->nullable()->comment('Сведения об органе по сертификации. Полное наименование');
            $table->text('organ_to_certification-reg_number')->nullable()->comment('Сведения об органе по сертификации. Номер аттестата');
            $table->text('organ_to_certification-reg_date')->nullable()->comment('Сведения об органе по сертификации. Дата регистрации аттестата');
            $table->text('organ_to_certification-head_name')->nullable()->comment('Сведения об органе по сертификации. ФИО руководителя');
            $table->text('organ_to_certification-address')->nullable()->comment('Сведения об органе по сертификации. Юридический адрес');
            $table->text('organ_to_certification-address_actual')->nullable()->comment('Сведения об органе по сертификации. Адрес места нахождения');
            $table->text('organ_to_certification-phone')->nullable()->comment('Сведения об органе по сертификации. Номер телефона');
            $table->text('organ_to_certification-fax')->nullable()->comment('Сведения об органе по сертификации. Номер факса');
            $table->text('organ_to_certification-email')->nullable()->comment('Сведения об органе по сертификации. Адрес электронной почты');

            $table->boolean('cgroup-apps-table_standart')->nullable()->comment('Сведения о приложениях к сертификату. Таблица стандартов');
            $table->text('a_table_standart_number')->nullable()->comment('Сведения о приложениях к сертификату. Номер бланка приложения к СС для таблицы стандартов');
            $table->text('rss-table_standart-designation')->nullable()->comment('Сведения о приложениях к сертификату. Обозначение национального стандарта или свода правил');
            $table->text('rss-table_standart-name')->nullable()->comment('Сведения о приложениях к сертификату. Наименование национального стандарта или свода правил');
            $table->text('rss-table_standart-confirmation_requirements')->nullable()->comment('Сведения о приложениях к сертификату. Подтверждение требованиям национального стандарта или свода правил');

            $table->text('a_reg_number')->nullable()->comment('Реквизиты сертификата. Регистрационный номер');
            $table->text('a_blank_number')->nullable()->comment('Реквизиты сертификата. Номер бланка');
            $table->string('ISSUE_DATE')->comment('Дата начала действия');
            $table->string('END_DATE')->nullable()->comment('Дата окончания действия');
            $table->boolean('cis_date_finish')->nullable()->comment('Реквизиты сертификата. Без срока действия');

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
        Schema::drop('rss_rf_pub');
    }
}
