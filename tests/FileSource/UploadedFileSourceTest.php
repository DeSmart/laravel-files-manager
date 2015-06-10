<?php

namespace test\DeSmart\Files\FileSource;

use Illuminate\Contracts\Filesystem\Filesystem;
use DeSmart\Files\FileSource\UploadedFileSource;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadedFileSourceTest extends \PHPUnit_Framework_TestCase
{

    public function testGettingName()
    {
        $uploadedFile = $this->getMockBuilder(UploadedFile::class)
            ->disableOriginalConstructor()
            ->getMock();

        $uploadedFile->method('getClientOriginalName')->willReturn('Chrysanthemum.jpg');

        $source = new UploadedFileSource($uploadedFile);
        $this->assertEquals('Chrysanthemum', $source->getName());
    }

    public function testGettingEmptyName()
    {
        $uploadedFile = $this->getMockBuilder(UploadedFile::class)
            ->disableOriginalConstructor()
            ->getMock();

        $uploadedFile->method('getClientOriginalName')->willReturn('');

        $source = new UploadedFileSource($uploadedFile);
        $this->assertNull($source->getName());
    }

    public function testGettingSize()
    {
        $uploadedFile = $this->getMockBuilder(UploadedFile::class)
            ->disableOriginalConstructor()
            ->getMock();

        $uploadedFile->method('getClientSize')->willReturn($size = 1000);

        $source = new UploadedFileSource($uploadedFile);
        $this->assertEquals($size, $source->getSize());
    }

    public function testGettingEmptySize()
    {
        $uploadedFile = $this->getMockBuilder(UploadedFile::class)
            ->disableOriginalConstructor()
            ->getMock();

        $uploadedFile->method('getClientSize')->willReturn(null);

        $source = new UploadedFileSource($uploadedFile);
        $this->assertSame(0, $source->getSize());
    }

    public function testSavingFile()
    {
        $uploadedFile = $this->getMockBuilder(UploadedFile::class)
            ->disableOriginalConstructor()
            ->getMock();

        $uploadedFile->method('getPathname')->willReturn($filePath = __DIR__.'/../../stubs/files/smile.png');

        $contents = file_get_contents($filePath);
        $filesystem = $this->prophesize(Filesystem::class);
        $filesystem->put($destinationPath = 'ab/cd/foo.png', $contents)->shouldBeCalled();

        $source = new UploadedFileSource($uploadedFile);
        $source->save($filesystem->reveal(), $destinationPath);
    }
}
