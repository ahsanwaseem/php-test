<?php

require_once("JSON.php");
define('DEBUG', false);
define('MAX_RECURSION_DEPTH_ALLOWED', 25);
define('EMPTY_STR', "");
define('SIMPLE_XML_ELEMENT_OBJECT_PROPERTY_FOR_ATTRIBUTES', '@attributes');
define('SIMPLE_XML_ELEMENT_PHP_CLASS', 'SimpleXMLElement');

class xml2json {
	public static function transformXmlStringToJson($xmlStringContents) {

		$simpleXmlElementObject = simplexml_load_string($xmlStringContents);

		if ($simpleXmlElementObject == null) {
			return(EMPTY_STR);
		}

		$simpleXmlRootElementName = $simpleXmlElementObject->getName();

		if (DEBUG) {
			// var_dump($simpleXmlRootElementName);
			// var_dump($simpleXmlElementObject);
		}

		$jsonOutput = EMPTY_STR;
		// Let us convert the XML structure into PHP array structure.
		$array1 = xml2json::convertSimpleXmlElementObjectIntoArray($simpleXmlElementObject);

		if (($array1 != null) && (sizeof($array1) > 0)) {
			//create a new instance of Services_JSON
			$json = new Services_JSON();
			$jsonOutput = $json->encode($array1);

			if (DEBUG) {
				// var_dump($array1);
				// var_dump($jsonOutput);
			}
		} // End of if (($array1 != null) && (sizeof($array1) > 0))

		return($jsonOutput);
	} // End of function transformXmlStringToJson

	public static function convertSimpleXmlElementObjectIntoArray($simpleXmlElementObject, &$recursionDepth=0) {
		// Keep an eye on how deeply we are involved in recursion.
		if ($recursionDepth > MAX_RECURSION_DEPTH_ALLOWED) {
			// Fatal error. Exit now.
			return(null);
		}

		if ($recursionDepth == 0) {
			if (get_class($simpleXmlElementObject) != SIMPLE_XML_ELEMENT_PHP_CLASS) {
				// If the external caller doesn't call this function initially
				// with a SimpleXMLElement object, return now.
				return(null);
			} else {
				// Store the original SimpleXmlElementObject sent by the caller.
				// We will need it at the very end when we return from here for good.
				$callerProvidedSimpleXmlElementObject = $simpleXmlElementObject;
			}
		} // End of if ($recursionDepth == 0) {

		if (get_class($simpleXmlElementObject) == SIMPLE_XML_ELEMENT_PHP_CLASS) {
			// Get a copy of the simpleXmlElementObject
			$copyOfsimpleXmlElementObject = $simpleXmlElementObject;
      		// Get the object variables in the SimpleXmlElement object for us to iterate.
       		$simpleXmlElementObject = get_object_vars($simpleXmlElementObject);
	   	}

       	// It needs to be an array of object variables.
   		if (is_array($simpleXmlElementObject)) {
   			// Initialize the result array.
   			$resultArray = array();
       		// Is the input array size 0? Then, we reached the rare CDATA text if any.
   			if (count($simpleXmlElementObject) <= 0) {
   				// Let us return the lonely CDATA. It could even be whitespaces.
   				return (trim(strval($copyOfsimpleXmlElementObject)));
   			}

   			// Let us walk through the child elements now.
       		foreach($simpleXmlElementObject as $key=>$value) {
       			// When this block of code is commented, XML attributes will be
       			// added to the result array.
       			// Uncomment the following block of code if XML attributes are
       			// NOT required to be returned as part of the result array.
       			/*
  	     		if((is_string($key)) && ($key == SIMPLE_XML_ELEMENT_OBJECT_PROPERTY_FOR_ATTRIBUTES)) {
  	     			continue;
       			}
       			*/
       			// Let us recursively process the current element we just visited.
				// Increase the recursion depth by one.
				$recursionDepth++;
           		$resultArray[$key] = xml2json::convertSimpleXmlElementObjectIntoArray($value, $recursionDepth);
           		// Decrease the recursion depth by one.
           		$recursionDepth--;
       		} // End of foreach($simpleXmlElementObject as $key=>$value) {

       		if ($recursionDepth == 0) {
				// That is it. We are heading to the exit now.
				// Set the XML root element name as the root [top-level] key of
				// the associative array that we are going to return to the caller of this
				// recursive function.
				$tempArray = $resultArray;
				$resultArray = array();
				$resultArray[$callerProvidedSimpleXmlElementObject->getName()] = $tempArray;
       		}

       		return ($resultArray);
   		} else {
   			// We are now looking at either the XML attribute text or
   			// the text between the XML tags.
   			return (trim(strval($simpleXmlElementObject)));
   		} // End of else
	} // End of function convertSimpleXmlElementObjectIntoArray.

} // End of class xml2json
?>
