<?php

namespace App\Tests\Unit\Database;

use App\Database\Exceptions\UnexpectedTypeException;
use App\Database\ParameterTypes;
use PHPUnit\Framework\TestCase;

class ParameterTypesTest extends TestCase
{
    public function testTypes()
    {
        $this->assertEquals(ParameterTypes::TYPE_STRING, ParameterTypes::tryFrom("string"));
        $this->assertEquals(ParameterTypes::TYPE_INT, ParameterTypes::tryFrom("integer"));
        $this->assertEquals(ParameterTypes::TYPE_BOOL, ParameterTypes::tryFrom("bool"));
        $this->assertEquals(ParameterTypes::TYPE_NULL, ParameterTypes::tryFrom("null"));
        $this->assertEquals(ParameterTypes::TYPE_FLOAT, ParameterTypes::tryFrom("float"));
        $this->assertEquals(null, ParameterTypes::tryFrom("somerthinf"));
    }

    public function testToPDo()
    {
        $this->assertEquals(\PDO::PARAM_STR, ParameterTypes::tryFrom("string")->toPDO());
        $this->assertEquals(\PDO::PARAM_INT, ParameterTypes::tryFrom("integer")->toPDO());
        $this->assertEquals(\PDO::PARAM_BOOL, ParameterTypes::tryFrom("bool")->toPDO());
        $this->assertEquals(\PDO::PARAM_NULL, ParameterTypes::tryFrom("null")->toPDO());
        $this->assertEquals(\PDO::PARAM_STR, ParameterTypes::tryFrom("float")->toPDO());
    }
}