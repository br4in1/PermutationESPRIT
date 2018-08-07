<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Serializer\Tests\Normalizer;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Normalizer\DataUriNormalizer;

/**
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class DataUriNormalizerTest extends TestCase
{
    const TEST_GIF_DATA = 'data:image/gif;base64,R0lGODdhAQABAIAAAP///////ywAAAAAAQABAAACAkQBADs=';
    const TEST_TXT_DATA = 'data:text/plain,K%C3%A9vin%20Dunglas%0A';
    const TEST_TXT_CONTENT = "Kévin Dunglas\n";

    /**
     * @var DataUriNormalizer
     */
    private $normalizer;

    protected function setUp()
    {
        $this->normalizer = new DataUriNormalizer();
    }

    public function testInterface()
    {
        $this->assertInstanceOf('Symfony\Component\Serializer\Normalizer\NormalizerInterface', $this->normalizer);
        $this->assertInstanceOf('Symfony\Component\Serializer\Normalizer\DenormalizerInterface', $this->normalizer);
    }

    public function testSupportNormalization()
    {
        $this->assertFalse($this->normalizer->supportsNormalization(new \stdClass()));
        $this->assertTrue($this->normalizer->supportsNormalization(new \SplFileObject('data:,Hello%2C%20World!')));
    }

    /**
     * @requires extension fileinfo
     */
    public function testNormalizeHttpFoundationFile()
    {
        $file = new File(__DIR__.'/../Fixtures/test.gif');

        $this->assertSame(self::TEST_GIF_DATA, $this->normalizer->normalize($file));
    }

    /**
     * @requires extension fileinfo
     */
    public function testNormalizeSplFileInfo()
    {
        $file = new \SplFileInfo(__DIR__.'/../Fixtures/test.gif');

        $this->assertSame(self::TEST_GIF_DATA, $this->normalizer->normalize($file));
    }

    /**
     * @requires extension fileinfo
     */
    public function testNormalizeText()
    {
        $file = new \SplFileObject(__DIR__.'/../Fixtures/test.txt');

        $data = $this->normalizer->normalize($file);

        $this->assertSame(self::TEST_TXT_DATA, $data);
        $this->assertSame(self::TEST_TXT_CONTENT, file_get_contents($data));
    }

    public function testSupportsDenormalization()
    {
        $this->assertFalse($this->normalizer->supportsDenormalization('foo', 'Bar'));
        $this->assertTrue($this->normalizer->supportsDenormalization(self::TEST_GIF_DATA, 'SplFileInfo'));
        $this->assertTrue($this->normalizer->supportsDenormalization(self::TEST_GIF_DATA, 'SplFileObject'));
        $this->assertTrue($this->normalizer->supportsDenormalization(self::TEST_TXT_DATA, 'Symfony\Component\HttpFoundation\File\File'));
    }

    public function testDenormalizeSplFileInfo()
    {
        $file = $this->normalizer->denormalize(self::TEST_TXT_DATA, 'SplFileInfo');

        $this->assertInstanceOf('SplFileInfo', $file);
        $this->assertSame(file_get_contents(self::TEST_TXT_DATA), $this->getContent($file));
    }

    public function testDenormalizeSplFileObject()
    {
        $file = $this->normalizer->denormalize(self::TEST_TXT_DATA, 'SplFileObject');

        $this->assertInstanceOf('SplFileObject', $file);
        $this->assertEquals(file_get_contents(self::TEST_TXT_DATA), $this->getContent($file));
    }

    public function testDenormalizeHttpFoundationFile()
    {
        $file = $this->normalizer->denormalize(self::TEST_GIF_DATA, 'Symfony\Component\HttpFoundation\File\File');

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\File\File', $file);
        $this->assertSame(file_get_contents(self::TEST_GIF_DATA), $this->getContent($file->openFile()));
    }

    /**
     * @expectedException \Symfony\Component\Serializer\Exception\UnexpectedValueException
     * @expectedExceptionMessage The provided "data:" URI is not valid.
     */
    public function testGiveNotAccessToLocalFiles()
    {
        $this->normalizer->denormalize('/etc/shadow', 'SplFileObject');
    }

    /**
     * @expectedException \Symfony\Component\Serializer\Exception\UnexpectedValueException
     * @dataProvider invalidUriProvider
     */
    public function testInvalidData($uri)
    {
        $this->normalizer->denormalize($uri, 'SplFileObject');
    }

    public function invalidUriProvider()
    {
        return array(
            array('dataxbase64'),
            array('data:HelloWorld'),
            array('data:text/html;charset=,%3Ch1%3EHello!%3C%2Fh1%3E'),
            array('data:text/html;charset,%3Ch1%3EHello!%3C%2Fh1%3E'),
            array('data:base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQAQMAAAAlPW0iAAAABlBMVEUAAAD///+l2Z/dAAAAM0lEQVR4nGP4/5/h/1+G/58ZDrAz3D/McH8yw83NDDeNGe4Ug9C9zwz3gVLMDA/A6P9/AFGGFyjOXZtQAAAAAElFTkSuQmCC'),
            array(''),
            array('http://wikipedia.org'),
            array('base64'),
            array('iVBORw0KGgoAAAANSUhEUgAAABAAAAAQAQMAAAAlPW0iAAAABlBMVEUAAAD///+l2Z/dAAAAM0lEQVR4nGP4/5/h/1+G/58ZDrAz3D/McH8yw83NDDeNGe4Ug9C9zwz3gVLMDA/A6P9/AFGGFyjOXZtQAAAAAElFTkSuQmCC'),
            array(' data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAIBAMAAAA2IaO4AAAAFVBMVEXk5OTn5+ft7e319fX29vb5+fn///++GUmVAAAALUlEQVQIHWNICnYLZnALTgpmMGYIFWYIZTA2ZFAzTTFlSDFVMwVyQhmAwsYMAKDaBy0axX/iAAAAAElFTkSuQmCC'),
            array('   data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAIBAMAAAA2IaO4AAAAFVBMVEXk5OTn5+ft7e319fX29vb5+fn///++GUmVAAAALUlEQVQIHWNICnYLZnALTgpmMGYIFWYIZTA2ZFAzTTFlSDFVMwVyQhmAwsYMAKDaBy0axX/iAAAAAElFTkSuQmCC'),
        );
    }

    /**
     * @dataProvider validUriProvider
     */
    public function testValidData($uri)
    {
        $this->assertInstanceOf('SplFileObject', $this->normalizer->denormalize($uri, 'SplFileObject'));
    }

    public function validUriProvider()
    {
        $data = array(
            array('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQAQMAAAAlPW0iAAAABlBMVEUAAAD///+l2Z/dAAAAM0lEQVR4nGP4/5/h/1+G/58ZDrAz3D/McH8yw83NDDeNGe4Ug9C9zwz3gVLMDA/A6P9/AFGGFyjOXZtQAAAAAElFTkSuQmCC'),
            array('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAIBAMAAAA2IaO4AAAAFVBMVEXk5OTn5+ft7e319fX29vb5+fn///++GUmVAAAALUlEQVQIHWNICnYLZnALTgpmMGYIFWYIZTA2ZFAzTTFlSDFVMwVyQhmAwsYMAKDaBy0axX/iAAAAAElFTkSuQmCC'),
            array('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAgAAAAIBAMAAAA2IaO4AAAAFVBMVEXk5OTn5+ft7e319fX29vb5+fn///++GUmVAAAALUlEQVQIHWNICnYLZnALTgpmMGYIFWYIZTA2ZFAzTTFlSDFVMwVyQhmAwsYMAKDaBy0axX/iAAAAAElFTkSuQmCC   '),
            array('data:,Hello%2C%20World!'),
            array('data:text/html,%3Ch1%3EHello%2C%20World!%3C%2Fh1%3E'),
            array('data:,A%20brief%20note'),
            array('data:text/html;charset=US-ASCII,%3Ch1%3EHello!%3C%2Fh1%3E'),
            array('data:application/ld+json;base64,eyJAaWQiOiAiL2ZvbyJ9'),
            array('data:application/vnd.ms-word.document.macroenabled.12;base64,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��d    ��d                    `{7            �O    ��d            ��d     @      ��d            6JrOmX10AejUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAfzL0Af/1f7q/gp/yRr4Sf8AZMvAf/qK6VQB6bQAUAFABQB4L8Vv2YvgX8b9UbWvij4Ct/FWqP4Yv/BbXsut+JtKlPhTV9K8WaFrWgf8SPWdMT+zNd0Lx14t0TXrbAXW9H1y70zVftliILeIA4/Rv2Jv2Y9AvvDuqaX8Mkg1Pwr4a8O+D9Dv5PF3ju6u7Xw74T+M9h+0J4e06aW68TzG/GnfGDTLTxglzqH2q7mKS6FczzeGbm50eUA9j+Gfwk+Hvwd0e80D4c+H/wDhHdJv5PDUl3a/2rrerec/hD4eeCvhT4dbz9c1LU7lP7O8A/DvwdoOI5lF3/Y/9qX32nWdQ1PUb0A9HoAKACgAoAKACgAoAKACgAoAKACgAoAKACgAoAKACgAoAKACgAoAKACgAoAKACgAoAKACgAoAKACgAoAKACgAoA/mXoA/9b+6v4Kf8ka+En/AGTLwH/6iulUAem0AFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFAH8y9AH/1/7q/gp/yRr4Sf8AZMvAf/qK6VQB6bQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAfzL0Af/Q/ur+Cn/JGvhJ/wBky8B/+orpVAHptABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQB/MvQB/9H+6v4Kf8ka+En/AGTLwH/6iulUAem0AFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFAH8y9AH/0v7q/gp/yRr4Sf8AZMvAf/qK6VQB6bQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAfzL0Af/T/ur+Cn/JGvhJ/wBky8B/+orpVAHptABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQB/MvQB/9T+6v4Kf8ka+En/AGTLwH/6iulUAem0AFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFAH8y9AH/1f7q/gp/yRr4Sf8AZMvAf/qK6VQB6bQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAfzL0Af/W/ur+Cn/JGvhJ/wBky8B/+orpVAHptABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQB/MvQB/9f+6v4Kf8ka+En/AGTLwH/6iulUAem0AFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFAH8y9AH/0P7q/gp/yRr4Sf8AZMvAf/qK6VQB6bQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAfzL0Af/R/ur+Cn/JGvhJ/wBky8B/+orpVAHptABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQB/MvQB/9L+6v4Kf8ka+En/AGTLwH/6iulUAem0AFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFAH8y9AH/0/7q/gp/yRr4Sf8AZMvAf/qK6VQB6bQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAfzL0Af/U/ur+Cn/JGvhJ/wBky8B/+orpVAHptABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQB/MvQB/9X+6v4Kf8ka+En/AGTLwH/6iulUAem0AFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFAH8y9AH/1v7q/gp/yRr4Sf8AZMvAf/qK6VQB6bQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAfzL0Af/X/ur+Cn/JGvhJ/wBky8B/+orpVAHptABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQB/MvQB/9D+6v4Kf8ka+En/AGTLwH/6iulUAem0AFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFAH8y9AH/0f7q/gp/yRr4Sf8AZMvAf/qK6VQB6bQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAfzL0Af/S/ur+Cn/JGvhJ/wBky8B/+orpVAHptABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQB/MvQB/9P+6v4Kf8ka+En/AGTLwH/6iulUAem0AFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFAH8y9AH/1P7q/gp/yRr4Sf8AZMvAf/qK6VQB6bQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAfzL0Af/V/ur+Cn/JGvhJ/wBky8B/+orpVAHptABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQB/MvQB/9b+6v4Kf8ka+En/AGTLwH/6iulUAem0AFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFAH8y9AH/1/7q/gp/yRr4Sf8AZMvAf/qK6VQB6bQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAfzL0Af/Q/ur+Cn/JGvhJ/wBky8B/+orpVAHptABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQB/MvQB/9H+6v4Kf8ka+En/AGTLwH/6iulUAem0AFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFAH8y9AH/0v7q/gp/yRr4Sf8AZMvAf/qK6VQB6bQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAfzL0Af/T/ur+Cn/JGvhJ/wBky8B/+orpVAHptABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQB/MvQB/9T+6v4Kf8ka+En/AGTLwH/6iulUAem0AFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFAH8y9AH/1f7q/gp/yRr4Sf8AZMvAf/qK6VQB6bQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAfzL0Af/W/ur+Cn/JGvhJ/wBky8B/+orpVAHptABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQB/MvQB/9f+6v4Kf8ka+En/AGTLwH/6iulUAem0AFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFAH8y9AH/0P7q/gp/yRr4Sf8AZMvAf/qK6VQB6bQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAfzL0Af/R/ur+Cn/JGvhJ/wBky8B/+orpVAHptABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQB/MvQB/9L+6v4Kf8ka+En/AGTLwH/6iulUAem0AFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFAH8y9AH/0/7q/gp/yRr4Sf8AZMvAf/qK6VQB6bQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAfzL0Af/U/ur+Cn/JGvhJ/wBky8B/+orpVAHptABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQB/MvQB/9X+6v4Kf8ka+En/AGTLwH/6iulUAem0AFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFAH8y9AH/1v7q/gp/yRr4Sf8AZMvAf/qK6VQB6bQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAfzL0Af/X/ur+Cn/JGvhJ/wBky8B/+orpVAHptABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQB/MvQB/9D+6v4Kf8ka+En/AGTLwH/6iulUAem0AFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFAH8y9AH/0f7q/gp/yRr4Sf8AZMvAf/qK6VQB6bQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAfzL0Af/S/ur+Cn/JGvhJ/wBky8B/+orpVAHptABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQB/MvQB/9P+6v4Kf8ka+En/AGTLwH/6iulUAem0AFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFAH8y9AH/1P7q/gp/yRr4Sf8AZMvAf/qK6VQB6bQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAfzL0Af/V/ur+Cn/JGvhJ/wBky8B/+orpVAHptABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQB/MvQB/9b+6v4Kf8ka+En/AGTLwH/6iulUAem0AFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFAH8y9AH/1/7q/gp/yRr4Sf8AZMvAf/qK6VQB6bQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAfzL0Af/Q/ur+Cn/JGvhJ/wBky8B/+orpVAHptABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQB/MvQB/9H+6v4Kf8ka+En/AGTLwH/6iulUAem0AFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFAH8y9AH/0v7q/gp/yRr4Sf8AZMvAf/qK6VQB6bQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAfzL0Af/T/ur+Cn/JGvhJ/wBky8B/+orpVAHptABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQB/MvQB/9T+6v4Kf8ka+En/AGTLwH/6iulUAem0AFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFAH8y9AH/1f7q/gp/yRr4Sf8AZMvAf/qK6VQB6bQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAFABQAUAfzL0Af/W/th+DHxn+D0Pwe+FEUvxX+GsUsXw18CxyRyeO/CySRyJ4X0tXR0bUgyOjAqysNysCDgg0Aelf8Lr+Df/AEVr4Zf+F74V/wDl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'),
            array('data:a!b#c&d-e^f_g+h.i/a!b#c&d-e^f_g+h.i;base64,foobar'),
        );

        if (!defined('HHVM_VERSION')) {
            // See https://github.com/facebook/hhvm/issues/6354
            $data[] = array('data:text/plain;charset=utf-8;base64,SGVsbG8gV29ybGQh');
        }

        return $data;
    }

    private function getContent(\SplFileObject $file)
    {
        $buffer = '';
        while (!$file->eof()) {
            $buffer .= $file->fgets();
        }

        return $buffer;
    }
}
