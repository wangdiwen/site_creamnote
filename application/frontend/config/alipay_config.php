<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['seller_email'] = 'creamnote@163.com';                  // 卖家的支付宝账户，E-mail
$config['partner'] = '2088902931436854';                        // 合作身份者id，以2088开头的16位纯数字
$config['key'] = 'jhudeq3yib01mw3a8admmpgv0xa29km9';            // 安全检验码，以数字和字母组成的32位字符

$config['sign_type'] = strtoupper('MD5');                       // 签名方式 不需修改
$config['input_charset'] = strtolower('utf-8');                 // 字符编码格式 目前支持 gbk 或 utf-8
$config['cacert'] = 'application/frontend/license/cacert.pem';  // ca证书路径地址，用于curl中ssl校验
$config['transport'] = 'http';                                  // 访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
