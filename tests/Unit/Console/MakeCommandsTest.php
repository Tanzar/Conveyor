<?php

namespace Tanzar\Conveyor\Tests\Unit\Console;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Tanzar\Conveyor\Tests\TestCase;

class MakeCommandsTest extends TestCase
{

    public function test_make_conveyor(): void
    {
        // destination path of the Foo class
        $fooClass = app_path('Conveyor/MyCore.php');

        // make sure we're starting from a clean state
        if (File::exists($fooClass)) {
            unlink($fooClass);
        }

        $this->assertFalse(File::exists($fooClass));

        // Run the make command
        Artisan::call('make:conveyor MyCore');

        // Assert a new file is created
        $this->assertTrue(File::exists($fooClass));

        // Assert the file contains the right contents
        $expectedContents = <<<CLASS
<?php

namespace App\Conveyor;

use Tanzar\Conveyor\Configs\ConveyorConfigInterface;
use Tanzar\Conveyor\Core\ConveyorCore;

class MyCore extends ConveyorCore
{
    protected function setup(ConveyorConfigInterface \$config): void
    {

    }

    protected function format(): array
    {
        return [];
    }

}\n
CLASS;

        $this->assertEquals($expectedContents, file_get_contents($fooClass));
    
    }

    public function test_make_conveyor_table(): void
    {
        // destination path of the Foo class
        $fooClass = app_path('Conveyor/MyTable.php');

        // make sure we're starting from a clean state
        if (File::exists($fooClass)) {
            unlink($fooClass);
        }

        $this->assertFalse(File::exists($fooClass));

        // Run the make command
        Artisan::call('make:conveyor-table MyTable');

        // Assert a new file is created
        $this->assertTrue(File::exists($fooClass));

        // Assert the file contains the right contents
        $expectedContents = <<<CLASS
<?php

namespace App\Conveyor;

use Tanzar\Conveyor\Configs\ConveyorConfigInterface;
use Tanzar\Conveyor\Table\Frame\Columns;
use Tanzar\Conveyor\Table\Frame\Rows;
use Tanzar\Conveyor\Table\Table;

class MyTable extends Table
{
    public function setupRows(Rows \$rows): void
    {

    }

    public function setupColumns(Columns \$columns): void
    {

    }

    protected function setup(ConveyorConfigInterface \$config): void
    {

    }

    protected function format(): array
    {
        return [];
    }

}\n
CLASS;

        $this->assertEquals($expectedContents, file_get_contents($fooClass));
    
    }
}
