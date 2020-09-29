<?php

namespace Dynamic\Foxy\Orders\Tests\TestOnly\Controller;

use Dynamic\Foxy\Orders\Tests\Factory\OrderFactoryTest;
use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\HTTPResponse;
use SilverStripe\Dev\TestOnly;
use SilverStripe\ORM\ArrayList;
use SilverStripe\Security\PasswordEncryptor;
use SilverStripe\Security\PasswordEncryptor_NotFoundException;
use SilverStripe\View\ArrayData;

/**
 * Class FoxyDataTestController
 * @package Dynamic\Foxy\Orders\Tests\TestOnly\Controller
 */
class FoxyDataTestController extends Controller implements TestOnly
{
    /**
     * @var string[]
     */
    private static $allowed_actions = [
        'foxytestdata',
    ];

    /**
     * @param null $action
     * @return string|null
     */
    public function Link($action = null)
    {
        return '/foxytestdata';
    }

    /**
     * @param HTTPRequest $request
     * @return HTTPResponse
     * @throws PasswordEncryptor_NotFoundException
     */
    public function index(HTTPRequest $request)
    {
        return HTTPResponse::create()
            ->setBody($this->getTransactionData());
    }

    /**
     * @param HTTPRequest $request
     * @return HTTPResponse
     * @throws PasswordEncryptor_NotFoundException
     */
    public function foxytestdata(HTTPRequest $request)
    {
        return HTTPResponse::create()
            ->setBody($this->getTransactionData());
    }

    /**
     * @return string
     * @throws PasswordEncryptor_NotFoundException
     */
    protected function getTransactionData()
    {
        return urlencode(\rc4crypt::encrypt(OrderFactoryTest::DUMMY_KEY, $this->getXML()));
    }

    /**
     * @return string
     * @throws PasswordEncryptor_NotFoundException
     */
    protected function getXML()
    {
        $xml = Controller::singleton()->renderWith(
            'TestData',
            [
                'OrderID' => 111111222222333333,
                'TransactionDate' => strtotime('now'),
                'Email' => 'test@test.test',
                'HashType' => 'sha1_salted_suffix',
                'Salt' => 'faGgWXUTdZ7i42lpA6cljzKeGBeUwShBSNHECwsJmt',
                'HashedPassword' => PasswordEncryptor::create_for_algorithm('sha1_v2.4')
                    ->encrypt('MyF00p@$$w0Rd', 'faGgWXUTdZ7i42lpA6cljzKeGBeUwShBSNHECwsJmt'),
                'OrderDetails' => $this->getOrderDetails(),
            ]
        )->getValue();

        return <<<XML
{$xml}
XML;
    }

    /**
     * @return ArrayList
     */
    protected function getOrderDetails()
    {
        return ArrayList::create([
            ArrayData::create([
                'Options' => $this->getOptions(),
            ]),
        ]);
    }

    /**
     * @return ArrayList
     */
    protected function getOptions()
    {
        return ArrayList::create();
    }
}
