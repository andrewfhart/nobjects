<?php
namespace NObjects\Tests;
use NObjects\Directory;

class DirectoryTest extends \PHPUnit_Framework_TestCase
{

    public function testLs()
    {
        $current = dirname(__FILE__);
        $ls = Directory::ls($current);
        // check if current file in list
        $this->assertTrue(array_search(__FILE__, $ls) !== false);
    }

    public function testLsOptions()
    {
        $current = dirname(__FILE__);
        $root = dirname(dirname(dirname(__FILE__)));

        $ls = Directory::ls($root);
        $this->assertEquals(3, count($ls));

        $opts = array('showDirs' => true);
        $ls = Directory::ls($root, $opts);

        $this->assertEquals(5, count($ls));

        $opts['showDirs'] = false;
        $opts['recursive'] = true;
        $opts['filter'] = '/.php$/';
        $ls = Directory::ls($root, $opts);
        $this->assertEquals(41, count($ls));

        $opts['group'] = true;
        $ls = Directory::ls($root, $opts);
        $this->assertEquals(8, count($ls));
        $this->assertEquals(11, count($ls[$current]));

        $opts['showDirs'] = true;
        $ls = Directory::ls($root, $opts);
        $this->assertEquals(11, count($ls));
        $this->assertEquals(13, count($ls[$current]));

        $ls = Directory::ls($root, array('showDirs' => true, 'showInvisible' => true));
        $this->assertEquals(7, count($ls));
        $this->assertTrue(array_search($root . DIRECTORY_SEPARATOR . '.git', $ls) !== false);
    }

    public function testLsWithFilename()
    {
        $current = dirname(__FILE__);
        $files = Directory::ls($current);
        $ls = Directory::lsWithFilename($current);
        $phpunit = 'phpunit.xml';

        foreach ($files as $file) {
            $basename = basename($file, '.php');

            if ($basename == $phpunit) {
                continue;
            } else {
                $this->assertArrayHasKey($basename, $ls);
            }

            $this->assertTrue(in_array($file, $ls));

        }

        $current = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..';
        $ls = Directory::lsWithFilename($current, 'xml');
        $this->assertEquals(1, count($ls));
        $this->assertEquals($current . DIRECTORY_SEPARATOR . $phpunit, current($ls));
    }

}
