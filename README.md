# php-test

## Usage

#### You can run the program from the command line with an XML filename as a command-line argument:

### php -f xml2json_test.php test1.xml


# Solution Explained

By using two core PHP features SimpleXMLElement and Services_JSON, it is possible to convert any arbitrary XML data into JSON, first we need to convert the XML content into a suitable PHP data type using SimpleXMLElement, then we feed the PHP data to the Services_JSON encoder, which in turn produces the final JSON-formatted output.

This xml2json implementation has three parts:

xml2json.php - A PHP class with two static functions
xml2json_test.php - A test driver to exercise the xml2json conversion function
test1.xml


## xml2json.php

### Using Services_JSON in xml2json.php

first we defined some useful constants. The first line of code imports the Services_JSON implementation. It takes XML data as input, and it converts the XML string into a SimpleXMLElement object, which is sent as input to another (recursive) function in this class. This function then converts the XML elements into a PHP associative array. That array is then passed as an input to the Services_JSON encoder, which gives you the JSON-formatted output.

### Conversion logic in xml2json.php

It uses a recursion technique, It accepts a SimpleXMLElement object as input and conducts a recursive tree walk through the nested XML tree. It stores all the visited XML elements in a PHP associative array. At the end of a successful XML tree walk, this function will convert and store all the XML elements (the root element and all the child elements) in a PHP associative array. For complex XML documents, the resulting PHP array will be equally complex. Once that PHP array is fully constructed, then the Services_JSON encoder can easily convert it to JSON-formatted data.

## xml2json_test.php

### Implement a test driver for xml2json

You can run the program from the command line with an XML filename as a command-line argument:

php -f xml2json_test.php test2.xml

When executed from a command line, the program reads the XML content from a file into a string variable. Then it calls a static function in the xml2json class to get the JSON-formatted result.
