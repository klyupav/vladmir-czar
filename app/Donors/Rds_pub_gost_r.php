<?php

namespace App\Donors;

use App\Http\Controllers\LoggerController;
use App\Models\ProxyList;
use ParseIt\_String;
use ParseIt\nokogiri;
use App\Donors\ParseIt\simpleParser;
use ParseIt\ParseItHelpers;

Class Rds_pub_gost_r extends simpleParser {

    public $data = [];
    public $reload = [];
    public $project = 'rds_pub_gost_r';
    public $project_link = 'http://188.254.71.82';
    public $source = 'http://public.fsa.gov.ru/table_rds_pub_gost_r/';
    public $cache = false;
    public $proxy = false;
    public $cookieFile = '';
    public $version_id = 1;
    public $donor = 'Rds_pub_gost_r';

    function __construct()
    {
        $this->cookieFile = __DIR__.'/cookie/'.class_basename(get_class($this)).'/'.class_basename(get_class($this)).'.txt';
    }

    public function getSources($opt = [])
    {
        $link = $this->source;
        $hash = md5($link);
        $sources[$hash]= [
            'hash' => $hash,
            'name' => 'Реестр деклараций о соответствии продукции, включенной в Единый перечень продукции РФ',
            'source' => $link,
            'donor_class_name' => $this->donor,
            'version' => $this->version_id,
        ];

        return $sources;
    }

    public function getData($content, $url, $source = [])
    {
        $data = false;
        $content = ParseItHelpers::fixEncoding($content);
        $content = ParseItHelpers::fixHeader($content);
        $nokogiri = new nokogiri($content);
        $rows = $nokogiri->get("#bodyTableData tbody tr")->toArray();
        foreach ($rows as $key => $row)
        {
            if ( isset($row['td'][1]['a']['href']) )
            {
                unset($source['post']);
                $source['referer'] = $url;
//                $href = "http://188.254.71.82/rds_pub_gost_r/?show=view&id_object=08D2DFB3508F4E15AFFCC790B62EE5CC";
                $href = $row['td'][1]['a']['href'];
                $content = $this->loadUrl($href, $source);
                $content = preg_replace( "%windows-1251%is", 'UTF-8', $content );
                $content = iconv('windows-1251', 'UTF-8', $content);
                $content = ParseItHelpers::fixEncoding($content);
                $content = ParseItHelpers::fixHeader($content);
                $nokogiri = new nokogiri($content);
                if ($captcha = $nokogiri->get("input[name=captcha]")->toArray())
                {
                    if ( !$this->entry_captcha($content, $href) )
                    {
                        continue;
                    }
                    unset($content, $nokogiri);
                    $content = $this->loadUrl($href, $source);
                    $content = preg_replace( "%windows-1251%is", 'UTF-8', $content );
                    $content = iconv('windows-1251', 'UTF-8', $content);
                    $content = ParseItHelpers::fixEncoding($content);
                    $content = ParseItHelpers::fixHeader($content);
                    $nokogiri = new nokogiri($content);
                    if ($captcha = $nokogiri->get("input[name=captcha]")->toArray())
                    {
                        continue;
                    }
                }

                $a_cert_type_cert_ts = $nokogiri->get("#a_cert_type-cert_gost_r .form-left-col .text-label")->toArray();

                $a_applicant_org_type_ul = $nokogiri->get("#a_applicant_org_type-ul .form-left-col .text-label")->toArray();
                $a_manufacturer_type_iul = $nokogiri->get("#a_manufacturer_type-iul .form-left-col .text-label")->toArray();

                $a_applicant_info_rss_app_legal_person_applicant_type = $nokogiri->get("#a_applicant_info-rds-app_legal_person-applicant_type .form-right-col")->toArray();
                $a_applicant_info_rds_app_legal_person_legal_form = $nokogiri->get("#a_applicant_info-rds-app_legal_person-legal_form .form-right-col")->toArray();
                $a_applicant_info_rss_app_legal_person_name = $nokogiri->get("#a_applicant_info-rds-app_legal_person-name .form-right-col")->toArray();
                $a_applicant_info_rds_app_legal_person_short_name_gost_r = $nokogiri->get("#a_applicant_info-rds-app_legal_person-short_name_gost_r .form-right-col")->toArray();
                $a_applicant_info_rss_app_legal_person_director_name = $nokogiri->get("#a_applicant_info-rds-app_legal_person-director_name .form-right-col")->toArray();
                $a_applicant_info_rss_app_legal_person_address = $nokogiri->get("#a_applicant_info-rds-app_legal_person-address .form-right-col")->toArray();
                $a_applicant_info_rss_app_legal_person_phone = $nokogiri->get("#a_applicant_info-rds-app_legal_person-phone .form-right-col")->toArray();
                $a_applicant_info_rss_app_legal_person_fax = $nokogiri->get("#a_applicant_info-rds-app_legal_person-fax .form-right-col")->toArray();
                $a_applicant_info_rss_app_legal_person_email = $nokogiri->get("#a_applicant_info-rds-app_legal_person-email .form-right-col")->toArray();
                $a_applicant_info_rss_app_legal_person_ogrn = $nokogiri->get("#a_applicant_info-rds-app_legal_person-ogrn .form-right-col")->toArray();

                $a_manufacturer_info_rss_man_foreign_legal_person_name = $nokogiri->get("#a_manufacturer_info-rds-man_foreign_legal_person-name .form-right-col")->toArray();
                $a_manufacturer_info_rss_man_foreign_legal_person_address = $nokogiri->get("#a_manufacturer_info-rds-man_foreign_legal_person-address .form-right-col")->toArray();
                $a_manufacturer_info_rss_man_foreign_legal_person_address_actual = $nokogiri->get("#a_manufacturer_info-rds-man_foreign_legal_person-address_actual .form-right-col")->toArray();

                $a_manufacturer_info_rss_man_legal_person_name = $nokogiri->get("#a_manufacturer_info-rds-man_legal_person-name .form-right-col")->toArray();
                $a_manufacturer_info_rss_man_legal_person_address = $nokogiri->get("#a_manufacturer_info-rds-man_legal_person-address .form-right-col")->toArray();
                $a_manufacturer_info_rss_man_legal_person_phone = $nokogiri->get("#a_manufacturer_info-rds-man_legal_person-phone .form-right-col")->toArray();
                $a_manufacturer_info_rss_man_legal_person_fax = $nokogiri->get("#a_manufacturer_info-rds-man_legal_person-fax .form-right-col")->toArray();
                $a_manufacturer_info_rss_man_legal_person_email = $nokogiri->get("#a_manufacturer_info-rds-man_legal_person-email .form-right-col")->toArray();
                $a_manufacturer_info_rss_man_legal_person_ogrn = $nokogiri->get("#a_manufacturer_info-rds-man_legal_person-ogrn .form-right-col")->toArray();

                $a_cert_doc_issued_rss_cert_doc_issued_document_info = $nokogiri->get("#a_cert_doc_issued-rds-cert_doc_issued-document_info .form-right-col")->toArray();
                $a_cert_doc_issued_rds_cert_doc_issued_certification_scheme = $nokogiri->get("#a_cert_doc_issued-rds-cert_doc_issued-certification_scheme .form-right-col")->toArray();
                $a_cert_doc_issued_rss_cert_doc_issued_testing_lab_basis_for_certificate = $nokogiri->get("#a_cert_doc_issued-rds-cert_doc_issued-testing_lab-0-basis_for_certificate .form-right-col")->toArray();

                $a_cert_doc_issued_cert_doc_issued_testing_lab_basis_for_certificate = $nokogiri->get("#a_cert_doc_issued-rds-cert_doc_issued-testing_lab-1-basis_for_certificate .form-right-col")->toArray();
                $a_cert_doc_issued_rss_cert_doc_issued_testing_lab_reg_number = $nokogiri->get("#a_cert_doc_issued-rds-cert_doc_issued-testing_lab-0-reg_number .form-right-col")->toArray();
                $a_cert_doc_issued_rds_cert_doc_issued_testing_lab_0_name = $nokogiri->get("#a_cert_doc_issued-rds-cert_doc_issued-testing_lab-0-name .form-right-col")->toArray();
                $a_cert_doc_issued_rds_cert_doc_issued_testing_lab_0_date_reg = $nokogiri->get("#a_cert_doc_issued-rds-cert_doc_issued-testing_lab-0-date_reg .form-right-col")->toArray();
                $a_cert_doc_issued_rss_cert_doc_issued_additional_info = $nokogiri->get("#a_cert_doc_issued-rds-cert_doc_issued-additional_info .form-right-col")->toArray();

                $a_product_info_rss_product_ts_object_type_cert = $nokogiri->get("#a_product_info-rds-product_gost_r-object_type_cert .form-right-col")->toArray();
                $a_product_info_rss_product_ts_product_type = $nokogiri->get("#a_product_info-rds-product_gost_r-product_type .form-right-col")->toArray();
                $a_product_info_rss_product_ts_product_name = $nokogiri->get("#a_product_info-rds-product_gost_r-product_name .form-right-col")->toArray();
                $a_product_info_rss_product_ts_product_info = $nokogiri->get("#a_product_info-rds-product_gost_r-product_info .form-right-col")->toArray();
                $a_product_info_rss_product_ts_okp = $nokogiri->get("#a_product_info-rds-product_gost_r-okp .form-right-col")->toArray();
                $a_product_info_rss_product_ts_okp_text = $nokogiri->get("#a_product_info-rds-product_gost_r-okp_text .form-right-col")->toArray();
                $a_product_info_rss_product_ts_okpd2 = $nokogiri->get("#a_product_info-rds-product_gost_r-okpd2 .form-right-col")->toArray();
                $a_product_info_rss_product_ts_okpd2_text = $nokogiri->get("#a_product_info-rds-product_gost_r-okpd2_text .form-right-col")->toArray();
                $a_product_info_rss_product_ts_tn_ved = $nokogiri->get("#a_product_info-rds-product_gost_r-tn_ved .form-right-col")->toArray();
                $a_product_info_rss_product_ts_tn_ved_text = $nokogiri->get("#a_product_info-rds-product_gost_r-tn_ved_text .form-right-col")->toArray();
                $a_product_info_rss_product_ts_name_doc_made_product = $nokogiri->get("#a_product_info-rds-product_gost_r-name_doc_made_product .form-right-col")->toArray();
                $a_product_info_rss_product_ts_product_info_ext = $nokogiri->get("#a_product_info-rds-product_gost_r-product_info_ext .form-right-col")->toArray();
                $a_product_info_rss_product_ts_serial_number_product = $nokogiri->get("#a_product_info-rds-product_gost_r-serial_number_product .form-right-col")->toArray();
                $a_product_info_rss_product_ts_requisites_doc = $nokogiri->get("#a_product_info-rds-product_gost_r-requisites_doc .form-right-col")->toArray();

                $div_tech_reg = $nokogiri->get("div[fsa-id=tech_reg] .form-right-col")->toArray();
                $tech_reg = '';
                foreach ( $div_tech_reg as $k => $v )
                {
                    $tech_reg .= @$div_tech_reg[$k]['__ref']->nodeValue.'|';
                }

                $rds_standart_requlations_notation_national_standart = $nokogiri->get("#a_product_info-rds-product_gost_r-standart_requlations-0-rds-standart_requlations-notation_national_standart .form-right-col")->toArray();

                $a_expert_0_last_name = $nokogiri->get("#a_expert-0-last_name .form-right-col")->toArray();
                $a_expert_0_first_name = $nokogiri->get("#a_expert-0-first_name .form-right-col")->toArray();
                $a_expert_0_patr_name = $nokogiri->get("#a_expert-0-patr_name .form-right-col")->toArray();

                $organ_to_certification_name = $nokogiri->get("#a_organ_to_certification-rds-organ_to_certification-name .form-right-col")->toArray();
                $organ_to_certification_reg_number = $nokogiri->get("#a_organ_to_certification-rds-organ_to_certification-reg_number .form-right-col")->toArray();
                $organ_to_certification_reg_date = $nokogiri->get("#a_organ_to_certification-rds-organ_to_certification-reg_date .form-right-col")->toArray();
                $organ_to_certification_head_name = $nokogiri->get("#a_organ_to_certification-rds-organ_to_certification-head_name .form-right-col")->toArray();
                $organ_to_certification_address = $nokogiri->get("#a_organ_to_certification-rds-organ_to_certification-address .form-right-col")->toArray();
                $organ_to_certification_address_actual = $nokogiri->get("#a_organ_to_certification-rds-organ_to_certification-address_actual .form-right-col")->toArray();
                $organ_to_certification_phone = $nokogiri->get("#a_organ_to_certification-rds-organ_to_certification-phone .form-right-col")->toArray();
                $organ_to_certification_fax = $nokogiri->get("#a_organ_to_certification-rds-organ_to_certification-fax .form-right-col")->toArray();
                $organ_to_certification_email = $nokogiri->get("#a_organ_to_certification-rds-organ_to_certification-email .form-right-col")->toArray();

                $a_info_pril = $nokogiri->get("#a_info_pril .form-right-col")->toArray();
                $a_apps_free_form = $nokogiri->get("#a_apps-free_form")->toArray();
                $a_apps_table_standart = $nokogiri->get("#a_apps-table_standart")->toArray();
                $a_table_standart_number = $nokogiri->get("#a_table_standart_number .form-right-col")->toArray();
                $div_designation = $nokogiri->get("#a_table_standart div[fsa-id=designation] .form-right-col")->toArray();
                $div_name = $nokogiri->get("#a_table_standart div[fsa-id=name] .form-right-col")->toArray();
                $div_confirmation_requirements = $nokogiri->get("#a_table_standart div[fsa-id=confirmation_requirements] .form-right-col")->toArray();
                $designation = '';
                $name = '';
                $confirmation_requirements = '';
                foreach ( $div_designation as $k => $v )
                {
                    $designation .= @$div_designation[$k]['__ref']->nodeValue.'|';
                    $name .= @$div_name[$k]['__ref']->nodeValue.'|';
                    $confirmation_requirements .= @$div_confirmation_requirements[$k]['__ref']->nodeValue.'|';
                }
                $div_a_free_form = $nokogiri->get("#a_free_form .array-field")->toArray();
//                print_r($div_a_free_form);die();
                $a_free_form = '';
                foreach ( $div_a_free_form as $k => $v )
                {
                    $a_free_form .= str_replace('Прочие сведения о сертификате соответствия', '', @$div_a_free_form[$k]['__ref']->nodeValue).'|';
                }

                $a_reg_number = $nokogiri->get("#a_reg_number .form-right-col")->toArray();
                $a_blank_number = $nokogiri->get("#a_blank_number .form-right-col")->toArray();
                $a_date_begin = $nokogiri->get("#a_date_begin .form-right-col")->toArray();
                $a_date_finish = $nokogiri->get("#a_date_finish .form-right-col")->toArray();
                $a_is_date_finish = $nokogiri->get("#cis_date_finish")->toArray();

                $data[$key] = [
                    'STATUS' => trim(@$row['td'][0]['span']['title']),
                    'DECL_NUM' => trim(@$row['td'][1]['__ref']->nodeValue),

                    'a_cert_type-cert_gost_r' => trim(@$a_cert_type_cert_ts[0]['__ref']->nodeValue),

                    'a_applicant_org_type-ul' => trim(@$a_applicant_org_type_ul[0]['__ref']->nodeValue),
                    'a_manufacturer_type-iul' => trim(@$a_manufacturer_type_iul[0]['__ref']->nodeValue),

                    'a_applicant_info-rds-app_legal_person-applicant_type' => trim(@$a_applicant_info_rss_app_legal_person_applicant_type[0]['__ref']->nodeValue),
                    'a_applicant_info-rds-app_legal_person-legal_form' => trim(@$a_applicant_info_rds_app_legal_person_legal_form[0]['__ref']->nodeValue),
                    'a_applicant_info-rds-app_legal_person-name' => trim(@$a_applicant_info_rss_app_legal_person_name[0]['__ref']->nodeValue),
                    'a_applicant_info-rds-app_legal_person-short_name_gost_r' => trim(@$a_applicant_info_rds_app_legal_person_short_name_gost_r[0]['__ref']->nodeValue),
                    'a_applicant_info-rds-app_legal_person-director_name' => trim(@$a_applicant_info_rss_app_legal_person_director_name[0]['__ref']->nodeValue),
                    'a_applicant_info-rds-app_legal_person-address' => trim(@$a_applicant_info_rss_app_legal_person_address[0]['__ref']->nodeValue),
                    'a_applicant_info-rds-app_legal_person-phone' => trim(@$a_applicant_info_rss_app_legal_person_phone[0]['__ref']->nodeValue),
                    'a_applicant_info-rds-app_legal_person-fax' => trim(@$a_applicant_info_rss_app_legal_person_fax[0]['__ref']->nodeValue),
                    'a_applicant_info-rds-app_legal_person-email' => trim(@$a_applicant_info_rss_app_legal_person_email[0]['__ref']->nodeValue),
                    'a_applicant_info-rds-app_legal_person-ogrn' => trim(@$a_applicant_info_rss_app_legal_person_ogrn[0]['__ref']->nodeValue),

                    'a_manufacturer_info-rds-man_foreign_legal_person-name' => trim(@$a_manufacturer_info_rss_man_foreign_legal_person_name[0]['__ref']->nodeValue),
                    'a_manufacturer_info-rds-man_foreign_legal_person-address' => trim(@$a_manufacturer_info_rss_man_foreign_legal_person_address[0]['__ref']->nodeValue),
                    'a_manufacturer_info-rds-man_foreign_legal_person-address_actual' => trim(@$a_manufacturer_info_rss_man_foreign_legal_person_address_actual[0]['__ref']->nodeValue),

                    'a_manufacturer_info-rds-man_legal_person-name' => trim(@$a_manufacturer_info_rss_man_legal_person_name[0]['__ref']->nodeValue),
                    'a_manufacturer_info-rds-man_legal_person-address' => trim(@$a_manufacturer_info_rss_man_legal_person_address[0]['__ref']->nodeValue),
                    'a_manufacturer_info-rds-man_legal_person-phone' => trim(@$a_manufacturer_info_rss_man_legal_person_phone[0]['__ref']->nodeValue),
                    'a_manufacturer_info-rds-man_legal_person-fax' => trim(@$a_manufacturer_info_rss_man_legal_person_fax[0]['__ref']->nodeValue),
                    'a_manufacturer_info-rds-man_legal_person-email' => trim(@$a_manufacturer_info_rss_man_legal_person_email[0]['__ref']->nodeValue),
                    'a_manufacturer_info-rds-man_legal_person-ogrn' => trim(@$a_manufacturer_info_rss_man_legal_person_ogrn[0]['__ref']->nodeValue),

                    'cert_doc_issued-document_info' => trim(@$a_cert_doc_issued_rss_cert_doc_issued_document_info[0]['__ref']->nodeValue),
                    'cert_doc_issued-certification_scheme' => trim(@$a_cert_doc_issued_rds_cert_doc_issued_certification_scheme[0]['__ref']->nodeValue),
                    'cert_doc_issued-testing_lab-0-basis_for_certificate' => trim(@$a_cert_doc_issued_rss_cert_doc_issued_testing_lab_basis_for_certificate[0]['__ref']->nodeValue),

                    'cert_doc_issued-testing_lab-1-basis_for_certificate' => trim(@$a_cert_doc_issued_cert_doc_issued_testing_lab_basis_for_certificate[0]['__ref']->nodeValue),
                    'cert_doc_issued-testing_lab-0-reg_number' => trim(@$a_cert_doc_issued_rss_cert_doc_issued_testing_lab_reg_number[0]['__ref']->nodeValue),
                    'cert_doc_issued-testing_lab-0-name' => trim(@$a_cert_doc_issued_rds_cert_doc_issued_testing_lab_0_name[0]['__ref']->nodeValue),
                    'cert_doc_issued-testing_lab-0-date_reg' => trim(@$a_cert_doc_issued_rds_cert_doc_issued_testing_lab_0_date_reg[0]['__ref']->nodeValue),
                    'a_cert_doc_issued-rds-cert_doc_issued-additional_info' => trim(@$a_cert_doc_issued_rss_cert_doc_issued_additional_info[0]['__ref']->nodeValue),

                    'a_product_info-rds-product_gost_r-object_type_cert' => trim(@$a_product_info_rss_product_ts_object_type_cert[0]['__ref']->nodeValue),
                    'a_product_info-rds-product_gost_r-product_type' => trim(@$a_product_info_rss_product_ts_product_type[0]['__ref']->nodeValue),
                    'a_product_info-rds-product_gost_r-product_name' => trim(@$a_product_info_rss_product_ts_product_name[0]['__ref']->nodeValue),
                    'a_product_info-rds-product_gost_r-product_info' => trim(@$a_product_info_rss_product_ts_product_info[0]['__ref']->nodeValue),
                    'a_product_info-rds-product_gost_r-okp' => trim(@$a_product_info_rss_product_ts_okp[0]['__ref']->nodeValue),
                    'a_product_info-rds-product_gost_r-okp_text' => trim(@$a_product_info_rss_product_ts_okp_text[0]['__ref']->nodeValue),
                    'a_product_info-rds-product_gost_r-okpd2' => trim(@$a_product_info_rss_product_ts_okpd2[0]['__ref']->nodeValue),
                    'a_product_info-rds-product_gost_r-okpd2_text' => trim(@$a_product_info_rss_product_ts_okpd2_text[0]['__ref']->nodeValue),
                    'a_product_info-rds-product_gost_r-tn_ved' => trim(@$a_product_info_rss_product_ts_tn_ved[0]['__ref']->nodeValue),
                    'a_product_info-rds-product_gost_r-tn_ved_text' => trim(@$a_product_info_rss_product_ts_tn_ved_text[0]['__ref']->nodeValue),
                    'a_product_info-rds-product_gost_r-name_doc_made_product' => trim(@$a_product_info_rss_product_ts_name_doc_made_product[0]['__ref']->nodeValue),
                    'a_product_info-rds-product_gost_r-product_info_ext' => trim(@$a_product_info_rss_product_ts_product_info_ext[0]['__ref']->nodeValue),
                    'a_product_info-rds-product_gost_r-serial_number_product' => trim(@$a_product_info_rss_product_ts_serial_number_product[0]['__ref']->nodeValue),
                    'a_product_info-rds-product_gost_r-requisites_doc' => trim(@$a_product_info_rss_product_ts_requisites_doc[0]['__ref']->nodeValue),

//                    'tech_reg' => trim(@$tech_reg, '|'),
                    'rds-standart_requlations-notation_national_standart' => trim(@$rds_standart_requlations_notation_national_standart[0]['__ref']->nodeValue),

                    'a_expert-0-last_name' => trim(@$a_expert_0_last_name[0]['__ref']->nodeValue),
                    'a_expert-0-first_name' => trim(@$a_expert_0_first_name[0]['__ref']->nodeValue),
                    'a_expert-0-patr_name' => trim(@$a_expert_0_patr_name[0]['__ref']->nodeValue),

                    'organ_to_certification-name' => trim(@$organ_to_certification_name[0]['__ref']->nodeValue),
                    'organ_to_certification-reg_number' => trim(@$organ_to_certification_reg_number[0]['__ref']->nodeValue),
                    'organ_to_certification-reg_date' => trim(@$organ_to_certification_reg_date[0]['__ref']->nodeValue),
                    'organ_to_certification-head_name' => trim(@$organ_to_certification_head_name[0]['__ref']->nodeValue),
                    'organ_to_certification-address' => trim(@$organ_to_certification_address[0]['__ref']->nodeValue),
                    'organ_to_certification-address_actual' => trim(@$organ_to_certification_address_actual[0]['__ref']->nodeValue),
                    'organ_to_certification-phone' => trim(@$organ_to_certification_phone[0]['__ref']->nodeValue),
                    'organ_to_certification-fax' => trim(@$organ_to_certification_fax[0]['__ref']->nodeValue),
                    'organ_to_certification-email' => trim(@$organ_to_certification_email[0]['__ref']->nodeValue),

                    'a_info_pril' => trim(@$a_info_pril[0]['__ref']->nodeValue),
                    'a_apps-free_form' => empty($a_apps_free_form) ? 0 : 1,
                    'a_apps-table_standart' => empty($a_apps_table_standart) ? 0 : 1,
                    'a_table_standart_number' => trim(@$a_table_standart_number[0]['__ref']->nodeValue),
                    'rss-table_standart_designation' => trim(@$designation, '|'),
                    'rss-table_standart_name' => trim(@$name, '|'),
                    'rss-table_standart_confirmation_requirements' => trim(@$confirmation_requirements, '|'),
                    'a_free_form' => trim(@$a_free_form, '|'),

                    'a_reg_number' => trim(@$a_reg_number[0]['__ref']->nodeValue),
                    'a_blank_number' => trim(@$a_blank_number[0]['__ref']->nodeValue),
                    'a_date_begin' => trim(@$a_date_begin[0]['__ref']->nodeValue),
                    'a_date_finish' => trim(@$a_date_finish[0]['__ref']->nodeValue),
                    'a_is_date_finish' => empty($a_is_date_finish) ? 0 : 1,
                ];
//                print_r($data);die();
            }
        }

        return $data;
    }

    public function entry_captcha($content, $url)
    {
        $nokogiri = new nokogiri($content);
        $img = "http://188.254.71.82/".$this->project."/".$nokogiri->get("form img")->toArray()[0]['src'];
        $api = new \ImageToText();
        $api->setVerboseMode(true);
        $api->setKey("796a951c921fd7780e55747860cfeb2f");
        $hash = md5($img);
        $filename = \App::basePath()."/storage/".$hash.".png";
        $opt['cookieFile'] = $this->cookieFile;
        $opt['referer'] = $img;
        $opt['origin'] = 'http://188.254.71.82';
        $opt['host'] = '188.254.71.82';
        $file = $this->loadUrl($img, $opt);
        file_put_contents($filename, $file);
        $api->setFile($filename);

        if (!$api->createTask()) {
            $api->debout("API v2 send failed - ".$api->getErrorMessage(), "red");
            return false;
        }
        $taskId = $api->getTaskId();
        if (!$api->waitForResult()) {
            $api->debout("could not solve captcha", "red");
            $api->debout($api->getErrorMessage());
        } else {
            $captcha = $api->getTaskSolution();
            print_r($captcha);
            $opt['post'] = [
                'captcha' => trim($captcha),
            ];
            $opt['referer'] = $url;
            $content = $this->loadUrl("http://188.254.71.82/".$this->project."/reg.php", $opt);
            print_r($content);
            return true;
        }
        return false;
    }
}