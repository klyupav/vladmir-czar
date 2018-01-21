<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RssRfPub
 *
 * @property int $id
 * @property string $STATUS Статус
 * @property string $CERT_NUM Номер сертификата
 * @property string $CERF_RF Выбор раздела
 * @property string $ORG_TYPE Тип заявителя
 * @property string $MANUFACTURER_TYPE Тип изготовителя
 * @property string $O_APPLICANT_TYPE Вид заявителя
 * @property string $DECLARANT Заявитель
 * @property string $O_DIRECTOR_NAME ФИО руководителя (заявитель)
 * @property string $O_ADDRESS Адрес места нахождения (заявитель)
 * @property string $O_PHONE Номер телефона (заявитель)
 * @property string $O_FAX Номер факса (заявитель)
 * @property string $O_EMAIL Адрес электронной почты (заявитель)
 * @property string $O_OGRN Основной государственный регистрационный номер записи о государственной регистрации юридического лица (ОГРН) (заявитель)
 * @property string $MANUFACTURER Изготовитель
 * @property string $M_ADDRESS Адрес места нахождения (изготовитель)
 * @property string $M_PHONE Номер телефона (изготовитель)
 * @property string $M_FAX Номер факса (изготовитель)
 * @property string $M_EMAIL Адрес электронной почты (изготовитель)
 * @property string $M_OGRN Основной государственный регистрационный номер записи о государственной регистрации юридического лица (ОГРН) (изготовитель)
 * @property string $DOCUMENT_INFO Представленные документы
 * @property string $BASIC_FOR_CERTIFICATE Основание выдачи сертификата
 * @property string $REG_NUMBER Регистрационный номер аттестата аккредитации
 * @property string $TESTING_LAB_0_NAME Наименование испытательной лаборатории (центра)
 * @property string $DATE_REG Дата регистрации аттестата
 * @property string $OTHER_DOCUMENT_INFO Прочие документы, послужившие основанием выдачи сертификата
 * @property string $OBJECT_TYPE_CERT «Тип объекта сертификации»: серийный выпуск, партия, единичное изделие
 * @property string $PRODUCT_TYPE Вид продукции
 * @property string $PRODUCTION Полное наименование продукции
 * @property string $PRODUCTION_INFO Сведения о продукции (тип, марка, модель, сорт, артикул и др.), обеспечивающие ее идентификацию
 * @property string $OKPD2 Коды ОКПД 2
 * @property string $TN_VED Код ТН ВЭД ЕАЭС
 * @property string $PRODUCT_INFO_EXT Иная информация, идентифицирующая продукцию
 * @property string $REGLAMENT Технический регламент
 * @property string $PROD_DOC_ISSUED Дополнительные сведения о технических регламентах
 * @property string $NOTATION_NATIONAL_STANDART Обозначение национального стандарта или свода правил
 * @property string $a_expert-0-last_name Cведения об экспертах по сертификации, проводивших работы по сертификации. Фамилия
 * @property string $a_expert-0-first_name Cведения об экспертах по сертификации, проводивших работы по сертификации. Имя
 * @property string $a_expert-0-patr_name Cведения об экспертах по сертификации, проводивших работы по сертификации. Отчество
 * @property string $organ_to_certification-name Сведения об органе по сертификации. Полное наименование
 * @property string $organ_to_certification-reg_number Сведения об органе по сертификации. Номер аттестата
 * @property string $organ_to_certification-reg_date Сведения об органе по сертификации. Дата регистрации аттестата
 * @property string $organ_to_certification-head_name Сведения об органе по сертификации. ФИО руководителя
 * @property string $organ_to_certification-address Сведения об органе по сертификации. Юридический адрес
 * @property string $organ_to_certification-address_actual Сведения об органе по сертификации. Адрес места нахождения
 * @property string $organ_to_certification-phone Сведения об органе по сертификации. Номер телефона
 * @property string $organ_to_certification-fax Сведения об органе по сертификации. Номер факса
 * @property string $organ_to_certification-email Сведения об органе по сертификации. Адрес электронной почты
 * @property bool $cgroup-apps-table_standart Сведения о приложениях к сертификату. Таблица стандартов
 * @property string $a_table_standart_number Сведения о приложениях к сертификату. Номер бланка приложения к СС для таблицы стандартов
 * @property string $rss-table_standart-designation Сведения о приложениях к сертификату. Обозначение национального стандарта или свода правил
 * @property string $rss-table_standart-name Сведения о приложениях к сертификату. Наименование национального стандарта или свода правил
 * @property string $rss-table_standart-confirmation_requirements Сведения о приложениях к сертификату. Подтверждение требованиям национального стандарта или свода правил
 * @property string $a_reg_number Реквизиты сертификата. Регистрационный номер
 * @property string $a_blank_number Реквизиты сертификата. Номер бланка
 * @property string $ISSUE_DATE Дата начала действия
 * @property string $END_DATE Дата окончания действия
 * @property bool $cis_date_finish Реквизиты сертификата. Без срока действия
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereABlankNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereAExpert0FirstName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereAExpert0LastName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereAExpert0PatrName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereARegNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereATableStandartNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereBASICFORCERTIFICATE($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereCERFRF($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereCERTNUM($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereCgroupAppsTableStandart($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereCisDateFinish($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereDATEREG($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereDECLARANT($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereDOCUMENTINFO($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereENDDATE($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereISSUEDATE($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereMADDRESS($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereMANUFACTURER($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereMANUFACTURERTYPE($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereMEMAIL($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereMFAX($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereMOGRN($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereMPHONE($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereNOTATIONNATIONALSTANDART($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereOADDRESS($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereOAPPLICANTTYPE($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereOBJECTTYPECERT($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereODIRECTORNAME($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereOEMAIL($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereOFAX($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereOKPD2($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereOOGRN($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereOPHONE($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereORGTYPE($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereOTHERDOCUMENTINFO($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereOrganToCertificationAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereOrganToCertificationAddressActual($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereOrganToCertificationEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereOrganToCertificationFax($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereOrganToCertificationHeadName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereOrganToCertificationName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereOrganToCertificationPhone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereOrganToCertificationRegDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereOrganToCertificationRegNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub wherePRODDOCISSUED($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub wherePRODUCTINFOEXT($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub wherePRODUCTION($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub wherePRODUCTIONINFO($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub wherePRODUCTTYPE($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereREGLAMENT($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereREGNUMBER($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereRssTableStandartConfirmationRequirements($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereRssTableStandartDesignation($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereRssTableStandartName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereSTATUS($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereTESTINGLAB0NAME($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereTNVED($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssRfPub whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RssRfPub extends Model
{
    protected $table = 'rss_rf_pub';

    public $timestamps = true;

    protected $fillable = [
        'STATUS',
        'CERT_NUM',
        'CERF_RF',
        'ORG_TYPE',
        'MANUFACTURER_TYPE',
        'O_APPLICANT_TYPE',
        'DECLARANT',
        'O_DIRECTOR_NAME',
        'O_ADDRESS',
        'O_PHONE',
        'O_FAX',
        'O_EMAIL',
        'O_OGRN',
        'MANUFACTURER',
        'M_ADDRESS',
        'M_PHONE',
        'M_FAX',
        'M_EMAIL',
        'M_OGRN',
        'DOCUMENT_INFO',
        'BASIC_FOR_CERTIFICATE',
        'REG_NUMBER',
        'TESTING_LAB_0_NAME',
        'DATE_REG',
        'OTHER_DOCUMENT_INFO',
        'OBJECT_TYPE_CERT',
        'PRODUCT_TYPE',
        'PRODUCTION',
        'PRODUCTION_INFO',
        'OKPD2',
        'TN_VED',
        'PRODUCT_INFO_EXT',
        'REGLAMENT',
        'PROD_DOC_ISSUED',
        'NOTATION_NATIONAL_STANDART',
        'a_expert-0-last_name',
        'a_expert-0-first_name',
        'a_expert-0-patr_name',
        'organ_to_certification-name',
        'organ_to_certification-reg_number',
        'organ_to_certification-reg_date',
        'organ_to_certification-head_name',
        'organ_to_certification-address',
        'organ_to_certification-address_actual',
        'organ_to_certification-phone',
        'organ_to_certification-fax',
        'organ_to_certification-email',
        'cgroup-apps-table_standart',
        'a_table_standart_number',
        'rss-table_standart-designation',
        'rss-table_standart-name',
        'rss-table_standart-confirmation_requirements',
        'a_reg_number',
        'a_blank_number',
        'ISSUE_DATE',
        'END_DATE',
        'cis_date_finish'
    ];

    protected $guarded = [];

    public static function rules()
    {
        return [
            'CERT_NUM' => 'required',
        ];
    }
}