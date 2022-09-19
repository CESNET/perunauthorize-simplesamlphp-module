# perunauthorize-simplesamlphp-module
[![Latest Stable Version](https://poser.pugx.org/cesnet/simplesamlphp-module-perunauthorize/v/stable)](https://packagist.org/packages/cesnet/simplesamlphp-module-perunauthorize)
[![Latest Unstable Version](https://poser.pugx.org/cesnet/simplesamlphp-module-perunauthorize/v/unstable)](https://packagist.org/packages/cesnet/simplesamlphp-module-perunauthorize)
[![CodeFactor](https://www.codefactor.io/repository/github/cesnet/perunauthorize-simplesamlphp-module/badge)](https://www.codefactor.io/repository/github/cesnet/perunauthorize-simplesamlphp-module)
[![License](https://poser.pugx.org/cesnet/simplesamlphp-module-perunauthorize/license)](https://packagist.org/packages/cesnet/simplesamlphp-module-perunauthorize)

**REPOSITORY HAS BEEN MOVED TO: https://gitlab.ics.muni.cz/perun-proxy-aai/simplesamlphp/simplesamlphp-module-perunauthorize**

This module is a modification of SSP module authorize.
The difference is an option to add placeholders to the unauthorized message, which are replaced with actual values from a request.
For the detailed documentation, see below.

## Instalation

`php composer.phar require cesnet/simplesamlphp-module-perunauthorize`

## Perunauthorize process filter

Perunauthorize is a user authorization filter based on attribute matching. Unauthorized users will be shown a 403 Forbidden page. Configuration options are listed below.

* **deny** (default **false**):
  When set to **false**, user is authorized **in case of** found attribute match.
  When set to **true**, user is authorized **unless** an attribute match is found.
  

* **regex** (default **true**):
  Turn regex pattern matching on or off for the attribute values defined.
  

* **message** (default **null**):
  If set, a user is (when unauthorized) sent to an unauthorized page with a custom text.
  A value of this option has to be a dictionary with the message translated to supported languages.
  User's supported translation is then shown.
  The message can also contain **%SERVICE_NAME%** and **%SERVICE_EMAIL%** as placeholders.
  **%SERVICE_NAME%** is then replaced with a service name.
  **%SERVICE_EMAIL%** is replaced with a value of attribute specified in 'administrationContact' option (see below).
  
  * **Example:**
  ```
  'message' => [
    'en' => 'Example message with a service name: %SERVICE_NAME% and administration contact: %SERVICE_EMAIL%',
    'cs' => 'Příklad zprávy s názvem služby: %SERVICE_NAME% a kontaktem na administrátory: %SERVICE_EMAIL%',
  ]
  ```
 
* **administrationContact** (default **null**):
  A string value containing a name of attribute.
  If is this attribute found in a request, its value is used in the **message** instead of **%SERVICE_EMAIL%** placeholder.
  If **message** does not contain this placeholder, there is no reason to set this option.
  

* Each additional filter configuration option is considered an attribute matching rule.
  For each attribute, you can specify a string or array of strings to match.
  If one of those attributes match one of the rules (OR operator), the user is authorized/unauthorized (depending on the deny config option).
  
