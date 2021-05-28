<?php

namespace SimpleSAML\Module\perunauthorize\Auth\Process;

use SimpleSAML\Auth\ProcessingFilter;
use SimpleSAML\Error\Exception;
use SimpleSAML\Auth\State;
use SimpleSAML\Module;
use SimpleSAML\Utils\HTTP;
use SimpleSAML\Configuration;
use SimpleSAML\Locale\Translate;

/**
 * Filter to authorize only certain users.
 * See docs directory.
 *
 * @author Pavel Vyskocil vyskocilpavel@muni.cz
 * @author Dominik Baranek baranek@ics.muni.cz
 */
class Perunauthorize extends ProcessingFilter
{

    const DENY = 'deny';
    const REGEX = 'regex';
    const SERVICE_NAME_PLACEHOLDER = '%SERVICE_NAME%';
    const SERVICE_EMAIL_PLACEHOLDER = '%SERVICE_EMAIL%';
    const SP_METADATA = 'SPMetadata';
    const ADMINISTRATION_CONTACT = 'administrationContact';
    const MESSAGE = 'message';
    const NAME = 'name';

    /**
     * Flag to deny/unauthorize the user a attribute filter IS found
     *
     * @var bool
     */
    protected $deny;

    /**
     * Flag to turn the REGEX pattern matching on or off
     *
     * @var bool
     */
    protected $regex;

    /**
     * Array of valid users. Each element is a regular expression. You should
     * user \ to escape special chars, like '.' etc.
     *
     */
    protected $valid_attribute_values = [];

    private $message;

    private $administrationContactAttribute;

    /**
     * Initialize this filter.
     * Validate configuration parameters.
     *
     * @param array $config Configuration information about this filter.
     * @param mixed $reserved For future use.
     * @throws Exception
     */
    public function __construct($config, $reserved)
    {
        parent::__construct($config, $reserved);

        $conf = Configuration::loadFromArray($config);

        // Check for the deny option, get it and remove it
        // Must be bool specifically, if not, it might be for a attrib filter below
        $this->deny = $conf->getBoolean(self::DENY, false);
        unset($config[self::DENY]);

        // Check for the regex option, get it and remove it
        // Must be bool specifically, if not, it might be for a attrib filter below
        $this->regex = $conf->getBoolean(self::REGEX, true);
        unset($config[self::REGEX]);

        $this->administrationContactAttribute = $conf->getString(self::ADMINISTRATION_CONTACT, null);
        unset($config[self::ADMINISTRATION_CONTACT]);

        $this->message = $conf->getArray(self::MESSAGE, null);
        unset($config[self::MESSAGE]);

        foreach ($config as $attribute => $values) {
            if (is_string($values)) {
                $values = [$values];
            }
            if (!is_array($values)) {
                throw new Exception('Filter Pauthorize: Attribute values is neither string nor array: ' .
                    var_export($attribute, true));
            }
            foreach ($values as $value) {
                if (!is_string($value)) {
                    throw new Exception('Filter Pauthorize: Each value should be a string for attribute: ' .
                        var_export($attribute, true) . ' value: ' . var_export($value, true) . ' Config is: ' .
                        var_export($config, true));
                }
            }
            $this->valid_attribute_values[$attribute] = $values;
        }
    }

    /**
     * Apply filter to validate attributes.
     *
     * @param array &$request The current request
     */
    public function process(&$request)
    {
        $authorize = $this->deny;

        if (is_array($request) && array_key_exists("Attributes", $request)) {
            if ($this->message !== null) {
                $translate = new Translate(Configuration::getInstance());
                $this->message = $translate->getPreferredTranslation($this->message);

                $this->message = str_replace(
                    self::SERVICE_NAME_PLACEHOLDER,
                    $translate->getPreferredTranslation($request[self::SP_METADATA][self::NAME]),
                    $this->message
                );

                if (is_string($request[self::SP_METADATA][$this->administrationContactAttribute])) {
                    $request[self::SP_METADATA][$this->administrationContactAttribute] =
                        [$request[self::SP_METADATA][$this->administrationContactAttribute]];
                }

                $this->message = str_replace(
                    self::SERVICE_EMAIL_PLACEHOLDER,
                    $request[self::SP_METADATA][$this->administrationContactAttribute][0],
                    $this->message
                );
            }

            $attributes =& $request['Attributes'];

            foreach ($this->valid_attribute_values as $name => $patterns) {
                if (array_key_exists($name, $attributes)) {
                    foreach ($patterns as $pattern) {
                        $values = $attributes[$name];
                        if (!is_array($values)) {
                            $values = [$values];
                        }
                        foreach ($values as $value) {
                            if ($this->regex) {
                                $matched = preg_match($pattern, $value);
                            } else {
                                $matched = ($value === $pattern);
                            }
                            if ($matched) {
                                $authorize = !$this->deny;
                                break 3;
                            }
                        }
                    }
                }
            }
        }

        if (!$authorize) {
            $this->unauthorized($request);
        }
    }

    /**
     * When the process logic determines that the user is not
     * authorized for this service, then forward the user to
     * an 403 unauthorized page.
     *
     * Separated this code into its own method so that child
     * classes can override it and change the action. Forward
     * thinking in case a "chained" ACL is needed, more complex
     * permission logic.
     *
     * @param array $request
     */
    protected function unauthorized(&$request)
    {
        // Save state and redirect to 403 page

        if (!empty($this->message)) {
            $url = Module::getModuleURL(
                'perunauthorize/perunauthorize_403_custom.php'
            );

            $request['message'] = $this->message;
        } else {
            $url = Module::getModuleURL(
                'perunauthorize/perunauthorize_403.php'
            );
        }

        $id = State::saveState(
            $request,
            'perunauthorize:Perunauthorize'
        );

        HTTP::redirectTrustedURL($url, ['StateId' => $id]);
    }
}
