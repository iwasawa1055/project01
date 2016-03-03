<?php

App::uses('InfoBox', 'Model');
App::uses('ArraySorter', 'Model');

class InfoBoxTest extends CakeTestCase
{
    /**
     * @test
     */
    function 配列_単一ソートキー() {
        $actual = [
            ['aa' => 1, 'bb' => 1],
            ['aa' => 3, 'bb' => 3],
            ['aa' => 2, 'bb' => 2],
        ];
        $expected_true = [
            ['aa' => 1, 'bb' => 1],
            ['aa' => 2, 'bb' => 2],
            ['aa' => 3, 'bb' => 3],
        ];
        $expected_false = [
            ['aa' => 3, 'bb' => 3],
            ['aa' => 2, 'bb' => 2],
            ['aa' => 1, 'bb' => 1],
        ];
        ArraySorter::sort($actual, ['aa' => true]);
        $this->assertEmpty(Hash::diff($expected_true, $actual));
        ArraySorter::sort($actual, ['aa' => false]);
        $this->assertEmpty(Hash::diff($expected_false, $actual));
        ArraySorter::sort($actual, ['aa' => true]);
        $this->assertEmpty(Hash::diff($expected_true, $actual));
    }

    /**
     * @test
     */
    function 配列_複数ソートキー() {
        $actual = [
            ['aa' => 1, 'bb' => 1],
            ['aa' => 1, 'bb' => 2],
            ['aa' => 2, 'bb' => 2],
        ];
        $expected_true_true = [
            ['aa' => 1, 'bb' => 1],
            ['aa' => 1, 'bb' => 2],
            ['aa' => 2, 'bb' => 2],
        ];
        $expected_true_false = [
            ['aa' => 1, 'bb' => 2],
            ['aa' => 1, 'bb' => 1],
            ['aa' => 2, 'bb' => 2],
        ];
        $expected_false_false = [
            ['aa' => 2, 'bb' => 2],
            ['aa' => 1, 'bb' => 2],
            ['aa' => 1, 'bb' => 1],
        ];
        $expected_false_true = [
            ['aa' => 2, 'bb' => 2],
            ['aa' => 1, 'bb' => 1],
            ['aa' => 1, 'bb' => 2],
        ];
        ArraySorter::sort($actual, ['aa' => true, 'bb' => true]);
        $this->assertEmpty(Hash::diff($expected_true_true, $actual));
        ArraySorter::sort($actual, ['aa' => true, 'bb' => false]);
        $this->assertEmpty(Hash::diff($expected_true_false, $actual));
        ArraySorter::sort($actual, ['aa' => false, 'bb' => true]);
        $this->assertEmpty(Hash::diff($expected_false_true, $actual));
        ArraySorter::sort($actual, ['aa' => false, 'bb' => false]);
        $this->assertEmpty(Hash::diff($expected_false_false, $actual));
        ArraySorter::sort($actual, ['aa' => true, 'bb' => true]);
        $this->assertEmpty(Hash::diff($expected_true_true, $actual));
    }

    /**
     * @test
     */
    function 多次元配列_複数ソートキー() {
        $actual = [
            ['aa' => 1, 'bb' => 1, ['cc' => 3, 'bb' => 3]],
            ['aa' => 1, 'bb' => 2, ['cc' => 4, 'bb' => 4]],
            ['aa' => 2, 'bb' => 2, ['cc' => 3, 'bb' => 4]],
        ];
        $expected_true_true = [
            ['aa' => 1, 'bb' => 1, ['cc' => 3, 'bb' => 3]],
            ['aa' => 1, 'bb' => 2, ['cc' => 4, 'bb' => 4]],
            ['aa' => 2, 'bb' => 2, ['cc' => 3, 'bb' => 4]],
        ];
        $expected_true_false = [
            ['aa' => 1, 'bb' => 2, ['cc' => 4, 'bb' => 4]],
            ['aa' => 1, 'bb' => 1, ['cc' => 3, 'bb' => 3]],
            ['aa' => 2, 'bb' => 2, ['cc' => 3, 'bb' => 4]],
        ];
        $expected_false_false = [
            ['aa' => 2, 'bb' => 2, ['cc' => 3, 'bb' => 4]],
            ['aa' => 1, 'bb' => 2, ['cc' => 4, 'bb' => 4]],
            ['aa' => 1, 'bb' => 1, ['cc' => 3, 'bb' => 3]],
        ];
        $expected_false_true = [
            ['aa' => 2, 'bb' => 2, ['cc' => 3, 'bb' => 4]],
            ['aa' => 1, 'bb' => 1, ['cc' => 3, 'bb' => 3]],
            ['aa' => 1, 'bb' => 2, ['cc' => 4, 'bb' => 4]],
        ];
        ArraySorter::sort($actual, ['aa' => true, 'bb' => true]);
        $this->assertEmpty(Hash::diff($expected_true_true, $actual));
        ArraySorter::sort($actual, ['aa' => true, 'bb' => false]);
        $this->assertEmpty(Hash::diff($expected_true_false, $actual));
        ArraySorter::sort($actual, ['aa' => false, 'bb' => true]);
        $this->assertEmpty(Hash::diff($expected_false_true, $actual));
        ArraySorter::sort($actual, ['aa' => false, 'bb' => false]);
        $this->assertEmpty(Hash::diff($expected_false_false, $actual));
        ArraySorter::sort($actual, ['aa' => true, 'bb' => true]);
        $this->assertEmpty(Hash::diff($expected_true_true, $actual));
    }

    /**
     * @test
     */
    function 多次元配列_階層単一ソートキー() {
        $actual = [
            ['aa' => 1, 'bb' => 1, 'cc' => ['cc' => 3]],
            ['aa' => 1, 'bb' => 2, 'cc' => ['cc' => 4]],
            ['aa' => 2, 'bb' => 2, 'cc' => ['cc' => 5]],
        ];
        $expected_true = [
            ['aa' => 1, 'bb' => 1, 'cc' => ['cc' => 3]],
            ['aa' => 1, 'bb' => 2, 'cc' => ['cc' => 4]],
            ['aa' => 2, 'bb' => 2, 'cc' => ['cc' => 5]],
        ];
        $expected_false = [
            ['aa' => 2, 'bb' => 2, 'cc' => ['cc' => 5]],
            ['aa' => 1, 'bb' => 2, 'cc' => ['cc' => 4]],
            ['aa' => 1, 'bb' => 1, 'cc' => ['cc' => 3]],
        ];
        ArraySorter::sort($actual, ['cc.cc' => true]);
        $this->assertEmpty(Hash::diff($expected_true, $actual));
        ArraySorter::sort($actual, ['cc.cc' => false]);
        $this->assertEmpty(Hash::diff($expected_false, $actual));
        ArraySorter::sort($actual, ['cc.cc' => true]);
        $this->assertEmpty(Hash::diff($expected_true, $actual));
    }

    /**
     * @test
     */
    function 多次元配列_階層複数ソートキー() {
        $actual = [
            ['aa' => 1, 'bb' => 1, 'cc' => ['cc' => 3, 'bb' => 3]],
            ['aa' => 1, 'bb' => 2, 'cc' => ['cc' => 4, 'bb' => 4]],
            ['aa' => 2, 'bb' => 2, 'cc' => ['cc' => 3, 'bb' => 4]],
        ];
        $expected_true_true = [
            ['aa' => 1, 'bb' => 1, 'cc' => ['cc' => 3, 'bb' => 3]],
            ['aa' => 2, 'bb' => 2, 'cc' => ['cc' => 3, 'bb' => 4]],
            ['aa' => 1, 'bb' => 2, 'cc' => ['cc' => 4, 'bb' => 4]],
        ];
        $expected_true_false = [
            ['aa' => 2, 'bb' => 2, 'cc' => ['cc' => 3, 'bb' => 4]],
            ['aa' => 1, 'bb' => 1, 'cc' => ['cc' => 3, 'bb' => 3]],
            ['aa' => 1, 'bb' => 2, 'cc' => ['cc' => 4, 'bb' => 4]],
        ];
        $expected_false_false = [
            ['aa' => 1, 'bb' => 2, 'cc' => ['cc' => 4, 'bb' => 4]],
            ['aa' => 2, 'bb' => 2, 'cc' => ['cc' => 3, 'bb' => 4]],
            ['aa' => 1, 'bb' => 1, 'cc' => ['cc' => 3, 'bb' => 3]],
        ];
        $expected_false_true = [
            ['aa' => 1, 'bb' => 2, 'cc' => ['cc' => 4, 'bb' => 4]],
            ['aa' => 1, 'bb' => 1, 'cc' => ['cc' => 3, 'bb' => 3]],
            ['aa' => 2, 'bb' => 2, 'cc' => ['cc' => 3, 'bb' => 4]],
        ];
        ArraySorter::sort($actual, ['cc.cc' => true, 'cc.bb' => true]);
        $this->assertEmpty(Hash::diff($expected_true_true, $actual));
        ArraySorter::sort($actual, ['cc.cc' => true, 'cc.bb' => false]);
        $this->assertEmpty(Hash::diff($expected_true_false, $actual));
        ArraySorter::sort($actual, ['cc.cc' => false, 'cc.bb' => true]);
        $this->assertEmpty(Hash::diff($expected_false_true, $actual));
        ArraySorter::sort($actual, ['cc.cc' => false, 'cc.bb' => false]);
        $this->assertEmpty(Hash::diff($expected_false_false, $actual));
        ArraySorter::sort($actual, ['cc.cc' => true, 'cc.bb' => true]);
        $this->assertEmpty(Hash::diff($expected_true_true, $actual));
    }
}
