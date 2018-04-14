<?php

require_once __DIR__ . '/../library/vendor/autoload.php';

use Phalcon\Mvc\Controller;
use mikehaertl\wkhtmlto\Pdf;
use Phalcon\Http\Request;

class IndexController extends Controller {

    public function indexAction() {
        $client_ip = $this->getRealIpAddr();
        $whitelist_ips = $this->config->whitelist_ip;
        $debug = $this->config->debug;
        
        foreach($whitelist_ips as $ip){
            if($client_ip == $ip){
                $authenticate = true;
                break;
            } else {
                $authenticate = false;
            }
        }

        $data = $this->request->getPost();
        if (!$data || !array_key_exists("html", $data) || !$authenticate) {
            if($debug) {
                $debug_msg = "";

                if(!$authenticate) {
                    $debug_msg = "Unauthorized IP [" . $client_ip . "]<br/>";
                }
                if(!$data) {
                    $debug_msg = "Post data missing<br/>";
                }
                if(!array_key_exists("html", $data)) {
                    $debug_msg = "Missing html entry in post data<br/>";
                }

                return $debug_msg;
            }

            return "Unknown Error";
        } else {
            $html = $data["html"];
            // $url = $data["base_url"];
            // $base = '<base href="' . $url . '">';
            // $content = str_replace('<base href="">', $base, $html);

            $pdf_string = "Unable to generate PDF";

            try {
                $pdf = new Pdf(array('print-media-type',
                    'load-error-handling' => 'ignore',
                    'load-media-error-handling' => 'ignore',
                    'commandOptions' => array(
                        'useExec' => true,
                        'procEnv' => array(
                            'LANG' => 'en_US.utf-8',
                        )
                )));

                $pdf->binary = $this->config->binary;
                $pageOptions = array(
                    'disable-smart-shrinking',
                );
                $pdf->addPage($html, $pageOptions);

                // Save PDF locally
                //$pdf->saveAs('<FILE_PATH>'.$filename);
                // Send PDF directly as a response
                // if (!$pdf->send($filename)) {
                //     $error = $pdf->getError();
                // }
                // Response as binary string of PDF content

                $pdf_string = $pdf->toString();
            } catch(\Exception $ex) {
                if($debug) {
                    return $ex->getMessage() . "<br/>\n" . $ex->getTraceAsString();
                }
            }

            if($debug && $pdf_string == "") {
                $pdf_string = "Unable to generate PDF";
            }

            return $pdf_string;
        }
    }

    public function getRealIpAddr()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
          $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        {
          $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
          $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

}
