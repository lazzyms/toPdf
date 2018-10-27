<?php

require_once __DIR__ . '/../library/vendor/autoload.php';

use Phalcon\Mvc\Controller;

class UrlController extends Controller
{
    public function indexAction()
    {
        $this->setJsonResponse();
        // echo "url contrller \n";
        $debug = $this->config->debug;
        $urls = $this->request->getPost();
        // echo $urls . "\n";
        // $url = "http://localhost:8007/erp_pcb/#/app/sales-url/salesreports";
        $config = \Phalcon\DI::getDefault()->getShared('config');
        $name = $error = null;
        $response = [
            "success" => [],
        ];
        foreach ($urls as $url) {
            if (strpos($url, 'sales') !== false) {
                $name = 'sales_report_' . date('dmYhms') . '.pdf';
            } else if (strpos($url, 'purchase') !== false) {
                $name = 'purchase_report_' . date('dmYhms') . '.pdf';
            } else {
                $name = 'stock_report_' . date('dmYhms') . '.pdf';
            }

            $error = $this->runCommand($url, $name, $config);
            if ($error) {
                return json_encode(array("error" => $error));
            } else {
                // echo "into else of " . $name . "\n";
                $pdfdtring = null;
                $file = fopen($config->temp_dest . "\\" . $name, "r") or die("Unable to open file!");
                $pdfdtring = fread($file, filesize($config->temp_dest . "\\" . $name));
                // echo $pdfdtring . "\n";
                // echo json_encode($response) . "\n";
                $pdfdtring = base64_encode($pdfdtring);
                array_push($response["success"], $pdfdtring);
                // echo json_encode($response) . "\n";
                fclose($file);
            }
        }
        // $error = $this->runCommand($url, 'random.pdf', $config);
        // $ee = json_encode($response);
        // echo ee;
        $result = array($response);
        $encoded = json_encode($response, 0, 1024);
        return $encoded;
    }

    private function setJsonResponse()
    {
        $this->response->setContentType('application/json', 'UTF-8');

        // If request AJAX is json type then parse it and update
        // php's POST member.
        $contentType = $this->request->getHeader('CONTENT_TYPE');
        if (strpos(strtolower($contentType), "application/json") !== false) {
            $jsonRawBody = $this->request->getJsonRawBody(true);
            if ($this->request->getRawBody() && !$jsonRawBody) {
                //throw new Exception("Invalid JSON syntax");
            } else {
                $_POST = $jsonRawBody;
            }
        }
    }

    private function runCommand($url, $name, $config)
    {
        // echo "run command \n";
        $chrome_path = $config->chrome_path;
        $output_file = $config->temp_dest . "\\" . $name;
        $command = $chrome_path . " --headless --disable-gpu --enable-logging --print-to-pdf="
            . $output_file . " " . $url . " --virtual-time-budget=10000";
        try {
            exec($command);
        } catch (Exception $ex) {
            return $ex;
        }
        return null;
    }

}
