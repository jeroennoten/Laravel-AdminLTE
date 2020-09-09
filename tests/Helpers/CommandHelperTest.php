<?php

use Illuminate\Support\Facades\File;
use JeroenNoten\LaravelAdminLte\Helpers\CommandHelper;

class CommandHelperTest extends TestCase
{
    protected $sourceFolder = 'test-folder';
    protected $targetFolder = 'test-folder-copy';

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

        // Check the structure is created.

        $this->assertDirectoryExists($folder);
        $this->assertFileExists($folder.'/file1.foo');
        $this->assertDirectoryExists($folder.'/folder1');
        $this->assertFileExists($folder.'/folder1/file3.foo');

        if ($full) {
            $this->assertFileExists($folder.'/file2.bar');
            $this->assertFileExists($folder.'/folder1/file4.bar');
        }
    }

    protected function clearFolder($folder)
    {
        if (File::isDirectory($folder)) {
            File::deleteDirectory($folder);
        }
    }

    public function testEnsureDirectoryExists()
    {
        // Ensure the folder do not exists.

        $this->clearFolder($this->sourceFolder);
        $this->assertDirectoryNotExists($this->sourceFolder);

        // Now, ensure the folder exists.

        CommandHelper::ensureDirectoryExists($this->sourceFolder);

        $this->assertDirectoryExists($this->sourceFolder);

        // Clear the created folder.

        $this->clearFolder($this->sourceFolder);
    }

    public function testEnsureDirectoryExistsWithSubfolders()
    {
        $folder = $this->sourceFolder.'/folder1/folder2';

        // Ensure the root folder do not exists.

        $this->clearFolder($this->sourceFolder);
        $this->assertDirectoryNotExists($this->sourceFolder);

        // Now, ensure the subfolder exists.

        CommandHelper::ensureDirectoryExists($folder);

        $this->assertDirectoryExists($this->sourceFolder);
        $this->assertDirectoryExists($folder);

        // Clean the created folder.

        $this->clearFolder($this->sourceFolder);
    }

    public function testCopyDirectory()
    {
        // Create folder structure.

        $this->createFolderStructure($this->sourceFolder);

        // Make a copy of the folder.

        CommandHelper::copyDirectory($this->sourceFolder, $this->targetFolder);

        $this->assertDirectoryExists($this->targetFolder);
        $this->assertFileExists($this->targetFolder.'/file1.foo');
        $this->assertFileExists($this->targetFolder.'/file2.bar');
        $this->assertDirectoryNotExists($this->targetFolder.'/folder1');
        $this->assertFileNotExists($this->targetFolder.'/folder1/file3.foo');
        $this->assertFileNotExists($this->targetFolder.'/folder1/file4.bar');

        // Clear the created folders.

        $this->clearFolder($this->sourceFolder);
        $this->clearFolder($this->targetFolder);
    }

    public function testCopyDirectoryWhenSourceNotExists()
    {
        // Make a copy of the folder.

        CommandHelper::copyDirectory($this->sourceFolder, $this->targetFolder);

        $this->assertDirectoryNotExists($this->sourceFolder);
        $this->assertDirectoryNotExists($this->targetFolder);
    }

    public function testCopyDirectoryWhenSourceRestricted()
    {
        // Create folder structure.

        $this->createFolderStructure($this->sourceFolder);
        chmod($this->sourceFolder, 0300);

        // Make a copy of the folder.

        CommandHelper::copyDirectory($this->sourceFolder, $this->targetFolder);

        $this->assertDirectoryExists($this->sourceFolder);
        $this->assertDirectoryNotExists($this->targetFolder);

        // Try to copy a file.

        chmod($this->sourceFolder, 0755);
        CommandHelper::copyDirectory(
            $this->sourceFolder.'/file1.foo',
            $this->targetFolder
        );

        $this->assertDirectoryExists($this->sourceFolder);
        $this->assertDirectoryNotExists($this->targetFolder);

        // Clear the created folders.

        $this->clearFolder($this->sourceFolder);
    }

    public function testCopyDirectoryRecursive()
    {
        // Create folder structure.

        $this->createFolderStructure($this->sourceFolder);

        // Make a copy of the folder.

        CommandHelper::copyDirectory(
            $this->sourceFolder,
            $this->targetFolder,
            false, true
        );

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

    public function testCopyDirectoryWithoutForce()
    {
        // Create folder structure.

        $this->createFolderStructure($this->sourceFolder);

        // Partially create the target folder.

        $this->createFolderStructure($this->targetFolder, false);

        // Make a copy of the folder.

        CommandHelper::copyDirectory(
            $this->sourceFolder,
            $this->targetFolder,
            false, true
        );

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

    public function testCopyDirectoryWithForce()
    {
        // Create folder structure.

        $this->createFolderStructure($this->sourceFolder);

        // Partially create the target folder.

        $this->createFolderStructure($this->targetFolder, false);

        // Make a copy of the folder.

        CommandHelper::copyDirectory(
            $this->sourceFolder,
            $this->targetFolder,
            true, true
        );

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
        CommandHelper::copyDirectory(
            $this->sourceFolder,
            $this->targetFolder,
            true, true, $ignores
        );

        $this->assertDirectoryExists($this->targetFolder);
        $this->assertFileExists($this->targetFolder.'/file1.foo');
        $this->assertFileNotExists($this->targetFolder.'/file2.bar');
        $this->assertDirectoryExists($this->targetFolder.'/folder1');
        $this->assertFileExists($this->targetFolder.'/folder1/file3.foo');
        $this->assertFileNotExists($this->targetFolder.'/folder1/file4.bar');

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
        CommandHelper::copyDirectory(
            $this->sourceFolder,
            $this->targetFolder,
            true, true, $ignores
        );

        $this->assertDirectoryExists($this->targetFolder);
        $this->assertFileNotExists($this->targetFolder.'/file1.foo');
        $this->assertFileNotExists($this->targetFolder.'/file2.bar');
        $this->assertDirectoryExists($this->targetFolder.'/folder1');
        $this->assertFileExists($this->targetFolder.'/folder1/file3.foo');
        $this->assertFileNotExists($this->targetFolder.'/folder1/file4.bar');

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
        CommandHelper::copyDirectory(
            $this->sourceFolder,
            $this->targetFolder,
            true, true, $ignores
        );

        $this->assertDirectoryExists($this->targetFolder);
        $this->assertFileNotExists($this->targetFolder.'/file1.foo');
        $this->assertFileExists($this->targetFolder.'/file2.bar');
        $this->assertDirectoryExists($this->targetFolder.'/folder1');
        $this->assertFileNotExists($this->targetFolder.'/folder1/file3.foo');
        $this->assertFileExists($this->targetFolder.'/folder1/file4.bar');

        // Clear the created folders.

        $this->clearFolder($this->sourceFolder);
        $this->clearFolder($this->targetFolder);
    }

    public function testCompareDirectoriesWhenEqual()
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

    public function testCompareDirectoriesWhenNotEquals()
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

    public function testCompareDirectoriesWhenDestinyNotExists()
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

    public function testCompareDirectoriesWhenBasicFilesIgnored()
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

        // Compare the directories with recursion ignoring files.

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

    public function testCompareDirectoriesWhenWildcardFilesIgnored()
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

        // Compare the directories with recursion ignoring files.

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

    public function testCompareDirectoriesWhenRegexFilesIgnored()
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

        // Compare the directories with recursion ignoring files.

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

    public function testRemoveDirectory()
    {
        // Create folder structure.

        $this->createFolderStructure($this->sourceFolder);

        // Remove the created folder.

        if (File::isDirectory($this->sourceFolder)) {
            CommandHelper::removeDirectory($this->sourceFolder);
        }

        $this->assertDirectoryNotExists($this->sourceFolder);
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
