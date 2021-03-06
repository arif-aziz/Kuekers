<?php
/**
 * BulkSMS PHP implementation
 *
 * @author    Andreas Lutro <anlutro@gmail.com>
 * @license   http://opensource.org/licenses/MIT
 * @package   anlutro/bulk-sms
 */

namespace anlutro\BulkSms;

/**
 * Container class for a single SMS message.
 */
class Message
{
    /**
     * Phone number of the recipient.
     *
     * @var string
     */
    protected $recipient;

    /**
     * The text message to be sent.
     *
     * @var string
     */
    protected $message;

    /**
     * Whether or not the message needs to be concatenated.
     *
     * @var bool
     */
    protected $concat = false;

    /**
     * Where to start concatenating SMSes.
     *
     * @var integer
     */
    protected $concatLimit = 140;

    /**
     * @param $recipient
     * @param $text
     */
    public function __construct($recipient, $text)
    {
        $this->setRecipient($recipient);
        $this->setMessage($text);
    }

    /**
     * Get the recipient.
     *
     * @return string
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * Set the recipient.
     *
     * @param  string|int $recipient
     *
     * @return $this
     */
    protected function setRecipient($recipient)
    {
        $this->recipient = $this->parseNumber($recipient);

        return $this;
    }

    /**
     * Get the message.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set the message.
     *
     * @param  string $message
     *
     * @return $this
     */
    protected function setMessage($message)
    {
        $this->message = $this->encodeMessage($message);

        if (strlen($this->message) > $this->concatLimit) {
            $this->concat = true;
        }

        return $this;
    }

    /**
     * Get how many SMSes the message may have to be concatenated into.
     *
     * @return int
     */
    public function getConcatParts()
    {
        if (!$this->concat) {
            return 1;
        } else {
            return $this->calculateConcat();
        }
    }

    /**
     * Calculate from the message string how many SMSes it may have to span.
     *
     * @return int
     */
    protected function calculateConcat()
    {
        $len = strlen($this->message);
        $i   = $this->concatLimit;
        $j   = 1;

        while ($i < $len) {
            $i += $this->concatLimit;
            $j++;
        }

        return $j;
    }

    /**
     * Parse a phone number.
     *
     * @param  string $number
     *
     * @return string
     */
    protected function parseNumber($number)
    {
        $number = (string) $number;

        // remove whitespaces
        $number = trim($number);
        $number = str_replace(' ', '', $number);

        // remove + in front if exists
        if (substr($number, 0, 1) == '+') {
            $number = substr($number, 1);
        }

        // remove 0s in front if exists
        while (substr($number, 0, 1) === '0') {
            $number = substr($number, 1);
        }

        // we should at this point have a normal number
        if (!is_numeric($number)) {
            throw new \InvalidArgumentException("Invalid SMS recipient: $number");
        }

        // is phone number is less than 9 characters, assume we need to append
        // a country code
        if (strlen($number) <= 8) {
            throw new \InvalidArgumentException(
                "Recipient number is too short. Is the country code missing?: " . $number
            );
        }

        return $number;
    }

    /**
     * Encode a message to the retarded GSM-charset.
     *
     * @param  string $message
     *
     * @return string
     */
    protected function encodeMessage($message)
    {
        $replaceCharacters = array(
            '??' => '0xD0',
            '??' => '0xDE',
            '??' => '0xAC',
            '??' => '0xC2',
            '??' => '0xDB',
            '??' => '0xBA',
            '??' => '0xDD',
            '??' => '0xCA',
            '??' => '0xD4',
            '??' => '0xB1',
            '??' => '0xA1',
            '??' => '0xA3',
            '??' => '0xA4',
            '??' => '0xA5',
            '??' => '0xA7',
            '??' => '0xBF',
            '??' => '0xC4',
            '??' => '0xC5',
            '??' => '0xC6',
            '??' => '0xC7',
            '??' => '0xC9',
            '??' => '0xD1',
            '??' => '0xD6',
            '??' => '0xD8',
            '??' => '0xDC',
            '??' => '0xDF',
            '??' => '0xE0',
            '??' => '0xE4',
            '??' => '0xE5',
            '??' => '0xE6',
            '??' => '0xE8',
            '??' => '0xE9',
            '??' => '0xEC',
            '??' => '0xF1',
            '??' => '0xF2',
            '??' => '0xF6',
            '??' => '0xF8',
            '??' => '0xF9',
            '??' => '0xFC',
        );

        $message = utf8_decode($message);
        $message = str_replace('"', '\"', $message);
        $message = strtr($message, $replaceCharacters);

        return $message;
    }
}
