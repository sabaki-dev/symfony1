<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfValidatorEmail validates emails.
 *
 * @package    symfony
 * @subpackage validator
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id$
 */
class sfValidatorEmail extends sfValidatorRegex
{
  const REGEX_EMAIL = '/^([^@\s]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i';

  /**
   * @see sfValidatorRegex
   */
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);

    $this->setOption('pattern', self::REGEX_EMAIL);
    $this->addOption('check_domain', false);
  }

  protected function doClean($value)
  {
    $clean = parent::doClean($value);

    if ($this->getOption('check_domain') && function_exists('checkdnsrr'))
    {
      $tokens = explode('@', $clean);
      if (!checkdnsrr($tokens[1], 'MX') && !checkdnsrr($tokens[1], 'A'))
      {
        throw new sfValidatorError($this, 'invalid', array('value' => $clean));
      }
    }

    return $clean;
  }
}
