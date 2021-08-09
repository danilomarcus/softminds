<?php
/***
 * constantes do sistema
 */

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SITE
 */
define("CONF_SITE_VERSION", "1.0");
define("CONF_SITE_NAME", "Soft Minds");
define("CONF_SITE_TITLE", "Soft Minds");
define("CONF_SITE_CNPJ", "CNPJ 12.345.678-0009/99");
define("CONF_SITE_DESC", "Awesome chalenge from ManyMinds");
define("CONF_SITE_DOMAIN", "softminds.com.br");
define("CONF_SITE_ADDR_STREET", "Rua bauru");
define("CONF_SITE_ADDR_NUMBER", "7");
define("CONF_SITE_ADDR_COMPLEMENT", "Sala 7");
define("CONF_SITE_ADDR_CITY", "Bauru");
define("CONF_SITE_ADDR_STATE", "SP");
define("CONF_SITE_ADDR_ZIPCODE", "16000-000");

/**
 * PASSWORD
 */
define("CONF_PASSWD_MIN_LEN",8);
define("CONF_PASSWD_MAX_LEN",40);
define("CONF_PASSWD_ALGO",PASSWORD_DEFAULT);
define("CONF_PASSWD_OPTION",["cost" => 10]);


