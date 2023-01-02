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

use function array_pop;
use function explode;
use function implode;
use function substr;
use ReflectionClass;

final class TypeName
{
    /**
     * @var ?string
     */
    private $namespaceName;

    /**
     * @var string
     */
    private $simpleName;

    /**
     * @var string
     */
    private $canonicalName;

    public static function fromQualifiedName(string $fullClassName): self
    {
        if ($fullClassName[0] === '\\') {
            $fullClassName = substr($fullClassName, 1);
        }

        $classNameParts = explode('\\', $fullClassName);

        $simpleName    = array_pop($classNameParts);
        $namespaceName = implode('\\', $classNameParts);

        return new self($namespaceName, $simpleName);
    }

    public static function fromReflection(ReflectionClass $type): self
    {
        return new self(
            $type->getNamespaceName(),
            $type->getShortName()
        );
    }

    public function __construct(?string $namespaceName, string $simpleName)
    {
        if ($namespaceName === '') {
            $namespaceName = null;
        }

        $this->namespaceName = $namespaceName;
        $this->simpleName    = $simpleName;

        try {
            $this->canonicalName = (new \ReflectionClass($this->qualifiedName()))->name;
        } catch (\ReflectionException) {
            // If the class represented by instance does not exist, it can't be an
            // alias of existing class. We can safely assume the qualified name is
            // also cannonical name.
            $this->canonicalName = $this->qualifiedName();
        }
    }

    public function namespaceName(): ?string
    {
        return $this->namespaceName;
    }

    public function simpleName(): string
    {
        return $this->simpleName;
    }

    public function qualifiedName(): string
    {
        return $this->namespaceName === null
             ? $this->simpleName
             : $this->namespaceName . '\\' . $this->simpleName;
    }

    public function isNamespaced(): bool
    {
        return $this->namespaceName !== null;
    }

    public function isCanonical(): bool
    {
        return $this->qualifiedName() === $this->canonicalName();
    }

    public function canonicalName(): string
    {
        return $this->canonicalName;
    }
}
