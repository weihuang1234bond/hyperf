<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace HyperfTest\Validation\Cases;

use Hyperf\Validation\Rule;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ValidationRuleTest extends TestCase
{
    public function testMacroable()
    {
        // phone macro : validate a phone number
        Rule::macro('phone', function () {
            return 'regex:/^([0-9\s\-\+\(\)]*)$/';
        });
        $c = Rule::phone();
        $this->assertSame('regex:/^([0-9\s\-\+\(\)]*)$/', $c);
    }
}
