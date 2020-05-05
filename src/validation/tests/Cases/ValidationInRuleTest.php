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
use Hyperf\Validation\Rules\In;
use HyperfTest\Validation\Cases\fixtures\Values;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class ValidationInRuleTest extends TestCase
{
    public function testItCorrectlyFormatsAStringVersionOfTheRule()
    {
        $rule = new In(['Hyperf', 'Framework', 'PHP']);

        $this->assertEquals('in:"Hyperf","Framework","PHP"', (string) $rule);

        $rule = new In(['Life, the Universe and Everything', 'this is a "quote"']);

        $this->assertEquals('in:"Life, the Universe and Everything","this is a ""quote"""', (string) $rule);

        $rule = new In(["a,b\nc,d"]);

        $this->assertEquals("in:\"a,b\nc,d\"", (string) $rule);

        $rule = Rule::in([1, 2, 3, 4]);

        $this->assertEquals('in:"1","2","3","4"', (string) $rule);

        $rule = Rule::in(collect([1, 2, 3, 4]));

        $this->assertEquals('in:"1","2","3","4"', (string) $rule);

        $rule = Rule::in(new Values());

        $this->assertEquals('in:"1","2","3","4"', (string) $rule);

        $rule = Rule::in('1', '2', '3', '4');

        $this->assertEquals('in:"1","2","3","4"', (string) $rule);
    }
}
