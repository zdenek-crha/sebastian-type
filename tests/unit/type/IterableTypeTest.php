<?php declare(strict_types=1);
/*
 * This file is part of sebastian/type.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SebastianBergmann\Type;

use PHPUnit\Framework\TestCase;
use SebastianBergmann\Type\TestFixture\Iterator;

/**
 * @covers \SebastianBergmann\Type\IterableType
 * @covers \SebastianBergmann\Type\Type
 *
 * @uses \SebastianBergmann\Type\ObjectType
 * @uses \SebastianBergmann\Type\SimpleType
 * @uses \SebastianBergmann\Type\TypeName
 */
final class IterableTypeTest extends TestCase
{
    /**
     * @var IterableType
     */
    private $type;

    protected function setUp(): void
    {
        $this->type = new IterableType(false);
    }

    public function testHasName(): void
    {
        $this->assertSame('iterable', $this->type->name());
    }

    public function testMayDisallowNull(): void
    {
        $this->assertFalse($this->type->allowsNull());
    }

    public function testMayAllowNull(): void
    {
        $type = new IterableType(true);

        $this->assertTrue($type->allowsNull());
    }

    public function testNullCanBeAssignedToNullableIterable(): void
    {
        $type = new IterableType(true);

        $this->assertTrue($type->isAssignable(new NullType));
    }

    public function testIterableCanBeAssignedToIterable(): void
    {
        $this->assertTrue($this->type->isAssignable(new IterableType(false)));
    }

    public function testArrayCanBeAssignedToIterable(): void
    {
        $this->assertTrue(
            $this->type->isAssignable(
                Type::fromValue([], false)
            )
        );
    }

    public function testIteratorCanBeAssignedToIterable(): void
    {
        $this->assertTrue(
            $this->type->isAssignable(
                Type::fromValue(new Iterator, false)
            )
        );
    }

    public function testSomethingThatIsNotIterableCannotBeAssignedToIterable(): void
    {
        $this->assertFalse(
            $this->type->isAssignable(
                Type::fromValue(null, false)
            )
        );
    }

    public function testCanBeQueriedForType(): void
    {
        $this->assertFalse($this->type->isCallable());
        $this->assertFalse($this->type->isGenericObject());
        $this->assertFalse($this->type->isIntersection());
        $this->assertTrue($this->type->isIterable());
        $this->assertFalse($this->type->isMixed());
        $this->assertFalse($this->type->isNever());
        $this->assertFalse($this->type->isNull());
        $this->assertFalse($this->type->isObject());
        $this->assertFalse($this->type->isSimple());
        $this->assertFalse($this->type->isStatic());
        $this->assertFalse($this->type->isUnion());
        $this->assertFalse($this->type->isUnknown());
        $this->assertFalse($this->type->isVoid());
    }
}
