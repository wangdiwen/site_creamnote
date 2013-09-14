<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class WX_Weibo_Renren_API
{
/*****************************************************************************/
    var $CI;  // Get the CI super object

    var $weibo_appkey = '1899806133';
    var $weibo_appsecret = '051f07adc64242feef0a05e18087febd';
    var $weibo_redirect_url = 'http://www.creamnote.com/core/wxc_user_manager/weibo_back_func';

    var $renren_appkey = 'caa721480d9c474cb6c62fbd3a59705e';
    var $renren_appsecret = '8d43c8cef62c4e2e8c98c3dbc16ee521';
    var $renren_redirect_url = 'http://www.creamnote.com/core/wxc_user_manager/renren_back_func';

    var $qq_appkey = '100486041';
    var $qq_appsecret = '09427c30c883122a17663ae0ea368fc2';
    var $qq_redirect_url = 'http://www.creamnote.com/core/wxc_user_manager/qq_back_func';

    // 中文分词工具
    var $zh_word_segment_url = 'http://www.xunsearch.com/scws/api.php';
    // 淘宝IP地址库，开放RESTful API
    var $taobao_ip_restapi = 'http://ip.taobao.com/service/getIpInfo.php';

/*****************************************************************************/
    public function __construct()
    {
        $this->CI =& get_instance();
    }
/*****************************************************************************/
    public function get_qq_params()
    {
        $data = array(
            'appkey' => $this->qq_appkey,
            'appsecret' => $this->qq_appsecret,
            'redirect_url' => $this->qq_redirect_url
            );
        return $data;
    }
/*****************************************************************************/
    public function get_renren_params()
    {
        $data = array(
            'appkey' => $this->renren_appkey,
            'appsecret' => $this->renren_appsecret,
            'redirect_url' => $this->renren_redirect_url
            );
        return $data;
    }
/*****************************************************************************/
    public function get_weibo_params()
    {
        $data = array(
            'appkey' => $this->weibo_appkey,
            'appsecret' => $this->weibo_appsecret,
            'redirect_url' => $this->weibo_redirect_url
            );
        return $data;
    }
/*****************************************************************************/
    public function get_request($url = '', $params = array())
    {
        if ($url) {
            $curl_obj = curl_init();

            $curl_params = '';
            if ($params) {
                $tmp_array = array();
                foreach ($params as $key => $value) {
                    if ($key && $value) {
                        $tmp_array[] = $key.'='.$value;
                    }
                }
                $curl_params = implode('&', $tmp_array);
            }

            $url .= '?'.$curl_params;

            curl_setopt($curl_obj, CURLOPT_URL, $url);
            curl_setopt($curl_obj, CURLOPT_TIMEOUT, 30);
            curl_setopt($curl_obj, CURLOPT_RETURNTRANSFER, true);

            $ret_data = curl_exec($curl_obj);
            curl_close($curl_obj);

            return $ret_data;
        }
        return false;
    }
/*****************************************************************************/
    public function post_request($url = '', $params = array())
    {
        if ($url && $params) {
            $curl_obj = curl_init();

            $curl_params = '';
            if ($params) {
                $tmp_array = array();
                foreach ($params as $key => $value) {
                    $tmp_array[] = $key.'='.$value;
                }
                $curl_params = implode('&', $tmp_array);
            }

            curl_setopt($curl_obj, CURLOPT_URL, $url);
            curl_setopt($curl_obj, CURLOPT_HEADER, false);
            curl_setopt($curl_obj, CURLOPT_TIMEOUT, 30);
            curl_setopt($curl_obj, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl_obj, CURLOPT_POST, true);
            if ($curl_params) {
                curl_setopt($curl_obj, CURLOPT_POSTFIELDS, $curl_params);
            }

            $ret_data = curl_exec($curl_obj);
            curl_close($curl_obj);

            return $ret_data;
        }
        return false;
    }
/*****************************************************************************/
    public function get_word_segment($context = '') {
        $word_list = array();
        if ($context) {
            $long_words_list = array();
            $short_words_list = array();
            $other_words_list = array();

            // match chinese word
            $url = $this->zh_word_segment_url;
            $params = array(
                'data' => $context,
                'respond' => 'json',
                'charset' => 'utf8',
                'ignore' => 'yes',
                'multi' => 1,
                );
            $info = $this->post_request($url, $params);
            if ($info) {
                $json_data = json_decode($info);
                if ($json_data && $json_data->status == 'ok') {
                    $words = $json_data->words;
                    // wx_echoxml($words);
                    foreach ($words as $key => $value) {
                        $filter = array('a', 'n', 'nr', 'nt', 't', 'vn', 'ns', 'v');
                        $word_len = mb_strlen($value->word, 'UTF-8');
                        if ($word_len >= 2 && in_array($value->attr, $filter)) {
                            if ($word_len < 4) {
                                $short_words_list[] = $value->word;
                            }
                            else {
                                $long_words_list[] = $value->word;
                            }
                            // $word_list[] = $value->word;
                        }
                    }
                }
                // match numberic
                $num_match = array();
                preg_match_all('/(\d)+/', $context, $num_match);
                if ($num_match && $num_match[0]) {
                    // wx_echoxml($num_match);
                    foreach ($num_match[0] as $key => $value) {
                        $str_len = strlen($value);
                        if ($str_len > 2 && $str_len < 5) {
                            // $word_list[] = $value;
                            $other_words_list[] = $value;
                        }
                    }
                }
                // match english
                $eng_match = array();
                preg_match_all('/([a-zA-Z-_])+/', $context, $eng_match);
                if ($eng_match && $eng_match[0]) {
                    foreach ($eng_match[0] as $key => $value) {
                        $str_len = strlen($value);
                        if ($str_len >= 5 && $str_len < 10) {
                            // $word_list[] = $value;
                            $other_words_list[] = $value;
                        }
                    }
                }
            }
            // filter short from long words, unique the result
            foreach ($long_words_list as $long_key => $long_word) {
                foreach ($short_words_list as $short_key => $short_word) {
                    if (stristr($long_word, $short_word)) {
                        unset($short_words_list[$short_key]);
                    }
                }
            }
            $word_list = array_merge($long_words_list, $short_words_list, $other_words_list);
        }
        return $word_list;
    }
/*****************************************************************************/
    public function get_taobao_ip_info($query_ip = '') {
        if ($query_ip) {
            $url = $this->taobao_ip_restapi;
            $params = array(
                'ip' => $query_ip,
                );
            $info = $this->get_request($url, $params);
            if ($info) {
                $json_data = json_decode($info, true);
                if ($json_data && $json_data['code'] == 0) {
                    return $json_data;
                }
            }
        }
        return false;
    }
/*****************************************************************************/
}

/* End of file WX_Weibo_api.php */
/* Location: ./application/frontend/libraries/WX_Weibo_api.php */
