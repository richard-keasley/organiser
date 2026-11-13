<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter.com/user_guide/extending/common.html
 */

function colour_dechex(int $dec) : string {
	$val = dechex($dec); 
	$val = str_pad($val, 6, "0", STR_PAD_LEFT);
	return "#{$val}";
}

function colour_hexdec(string $hex) : int {
	$hex = trim($hex, '#');
	return hexdec($hex);
}

/**
 * Normalises a URL or phone number and returns a valid URI string with a scheme.
 * Defaults to "https" if no scheme is present. Returns false if invalid.
 *
 * @param string $input The input URL, domain, or phone number.
 * @param string $defaultScheme The default scheme for schemeless web URLs.
 * @return string|false
 */
function normalise_url(string $address, string $defscheme='https'): string {
    $address = trim($address);
    
	// work out scheme and address
	if(preg_match('#^([a-z][a-z0-9+.-]*):/*(.*)$#i', $address, $matches)) {
        $scheme = strtolower($matches[1]);
        $address = trim($matches[2]);
		
	}
	else {
		$scheme = $defscheme;
	}
		
	// normalise address
	switch($scheme) {
		case 'tel':
		// strip spaces and punctuation
		$address = preg_replace('/[^\d+]/', '', $address);
		break;
		
		case 'query':
		// convert multiple white spaces
		$address = preg_replace('/\s+/', ' ', $address);
		$address = urlencode($address);
		$scheme = null;
		break;
		
		// external links
		case 'http':
		case 'https':
		$address = '//' . $address;
		break;
	}
	# d($scheme, $address);
	
	if($address) {
		return $scheme ? "{$scheme}:{$address}" : $address;
	}
	return '';
}
