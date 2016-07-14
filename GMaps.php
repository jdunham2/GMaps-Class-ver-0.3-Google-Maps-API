<?php
/**
 * GMaps class ver 0.3
 *
 * Gets geo-informations from the Google Maps API
 * http://code.google.com/apis/maps/index.html
 *
 * Copyright 2008-2016 by Enrico Zimuel (enrico@zimuel.it)
 *
 */
class GMaps
{
    const MAPS_HOST = 'maps.googleapis.com';

    /**
     * Latitude
     *
     * @var double
     */
    private $_latitude;

    /**
     * Longitude
     *
     * @var double
     */
    private $_longitude;

    /**
     * Address
     *
     * @var string
     */
    private $_address;

    /**
     * Full Country name
     *
     * @var string
     */
    private $_countryLongName;

    /**
     * Country Abbreviation
     *
     * @var string
     */
    private $_countryShortName;

    /**
     * Street Number
     *
     * @var string
     */
    private $_streetNumber;

    /**
     * Street Name
     *
     * @var string
     */
    private $_streetName;

    /**
     * Town Name
     *
     * @var string
     */
    private $_townName;

    /**
     * County Name
     *
     * @var string
     */
    private $_countyName;

    /**
     * State Full name
     *
     * @var string
     */
    private $_stateLongName;

    /**
     * State Abbreviation
     *
     * @var string
     */
    private $_stateShortName;

    /**
     * Postal Code
     *
     * @var string
     */
    private $_postalCode;

    /**
     * Google Maps Key
     *
     * @var string
     */
    private $_key;

    /**
     * Base Url
     *
     * @var string
     */
    private $_baseUrl;


    /**
     * Construct
     *
     * @param string $key
     */
    function __construct ($key='')
    {
        $this->_key= $key;
        $this->_baseUrl= "https://" . self::MAPS_HOST . "/maps/api/geocode/json?key=". $this->_key;
        //&address=1600+Amphitheatre+Parkway,+Mountain+View,+CA
    }


    /**
     * getInfoLocation
     *
     * @param string $address
     * @return bool
     * @internal param string $city
     * @internal param string $state
     */
    public function getInfoLocation ($address) {
        if (!empty($address)) {
            return $this->_connect($address);
        }
        return false;
    }


    /**
     * connect to Google Maps
     *
     * @param string $param
     * @return boolean
     */
    private function _connect($param) {
        $request_url = $this->_baseUrl . "&address=" . urlencode($param);
        $json = json_decode(file_get_contents($request_url));

        if ($json = $json->results[0]) {
            $this->_latitude = $json->geometry->location->lat;
            $this->_longitude = $json->geometry->location->lng;
            $this->_address= $json->formatted_address;
            foreach ($json->address_components as $component){
                switch ($component->types[0]) {
                    case 'street_number':
                        $this->_streetNumber = $component->long_name;
                        break;
                    case 'route':
                        $this->_streetNumber = $component->short_name;
                        break;
                    case 'administrative_area_level_3':
                        $this->_townName = $component->long_name;
                        break;
                    case 'administrative_area_level_2':
                        $this->_countyName = $component->long_name;
                        break;
                    case 'administrative_area_level_1':
                        $this->_stateLongName = $component->long_name;
                        $this->_stateShortName = $component->short_name;
                        break;
                    case 'country':
                        $this->_countryLongName = $component->long_name;
                        $this->_countryShortName = $component->short_name;
                        break;
                    case 'postal_code':
                        $this->_postalCode = $component->short_name;
                        break;
                }
            }
            return true;
        } else {
            return false;
        }
    }


    /**
     * get the Postal Code
     *
     * @return string
     */
    public function getPostalCode () {
        return $this->_postalCode;
    }


	/**
     * get the Address
     *
     * @return string
     */
    public function getAddress () {
        return $this->_address;
    }


	/**
     * get Full Country name
     *
     * @return string
     */
    public function getCountryFullName () {
        return $this->_countryLongName;
    }

    /**
     * get Country abbreviation
     *
     * @return string
     */
    public function getCountryShortName () {
        return $this->_countryShortName;
    }


	/**
     * get the Street Number
     *
     * @return string
     */
    public function getStreetNumber () {
        return $this->_streetNumber;
    }


    /**
     * get the Street Name
     *
     * @return string
     */
    public function getStreetName () {
        return $this->_streetName;
    }


	/**
     * get the Town Name
     *
     * @return string
     */
    public function getTownName () {
        return $this->_townName;
    }


	/**
     * get the Full County Name
     *
     * @return string
     */
    public function getCountyName () {
        return $this->_countyName;
    }


	/**
     * get the State Full Name
     *
     * @return string
     */
    public function getStateLongName () {
        return $this->_stateLongName;
    }


	/**
     * get the State Abbreviation
     *
     * @return string
     */
    public function getStateShortName () {
        return $this->_stateShortName;
    }


    /**
     * get the Latitude coordinate
     *
     * @return double
     */
    public function getLatitude () {
        return $this->_latitude;
    }


    /**
     * get the Longitude coordinate
     *
     * @return double
     */
    public function getLongitude () {
        return $this->_longitude;
    }
}
