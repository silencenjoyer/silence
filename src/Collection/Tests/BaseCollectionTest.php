<?php

declare(strict_types=1);

namespace Silence\Collection\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Silence\Collection\BaseCollection;

class BaseCollectionTest extends TestCase
{
    /**
     * Fabric method for tested class.
     *
     * @param BaseCollection|array $data
     * @return BaseCollection
     */
    protected function createCollection(BaseCollection|array $data = []): BaseCollection
    {
        return new BaseCollection($data);
    }

    /**
     * Array data provider for tests.
     *
     * @return list<array>
     */
    public static function arrayDataProvider(): array
    {
        return [
            [
                ['a' => 1, 'b' => 2],
            ],
        ];
    }

    /**
     * Data provider for merging methods.
     *
     * @return list<array> Must return list of 3 array elements:
     *  - First element for merging
     *  - Second element for merging
     *  - Expected merge result
     */
    public static function mergeDataProvider(): array
    {
        return [
            [
                ['a' => 1, 'b' => 2], // First element
                ['b' => 3, 'c' => 4], // Second element
                ['a' => 1, 'b' => [2, 3], 'c' => 4], // Expected merge result
            ],
        ];
    }

    #[DataProvider('arrayDataProvider')]
    public function testConstructorWithArray(array $data): void
    {
        $collection = $this->createCollection($data);

        $this->assertSame($data, $collection->getArrayCopy());
    }

    #[DataProvider('arrayDataProvider')]
    public function testConstructorWithCollection(array $data): void
    {
        $collection1 = $this->createCollection($data);
        $collection2 = $this->createCollection($collection1);

        $this->assertSame($data, $collection2->getArrayCopy());
    }

    #[DataProvider('arrayDataProvider')]
    public function testGetIterator(array $data): void
    {
        $collection = $this->createCollection($data);

        $iterator = $collection->getIterator();

        $result = [];
        foreach ($iterator as $key => $value) {
            $result[$key] = $value;
        }

        $this->assertSame($data, $result);
    }

    public function testArrayAccess(): void
    {
        $collection = $this->createCollection();

        // Test offsetSet and offsetGet
        $collection['a'] = 1;

        // Test offsetExists
        $this->assertTrue(isset($collection['a']));

        $this->assertSame(1, $collection['a']);

        $this->assertFalse(isset($collection['b']));

        // Test offsetUnset
        unset($collection['a']);
        $this->assertFalse(isset($collection['a']));
    }

    #[DataProvider('arrayDataProvider')]
    public function testCount(array $data): void
    {
        $count = count($data);

        $collection = $this->createCollection($data);
        $this->assertSame($count, $collection->count());

        $collection->remove(array_key_first($data));
        $this->assertSame($count - 1, $collection->count());
    }

    public function testSetAndGet(): void
    {
        $collection = $this->createCollection();

        $collection->set('key1', 'value1');
        $this->assertSame('value1', $collection->get('key1'));

        $this->assertNull($collection->get('nonexistent'));
        $this->assertSame('default', $collection->get('nonexistent', 'default'));
    }

    public function testAppend(): void
    {
        $collection = $this->createCollection();
        $collection->append('first');
        $collection->append('second');

        $this->assertSame('first', $collection->get(0));
        $this->assertSame('second', $collection->get(1));
    }

    public function testRemove(): void
    {
        $collection = $this->createCollection(['a' => 1, 'b' => 2]);

        $collection->remove('a');
        $this->assertFalse($collection->has('a'));
        $this->assertTrue($collection->has('b'));
    }

    public function testHas(): void
    {
        $collection = $this->createCollection(['a' => 1]);

        $this->assertTrue($collection->has('a'));
        $this->assertFalse($collection->has('b'));
    }

    public function testEach(): void
    {
        $collection = $this->createCollection([1, 2, 3]);
        $sum = 0;

        $collection->each(function (int $item) use (&$sum): void {
            $sum += $item;
        });

        $this->assertSame(6, $sum);
    }

    public function testMap(): void
    {
        $collection = $this->createCollection([1, 2, 3]);
        $mapped = $collection->map(function (int $item): int {
            return $item * 2;
        });

        $this->assertSame([2, 4, 6], $mapped->getArrayCopy());
    }

    #[DataProvider('mergeDataProvider')]
    public function testMerge(array $first, array $second, array $expected): void
    {
        $collection1 = $this->createCollection($first);
        $collection2 = $this->createCollection($second);

        $merged = $collection1->merge($collection2);

        $this->assertSame($expected, $merged->getArrayCopy());
    }

    #[DataProvider('mergeDataProvider')]
    public function testMergeArray(array $first, array $second, array $expected): void
    {
        $collection = $this->createCollection($first);

        $merged = $collection->mergeArray($second);

        $this->assertSame($expected, $merged->getArrayCopy());
    }

    public function testChainingMethods(): void
    {
        $collection = $this->createCollection();

        $result = $collection
            ->append(1)
            ->append(2)
            ->map(fn(int $x): int => $x * 2)
            ->each(fn(int $x): null => null);

        $this->assertSame([2, 4], $result->getArrayCopy());
    }
}
