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

namespace Hyperf\GraphQL\Annotation;

use Hyperf\Di\Annotation\AnnotationInterface;

/**
 * @Annotation
 * @Target({"METHOD"})
 * @Attributes({
 *     @Attribute("outputType", type="string"),
 * })
 */
class Field extends \TheCodingMachine\GraphQLite\Annotations\Field implements AnnotationInterface
{
    use AnnotationTrait;
}
