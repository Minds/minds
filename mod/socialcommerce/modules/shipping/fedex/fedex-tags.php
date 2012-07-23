<?php
/*
$Id: fedex-tags.php,v 1.2 2004/08/31 19:55:09 jay.powers Exp $

This module contains all the required codes needed by the FedEx Ship Manager API.
All hash key lookups need to be done with lower case keys.  I wanted to follow the
FedEx Tagged Transaction Guide as close as possible so some of the names are pretty long.
FedEx does occasionally change these tags so please check that you always have the most
current version of this module.  Changes should not break existing code.  All changes are
documented in a Changes file.

Copyright (c) 2004 Vermonster LLC
All rights reserved.
*/

class FedExTags {
    
    
    /**
    *  load FedEx services => Transaction Types, Applicable Carrier
    *
    * @var      FE_TT
    * @access   public
    */
    var $FE_TT = array (
        'ground_close'          =>  array ('007','FDXG'),
        'express_cancel'        =>  array ('023','FDXE'),
        'express_ship'          =>  array ('021','FDXE'),
        'express_global_rate'   =>  array ('022','FDXE'),
        'services_avail'        =>  array ('019','FDXE'),
        'services_rate'         =>  array ('025',''),
        'fedex_locater'         =>  array ('410',''),
        'express_email'         =>  array ('037','FDXE'),
        'express_cancel_email'  =>  array ('033','FDXE'),
        'express_tag'           =>  array ('046','FDXE'),
        'express_cancel_tag'    =>  array ('047','FDXE'),
        'express_tag_avail'     =>  array ('048','FDXE'),
        'ground_ship'           =>  array ('021','FDXG'),
        'ground_cancel'         =>  array ('023','FDXG'),
        'subscribe'             =>  array ('211',''),
        'ground_global_rate'    =>  array ('022','FDXG'),
        'ground_tag'            =>  array ('044','FDXE'),
        'ground_cancel_tag'     =>  array ('045','FDXE'),
        'ground_email'          =>  array ('037','FDXE'),
        'ground_cancel_email'   =>  array ('033','FDXE'),
        'sig_proof_delivery'    =>  array ('405',''),
        'track'                 =>  array ('403',''),
        'address_validate'      =>  array ('415',''),
        'get_location'          =>  array ('018','FDXE'),
        'send_version'          =>  array ('070','')
    );

    /**
    *  A List of FedEx Service Types
    *
    * @var      FE_ST
    * @access   public
    */
    var $FE_ST = array (
        '01' => 'Express Priority Overnight',
        '03' => 'Express Economy Two Day',
        '05' => 'Express Standard Overnight',
        '06' => 'Express First Overnight',
        '20' => 'Express Saver',
        '70' => 'Freight Overnight',
        '80' => 'Freight Two Day',
        '83' => 'Freight Express Saver',
        '86' => 'Freight International Priority',
        '90' => 'Ground Home Delivery',
        '92' => 'Ground Business Delivery'
    );

    /**
    *  A list of all the FedEx tags.  An attempt to make items more human readable
    *
    * @var      FE_RE
    * @access   public
    */
    var $FE_RE = array(
        0 => 'transaction_code',
        1 => 'customer_transaction_identifier',
        2 => 'transaction_error_code',
        3 => 'transaction_error_message',
        4 => 'sender_company',
        5 => 'sender_address_line_1',
        6 => 'sender_address_line_2',
        7 => 'sender_city',
        8 => 'sender_state',
        9 => 'sender_postal_code',
        10 => 'sender_fedex_express_account_number',
        11 => 'recipient_company',
        12 => 'recipient_contact_name',
        13 => 'recipient_address_line_1',
        14 => 'recipient_address_line_2',
        15 => 'recipient_city',
        16 => 'recipient_state',
        17 => 'recipient_postal_code',
        18 => 'recipient_phone_number',
        20 => 'payer_account_number',
        23 => 'pay_type',
        24 => 'ship_date',
        25 => 'reference_information',
        27 => 'cod_flag',
        28 => 'cod_return_tracking_number',
        29 => 'tracking_number',
        30 => 'ursa_code',
        32 => 'sender_contact_name',
        33 => 'service_commitment',
        38 => 'sender_department',
        40 => 'alcohol_type',
        41 => 'alcohol_packaging',
        44 => 'hal_address',
        46 => 'hal_city',
        47 => 'hal_state',
        48 => 'hal_postal_code',
        49 => 'hal_phone_number',
        50 => 'recipient_country',
        51 => 'signature_release_ok_flag',
        52 => 'alcohol_packages',
        57 => 'dim_height',
        58 => 'dim_width',
        59 => 'dim_length',
        65 => 'astra_barcode',
        66 => 'broker_name',
        67 => 'broker_phone_number',
        68 => 'customs_declared_value_currency_type',
        70 => 'duties_pay_type',
        71 => 'duties_payer_account',
        72 => 'terms_of_sale',
        73 => 'parties_to_transaction',
        74 => 'country_of_ultimate_destination',
        75 => 'weight_units',
        76 => 'commodity_number_of_pieces',
        79 => 'description_of_contents',
        80 => 'country_of_manufacturer',
        81 => 'harmonized_code',
        82 => 'unit_quantity',
        83 => 'export_license_number',
        84 => 'export_license_expiration_date',
        99 => 'end_of_record',
        112 => 'total_weight',
        113 => 'commercial_invoice_flag',
        116 => 'package_total',
        117 => 'sender_country_code',
        118 => 'recipient_irs',
        120 => 'ci_marks_and_numbers',
        169 => 'importer_country',
        170 => 'importer_name',
        171 => 'importer_company',
        172 => 'importer_address_line_1',
        173 => 'importer_address_line_2',
        174 => 'importer_city',
        175 => 'importer_state',
        176 => 'importer_postal_code',
        177 => 'importer_account_number',
        178 => 'importer_number_phone',
        180 => 'importer_id',
        183 => 'sender_phone_number',
        186 => 'cod_add_freight_charges_flag',
        188 => 'label_buffer_data_stream',
        190 => 'document_pib_shipment_flag',
        194 => 'delivery_day',
        195 => 'destination',
        198 => 'destination_location_id',
        404 => 'commodity_license_exception',
        409 => 'delivery_date',
        411 => 'cod_return_label_buffer_data_stream',
        413 => 'nafta_flag',
        414 => 'commodity_unit_of_measure',
        418 => 'ci_comments',
        431 => 'dim_weight_used_flag',
        439 => 'cod_return_contact_name',
        440 => 'residential_delivery_flag',
        496 => 'freight_service_commitment',
        498 => 'meter_number',
        526 => 'form_id',
        527 => 'cod_return_form_id',
        528 => 'commodity_eccn',
        535 => 'cod_return',
        536 => 'cod_return_service_commitment',
        543 => 'cod_return_collect_plus_freight_amount',
        557 => 'message_type_code',
        558 => 'message_code',
        559 => 'message_text',
        600 => 'forwarding_agent_routed_export_transaction_indicator',
        602 => 'exporter_ein_ssn_indicator',
        603 => 'int-con_company_name',
        604 => 'int-con_contact_name',
        605 => 'int-con_address_line_1',
        606 => 'int-con_address_line_2',
        607 => 'int-con_city',
        608 => 'int-con_state',
        609 => 'int-con_zip',
        610 => 'int-con_phone_number',
        611 => 'int-con_country',
        1005 => 'manifest_invoic_e_file_name',
        1006 => 'domain_name',
        1007 => 'close_manifest_date',
        1008 => 'package_ready_time',
        1009 => 'time_companyclose',
        1010 => 'courier_remarks',
        1011 => 'dispatch_number',
        1012 => 'dispatch_location',
        1013 => 'dispatch_message',
        1032 => 'duties_payer_country_code',
        1089 => 'rate_scale',
        1090 => 'rate_currency_type',
        1092 => 'rate_zone',
        1096 => 'origin_location_id',
        1099 => 'volume_units',
        1101 => 'payer_credit_card_number',
        1102 => 'payer_credit_card_type',
        1103 => 'sender_fax',
        1104 => 'payer_credit_card_expiration_date',
        1115 => 'ship_time',
        1116 => 'dim_units',
        1117 => 'package_sequence',
        1118 => 'release_authorization_number',
        1119 => 'future_day_shipment',
        1120 => 'inside_pickup_flag',
        1121 => 'inside_delivery_flag',
        1123 => 'master_tracking_number',
        1124 => 'master_form_id',
        1137 => 'ursa_uned_prefix',
        1139 => 'sender_irs_ein_number',
        1145 => 'recipient_department',
        1159 => 'scan_description',
        1160 => 'scan_location_city',
        1161 => 'scan_location_state',
        1162 => 'scan_date',
        1163 => 'scan_time',
        1164 => 'scan_location_country',
        1167 => 'disp_exception_cd',
        1168 => 'status_exception_cd',
        1174 => 'bso_flag',
        1178 => 'ursa_suffix_code',
        1179 => 'broker_fdx_account_number',
        1180 => 'broker_company',
        1181 => 'broker_line_1_address',
        1182 => 'broker_line_2_address',
        1183 => 'broker_city',
        1184 => 'broker_state',
        1185 => 'broker_postal_code',
        1186 => 'broker_country_code',
        1187 => 'broker_id_number',
        1193 => 'ship_delete_message',
        1195 => 'payer_country_code',
        1200 => 'hold_at_location_hal_flag',
        1201 => 'sender_email_address',
        1202 => 'recipient_email_address',
        1203 => 'optional_ship_alert_message',
        1204 => 'ship_alert_email_address',
        1206 => 'ship_alert_notification_flag',
        1208 => 'no_indirect_delivery_flag_signature_required',
        1210 => 'purpose_of_shipment',
        1211 => 'pod_address',
        1213 => 'proactive_notification_flag',
        1237 => 'cod_return_phone',
        1238 => 'cod_return_company',
        1239 => 'cod_return_department',
        1240 => 'cod_return_address_1',
        1241 => 'cod_return_address_2',
        1242 => 'cod_return_city',
        1243 => 'cod_return_state',
        1244 => 'cod_return_postal_code',
        1253 => 'packaging_list_enclosed_flag',
        1265 => 'hold_at_location_contact_name',
        1266 => 'saturday_delivery_flag',
        1267 => 'saturday_pickup_flag',
        1268 => 'dry_ice_flag',
        1271 => 'shippers_load_and_count_slac',
        1272 => 'booking_number',
        1273 => 'packaging_type',
        1274 => 'service_type',
        1286 => 'exporter_ppi-_contact_name',
        1287 => 'exporter_ppi-company_name',
        1288 => 'exporter_ppi-address_line_1',
        1289 => 'exporter_ppi-address_line_2',
        1290 => 'exporter_ppi-city',
        1291 => 'exporter_ppi-state',
        1292 => 'exporter_ppi-zip',
        1293 => 'exporter_ppi-country',
        1294 => 'exporter_ppi-phone_number',
        1295 => 'exporter_ppi-ein_ssn',
        1297 => 'customer_invoice_number',
        1300 => 'purchase_order_number',
        1331 => 'dangerous',
        1332 => 'alcohol_flag',
        1333 => 'drop_off_type',
        1337 => 'package_content_information',
        1339 => 'estimated_delivery_date',
        1340 => 'estimated_delivery_time',
        1341 => 'sender_pager_number',
        1342 => 'recipient_pager_number',
        1343 => 'broker_email_address',
        1344 => 'broker_fax_number',
        1346 => 'emerge_shipment_identifier',
        1347 => 'emerge_merchant_identifier',
        1349 => 'aes_filing_status',
        1350 => 'xtn_suffix_number',
        1352 => 'sender_ein_ssn_identificator',
        1358 => 'aes_ftsr_exemption_number',
        1359 => 'sed_legend_number',
        1364 => 'dispatch_message_code',
        1365 => 'dispatch_date',
        1366 => 'close_manifest_time',
        1367 => 'close_manifest_data_buffer',
        1368 => 'label_type',
        1369 => 'label_printer_type',
        1370 => 'label_media_type',
        1371 => 'manifest_only_request_flag',
        1372 => 'manifest_total',
        1376 => 'rate_weight_unit_of_measure',
        1377 => 'dim_weight_unit_of_measure',
        1391 => 'client_revision_indicator',
        1392 => 'inbound_visibility_block_shipment_data_indicator',
        1394 => 'shipment_content_records_total',
        1395 => 'part_number',
        1396 => 'sku_item_upc',
        1397 => 'receive_quantity',
        1398 => 'description',
        1399 => 'aes_entry_number',
        1400 => 'total_shipment_weight',
        1401 => 'total_package_weight',
        1402 => 'billed_weight',
        1403 => 'dim_weight',
        1404 => 'total_volume',
        1405 => 'alcohol_volume',
        1406 => 'dry_ice_weight',
        1407 => 'commodity_weight',
        1408 => 'commodity_unit_value',
        1409 => 'cod_amount',
        1410 => 'commodity_customs_value',
        1411 => 'total_customs_value',
        1412 => 'freight_charge',
        1413 => 'insurance_charge',
        1414 => 'taxes_miscellaneous_charge',
        1415 => 'declared_value_carriage_value',
        1416 => 'base_rate_amount',
        1417 => 'total_surcharge_amount',
        1418 => 'total_discount_amount',
        1419 => 'net_charge_amount',
        1420 => 'total_rebate_amount',
        1429 => 'list_variable_handling_charge_amount',
        1431 => 'list_total_customer_charge',
        1432 => 'cod_customer_amount',
        1450 => 'more_data_indicator',
        1451 => 'sequence_number',
        1452 => 'last_tracking_number',
        1453 => 'track_reference_type',
        1454 => 'track_reference',
        1456 => 'spod_type_request',
        1458 => 'spod_fax_recipient_name',
        1459 => 'spod_fax_recipient_number',
        1460 => 'spod_fax_sender_name',
        1461 => 'spod_fax_sender_phone_number',
        1462 => 'language_indicator',
        1463 => 'spod_fax_recipient_company_name_mail',
        1464 => 'spod_fax_recipient_address_line_1_mail',
        1465 => 'spod_fax_recipient_address_line_2_mail',
        1466 => 'spod_fax_recipient_city_mail',
        1467 => 'spod_fax_recipient_state_mail',
        1468 => 'spod_fax_recipient_zip_postal_code_mail',
        1469 => 'spod_fax_recipient_country_mail',
        1470 => 'spod_fax_confirmation',
        1471 => 'spod_letter',
        1472 => 'spod_ground_recipient_name',
        1473 => 'spod_ground_recipient_company_name',
        1474 => 'spod_ground_recipient_address_line_1',
        1475 => 'spod_ground_recipient_address_line_2',
        1476 => 'spod_ground_recipient_city',
        1477 => 'spod_ground_recipient_state_province',
        1478 => 'spod_ground_recipient_zip_postal_code',
        1479 => 'spod_ground_recipient_country',
        1480 => 'more_information',
        1507 => 'list_total_surcharge_amount',
        1525 => 'effective_net_discount',
        1528 => 'list_net_charge_amount',
        1529 => 'rate_indicator_1_numeric_valid_values',
        1530 => 'list_base_rate_amount',
        1531 => 'list_total_discount_amount',
        1532 => 'list_total_rebate_amount',
        1534 => 'detail_scan_indicator',
        1535 => 'paging_token',
        1536 => 'number_of_relationships',
        1537 => 'search_relationship_string',
        1538 => 'search_relationship_type_code',
        1551 => 'delivery_notification_flag',
        1552 => 'language_code',
        1553 => 'shipper_delivery_notification_flag',
        1554 => 'shipper_ship_alert_flag',
        1555 => 'shipper_language_code',
        1556 => 'recipient_delivery_notification_flag',
        1557 => 'recipient_ship_alert_flag',
        1558 => 'recipient_language_code',
        1559 => 'broker_delivery_notification_flag',
        1560 => 'broker_ship_alert_flag',
        1561 => 'broker_language_code',    
        1562 => 'fedex_staffed_location_flag',
        1563 => 'fedex_self_service_location_indicator',
        1564 => 'fasc',
        1565 => 'latest_express_dropoff_flag',
        1566 => 'express_dropoff_after_time',
        1567 => 'fedex_location_intersection_street_address',
        1568 => 'distance',    
        1569 => 'hours_of_operation',
        1570 => 'hours_of_operation_sat',
        1571 => 'last_express_dropoff',
        1572 => 'last_express_dropoff_sat',
        1573 => 'express_service_flag',
        1574 => 'location_count',
        1575 => 'fedex_location_business_name',
        1576 => 'fedex_location_business_type',
        1577 => 'fedex_location_city',
        1578 => 'fedex_location_state',
        1579 => 'fedex_location_postal_code',
        1580 => 'dangerous_goods_flag',
        1581 => 'saturday_service_flag',
        1582 => 'begin_date',
        1583 => 'end_date',
        1584 => 'tracking_groups',
        1590 => 'csp_solution_type',
        1591 => 'csp_solution_indicator',
        1600 => 'urbanization_code',
        1601 => 'maximum_match_count',
        1602 => 'score',
        1603 => 'single_house_number_match_flag',
        1604 => 'actual_match_count',
        1604 => 'rank',
        1606 => 'variable_handling_charge_level',
        1607 => 'doc_tab_header_1',
        1608 => 'doc_tab_header_2',
        1609 => 'doc_tab_header_3',
        1610 => 'doc_tab_header_4',
        1611 => 'doc_tab_header_5',
        1612 => 'doc_tab_header_6',
        1613 => 'doc_tab_header_7',
        1614 => 'doc_tab_header_8',
        1615 => 'doc_tab_header_9',
        1616 => 'doc_tab_header_10',
        1617 => 'doc_tab_header_11',
        1618 => 'doc_tab_header_12',
        1624 => 'doc_tab_field_1',
        1625 => 'doc_tab_field_2',
        1626 => 'doc_tab_field_3',
        1627 => 'doc_tab_field_4',
        1628 => 'doc_tab_field_5',
        1629 => 'doc_tab_field_6',
        1630 => 'doc_tab_field_7',
        1631 => 'doc_tab_field_8',
        1632 => 'doc_tab_field_9',
        1633 => 'doc_tab_field_10',
        1634 => 'doc_tab_field_11',
        1635 => 'doc_tab_field_12',
        1636 => 'delivery_area_surcharge',
        1637 => 'list_delivery_area_surcharge',
        1638 => 'fuel_surcharge',
        1639 => 'list_fuel_surcharge',
        1640 => 'fice_surcharge',
        1642 => 'value_added_tax',
        1644 => 'offshore_surcharge',
        1645 => 'list_offshore_surcharge',
        1649 => 'other_surcharges',
        1650 => 'list_other_surcharges',
        1704 => 'service_type_description',
        1705 => 'deliver_to',
        1706 => 'signed_for',
        1707 => 'delivery_time',
        1711 => 'status_exception',
        1713 => 'tracking_cod_flag',
        1715 => 'number_of_track_activities',
        1716 => 'delivery_reattempt_date',
        1717 => 'delivery_reattempt_time',
        1718 => 'package_type_description',
        1720 => 'delivery_date_numeric',
        1721 => 'tracking_activity_line_1',
        1722 => 'tracking_activity_line_2',
        1723 => 'tracking_activity_line_3',
        1724 => 'tracking_activity_line_4',
        1725 => 'tracking_activity_line_5',
        1726 => 'tracking_activity_line_6',
        1727 => 'tracking_activity_line_7',
        1728 => 'tracking_activity_line_8',
        1729 => 'tracking_activity_line_9',
        1730 => 'tracking_activity_line_10',
        1731 => 'tracking_activity_line_11',
        1732 => 'tracking_activity_line_12',
        1733 => 'tracking_activity_line_13',
        1734 => 'tracking_activity_line_14',
        1735 => 'tracking_activity_line_15',
        2254 => 'recipient_fax_number',
        2382 => 'return_shipment_indicator',
        2399 => 'signature_option',
        3000 => 'cod_type_collection',
        3001 => 'fedex_ground_purchase_order',
        3002 => 'fedex_ground_invoice',
        3003 => 'fedex_ground_customer_reference',
        3008 => 'autopod_flag',
        3009 => 'aod_flag',
        3010 => 'oversize_flag',
        3011 => 'other_oversize_flag',
        3018 => 'nonstandard_container_flag',
        3019 => 'fedex_signature_home_delivery_flag',
        3020 => 'fedex_home_delivery_type',
        3023 => 'fedex_home_delivery_date',
        3024 => 'fedex_home_delivery_phone_number',
        3025 => 'carrier_code',
        3028 => 'ground_account_number',
        3033 => 'oversize_package_total',
        3034 => 'prp_control_number',
        3035 => 'ship_alert_fax_number',
        3044 => 'package_location_for_pickup',
        3045 => 'cod_return_reference_indicator',
        3046 => 'additional_handling_detected',
        3053 => 'multiweight_net_charge',
        3090 => 'last_ground_dropoff',
        3091 => 'last_ground_dropoff_sat',
        3092 => 'ground_service_flag',
        3124 => 'oversize_classification',
        4003 => 'subscriber_contact_name',
        4004 => 'subscriber_password_reminder',
        4007 => 'subscriber_company_name',
        4008 => 'subscriber_address_line_1',
        4009 => 'subscriber_address_line_2',
        4011 => 'subscriber_city_name',
        4012 => 'subscriber_state_code',
        4013 => 'subscriber_postal_code',
        4014 => 'subscriber_country_code',
        4015 => 'subscriber_phone_number',
        4017 => 'subscriber_pager_number',
        4018 => 'subscriber_email_address',
        4021 => 'subscription_service_name',
        4022 => 'subscriber_fax_number',
        9744 => 'vendor_product_name',
        9745 => 'vendor_product_platform',
        9746 => 'vendor_product_version',
    );

    var $FE_SE = array();
    
    /**
        Constructor
        build the FE_RE in reverse order
    */
    function FedExTags () {
        foreach ($this->FE_RE as $key => $value) {
            $this->FE_SE[$value] = $key;
        }
    }
    
    /**
    * Look up a field name and convert it to a FedEx tag
    *
    * @param    string $in field to look for
    * @return   string
    * @access   public
    */
    function fieldNameToTag($in) {
        list ($num, $name, $mult) = $this->splitField($in);
        return "$num$mult";
    }

    /**
    * Look up a FedEx tag and convert it to a field name
    *
    * @param    string $in field to look for
    * @return   string
    * @access   public
    */
    function fieldTagToName($in) {
        list($num, $name, $mult) = $this->splitField($in);
        return "$name$mult";
    }

    /**
    * Split field to find tag and name
    *
    * @param    string $tag
    * @return   string
    * @access   public
    */
    function splitField($tag) {
        if (preg_match('/^(.*?)(-[1-9]\d*)?\z/', $tag, $hits)) {
            $base = $hits[1];
            if (isset($hits[2])) $multi = $hits[2];
        } else {
            die ("Invalid field tag or name `$tag'");
        }
        if (empty($multi)) $multi = '';

        //echo "[$base] [$multi]";

        $num = preg_match('/^\d+\z/', $base)
            ? $base
            : $this->FE_SE[strtolower($base)];

        $name = $this->FE_RE[$num];
        
        if (!$name) die("Invalid FE_RE `$num'");
        
        return array($num, $name, $multi);
    }
    
    /**
    * get service type
    *
    * @param    string $tag
    * @return   string
    * @access   public
    */
    function service_type($in) {
        foreach ($this->FE_ST as $key => $value) {
            if ($key==$in) {
                return $value;
            }
        }
    }

}
?>
