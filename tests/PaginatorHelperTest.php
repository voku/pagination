<?php

use PHPUnit\Framework\TestCase;
use voku\helper\PaginatorHelper;

/**
 * PaginatorHelperTest
 *
 * @internal
 */
final class PaginatorHelperTest extends TestCase
{
    public function testReduceData()
    {
        $testArray = [
            0  => 'test0',
            1  => 'test1',
            2  => 'test2',
            3  => 'test3',
            4  => 'test4',
            5  => 'test5',
            6  => 'test6',
            7  => 'test7',
            8  => 'test8',
            9  => 'test9',
            10 => 'test10',
        ];

        $result = PaginatorHelper::reduceData($testArray, 2, 3);
        $expected = [
            4 => 'test4',
            5 => 'test5',
        ];

        static::assertSame($expected, $result);
    }
}
