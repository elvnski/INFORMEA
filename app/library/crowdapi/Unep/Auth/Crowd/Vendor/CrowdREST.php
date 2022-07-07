<?php

//   Copyright 2012 Palantir Technologies
//      
//   Licensed under the Apache License, Version 2.0 (the "License");
//   you may not use this file except in compliance with the License.
//   You may obtain a copy of the License at
//      
//       http://www.apache.org/licenses/LICENSE-2.0
//      
//   Unless required by applicable law or agreed to in writing, software
//   distributed under the License is distributed on an "AS IS" BASIS,
//   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
//   See the License for the specific language governing permissions and
//   limitations under the License.

/**
 * REST API as documented here:
 *    https://developer.atlassian.com/display/CROWDDEV/Crowd+REST+Resources#CrowdRESTResources-UserResource
 */
class CrowdREST {

	const CONFIG_SSO_ENABLED = 'CONFIG_SSO_ENABLED';
	const CROWD_REST_API_PATH = '/rest/usermanagement/1';

	private $crowd_config;
	private $cookies = null;
	private $base_url = null;
	private $crowd_cookie_config = null;

	public function CrowdREST($crowd_config) {
		$this->crowd_config = $crowd_config;
		$this->base_url = $crowd_config['crowd_url'] . self::CROWD_REST_API_PATH;
		if(!array_key_exists(self::CONFIG_SSO_ENABLED,$this->crowd_config)) {
        	$this->crowd_config[self::CONFIG_SSO_ENABLED] = true;
		}
	}

	/**
	 * Returns an associative array containing:
	 * - info => metadata array returned by curl_info
	 * - response => response body
	 * - headers => response headers (as unparsed text)
	 */
	 function curlDo($url, $attrs = null, $post_body = false) {
		// locallize this variable for terser syntax
		$crowd_config = $this->crowd_config;

		// build up the full url/query string
		$query = "";
		if($attrs != null && count($attrs) > 0) {
			$query = "?" . http_build_query($attrs);
		}
		$full_url = $this->base_url . $url . $query; 

		// get a curl handle
		$curl = curl_init($full_url);

		// Crowd REST uses HTTP auth for the app name and credential
		curl_setopt($curl, CURLOPT_USERPWD, $crowd_config['app_name'] . ':' . $crowd_config['app_credential'] );
		// get back the response document
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		// set our content-type correctly (we get a 415 with text/xml)
		curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/xml"));
		// only post if we have a body
		if($post_body) {
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $post_body);
		}
		// optional supress peer checking to deal with broken SSL configs - may be common with Crowd
		if (array_key_exists('verify_ssl_peer',$crowd_config)) {
		    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, $crowd_config['verify_ssl_peer']);
		}
		// give us back the response headers (so we can scrape cookies)
		curl_setopt($curl, CURLOPT_HEADER, true);
		// load our cookies from early requests
		$this->curl_load_cookies($curl);


		// fire off the request
		$response = curl_exec($curl);

		// check for curl errors 
		if (curl_errno($curl)) {
		   throw new Exception("curl error (${full_url}): " . curl_error($curl));
		}

		// extract response metadata
		$info = curl_getinfo($curl);

		// split headers out of the response
		$rc = $this->curl_split_headers($response,$info);
		// add in the metadata
		$rc['metadata'] = $info;

		// store cookies for later reuse
		$this->curl_store_cookies($rc);
		curl_close($curl);
		return $rc;
	}

	private function curl_split_headers($raw_response,$info) {
		$header_size = $info['header_size']; // offset to the end of the headers
		$headers = substr($raw_response, 0, $header_size);
		$response = substr($raw_response, $header_size);
		return array('headers' => $headers, 'response' => $response);
	}

	private function curl_store_cookies($rc) {
		$headers = $rc['headers'];
		preg_match_all('|Set-Cookie: (.*);|U', $headers, $matches);   
		$this->cookies = implode('; ', $matches[1]);
	}

	private function curl_load_cookies($curl) {
		if($this->cookies) {
			curl_setopt($curl,CURLOPT_COOKIE,$this->cookies);
		}
	}

	/**
	 * Reads a curl response, tests http return code and logs
	 * accordingly
	 * 
	 * returns false on error, true on no error.
	 */
	private function curl_logerror($rc, $msg_prefix = "curl error") {
		$http_response_code = $rc['metadata']['http_code'];

		if($http_response_code != 200) {
			error_log("${msg_prefix}:\n" . $rc['response']);
			return false;
		}

		return true;
	}

	/**
	 * Reads XML response document and checks for errors.  Logs errors
	 * accordingly.
	 *
	 * Returns null on error, the XML response on no error.
	 */
	private function crowd_xml_logerror($rc, $msg_prefix = "error in xml response") {
		// got back a valid XML response (hopefully)
		$xmlResponse = new SimpleXMLElement($rc['response']);
		if($xmlResponse[0]->getName() == "error") {
			$reason = (string)$xmlResponse->{reason};
			$message = (string)$xmlResponse->{message};
			error_log("${msg_prefix}: ${reason} - ${message}");
			return null;
		}

		return $xmlResponse;
	}

	function isSSOEnabled() {
		if($this->crowd_config[self::CONFIG_SSO_ENABLED]) {
			return true;
		} 
		return false;
	}

	/**
	 * Attempts token auth and then falls back to password
	 * authentication if credentials are provided
	 */
	function authenticateUser($username, $password) {
		if($this->isSSOEnabled() && empty($username) && empty($password)) {
			// SSO uses a different auth mechanism and handles cookies
			$authenticated_username = $this->tokenAuth();
			if($authenticated_username != null) {
				return $authenticated_username; // success!
			}

		}

		if(!empty($username) && !empty($password)) {
		    
		    if($this->isSSOEnabled()) {
		    	// authenticate and create SSO tokens
		    	return $this->tokenAuthCreateSession($username,$password);
		    } else {
				return $this->simpleAuth($username, $password);
		    }
		   
		}

		return false;
	}

	function simpleAuth($username, $password) {
		$xmlBody = $this->generateSimpleAuthXML($password);
		$rc = $this->curlDo("/authentication", array("username" => $username),$xmlBody);

		// check to make sure we got a 200 and response from the server
		if(!$this->curl_logerror($rc,"Error in performing simple authentication:\n")){
			return false;
		}

		// got back a valid XML response (hopefully)
		$xmlResponse = $this->crowd_xml_logerror($rc,"Error returned in Crowd XML response");
		if($xmlResponse) {
			$authenticated_username = $xmlResponse->user['name'];
			if($authenticated_username == $username) {
				// authentication successful
				return $authenticated_username;
			} else {
				error_log("Got unexpected Crowd XML response to auth query:\n" . $rc['response']);
			}
		}

		return false;
	}

	function tokenAuth() {
		$cookie_config = $this->getCrowdCookieConfig();

		if($cookie_config == null) {
			// errors have already been logged in this case
			return null;
		} else {
			$cookie_name = $this->getCookieName();
			if(array_key_exists($cookie_name, $_COOKIE)) {
				$token = $_COOKIE[$cookie_name];
				if($token) {
					$tokenCheckXML = $this->generateTokenVerificationXML($token);
					$rc = $this->curlDo("/session/${token}", null, $tokenCheckXML);
					$http_response_code = $rc['metadata']['http_code'];
					if ($http_response_code == 200) {
						// good to go, token is renewed
						// extract username from response
						$xmlResponse = new SimpleXMLElement($rc['response']);
						$userElement = $xmlResponse->user;
						$authenticated_username = (string)$userElement['name'];

						// confirm that tokens match (just in case)
						$authenticated_token = (string)$xmlResponse->token;
						if($authenticated_token != $token) {
							error_log("Crowd SSO inconsistency: sent token ${token}, got back ${authenticated_token}: mismatch");
						}

						return $authenticated_username; // SSO successful
					} elseif($http_response_code == 404) {
						// bad/expired token
					} elseif ($http_response_code == 400) {
						// validation factors did not match token - IP address changed
						// see https://answers.atlassian.com/questions/65240/how-is-the-remote-address-in-crowd-s-validationfactor-enforced
						error_log("Crowd SSO inconsistency: validation factors failed for token ${token} - malfeasance?");
					} else {
						error_log("Crowd SSO: got back unexpected HTTP response code: ${http_response_code}");
					}
					return null;
				} 
			} else {
				error_log("Crowd SSO Failure: Did not receive cookie name from Crowd or error.  Odd that.");
				return null;
			}
		}
	}

	function tokenAuthCreateSession($username,$password) {
		$cookie_config = $this->getCrowdCookieConfig();

		if($cookie_config == null) {
			// errors have already been logged in this case
			return null;
		} else {
			$tokenCreationXML = $this->generateTokenCreationXML($username, $password);
			$rc = $this->curlDo("/session", null, $tokenCreationXML);
			$http_response_code = $rc['metadata']['http_code'];
			if ($http_response_code == 201) { // 201 == created
				// good to go, token is created and authentication verified
				// extract username from response
				$xmlResponse = new SimpleXMLElement($rc['response']);
				$userElement = $xmlResponse->user;
				$authenticated_username = (string)$userElement['name'];

				// confirm that tokens match (just in case)
				$authenticated_token = (string)$xmlResponse->token;

				// set the cookie
				$name = $cookie_config['name'];
				$domain = $cookie_config['domain'];
				$secure = (bool)$cookie_config['secure'];
				$value = $authenticated_token;
				$session_only = 0;
				setcookie($name, $value, $session_only, '/', $domain, $secure);
				error_log("Auth sucess: ${authenticated_username} ${authenticated_token}");
				return $authenticated_username; // authentication successful and token set
			} elseif($http_response_code == 400) {
				// authentication failed - bad credentials
			} elseif ($http_response_code == 403) {
				// inactive user
			} else {
				error_log("Crowd SSO: got back unexpected HTTP response code: ${http_response_code}");
			}
			return null;
		}
	}

	function getCrowdCookieConfig() {
		if ($this->crowd_cookie_config == null) {
			$rc = $this->curlDo("/config/cookie");
			if(!$this->curl_logerror($rc,"Error while retrieving Crowd cookie config")){
				return null;
			} else {
				$xmlResponse = $this->crowd_xml_logerror($rc,"Crowd error while retrieving cookie config");
				$cookie_config = array();
				$cookie_config['domain'] = (string)$xmlResponse->domain;
				$cookie_config['secure'] = (string)$xmlResponse->secure;
				$cookie_config['name']   = (string)$xmlResponse->name;
				$this->crowd_cookie_config = $cookie_config;
				return $cookie_config;
			}
		} else {
			return $this->crowd_cookie_config;
		}

		return null;
	}

	function userIsInGroup($username, $groupname) {
		$rc = $this->curlDo("/user/group/nested",array("username" => $username, "groupname" => $groupname));

		$http_response_code = $rc['metadata']['CURLINFO_HTTP_CODE'];

		if($http_response_code == 200) {
			// user belongs to group
			return true;
		} elseif ($http_response_code == 404) {
			// user not in group
			return false;
		} else {
			// some other error
			curl_logerror($rc, "Error while confirming membership of '${username}' in group '${groupname}'");
			return false;
		}
	}
		function getUgroup()
		{
			$rc = $this->curlDo('/user/group/nested', array('username' => 'ofo.intern37@unep.org'));
			//return $rc;
			//$xmlResponse = $this->crowd_xml_logerror($rc,"Error returned in Crowd XML response");
			$xmlResponse = new SimpleXMLElement($rc['response']);
			return $xmlResponse;

		}

	function getUserInfo($username) {
		$rc = $this->curlDo('/user', array('username' => $username));
		if (!$this->curl_logerror($rc,"Error while retrieving user info for username '${username}'")){
			//return null;
			return "Error while retrieving user information";

		}

		// got back a valid XML response (hopefully)
		$xmlResponse = $this->crowd_xml_logerror($rc,"Error returned in Crowd XML response");
		if($xmlResponse) {
			if($xmlResponse[0]->getName() == "user") {

				// break out from the XML
				$firstname    = (string)$xmlResponse->{'first-name'};
				$lastname     = (string)$xmlResponse->{'last-name'};
				$email        = (string)$xmlResponse->{'email'};
				$display_name = (string)$xmlResponse->{'display-name'};
				$active       = (string)$xmlResponse->{'active'};

				// seed the array to be used for user creation
				$userData = array(
					'user_login'    => $username,
					'user_nicename' => strip_tags("${firstname} ${lastname}"),
					'user_email'    => $email,
					'display_name'  => strip_tags("${firstname} ${lastname}"),
					'first_name'    => $firstname,
					'last_name'     => $lastname,
					'active'        => $active
				);

				return $userData;
			} else {
				//error_log("Got unexpected Crowd XML response to auth query:\n" . $rc['response']);
				echo "Got unexpected Crowd XML response to auth query:\n" . $rc['response'];
			}
		}
	}

	private function generateSimpleAuthXML($password) {
		$document = new DOMDocument("1.0","UTF-8");
		$passwordNode = $document->appendChild($document->createElement("password"));
		$value = $passwordNode->appendChild($document->createElement("value"));
		$cdata = $document->createCDATASection($password);
		$value->appendChild($cdata);

		$xml = $document->saveXML();
		return $xml;
	}

	private function generateTokenVerificationXML() {
		$document = new DOMDocument("1.0","UTF-8");
		$validationFactors = $document->appendChild($document->createElement("validation-factors"));
		$validationFactor = $validationFactors->appendChild($document->createElement("validation-factor"));
		$validationFactor->appendChild($document->createElement('name','remote_address'));
		$validationFactor->appendChild($document->createElement('value',$_SERVER['REMOTE_ADDR']));
		$xml = $document->saveXML();
		return $xml;
	}

	 function generateTokenCreationXML($username, $password) {
		$document = new DOMDocument("1.0","UTF-8");
		$authenticationContext = $document->appendChild($document->createElement("authentication-context"));
		$authenticationContext->appendChild($document->createElement("username",$username));
		$passwordNode =	$authenticationContext->appendChild($document->createElement("password"));
		$cdata = $document->createCDATASection($password);
		$passwordNode->appendChild($cdata);
		$validationFactors = $authenticationContext->appendChild($document->createElement("validation-factors"));
		$validationFactor = $validationFactors->appendChild($document->createElement("validation-factor"));
		$validationFactor->appendChild($document->createElement('name','remote_address'));
		$validationFactor->appendChild($document->createElement('value',$_SERVER['REMOTE_ADDR']));
		$xml = $document->saveXML();
		return $xml;
	}

	private function getCookieName(){
		$crowd_cookie_config = $this->getCrowdCookieConfig();
		// replace all dots in the name with underscores
		return preg_replace('/\\./', "_", $crowd_cookie_config['name']);
	}
}
?>
