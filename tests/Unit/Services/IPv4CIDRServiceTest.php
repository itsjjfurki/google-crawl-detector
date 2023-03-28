<?php

namespace Itsjjfurki\GoogleCrawlDetector\Tests\Unit\Services;

use InvalidArgumentException;
use Itsjjfurki\GoogleCrawlDetector\Services\IPv4CIDRService;
use Itsjjfurki\GoogleCrawlDetector\Tests\TestCase;

class IPv4CIDRServiceTest extends TestCase
{
    public function test_should_throw_exception_when_trying_to_resolve_invalid_ipv4_cidr(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $service = new IPv4CIDRService();
        $cidr = 'invalid_cidr';
        $service->resolveCIDR($cidr);
    }

    public function test_should_resolve_ipv4_cidr(): void
    {
        $service = new IPv4CIDRService();
        $cidr = '192.168.0.0/24';
        $expected = $service->generateIPv4sFromCIDR($cidr);
        $result = $service->resolveCIDR($cidr);
        $this->assertEquals($expected, $result);
    }

    public function test_should_throw_exception_when_trying_to_generate_ipv4s_from_cidr(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $service = new IPv4CIDRService();
        $cidr = 'invalid_cidr';
        $service->generateIPv4sFromCIDR($cidr);
    }

    public function test_should_generate_ipv4s_from_cidr(): void
    {
        $service = new IPv4CIDRService();
        $cidr = '192.168.0.0/24';
        $result = $service->generateIPv4sFromCIDR($cidr);
        $this->assertCount(256, $result);
        $this->assertContains('192.168.0.0', $result);
        $this->assertContains('192.168.0.1', $result);
        $this->assertContains('192.168.0.253', $result);
        $this->assertContains('192.168.0.254', $result);
        $this->assertContains('192.168.0.255', $result);
    }

    public function test_should_determine_if_ipv4_cidr_is_valid(): void
    {
        $service = new IPv4CIDRService();

        $cidr = '192.168.1.0/24';
        $result = $service->isIPv4CIDR($cidr);
        $this->assertTrue($result);

        $cidr = '192.168.1.0';
        $result = $service->isIPv4CIDR($cidr);
        $this->assertFalse($result);

        $cidr = '300.168.1.0/24';
        $result = $service->isIPv4CIDR($cidr);
        $this->assertFalse($result);

        $cidr = '192.168.1.0/33';
        $result = $service->isIPv4CIDR($cidr);
        $this->assertFalse($result);
    }
}
