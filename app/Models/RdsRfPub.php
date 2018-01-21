<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class RdsRfPub
 *
 * @property int $id
 * @property string $STATUS Статус
 * @property string $DECL_NUM Номер декларации
 * @property string $a_cert_type-cert_rf Выбор раздела
 * @property string $a_applicant_org_type-ul Сведения о заявителе, изготовителе, продукции. Тип заявителя
 * @property string $a_manufacturer_type-ul Сведения о заявителе, изготовителе, продукции. Тип изготовителя
 * @property string $a_applicant_info-rds-app_legal_person-applicant_type Сведения о юридическом лице (заявитель). Тип декларанта
 * @property string $a_applicant_info-rds-app_legal_person-name Сведения о юридическом лице (заявитель). Полное наименование
 * @property string $a_applicant_info-rds-app_legal_person-director_name Сведения о юридическом лице (заявитель). ФИО руководителя
 * @property string $a_applicant_info-rds-app_legal_person-address Сведения о юридическом лице (заявитель). Адрес места нахождения
 * @property string $a_applicant_info-rds-app_legal_person-phone Сведения о юридическом лице (заявитель). Номер телефона
 * @property string $a_applicant_info-rds-app_legal_person-fax Сведения о юридическом лице (заявитель). Номер факса
 * @property string $a_applicant_info-rds-app_legal_person-email Сведения о юридическом лице (заявитель). Адрес электронной почты
 * @property string $a_applicant_info-rds-app_legal_person-ogrn Сведения о юридическом лице (заявитель). Основной государственный регистрационный номер записи о государственной регистрации юридического лица (ОГРН)
 * @property string $a_manufacturer_info-rds-man_foreign_legal_person-name Сведения об изготовителе иностранном юридическом лице (изготовитель). Полное наименование
 * @property string $a_manufacturer_info-rds-man_foreign_legal_person-address Сведения об изготовителе иностранном юридическом лице (изготовитель). Адрес места нахождения
 * @property string $a_manufacturer_info-rds-man_foreign_legal_person-phone Сведения об изготовителе иностранном юридическом лице (изготовитель). Номер телефона
 * @property string $a_manufacturer_info-rds-man_foreign_legal_person-fax Сведения об изготовителе иностранном юридическом лице (изготовитель). Номер факса
 * @property string $a_manufacturer_info-rds-man_foreign_legal_person-email Сведения об изготовителе иностранном юридическом лице (изготовитель). Адрес электронной почты
 * @property string $a_manufacturer_info-rds-man_legal_person-name Сведения о юридическом лице (изготовитель). Полное наименование
 * @property string $a_manufacturer_info-rds-man_legal_person-address Сведения о юридическом лице (изготовитель). Адрес места нахождения
 * @property string $a_manufacturer_info-rds-man_legal_person-phone Сведения о юридическом лице (изготовитель). Номер телефона
 * @property string $a_manufacturer_info-rds-man_legal_person-fax Сведения о юридическом лице (изготовитель). Номер факса
 * @property string $a_manufacturer_info-rds-man_legal_person-email Сведения о юридическом лице (изготовитель). Адрес электронной почты
 * @property string $a_manufacturer_info-rds-man_legal_person-ogrn Сведения о юридическом лице (изготовитель). Основной государственный регистрационный номер записи о государственной регистрации юридического лица (ОГРН)
 * @property string $cert_doc_issued-document_info Сведения о документах, на основании которых выдана декларация. Представленные документы
 * @property string $cert_doc_issued-certification_scheme Сведения о документах, на основании которых выдана декларация. Схема декларирования
 * @property string $cert_doc_issued-testing_lab-0-basis_for_certificate Сведения о документах, на основании которых выдана декларация. Основание выдачи декларации
 * @property string $cert_doc_issued-testing_lab-1-basis_for_certificate Сведения об испытательной лаборатории. Основание выдачи декларации
 * @property string $cert_doc_issued-testing_lab-0-reg_number Сведения об испытательной лаборатории. Регистрационный номер
 * @property string $cert_doc_issued-testing_lab-0-name Сведения об испытательной лаборатории. Нaименование испытательной лаборатории (центра)
 * @property string $cert_doc_issued-testing_lab-0-date_reg Сведения об испытательной лаборатории. Дата аккредитации
 * @property string $a_cert_doc_issued-rds-cert_doc_issued-additional_info Сведения об испытательной лаборатории. Дополнительная информация
 * @property string $a_product_info-rds-product_rf-object_type_cert Сведения о продукции. «Тип объекта декларирования»: серийный выпуск, партия, единичное изделие
 * @property string $a_product_info-rds-product_rf-product_type Сведения о продукции. Происхождение продукции
 * @property string $a_product_info-rds-product_rf-product_name Сведения о продукции. Полное наименование продукции
 * @property string $a_product_info-rds-product_rf-product_info Сведения о продукции. Сведения о продукции (тип, марка, модель, сорт, артикул и др.), обеспечивающие ее идентификацию
 * @property string $a_product_info-rds-product_rf-okpd2 Сведения о продукции. Коды ОКПД 2
 * @property string $a_product_info-rds-product_rf-okpd2_text Сведения о продукции. Коды ОКПД 2
 * @property string $a_product_info-rds-product_rf-tn_ved Сведения о продукции. Код ТН ВЭД ЕАЭС
 * @property string $a_product_info-rds-product_rf-tn_ved_text Сведения о продукции. Код ТН ВЭД ЕАЭС
 * @property string $a_product_info-rds-product_rf-name_doc_made_product Сведения о продукции. Наименование и реквизиты документа, в соответствии с которыми изготовлена продукция
 * @property string $a_product_info-rds-product_rf-product_info_ext Сведения о продукции. Иная информация, идентифицирующая продукцию
 * @property string $a_product_info-rds-product_rf-serial_number_product Сведения о продукции. Размер партии или заводской номер изделия
 * @property string $a_product_info-rds-product_rf-requisites_doc Сведения о продукции. Реквизиты товаросопроводительной документации
 * @property string $tech_reg Сведения о продукции. Технический регламент
 * @property string $tech_reg_ext Сведения о продукции. Дополнительные сведения о технических регламентах
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
 * @property string $rds-table_standart_designation Сведения о приложениях к сертификату. Обозначение национального стандарта или свода правил
 * @property string $rds-table_standart_name Сведения о приложениях к сертификату. Наименование национального стандарта или свода правил
 * @property string $rds-table_standart_confirmation_requirements Сведения о приложениях к сертификату. Подтверждение требованиям национального стандарта или свода правил
 * @property string $a_free_form Сведения о приложениях к сертификату. Прочие сведения о сертификате соответствия
 * @property string $a_reg_number Реквизиты декларации. Регистрационный номер
 * @property string $a_blank_number Реквизиты декларации. Номер бланка
 * @property string $a_date_begin Реквизиты декларации. Дата начала действия
 * @property string $a_date_finish Реквизиты декларации. Дата окончания действия
 * @property string $a_is_date_finish Реквизиты декларации. Без срока действия
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereAApplicantInfoRdsAppLegalPersonAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereAApplicantInfoRdsAppLegalPersonApplicantType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereAApplicantInfoRdsAppLegalPersonDirectorName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereAApplicantInfoRdsAppLegalPersonEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereAApplicantInfoRdsAppLegalPersonFax($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereAApplicantInfoRdsAppLegalPersonName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereAApplicantInfoRdsAppLegalPersonOgrn($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereAApplicantInfoRdsAppLegalPersonPhone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereAApplicantOrgTypeUl($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereAAppsFreeForm($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereAAppsTableStandart($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereABlankNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereACertDocIssuedRdsCertDocIssuedAdditionalInfo($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereACertTypeCertRf($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereADateBegin($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereADateFinish($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereAExpert0FirstName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereAExpert0LastName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereAExpert0PatrName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereAFreeForm($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereAInfoPril($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereAIsDateFinish($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereAManufacturerInfoRdsManForeignLegalPersonAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereAManufacturerInfoRdsManForeignLegalPersonEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereAManufacturerInfoRdsManForeignLegalPersonFax($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereAManufacturerInfoRdsManForeignLegalPersonName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereAManufacturerInfoRdsManForeignLegalPersonPhone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereAManufacturerInfoRdsManLegalPersonAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereAManufacturerInfoRdsManLegalPersonEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereAManufacturerInfoRdsManLegalPersonFax($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereAManufacturerInfoRdsManLegalPersonName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereAManufacturerInfoRdsManLegalPersonOgrn($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereAManufacturerInfoRdsManLegalPersonPhone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereAManufacturerTypeUl($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereAProductInfoRdsProductRfNameDocMadeProduct($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereAProductInfoRdsProductRfObjectTypeCert($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereAProductInfoRdsProductRfOkpd2($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereAProductInfoRdsProductRfOkpd2Text($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereAProductInfoRdsProductRfProductInfo($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereAProductInfoRdsProductRfProductInfoExt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereAProductInfoRdsProductRfProductName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereAProductInfoRdsProductRfProductType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereAProductInfoRdsProductRfRequisitesDoc($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereAProductInfoRdsProductRfSerialNumberProduct($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereAProductInfoRdsProductRfTnVed($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereAProductInfoRdsProductRfTnVedText($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereARegNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereATableStandartNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereCertDocIssuedCertificationScheme($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereCertDocIssuedDocumentInfo($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereCertDocIssuedTestingLab0BasisForCertificate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereCertDocIssuedTestingLab0DateReg($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereCertDocIssuedTestingLab0Name($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereCertDocIssuedTestingLab0RegNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereCertDocIssuedTestingLab1BasisForCertificate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereDECLNUM($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereOrganToCertificationAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereOrganToCertificationAddressActual($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereOrganToCertificationEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereOrganToCertificationFax($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereOrganToCertificationHeadName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereOrganToCertificationName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereOrganToCertificationPhone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereOrganToCertificationRegDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereOrganToCertificationRegNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereRdsTableStandartConfirmationRequirements($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereRdsTableStandartDesignation($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereRdsTableStandartName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereSTATUS($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereTechReg($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereTechRegExt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\RdsRfPub whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RdsRfPub extends Model
{
    protected $table = 'rds_rf_pub';

    public $timestamps = true;

    protected $fillable = [
        'STATUS',
        'DECL_NUM',
        'a_cert_type-cert_rf',
        'a_applicant_org_type-ul',
        'a_manufacturer_type-ul',
        'a_applicant_info-rds-app_legal_person-applicant_type',
        'a_applicant_info-rds-app_legal_person-name',
        'a_applicant_info-rds-app_legal_person-director_name',
        'a_applicant_info-rds-app_legal_person-address',
        'a_applicant_info-rds-app_legal_person-phone',
        'a_applicant_info-rds-app_legal_person-fax',
        'a_applicant_info-rds-app_legal_person-email',
        'a_applicant_info-rds-app_legal_person-ogrn',
        'a_manufacturer_info-rds-man_foreign_legal_person-name',
        'a_manufacturer_info-rds-man_foreign_legal_person-address',
        'a_manufacturer_info-rds-man_foreign_legal_person-phone',
        'a_manufacturer_info-rds-man_foreign_legal_person-fax',
        'a_manufacturer_info-rds-man_foreign_legal_person-email',
        'a_manufacturer_info-rds-man_legal_person-name',
        'a_manufacturer_info-rds-man_legal_person-address',
        'a_manufacturer_info-rds-man_legal_person-phone',
        'a_manufacturer_info-rds-man_legal_person-fax',
        'a_manufacturer_info-rds-man_legal_person-email',
        'a_manufacturer_info-rds-man_legal_person-ogrn',
        'cert_doc_issued-document_info',
        'cert_doc_issued-certification_scheme',
        'cert_doc_issued-testing_lab-0-basis_for_certificate',
        'cert_doc_issued-testing_lab-1-basis_for_certificate',
        'cert_doc_issued-testing_lab-0-reg_number',
        'cert_doc_issued-testing_lab-0-name',
        'cert_doc_issued-testing_lab-0-date_reg',
        'a_cert_doc_issued-rds-cert_doc_issued-additional_info',
        'a_product_info-rds-product_rf-object_type_cert',
        'a_product_info-rds-product_rf-product_type',
        'a_product_info-rds-product_rf-product_name',
        'a_product_info-rds-product_rf-product_info',
        'a_product_info-rds-product_rf-okpd2',
        'a_product_info-rds-product_rf-okpd2_text',
        'a_product_info-rds-product_rf-tn_ved',
        'a_product_info-rds-product_rf-tn_ved_text',
        'a_product_info-rds-product_rf-name_doc_made_product',
        'a_product_info-rds-product_rf-product_info_ext',
        'a_product_info-rds-product_rf-serial_number_product',
        'a_product_info-rds-product_rf-requisites_doc',
        'tech_reg',
        'tech_reg_ext',
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
        'rds-table_standart_designation',
        'rds-table_standart_name',
        'rds-table_standart_confirmation_requirements',
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
            'DECL_NUM' => 'required',
        ];
    }
}