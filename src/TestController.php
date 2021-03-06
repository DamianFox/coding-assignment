<?php

namespace EUFTest;

use EUFTest\Controller\EUFFetcher;

/**
 * Class for the exercise.
 */
class TestController extends EUFFetcher {

	/**
	 * Get the list of countries
	 *
	 * @return array
	 *   The list of countries
	 *
	 */
	public function getAllCountries() {
		return json_decode( parent::getRequest('getCountries'), true );;
	}

	/**
	 * Get the list of universities by CountryID
	 *
	 * @return array
	 *   The list of universities
	 *
	 */
	public function getUniversitiesByID($countryID) {
		return json_decode( parent::getRequest('getInstitutions', array("CountryID" => $countryID)), true );
	}

	/**
	 * Renders list of universities.
	 *
	 * @return string
	 *   The HTML for the view.
	 */
	private function renderUniversities($universities, $ID) {
		$html = "";
		foreach($universities as $u){
			$html .= "<div id=\"collapse".$ID."\" class=\"panel-collapse collapse\">";
			$html .= "<div class=\"card-body\">".$u["NameInLatinCharacterSet"]." (".$u["CityName"].")</div>";
			$html .= "</div>";
		}
		return $html;
	}

	/**
	 * Generate country code from the erasmus code
	 *
	 * @return string
	 *   The country code.
	 */
	private function getCountryCode($erasmusCode, $countryName) {
		if(!empty($erasmusCode)){
			$code = substr(strtolower($erasmusCode), 0, 2);

			if(trim($code) == "a" && $countryName == "Austria"){ $code = "at";
			} elseif (trim($code) == "nl" && $countryName == "Aruba"){
				$code = "aw";
			} elseif (trim($code) == "nl" && $countryName == "Curaçao"){
				$code = "cw";
			} elseif (trim($code) == "b" && $countryName == "Belgium"){
				$code = "be";
			} elseif (trim($code) == "sf" && $countryName == "Finland"){
				$code = "fi";
			} elseif (trim($code) == "f" && $countryName == "France"){
				$code = "fr";
			} elseif (trim($code) == "f" && $countryName == "New Caledonia"){
				$code = "nc";
			} elseif (trim($code) == "d" && $countryName == "Germany"){
				$code = "de";
			} elseif (trim($code) == "dk" && $countryName == "Greenland"){
				$code = "gl";
			} elseif (trim($code) == "g" && $countryName == "Greece"){
				$code = "gr";
			} elseif (trim($code) == "uk" && $countryName == "Gibraltar"){
				$code = "gi";
			} elseif (trim($code) == "uk" && $countryName == "United Kingdom"){
				$code = "gb";
			} elseif (trim($code) == "i" && $countryName == "Italy"){
				$code = "it";
			} elseif (trim($code) == "ir" && $countryName == "Ireland"){
				$code = "ie";
			} elseif (trim($code) == "n" && $countryName == "Norway"){
				$code = "no";
			} elseif (trim($code) == "p" && $countryName == "Portugal"){
				$code = "pt";
			} elseif (trim($code) == "e" && $countryName == "Spain"){
				$code = "es";
			} elseif (trim($code) == "s" && $countryName == "Sweden"){
				$code = "se";
			}

			return $code;
		} else {
			return "";
		}
	}

	/**
	 * Renders the data from the API request.
	 *
	 * @return string
	 *   The HTML for the view.
	 */
	public function render() {

    	$countries = $this->getAllCountries();

    	$html = "";
    	foreach($countries as $value){
    		$universities = $this->getUniversitiesByID($value["ID"]);
    		$erasmusCode = $universities[0]["ErasmusCode"];
    		$number = count($universities);
    		$countryName = $value["CountryName"];

    		$html .= "<div class=\"card\" style=\"margin-bottom: 5px;\">";
    		
    		$html .= "<div class=\"card-header\">";
    		$html .= "<a data-toggle=\"collapse\" href=\"#collapse".$value["ID"]."\">";
    		$html .= "<span class=\"flag-icon flag-icon-".$this->getCountryCode($erasmusCode, $countryName)."\"></span>";
    		$html .= $countryName." (".$number . ($number == 1 ? " university)" : " universities)");
    		$html .= "</a>";
    		$html .= "</div>";

    		$html .= $this->renderUniversities($universities, $value["ID"]);
    		
    		$html .= "</div>";
    	}

    	return $html;
  	}

}
