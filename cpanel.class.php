<?

    class Cpanel {

      private $user;
      private $token;
      private $server;
      private $api;
      private $callURL;
      private $cpanelUser;

      public function __construct($token) {
        $this->user = "root";
        $this->token = $token;
      }

      public function setServer($server) {
        $this->server = $server;
      }

      public function setAPI($api) {
        $this->api = $api;
      }

      public function setCpanelUser($user) {
        $this->cpanelUser = $user;
      }

      public function query($command, $parameters = "") {
        $server = $this->server; // Get server
        $api = $this->api; // Get API version

        if (!empty($parameters)) {
          $parameters = http_build_query($parameters);
          $amp = "&";
          $qsm = "?";
        } else {
          $amp = "";
          $qsm = "";
        }
        $beginningOfURL = "https://".$server.":2087/json-api/";
        $beginningOfUAPI = "https://".$server.":2087/execute/";

        if ($this->api == "1") {
          // WHM API 0
          $this->callURL = $beginningOfURL . $command . $qsm . $parameters;
        } elseif ($this->api == "2") {
          // WHM API 1
          $this->callURL = $beginningOfURL . $command . "?api.version=1" .$amp . $parameters;
        } elseif ($this->api == "3") {
          // cPanel API 1
          $this->callURL = $beginningOfURL . $command . "?cpanel_jsonapi_apiversion=1" . $amp . $parameters;
        } elseif ($this->api == "4") {
          // cPanel API 2
          list($module, $function) = explode("::", $command);
          $this->callURL = $beginningOfURL . "cpanel?cpanel_jsonapi_user=".$this->cpanelUser."&cpanel_jsonapi_apiversion=2&cpanel_jsonapi_module=" . $module . "&cpanel_jsonapi_func=". $function. $amp . $parameters;
        } elseif ($this->api == "5") {
          // UAPI
          list($module, $function) = explode("::", $command);
          // $this->callURL =  $beginningOfUAPI . $module. "/" . $function . $qsm . $parameters;
          $this->callURL = $beginningOfURL . "cpanel?cpanel_jsonapi_user=".$this->cpanelUser."&cpanel_jsonapi_apiversion=3&cpanel_jsonapi_module=" . $module . "&cpanel_jsonapi_func=". $function. $amp . $parameters;
        } else {

        }

      }

      public function send() {
        $query = $this->callURL;
        // var_dump($query);
        // die();
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,2);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);

        $header[0] = "Authorization: whm $this->user:$this->token";
        curl_setopt($curl,CURLOPT_HTTPHEADER,$header);
        curl_setopt($curl, CURLOPT_URL, $query);

        $result = curl_exec($curl);

        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($http_status != 200) {
          return "[!] Error: " . $http_status . " returned\n";
        } else {
          // return json_decode($result);
          return $result;
        }

        curl_close($curl);
      }


    }

?>
