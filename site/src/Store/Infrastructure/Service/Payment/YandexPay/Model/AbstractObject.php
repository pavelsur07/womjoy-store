<?php

declare(strict_types=1);

namespace App\Store\Infrastructure\Service\Payment\YandexPay\Model;

use ArrayAccess;
use DateTime;
use JsonSerializable;
use ReturnTypeWillChange;
use Traversable;

use function lcfirst;
use function str_replace;
use function ucwords;

abstract class AbstractObject implements ArrayAccess, JsonSerializable
{

    /**
     * @var array Свойства установленные пользователем
     */
    private array $unknownProperties = [];


    public function __construct(?array $data = [])
    {
        if (!empty($data) && is_array($data)) {
            $this->fromArray($data);
        }
    }

    /**
     * Возвращает значение свойства.
     *
     * @param string $propertyName Имя свойства
     *
     * @return mixed Значение свойства
     */
    public function __get(string $propertyName): mixed
    {
        return $this->offsetGet($propertyName);
    }

    /**
     * Устанавливает значение свойства.
     *
     * @param string $propertyName Имя свойства
     * @param mixed $value Значение свойства
     */
    public function __set(string $propertyName, mixed $value): void
    {
        $this->offsetSet($propertyName, $value);
    }

    /**
     * Проверяет наличие свойства.
     *
     * @param string $propertyName Имя проверяемого свойства
     *
     * @return bool True если свойство имеется, false если нет
     */
    public function __isset(string $propertyName): bool
    {
        return $this->offsetExists($propertyName);
    }

    /**
     * Удаляет свойство.
     *
     * @param string $propertyName Имя удаляемого свойства
     */
    public function __unset(string $propertyName): void
    {
        $this->offsetUnset($propertyName);
    }

    /**
     * Проверяет наличие свойства.
     *
     * @param string $offset Имя проверяемого свойства
     *
     * @return bool True если свойство имеется, false если нет
     */
    #[ReturnTypeWillChange]
    public function offsetExists(mixed $offset): bool
    {
        $method = 'get' . ucfirst($offset);
        if (method_exists($this, $method)) {
            return true;
        }
        $method = 'get' . self::matchPropertyName($offset);
        if (method_exists($this, $method)) {
            return true;
        }

        return array_key_exists($offset, $this->unknownProperties);
    }

    /**
     * Возвращает значение свойства.
     *
     * @param string $offset Имя свойства
     *
     * @return mixed Значение свойства
     */
    #[ReturnTypeWillChange]
    public function offsetGet(mixed $offset): mixed
    {
        if ($offset === 'validator') {
            return null;
        }
        $method = 'get' . ucfirst($offset);
        if (method_exists($this, $method)) {
            return $this->{$method}();
        }
        $method = 'get' . self::matchPropertyName($offset);
        if (method_exists($this, $method)) {
            return $this->{$method}();
        }

        return $this->unknownProperties[$offset] ?? null;
    }

    /**
     * Устанавливает значение свойства.
     *
     * @param string $offset Имя свойства
     * @param mixed $value Значение свойства
     */
    #[ReturnTypeWillChange]
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($offset === 'validator') {
            return;
        }
        $method = 'set' . ucfirst($offset);
        if (method_exists($this, $method)) {
            $this->{$method}($value);
        } else {
            $method = 'set' . self::matchPropertyName($offset);
            if (method_exists($this, $method)) {
                $this->{$method}($value);
            } else {
                $this->unknownProperties[$offset] = $value;
            }
        }
    }

    /**
     * Удаляет свойство.
     *
     * @param string $offset Имя удаляемого свойства
     */
    #[ReturnTypeWillChange]
    public function offsetUnset(mixed $offset): void
    {
        if ($offset === 'validator') {
            return;
        }
        $method = 'set' . ucfirst($offset);
        if (method_exists($this, $method)) {
            $this->{$method}(null);
        } else {
            $method = 'set' . self::matchPropertyName($offset);
            if (method_exists($this, $method)) {
                $this->{$method}(null);
            } else {
                unset($this->unknownProperties[$offset]);
            }
        }
    }

    /**
     * Устанавливает значения свойств текущего объекта из массива.
     *
     * @param array|Traversable $sourceArray Ассоциативный массив с настройками
     */
    public function fromArray(iterable $sourceArray): void
    {
        foreach ($sourceArray as $key => $value) {
            $this->offsetSet($key, $value);
        }
    }

    /**
     * Возвращает ассоциативный массив со свойствами текущего объекта для его дальнейшей JSON сериализации
     * Является алиасом метода AbstractObject::jsonSerialize().
     *
     * @return array Ассоциативный массив со свойствами текущего объекта
     */
    public function toArray(): array
    {
        return $this->jsonSerialize();
    }

    /**
     * Возвращает ассоциативный массив со свойствами текущего объекта для его дальнейшей JSON сериализации.
     *
     * @return array Ассоциативный массив со свойствами текущего объекта
     */
    #[ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        $excludedMethods = ['getUnknownProperties', 'getIterator', 'getValidator'];
        $result = [];
        foreach (get_class_methods($this) as $method) {
            if (0 === strncmp('get', $method, 3)) {
                if (in_array($method, $excludedMethods)) {
                    continue;
                }

//                $property = strtolower(preg_replace('/[A-Z]/', '_\0', lcfirst(substr($method, 3))));
                $property = lcfirst(str_replace('_', '', ucwords(lcfirst(substr($method, 3)), '_')));
                $value = $this->serializeValueToJson($this->{$method}());
                if (null !== $value) {
                    $result[$property] = $value;
                }
            }
        }
        if (!empty($this->unknownProperties)) {
            foreach ($this->unknownProperties as $property => $value) {
                if (!array_key_exists($property, $result)) {
                    $result[$property] = $this->serializeValueToJson($value);
                }
            }
        }

        return $result;
    }

    private function serializeValueToJson($value)
    {
        if (null === $value || is_scalar($value)) {
            return $value;
        }
        if (is_array($value)) {
            $array = [];
            foreach ($value as $key => $item) {
                if ('validator' === $key) {
                    continue;
                }
                $array[$key] = $this->serializeValueToJson($item);
            }

            return $array;
        }
        if ($value instanceof JsonSerializable) {
            return $value->jsonSerialize();
        }
        if ($value instanceof DateTime) {
            return $value->format('Y-m-d\\TH:i:s.v\\Z');
        }

        return $value;
    }

    private static function matchPropertyName(string $property): string
    {
        return preg_replace('/_(\w)/', '\1', $property);
    }
}
