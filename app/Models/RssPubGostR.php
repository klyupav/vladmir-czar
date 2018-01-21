<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RssPubGostR
 *
 * @property int $id
 * @property string $STATUS Статус
 * @property string $CERT_NUM Номер сертификата
 * @property string $a_cert_type-cert_gost_r Выбор раздела
 * @property string $a_applicant_org_type-ul Сведения о заявителе, изготовителе, продукции. Тип заявителя
 * @property string $a_manufacturer_type-ul Сведения о заявителе, изготовителе, продукции. Тип изготовителя
 * @property string $a_applicant_info-rss-app_legal_person-applicant_type Сведения о юридическом лице (заявитель). Вид заявителя
 * @property string $a_applicant_info-rss-app_legal_person-name Сведения о юридическом лице (заявитель). Полное наименование
 * @property string $a_applicant_info-rss-app_legal_person-director_name Сведения о юридическом лице (заявитель). ФИО руководителя
 * @property string $a_applicant_info-rss-app_legal_person-address Сведения о юридическом лице (заявитель). Адрес места нахождения
 * @property string $a_applicant_info-rss-app_legal_person-phone Сведения о юридическом лице (заявитель). Номер телефона
 * @property string $a_applicant_info-rss-app_legal_person-fax Сведения о юридическом лице (заявитель). Номер факса
 * @property string $a_applicant_info-rss-app_legal_person-email Сведения о юридическом лице (заявитель). Адрес электронной почты
 * @property string $a_applicant_info-rss-app_legal_person-ogrn Сведения о юридическом лице (заявитель). Основной государственный регистрационный номер записи о государственной регистрации юридического лица (ОГРН)
 * @property string $a_manufacturer_info-rss-man_foreign_legal_person-name Сведения об изготовителе иностранном юридическом лице (изготовитель). Полное наименование
 * @property string $a_manufacturer_info-rss-man_foreign_legal_person-address Сведения об изготовителе иностранном юридическом лице (изготовитель). Адрес места нахождения
 * @property string $a_manufacturer_info-rss-man_legal_person-name Сведения о юридическом лице (изготовитель). Полное наименование
 * @property string $a_manufacturer_info-rss-man_legal_person-address Сведения о юридическом лице (изготовитель). Адрес места нахождения
 * @property string $a_manufacturer_info-rss-man_legal_person-phone Сведения о юридическом лице (изготовитель). Номер телефона
 * @property string $a_manufacturer_info-rss-man_legal_person-fax Сведения о юридическом лице (изготовитель). Номер факса
 * @property string $a_manufacturer_info-rss-man_legal_person-email Сведения о юридическом лице (изготовитель). Адрес электронной почты
 * @property string $a_manufacturer_info-rss-man_legal_person-ogrn Сведения о юридическом лице (изготовитель). Основной государственный регистрационный номер записи о государственной регистрации юридического лица (ОГРН)
 * @property string $cert_doc_issued-document_info Сведения о документах, на основании которых выдан сертификат. Представленные документы
 * @property string $cert_doc_issued-testing_lab-0-basis_for_certificate Сведения о документах, на основании которых выдан сертификат. Основание выдачи сертификата
 * @property string $cert_doc_issued-testing_lab-1-basis_for_certificate Сведения об испытательной лаборатории. Основание выдачи сертификата
 * @property string $cert_doc_issued-testing_lab-0-reg_number Сведения об испытательной лаборатории. Регистрационный номер аттестата аккредитации
 * @property string $a_cert_doc_issued-rss-cert_doc_issued-additional_info Сведения об испытательной лаборатории. Дополнительная информация
 * @property string $a_product_info-rss-product_gost_r-object_type_cert Сведения о продукции. «Тип объекта сертификации»: серийный выпуск, партия, единичное изделие
 * @property string $a_product_info-rss-product_gost_r-product_type Сведения о продукции. Вид продукции
 * @property string $a_product_info-rss-product_gost_r-product_name Сведения о продукции. Полное наименование продукции
 * @property string $a_product_info-rss-product_gost_r-product_info Сведения о продукции. Сведения о продукции (тип, марка, модель, сорт, артикул и др.), обеспечивающие ее идентификацию
 * @property string $a_product_info-rss-product_gost_r-okpd2 Сведения о продукции. Коды ОКПД 2
 * @property string $a_product_info-rss-product_gost_r-okpd2_text Сведения о продукции. Коды ОКПД 2
 * @property string $a_product_info-rss-product_gost_r-tn_ved Сведения о продукции. Код ТН ВЭД ЕАЭС
 * @property string $a_product_info-rss-product_gost_r-tn_ved_text Сведения о продукции. Код ТН ВЭД ЕАЭС
 * @property string $a_product_info-rss-product_gost_r-name_doc_made_product Сведения о продукции. Наименование и реквизиты документа, в соответствии с которыми изготовлена продукция
 * @property string $a_product_info-rss-product_gost_r-product_info_ext Сведения о продукции. Иная информация, идентифицирующая продукцию
 * @property string $a_product_info-rss-product_gost_r-serial_number_product Сведения о продукции. Размер партии или заводской номер изделия
 * @property string $a_product_info-rss-product_gost_r-requisites_doc Сведения о продукции. Реквизиты товаросопроводительной документации
 * @property string $tech_reg Сведения о документах, на основании которых изготовлена продукция. Технический регламент
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
 * @property string $a_info_pril Сведения о приложениях к сертификату. Сведения о приложении (приложениях) к сертификату
 * @property string $a_apps-free_form Сведения о приложениях к сертификату. Свободная форма
 * @property string $a_apps-table_standart Сведения о приложениях к сертификату. Таблица стандартов
 * @property string $a_table_standart_number Сведения о приложениях к сертификату. Номер бланка приложения к СС для таблицы стандартов
 * @property string $rss-table_standart_designation Сведения о приложениях к сертификату. Обозначение национального стандарта или свода правил
 * @property string $rss-table_standart_name Сведения о приложениях к сертификату. Наименование национального стандарта или свода правил
 * @property string $rss-table_standart_confirmation_requirements Сведения о приложениях к сертификату. Подтверждение требованиям национального стандарта или свода правил
 * @property string $a_free_form Сведения о приложениях к сертификату. Прочие сведения о сертификате соответствия
 * @property string $a_reg_number Реквизиты сертификата. Регистрационный номер
 * @property string $a_blank_number Реквизиты сертификата. Номер бланка
 * @property string $a_date_begin Реквизиты сертификата. Дата начала действия
 * @property string $a_date_finish Реквизиты сертификата. Дата окончания действия
 * @property string $a_is_date_finish Реквизиты сертификата. Без срока действия
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereAApplicantInfoRssAppLegalPersonAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereAApplicantInfoRssAppLegalPersonApplicantType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereAApplicantInfoRssAppLegalPersonDirectorName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereAApplicantInfoRssAppLegalPersonEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereAApplicantInfoRssAppLegalPersonFax($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereAApplicantInfoRssAppLegalPersonName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereAApplicantInfoRssAppLegalPersonOgrn($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereAApplicantInfoRssAppLegalPersonPhone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereAApplicantOrgTypeUl($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereAAppsFreeForm($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereAAppsTableStandart($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereABlankNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereACertDocIssuedRssCertDocIssuedAdditionalInfo($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereACertTypeCertGostR($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereADateBegin($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereADateFinish($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereAExpert0FirstName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereAExpert0LastName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereAExpert0PatrName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereAFreeForm($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereAInfoPril($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereAIsDateFinish($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereAManufacturerInfoRssManForeignLegalPersonAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereAManufacturerInfoRssManForeignLegalPersonName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereAManufacturerInfoRssManLegalPersonAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereAManufacturerInfoRssManLegalPersonEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereAManufacturerInfoRssManLegalPersonFax($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereAManufacturerInfoRssManLegalPersonName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereAManufacturerInfoRssManLegalPersonOgrn($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereAManufacturerInfoRssManLegalPersonPhone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereAManufacturerTypeUl($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereAProductInfoRssProductGostRNameDocMadeProduct($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereAProductInfoRssProductGostRObjectTypeCert($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereAProductInfoRssProductGostROkpd2($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereAProductInfoRssProductGostROkpd2Text($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereAProductInfoRssProductGostRProductInfo($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereAProductInfoRssProductGostRProductInfoExt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereAProductInfoRssProductGostRProductName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereAProductInfoRssProductGostRProductType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereAProductInfoRssProductGostRRequisitesDoc($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereAProductInfoRssProductGostRSerialNumberProduct($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereAProductInfoRssProductGostRTnVed($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereAProductInfoRssProductGostRTnVedText($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereARegNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereATableStandartNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereCERTNUM($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereCertDocIssuedDocumentInfo($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereCertDocIssuedTestingLab0BasisForCertificate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereCertDocIssuedTestingLab0RegNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereCertDocIssuedTestingLab1BasisForCertificate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereOrganToCertificationAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereOrganToCertificationAddressActual($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereOrganToCertificationEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereOrganToCertificationFax($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereOrganToCertificationHeadName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereOrganToCertificationName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereOrganToCertificationPhone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereOrganToCertificationRegDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereOrganToCertificationRegNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereRssTableStandartConfirmationRequirements($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereRssTableStandartDesignation($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereRssTableStandartName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereSTATUS($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereTechReg($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RssPubGostR whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RssPubGostR extends Model
{
    protected $table = 'rss_pub_gost_r';

    public $timestamps = true;

    protected $fillable = [
        'STATUS',
        'CERT_NUM',
        'a_cert_type-cert_gost_r',
        'a_applicant_org_type-ul',
        'a_manufacturer_type-ul',
        'a_applicant_info-rss-app_legal_person-applicant_type',
        'a_applicant_info-rss-app_legal_person-name',
        'a_applicant_info-rss-app_legal_person-director_name',
        'a_applicant_info-rss-app_legal_person-address',
        'a_applicant_info-rss-app_legal_person-phone',
        'a_applicant_info-rss-app_legal_person-fax',
        'a_applicant_info-rss-app_legal_person-email',
        'a_applicant_info-rss-app_legal_person-ogrn',
        'a_manufacturer_info-rss-man_foreign_legal_person-name',
        'a_manufacturer_info-rss-man_foreign_legal_person-address',
        'a_manufacturer_info-rss-man_legal_person-name',
        'a_manufacturer_info-rss-man_legal_person-address',
        'a_manufacturer_info-rss-man_legal_person-phone',
        'a_manufacturer_info-rss-man_legal_person-fax',
        'a_manufacturer_info-rss-man_legal_person-email',
        'a_manufacturer_info-rss-man_legal_person-ogrn',
        'cert_doc_issued-document_info',
        'cert_doc_issued-testing_lab-0-basis_for_certificate',
        'cert_doc_issued-testing_lab-1-basis_for_certificate',
        'cert_doc_issued-testing_lab-0-reg_number',
        'a_cert_doc_issued-rss-cert_doc_issued-additional_info',
        'a_product_info-rss-product_gost_r-object_type_cert',
        'a_product_info-rss-product_gost_r-product_type',
        'a_product_info-rss-product_gost_r-product_name',
        'a_product_info-rss-product_gost_r-product_info',
        'a_product_info-rss-product_gost_r-okpd2',
        'a_product_info-rss-product_gost_r-okpd2_text',
        'a_product_info-rss-product_gost_r-tn_ved',
        'a_product_info-rss-product_gost_r-tn_ved_text',
        'a_product_info-rss-product_gost_r-name_doc_made_product',
        'a_product_info-rss-product_gost_r-product_info_ext',
        'a_product_info-rss-product_gost_r-serial_number_product',
        'a_product_info-rss-product_gost_r-requisites_doc',
        'tech_reg',
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
        'a_info_pril',
        'a_apps-free_form',
        'a_apps-table_standart',
        'a_table_standart_number',
        'rss-table_standart_designation',
        'rss-table_standart_name',
        'rss-table_standart_confirmation_requirements',
        'a_free_form',
        'a_reg_number',
        'a_blank_number',
        'a_date_begin',
        'a_date_finish',
        'a_is_date_finish'
    ];

    protected $guarded = [];

    public static function rules()
    {
        return [
            'CERT_NUM' => 'required',
        ];
    }
}