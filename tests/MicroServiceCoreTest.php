<?php
/**
 * @file
 * Contains \MicroServiceCoreTest.
 */

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use LushDigital\MicroServiceCore\Enum\BaseEnum;
use LushDigital\MicroServiceCore\Helpers\MicroServiceHelper;
use LushDigital\MicroServiceCore\Pagination\Paginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Test the core microservice functionality.
 *
 * Base functionality includes the info endpoint, health endpoint and response
 * formatter.
 */
class MicroServiceCoreTest extends PHPUnit_Framework_TestCase
{
    /**
     * Type value to pass to the JSON response formatter.
     *
     * @var string
     */
    protected $jsonResponseDataType = 'tests';

    /**
     * Test data to pass to the JSON response formatter.
     *
     * @var array
     */
    protected $jsonResponseData = [
        'tests' => 'ok',
        'framework' => 'Lumen',
    ];

    /**
     * Test data to validate th enum functionality.
     *
     * @var array
     */
    protected $expectedEnumValues = [
        'FOO' => 'bar',
        'BAZ' => 'qux'
    ];

    /**
     * Get the expected service information array.
     *
     * @return array
     */
    protected function getExpectedServiceInfo()
    {
        return [
            'service_name' => env('SERVICE_NAME'),
            'service_type' => env('SERVICE_TYPE'),
            'service_scope' => env('SERVICE_SCOPE'),
            'service_version' => env('SERVICE_VERSION')
        ];
    }

    /**
     * Get the expected format for an example JSON response (pre encode).
     *
     * @return stdClass
     */
    protected function getExpectedJsonResponse()
    {
        $expectedResponse = new stdClass();
        $expectedResponse->status = 'ok';
        $expectedResponse->code = 200;
        $expectedResponse->message = '';
        $expectedResponse->data = new stdClass();
        $expectedResponse->data->{$this->jsonResponseDataType} = $this->jsonResponseData;

        return $expectedResponse;
    }

    /**
     * Get the expected format for an example JSON response (pre encode).
     *
     * This is the expected format when no data is supplied.
     *
     * @return stdClass
     */
    protected function getExpectedJsonResponseNoData()
    {
        $expectedResponse = new stdClass();
        $expectedResponse->status = 'ok';
        $expectedResponse->code = 200;
        $expectedResponse->message = '';
        $expectedResponse->data = new stdClass();
        $expectedResponse->data->{$this->jsonResponseDataType} = [];

        return $expectedResponse;
    }

    /**
     * Get the expected format for an exception JSON response (pre encode).
     *
     * @param string $exceptionType
     * @return stdClass
     */
    protected function getExpectedExceptionJsonResponse($exceptionType)
    {
        $expectedResponse = new stdClass();
        $expectedResponse->status = 'fail';

        // Return the correct expected data based on the type of exception.
        switch ($exceptionType) {
            case HttpException::class:
                $expectedResponse->code = 404;
                $expectedResponse->message = 'Not Found';

                break;

            case ModelNotFoundException::class:
                $expectedResponse->code = 404;
                $expectedResponse->message = 'Example not found';
                break;

            case ValidationException::class:
                $expectedResponse->code = 422;
                $expectedResponse->message = '';
                break;

            default:
                $expectedResponse->code = 500;
                $expectedResponse->message = '';
        }

        return $expectedResponse;
    }

    /**
     * Check the JSON response formatter is providing a valid response.
     *
     * @return void
     */
    public function testJsonResponse()
    {
        // Build the test response data.
        $jsonResponse = MicroServiceHelper::jsonResponseFormatter($this->jsonResponseDataType, $this->jsonResponseData, 200, 'ok');

        $this->assertEquals($jsonResponse, $this->getExpectedJsonResponse());
    }

    /**
     * Check the microservice helper is providing the correct service info.
     *
     * @return void
     */
    public function testServiceInfo()
    {
        $this->assertEquals($this->getExpectedServiceInfo(), MicroServiceHelper::getServiceInfo());
    }

    /**
     * Check the JSON response formatter is providing a valid response.
     *
     * Test the response structure with no data provided.
     *
     * @return void
     */
    public function testJsonResponseNoData()
    {
        // Build the test response data.
        $jsonResponse = MicroServiceHelper::jsonResponseFormatter($this->jsonResponseDataType, null, 200, 'ok');

        $this->assertEquals($jsonResponse, $this->getExpectedJsonResponseNoData());
    }

    /**
     * Check the JSON response formatter is failing to provide valid response.
     *
     * Ensure that the formatter is throwing an error when trying to build a
     * response with data but no type.
     *
     * @return void
     */
    public function testJsonResponseWithDataNoType()
    {
        // We expect the formatter to throw an exception.
        $this->expectException(Exception::class);

        // Try to create a response with no data or type.
        MicroServiceHelper::jsonResponseFormatter('', $this->jsonResponseData, 200, 'ok');
    }

    /**
     * Test the Enum class is working.
     *
     * @return void
     */
    public function testEnum()
    {
        $this->assertEquals($this->expectedEnumValues, TestEnum::getAllowedValues());
        $this->assertEquals(array_keys($this->expectedEnumValues), TestEnum::getKeys());
    }

    /**
     * Test the date handling trait.
     *
     * @return void
     */
    public function testDateTrait()
    {
        $dateExample = new DateExample;

        // Test the date validation.
        $this->assertTrue($dateExample->validDate('Y-m-d', (new DateTime())->format('Y-m-d')));
        $this->assertFalse($dateExample->validDate('y-M-d', (new DateTime())->format('Y-m-d')));
    }

    /**
     * Test the exception handling trait.
     *
     * @return void
     */
    public function testExceptionTrait()
    {
        $exceptionHandlingExample = new ExceptionHandlingExample;

        // Test the exception detection.
        $this->assertTrue($exceptionHandlingExample->isMicroServiceException(new ModelNotFoundException));
        $this->assertFalse($exceptionHandlingExample->isMicroServiceException(new \Illuminate\Database\Eloquent\MassAssignmentException));

        // Test HTTP exception.
        $httpException = new HttpException(404);
        $this->assertEquals($this->getExpectedExceptionJsonResponse(HttpException::class), $exceptionHandlingExample->handleMicroServiceException($httpException)->getData());

        // Test model exception.
        $modelException = new ModelNotFoundException;
        $modelException->setModel('Example');
        $this->assertEquals($this->getExpectedExceptionJsonResponse(ModelNotFoundException::class), $exceptionHandlingExample->handleMicroServiceException($modelException)->getData());

        // Test generic exception.
        $genericException = new Exception;
        $this->assertEquals($this->getExpectedExceptionJsonResponse(Exception::class), $exceptionHandlingExample->handleMicroServiceException($genericException)->getData());
    }

    /**
     * Test the string handling trait.
     *
     * @return void
     */
    public function testStringTrait()
    {
        $stringExample = new StringExample;

        // Test string padding.
        $this->assertEquals('000Test', $stringExample->examplePadTrim('Test', '0', 7));
        $this->assertEquals('Test000', $stringExample->examplePadTrim('Test', '0', 7, STR_PAD_RIGHT));
        $this->assertEquals('0Test00', $stringExample->examplePadTrim('Test', '0', 7, STR_PAD_BOTH));
    }

    public function testPagination()
    {
        // Test the basics of pagination.
        $paginator = new Paginator(100, 10, 1);
        $this->assertEquals($paginator->getOffset(), 0);
        $this->assertEquals($paginator->getLastPage(), 10);

        $paginatorTwo = new Paginator(100, 10, 2);
        $this->assertEquals($paginatorTwo->getOffset(), 10);
        $this->assertEquals($paginatorTwo->getLastPage(), 10);

        $paginatorThree = new Paginator(100, 7, 1);
        $this->assertEquals($paginatorThree->getOffset(), 0);
        $this->assertEquals($paginatorThree->getLastPage(), 15);

        $paginatorFour = new Paginator(100, 7, 2);
        $this->assertEquals($paginatorFour->getOffset(), 7);
        $this->assertEquals($paginatorFour->getLastPage(), 15);

        // Test changing values after creation.
        $changedPaginator = new Paginator(100, 10, 1);
        $changedPaginator->setPage(2);
        $this->assertEquals($changedPaginator->getOffset(), 10);
        $this->assertEquals($changedPaginator->getLastPage(), 10);

        $changedPaginatorTwo = new Paginator(100, 10, 1);
        $changedPaginatorTwo->setPerPage(50);
        $this->assertEquals($changedPaginatorTwo->getOffset(), 0);
        $this->assertEquals($changedPaginatorTwo->getLastPage(), 2);

        $changedPaginatorThree = new Paginator(100, 10, 1);
        $changedPaginatorThree->setTotal(19);
        $this->assertEquals($changedPaginatorThree->getOffset(), 0);
        $this->assertEquals($changedPaginatorThree->getLastPage(), 2);

        $changedPaginatorFour = new Paginator(100, 10, 1);
        $changedPaginator->setPage(2);
        $changedPaginatorTwo->setPerPage(50);
        $changedPaginatorFour->setTotal(19);
        $this->assertEquals($changedPaginatorFour->getOffset(), 0);
        $this->assertEquals($changedPaginatorFour->getLastPage(), 2);

        // Test the pagination response.
        $responsePaginator = new Paginator(100, 10, 1);
        $this->assertEquals((array) [
            'total' => 100,
            'per_page' => 10,
            'current_page' => 1,
            'last_page' => 10,
            'next_page' => 2,
            'prev_page' => null
        ], (array) $responsePaginator->preparePaginationResponse()->snakeFormat());

        $responsePaginatorTwo = new Paginator(100, 10, 2);
        $this->assertEquals((array) [
            'total' => 100,
            'per_page' => 10,
            'current_page' => 2,
            'last_page' => 10,
            'next_page' => 3,
            'prev_page' => 1
        ], (array) $responsePaginatorTwo->preparePaginationResponse()->snakeFormat());
    }
}

/**
 * An test enumeration class.
 *
 * @package App\Enum
 */
class TestEnum extends BaseEnum
{
    /**
     * @var string
     */
    const FOO = 'bar';

    /**
     * @var string
     */
    const BAZ = 'qux';
}

/**
 * An example model class.
 */
class Example extends \Illuminate\Database\Eloquent\Model {}

/**
 * An example class to test the date handling trait.
 */
class DateExample
{
    use \LushDigital\MicroServiceCore\Traits\MicroServiceDateTrait;

    /**
     * Fake wrapper function to expose the protected trait function.
     *
     * @param string $format
     * @param string $date
     * @return bool
     */
    public function validDate($format, $date)
    {
        return $this->validateDate($format, $date);
    }
}

/**
 * An example class to test the exception handling trait.
 */
class ExceptionHandlingExample
{
    use \LushDigital\MicroServiceCore\Traits\MicroServiceExceptionHandlerTrait;
}

/**
 * An example class to test the string handling trait.
 */
class StringExample
{
    use \LushDigital\MicroServiceCore\Traits\MicroServiceStringTrait;

    /**
     * Fake wrapper function to expose the protected trait function.
     *
     * @param string $input
     *   The input string.
     * @param string $pad
     *   The padding character.
     * @param int $length
     *   The length of string we want.
     * @param int $mode
     *   The string padding mode.
     *
     * @return string
     */
    public function examplePadTrim($input, $pad = '0', $length = 3, $mode = STR_PAD_LEFT)
    {
        return $this->padTrim($input, $pad, $length, $mode);
    }
}