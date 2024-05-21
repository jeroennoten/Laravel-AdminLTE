<?php

use Illuminate\Support\Facades\File;
use JeroenNoten\LaravelAdminLte\Helpers\CommandHelper;

class CommandHelperTest extends TestCase
{
    /**
     * Holds the source folder path that will be used during the tests.
     *
     * @var string
     */
    protected $sourceFolder;

    /**
     * Holds the target folder path that will be used during the tests.
     *
     * @var string
     */
    protected $targetFolder;

    public function setUp(): void
    {
        parent::setUp();

        // Setup the source and target folders.

        $this->sourceFolder = base_path('foo-folder');
        $this->targetFolder = base_path('foo-folder-copy');
    }

    protected function createFolderStructure($folder, $full = true)
    {
        if (File::isDirectory($folder)) {
            return;
        }

        // Create the root directory.

        File::makeDirectory($folder);

        // Create files inside the root directory.

        $fileName = $folder.'/file1.foo';
        File::put($fileName, $fileName);

        if ($full) {
            $fileName = $folder.'/file2.bar';
            File::put($fileName, $fileName);
        }

        // Create folder indide the root directory.

        File::makeDirectory($folder.'/folder1');

        // Create files inside the subfolder directory.

        $fileName = $folder.'/folder1/file3.foo';
        File::put($fileName, $fileName);

        if ($full) {
            $fileName = $folder.'/folder1/file4.bar';
            File::put($fileName, $fileName);
        }
    }

    protected function clearFolder($folder)
    {
        if (File::isDirectory($folder)) {
            File::deleteDirectory($folder);
        }
    }

    public function testCopyDirectory()
    {
        // Create folder structure.

        $this->createFolderStructure($this->sourceFolder);

        // Make a non recursive copy of the folder.

        $res = CommandHelper::copyDirectory(
            $this->sourceFolder,
            $this->targetFolder
        );

        // Make the assertions.

        $this->assertTrue($res);
        $this->assertDirectoryExists($this->targetFolder);
        $this->assertFileExists($this->targetFolder.'/file1.foo');
        $this->assertFileExists($this->targetFolder.'/file2.bar');
        $this->assertDirectoryDoesNotExist($this->targetFolder.'/folder1');
        $this->assertFileDoesNotExist($this->targetFolder.'/folder1/file3.foo');
        $this->assertFileDoesNotExist($this->targetFolder.'/folder1/file4.bar');

        // Clear the created folders.

        $this->clearFolder($this->sourceFolder);
        $this->clearFolder($this->targetFolder);
    }

    public function testCopyDirectoryWhenSourceNotExists()
    {
        // Try to make a copy of a folder that does not exists.

        $res = CommandHelper::copyDirectory(
            $this->sourceFolder,
            $this->targetFolder
        );

        // Make the assertions.

        $this->assertFalse($res);
        $this->assertDirectoryDoesNotExist($this->sourceFolder);
        $this->assertDirectoryDoesNotExist($this->targetFolder);
    }

    public function testCopyDirectoryWithoutHavingReadPermissions()
    {
        // Create folder structure.

        $this->createFolderStructure($this->sourceFolder);
        chmod($this->sourceFolder, 0300);

        // Try to make a copy of the folder.

        $res = CommandHelper::copyDirectory(
            $this->sourceFolder,
            $this->targetFolder
        );

        // Make assertions.

        $this->assertFalse($res);
        $this->assertDirectoryExists($this->sourceFolder);
        $this->assertDirectoryDoesNotExist($this->targetFolder);

        // Clear the created folders.

        chmod($this->sourceFolder, 0777);
        $this->clearFolder($this->sourceFolder);
    }

    public function testCopyDirectoryWhenSubFolderIsRestricted()
    {
        // Create folder structure.

        $this->createFolderStructure($this->sourceFolder);
        chmod($this->sourceFolder.'/folder1', 0300);

        // Try to make a copy of the folder.

        $res = CommandHelper::copyDirectory(
            $this->sourceFolder,
            $this->targetFolder,
            false,
            true
        );

        // Make assertions.

        $this->assertFalse($res);
        $this->assertDirectoryExists($this->sourceFolder);
        $this->assertDirectoryExists($this->targetFolder);
        $this->assertDirectoryDoesNotExist($this->targetFolder.'/folder1');

        // Clear the created folders.

        chmod($this->sourceFolder.'/folder1', 0777);
        $this->clearFolder($this->sourceFolder);
        $this->clearFolder($this->targetFolder);
    }

    public function testCopyDirectoryUsingFileAsSource()
    {
        // Create folder structure.

        $this->createFolderStructure($this->sourceFolder);

        // Try to copy a file.

        $res = CommandHelper::copyDirectory(
            $this->sourceFolder.'/file1.foo',
            $this->targetFolder
        );

        // Make assertions.

        $this->assertFalse($res);
        $this->assertDirectoryExists($this->sourceFolder);
        $this->assertDirectoryDoesNotExist($this->targetFolder);

        // Clear the created folders.

        $this->clearFolder($this->sourceFolder);
    }

    public function testCopyDirectoryWithRecursiveFlag()
    {
        // Create folder structure.

        $this->createFolderStructure($this->sourceFolder);

        // Make a copy of the folder.

        $res = CommandHelper::copyDirectory(
            $this->sourceFolder,
            $this->targetFolder,
            false,
            true
        );

        // Make assertions.

        $this->assertTrue($res);
        $this->assertDirectoryExists($this->targetFolder);
        $this->assertFileExists($this->targetFolder.'/file1.foo');
        $this->assertFileExists($this->targetFolder.'/file2.bar');
        $this->assertDirectoryExists($this->targetFolder.'/folder1');
        $this->assertFileExists($this->targetFolder.'/folder1/file3.foo');
        $this->assertFileExists($this->targetFolder.'/folder1/file4.bar');

        // Clear the created folders.

        $this->clearFolder($this->sourceFolder);
        $this->clearFolder($this->targetFolder);
    }

    public function testCopyDirectoryWithoutForceFlag()
    {
        // Create folder structure.

        $this->createFolderStructure($this->sourceFolder);

        // Partially create the target folder.

        $this->createFolderStructure($this->targetFolder, false);

        // Make a copy of the folder.

        $res = CommandHelper::copyDirectory(
            $this->sourceFolder,
            $this->targetFolder,
            false,
            true
        );

        // Make assertions.

        $this->assertTrue($res);
        $this->assertDirectoryExists($this->targetFolder);
        $this->assertFileExists($this->targetFolder.'/file1.foo');
        $this->assertFileExists($this->targetFolder.'/file2.bar');
        $this->assertDirectoryExists($this->targetFolder.'/folder1');
        $this->assertFileExists($this->targetFolder.'/folder1/file3.foo');
        $this->assertFileExists($this->targetFolder.'/folder1/file4.bar');

        $this->assertEquals(
            $this->targetFolder.'/file1.foo',
            File::get($this->targetFolder.'/file1.foo')
        );

        $this->assertEquals(
            $this->sourceFolder.'/file2.bar',
            File::get($this->targetFolder.'/file2.bar')
        );

        $this->assertEquals(
            $this->targetFolder.'/folder1/file3.foo',
            File::get($this->targetFolder.'/folder1/file3.foo')
        );

        $this->assertEquals(
            $this->sourceFolder.'/folder1/file4.bar',
            File::get($this->targetFolder.'/folder1/file4.bar')
        );

        // Clear the created folders.

        $this->clearFolder($this->sourceFolder);
        $this->clearFolder($this->targetFolder);
    }

    public function testCopyDirectoryWithForceFlag()
    {
        // Create folder structure.

        $this->createFolderStructure($this->sourceFolder);

        // Partially create the target folder.

        $this->createFolderStructure($this->targetFolder, false);

        // Make a copy of the folder.

        $res = CommandHelper::copyDirectory(
            $this->sourceFolder,
            $this->targetFolder,
            true,
            true
        );

        // Make assertions.

        $this->assertTrue($res);
        $this->assertDirectoryExists($this->targetFolder);
        $this->assertFileExists($this->targetFolder.'/file1.foo');
        $this->assertFileExists($this->targetFolder.'/file2.bar');
        $this->assertDirectoryExists($this->targetFolder.'/folder1');
        $this->assertFileExists($this->targetFolder.'/folder1/file3.foo');
        $this->assertFileExists($this->targetFolder.'/folder1/file4.bar');

        $this->assertEquals(
            $this->sourceFolder.'/file1.foo',
            File::get($this->targetFolder.'/file1.foo')
        );

        $this->assertEquals(
            $this->sourceFolder.'/file2.bar',
            File::get($this->targetFolder.'/file2.bar')
        );

        $this->assertEquals(
            $this->sourceFolder.'/folder1/file3.foo',
            File::get($this->targetFolder.'/folder1/file3.foo')
        );

        $this->assertEquals(
            $this->sourceFolder.'/folder1/file4.bar',
            File::get($this->targetFolder.'/folder1/file4.bar')
        );

        // Clear the created folders.

        $this->clearFolder($this->sourceFolder);
        $this->clearFolder($this->targetFolder);
    }

    public function testCopyDirectoryWithBasicIgnoredFiles()
    {
        // Create folder structure.

        $this->createFolderStructure($this->sourceFolder);

        // Make a copy of the folder.

        $ignores = ['file2.bar', 'file4.bar'];
        $res = CommandHelper::copyDirectory(
            $this->sourceFolder,
            $this->targetFolder,
            true,
            true,
            $ignores
        );

        // Make assertions.

        $this->assertTrue($res);
        $this->assertDirectoryExists($this->targetFolder);
        $this->assertFileExists($this->targetFolder.'/file1.foo');
        $this->assertFileDoesNotExist($this->targetFolder.'/file2.bar');
        $this->assertDirectoryExists($this->targetFolder.'/folder1');
        $this->assertFileExists($this->targetFolder.'/folder1/file3.foo');
        $this->assertFileDoesNotExist($this->targetFolder.'/folder1/file4.bar');

        // Clear the created folders.

        $this->clearFolder($this->sourceFolder);
        $this->clearFolder($this->targetFolder);
    }

    public function testCopyDirectoryWithWildcardIgnoredFiles()
    {
        // Create folder structure.

        $this->createFolderStructure($this->sourceFolder);

        // Make a copy of the folder.

        $ignores = ['*.bar', 'file1.*'];
        $res = CommandHelper::copyDirectory(
            $this->sourceFolder,
            $this->targetFolder,
            true,
            true,
            $ignores
        );

        // Make assertions.

        $this->assertTrue($res);
        $this->assertDirectoryExists($this->targetFolder);
        $this->assertFileDoesNotExist($this->targetFolder.'/file1.foo');
        $this->assertFileDoesNotExist($this->targetFolder.'/file2.bar');
        $this->assertDirectoryExists($this->targetFolder.'/folder1');
        $this->assertFileExists($this->targetFolder.'/folder1/file3.foo');
        $this->assertFileDoesNotExist($this->targetFolder.'/folder1/file4.bar');

        // Clear the created folders.

        $this->clearFolder($this->sourceFolder);
        $this->clearFolder($this->targetFolder);
    }

    public function testCopyDirectoryWithRegexIgnoredFiles()
    {
        // Create folder structure.

        $this->createFolderStructure($this->sourceFolder);

        // Make a copy of the folder.

        $ignores = ['regex:@file[1,3].*@'];
        $res = CommandHelper::copyDirectory(
            $this->sourceFolder,
            $this->targetFolder,
            true,
            true,
            $ignores
        );

        // Make assertions.

        $this->assertTrue($res);
        $this->assertDirectoryExists($this->targetFolder);
        $this->assertFileDoesNotExist($this->targetFolder.'/file1.foo');
        $this->assertFileExists($this->targetFolder.'/file2.bar');
        $this->assertDirectoryExists($this->targetFolder.'/folder1');
        $this->assertFileDoesNotExist($this->targetFolder.'/folder1/file3.foo');
        $this->assertFileExists($this->targetFolder.'/folder1/file4.bar');

        // Clear the created folders.

        $this->clearFolder($this->sourceFolder);
        $this->clearFolder($this->targetFolder);
    }

    public function testCompareEqualDirectories()
    {
        // Create folder structure.

        $this->createFolderStructure($this->sourceFolder);

        // Copy the folder.

        File::copyDirectory($this->sourceFolder, $this->targetFolder);

        // Compare the directories without recursion.

        $this->assertTrue(CommandHelper::compareDirectories(
            $this->sourceFolder,
            $this->targetFolder
        ));

        // Compare the directories with recursion.

        $this->assertTrue(CommandHelper::compareDirectories(
            $this->sourceFolder,
            $this->targetFolder,
            true
        ));

        // Clear the created folders.

        $this->clearFolder($this->sourceFolder);
        $this->clearFolder($this->targetFolder);
    }

    public function testCompareDistinctDirectories()
    {
        // Create folder structure.

        $this->createFolderStructure($this->sourceFolder);
        $this->createFolderStructure($this->targetFolder);

        // Compare the directories without recursion.

        $this->assertFalse(CommandHelper::compareDirectories(
            $this->sourceFolder,
            $this->targetFolder
        ));

        // Compare the directories with recursion.

        $this->assertFalse(CommandHelper::compareDirectories(
            $this->sourceFolder,
            $this->targetFolder,
            true
        ));

        // Clear the created folders.

        $this->clearFolder($this->sourceFolder);
        $this->clearFolder($this->targetFolder);
    }

    public function testCompareDirectoriesWhenDirDoesNotExists()
    {
        // Compare the directories without recursion.

        $this->assertNull(CommandHelper::compareDirectories(
            $this->sourceFolder,
            $this->targetFolder
        ));

        // Create folder structure.

        $this->createFolderStructure($this->sourceFolder);

        // Compare the directories with recursion.

        $this->assertNull(CommandHelper::compareDirectories(
            $this->sourceFolder,
            $this->targetFolder,
            true
        ));

        // Clear the created folders.

        $this->clearFolder($this->sourceFolder);
        $this->clearFolder($this->targetFolder);
    }

    public function testCompareDirectoriesWithBasicFilesIgnored()
    {
        // Create folder structure.

        $this->createFolderStructure($this->sourceFolder);

        // Make a copy of the folder and make some changes.

        File::copyDirectory($this->sourceFolder, $this->targetFolder);
        File::append($this->targetFolder.'/file1.foo', 'FOO');
        File::put($this->targetFolder.'/folder1/file3.foo', 'FOO DATA');

        // Compare the directories without recursion.

        $this->assertFalse(CommandHelper::compareDirectories(
            $this->sourceFolder,
            $this->targetFolder
        ));

        // Compare the directories without recursion ignoring files.

        $this->assertTrue(CommandHelper::compareDirectories(
            $this->sourceFolder,
            $this->targetFolder,
            false,
            ['file1.foo']
        ));

        // Compare the directories with recursion ignoring some files.

        $this->assertFalse(CommandHelper::compareDirectories(
            $this->sourceFolder,
            $this->targetFolder,
            true,
            ['file1.foo']
        ));

        // Compare the directories with recursion ignoring all distinct files.

        $this->assertTrue(CommandHelper::compareDirectories(
            $this->sourceFolder,
            $this->targetFolder,
            true,
            ['file1.foo', 'file3.foo']
        ));

        // Clear the created folders.

        $this->clearFolder($this->sourceFolder);
        $this->clearFolder($this->targetFolder);
    }

    public function testCompareDirectoriesWithWildcardFilesIgnored()
    {
        // Create folder structure.

        $this->createFolderStructure($this->sourceFolder);

        // Make a copy of the folder and make some changes.

        File::copyDirectory($this->sourceFolder, $this->targetFolder);
        File::put($this->targetFolder.'/folder1/file3.foo', 'FOO DATA');
        File::delete($this->targetFolder.'/folder1/file4.bar');

        // Compare the directories without recursion.

        $this->assertTrue(CommandHelper::compareDirectories(
            $this->sourceFolder,
            $this->targetFolder
        ));

        // Compare the directories with recursion ignoring some files.

        $this->assertFalse(CommandHelper::compareDirectories(
            $this->sourceFolder,
            $this->targetFolder,
            true,
            ['*.foo']
        ));

        // Compare the directories with recursion ignoring all distinct files.

        $this->assertTrue(CommandHelper::compareDirectories(
            $this->sourceFolder,
            $this->targetFolder,
            true,
            ['*.foo', 'file4.*']
        ));

        // Clear the created folders.

        $this->clearFolder($this->sourceFolder);
        $this->clearFolder($this->targetFolder);
    }

    public function testCompareDirectoriesWithRegexFilesIgnored()
    {
        // Create folder structure.

        $this->createFolderStructure($this->sourceFolder);

        // Make a copy of the folder and make some changes.

        File::copyDirectory($this->sourceFolder, $this->targetFolder);
        File::put($this->targetFolder.'/folder1/file3.foo', 'FOO DATA');
        File::delete($this->targetFolder.'/folder1/file4.bar');

        // Compare the directories without recursion.

        $this->assertTrue(CommandHelper::compareDirectories(
            $this->sourceFolder,
            $this->targetFolder
        ));

        // Compare the directories with recursion ignoring some files.

        $this->assertFalse(CommandHelper::compareDirectories(
            $this->sourceFolder,
            $this->targetFolder,
            true,
            ['regex:/file.*foo/']
        ));

        // Compare the directories with recursion ignoring all distinct files.

        $this->assertTrue(CommandHelper::compareDirectories(
            $this->sourceFolder,
            $this->targetFolder,
            true,
            ['regex:@file[3,4].*@']
        ));

        // Clear the created folders.

        $this->clearFolder($this->sourceFolder);
        $this->clearFolder($this->targetFolder);
    }

    public function testGetPackagePath()
    {
        $this->assertEquals(
            realpath(CommandHelper::getPackagePath()),
            realpath(__DIR__.'/../../')
        );

        $this->assertEquals(
            realpath(CommandHelper::getPackagePath('public')),
            realpath(__DIR__.'/../../public')
        );
    }

    public function testGetStubPath()
    {
        $this->assertEquals(
            realpath(CommandHelper::getStubPath()),
            realpath(__DIR__.'/../../src/Console/stubs')
        );

        $this->assertEquals(
            realpath(CommandHelper::getStubPath('home.stub')),
            realpath(__DIR__.'/../../src/Console/stubs/home.stub')
        );
    }

    public function testGetViewsPath()
    {
        $this->assertEquals(
            realpath(CommandHelper::getViewPath()),
            realpath(resource_path('views'))
        );

        $this->assertEquals(
            realpath(CommandHelper::getViewPath('home.blade.php')),
            realpath(resource_path('views/home.blade.php'))
        );
    }
}
