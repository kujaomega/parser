<?php
/**
 * Created by JetBrains PhpStorm.
 * User: root
 * Date: 19/11/13
 * Time: 16:14
 * To change this template use File | Settings | File Templates.
 */
/**
 * Make a curl request respecting redirects
 * Also supports posts
 */
class pegCurlRequest {
    private $url, $postFields = array(), $referer = NULL, $timeout = 3;
    private $debug = false, $postString = "";
    private $curlInfo = array();
    private $content = "";
    private $response_meta_info = array();



    static $cookie;

    function __construct($url, $postFields = array(), $referer = NULL, $timeout = 3) {
        $this->setUrl($url);
        $this->setPost($postFields);
        $this->setReferer($referer);
        $this->setTimeout($timeout);
        if(empty(self::$cookie)) self::$cookie = tempnam("/tmp", "pegCurlRequest"); //one time cookie
    }

    function setUrl($url) {
        $this->url = $url;
    }

    function setTimeout($timeout) {
        $this->timeout = $timeout;
    }

    function setPost($postFields) {
        if(is_array($postFields)) {
            $this->postFields = $postFields;
        }
        $this->updatePostString();
    }

    function updatePostString() {
        //Cope with posting
        $this->postString = "";
        if(!empty($this->postFields)) {
            foreach($this->postFields as $key=>$value) { $this->postString .= $key.'='.$value.'&'; }
            $this->postString= rtrim($this->postString,'&'); //Trim off the waste
        }
    }

    function setReferer($referer) {
        //Set a referee either specified or based on the url
        $this->referer = $referer;
    }

    function debugInfo() {
        //Debug
        if($this->debug) {
            echo "<table><tr><td colspan='2'><b><u>Pre Curl Request</b><u></td></tr>";
            echo "<tr><td><b>URL: </b></td><td>{$this->url}</td></tr>";
            if(!empty(self::$cookie)) echo "<tr><td><b>Cookie String: </b></td><td>".self::$cookie."</td></tr>";
            if(!empty($this->referer)) echo "<tr><td><b>Referer: </b></td><td>".$this->referer."</td></tr>";
            if(!empty($this->postString)) echo "<tr><td><b>Post String: </b></td><td>".$this->postString."</td></tr>";

            if(!empty($this->postFields)) {
                echo "<tr><td><b>Post Values:</b></td><td><table>";
                foreach($this->postFields as $key=>$value)
                    echo "<tr><td>$key</td><td>$value</td></tr>";
                echo "</table>";
            }
            echo "</td></tr></table><br />\n";
        }
    }

    function debugFurtherInfo() {
        //Debug
        if($this->debug) {
            echo "<table><tr><td colspan='2'><b><u>Post Curl Request</b><u></td></tr>";
            echo "<tr><td><b>URL: </b></td><td>{$this->url}</td></tr>";
            if(!empty($this->referer)) echo "<tr><td><b>Referer: </b></td><td>".$this->referer."</td></tr>";
            if(!empty($this->curlInfo)) {
                echo "<tr><td><b>Curl Info:</b></td><td><table>";
                foreach($this->curlInfo as $key=>$value)
                    echo "<tr><td>$key</td><td>$value</td></tr>";
                echo "</table>";
            }
            echo "</td></tr></table><br />\n";
        }
    }

    /**
     * Make the actual request
     */
    function makeRequest($url=NULL) {
        //Shorthand request
        if(!is_null($url))
            $this->setUrl($url);

        //Output debug info
        $this->debugInfo();

        //Using a shared cookie
        $cookie = self::$cookie;

        //Setting up the starting information
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_3) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.11 Safari/536.11" );
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");

        //register a callback function which will process the headers
        //this assumes your code is into a class method, and uses $this->readHeader as the callback //function
        curl_setopt($ch, CURLOPT_HEADERFUNCTION, array(&$this,'readHeader'));

        //Some servers (like Lighttpd) will not process the curl request without this header and will return error code 417 instead.
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Expect:"));

        //Referer
        if(empty($this->referer)) {
            curl_setopt($ch, CURLOPT_REFERER, dirname($this->url));
        } else {
            curl_setopt($ch, CURLOPT_REFERER, $this->referer);
        }

        //Posts
        if(!empty($this->postFields)) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->postString);
        }

        //Redirects, transfers and timeouts
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);

        //Debug
        if($this->debug) {
            curl_setopt($ch, CURLOPT_VERBOSE, true); // logging stuffs
            curl_setopt($ch, CURLINFO_HEADER_OUT, true); // enable tracking
        }

        //Get the content and the header info
        $content = curl_exec($ch);
        $response = curl_getinfo($ch);

        //get the default response headers
        $headers = curl_getinfo($ch);

        //add the headers from the custom headers callback function
        $this->response_meta_info = array_merge($headers, $this->response_meta_info);

        curl_close($ch); //be nice

        //Curl info
        $this->curlInfo = $response;

        //Output debug info
        $this->debugFurtherInfo();

        //Are we being redirected?
        if ($response['http_code'] == 301 || $response['http_code'] == 302) {
            $location = $this->getHeaderLocation();
            if(!empty($location)) { //the location exists
                $this->setReferer($this->getTrueUrl()); //update referer
                return $this->makeRequest($location); //recurse to location
            }
        }
        //Is there a javascript redirect on the page?
        elseif (preg_match("/window\.location\.replace\('(.*)'\)/i", $content, $value) ||
            preg_match("/window\.location\=\"(.*)\"/i", $content, $value)) {
            $this->setReferer($this->getTrueUrl()); //update referer
            return $this->makeRequest($value[1]); //recursion
        } else {
            $this->content = $content; //set the content - final page
        }
    }

    /**
     * Get the url after any redirection
     */
    function getTrueUrl() {
        return $this->curlInfo['url'];
    }

    function __toString() {
        return $this->content;
    }

    /**
     * CURL callback function for reading and processing headers
     * Override this for your needs
     *
     * @param object $ch
     * @param string $header
     * @return integer
     */
    private function readHeader($ch, $header) {
        //This is run for every header, use ifs to grab and add
        $location = $this->extractCustomHeader('Location: ', '\n', $header);
        if ($location) {
            $this->response_meta_info['location'] = trim($location);
        }
        return strlen($header);
    }

    private function extractCustomHeader($start,$end,$header) {
        $pattern = '/'. $start .'(.*?)'. $end .'/';
        if (preg_match($pattern, $header, $result)) {
            return $result[1];
        } else {
            return false;
        }
    }

    function getHeaders() {
        return $this->response_meta_info;
    }

    function getHeaderLocation() {
        return $this->response_meta_info['location'];
    }
}