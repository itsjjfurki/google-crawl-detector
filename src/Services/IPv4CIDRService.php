<?php

namespace Itsjjfurki\GoogleCrawlDetector\Services;

use InvalidArgumentException;

class IPv4CIDRService
{
    /**
     * This function first separates the base IP address and subnet mask length from the CIDR using explode().
     * It then converts the base IP address to a long integer using ip2long().
     * Next, it calculates the number of IP addresses in the subnet using the formula 2^(32-mask).
     * Finally, it loops through each IP address in the subnet, calculates the long integer representation of
     * the IP address, converts it back to dotted decimal format using long2ip(), and adds it to the list of
     * resolved IP addresses.
     *
     * Note that this function assumes that the input is a valid IPv4 CIDR address in the format x.x.x.x/y.
     *
     * @param  string  $cidr An IPv4 CIDR address.
     * @return string[]
     *
     * @throws InvalidArgumentException If the CIDR address is invalid.
     */
    public function resolveCIDR(string $cidr): array
    {
        if (! $this->isIpv4Cidr($cidr)) {
            throw new InvalidArgumentException('CIDR address is invalid IPv4 CIDR');
        }

        $ips = [];

        // Get the base IP address and subnet mask length from the CIDR
        [$base, $mask] = explode('/', $cidr);

        // Convert the base IP address to a long integer
        $baseLong = ip2long($base);

        // Calculate the number of IP addresses in the subnet
        $numAddresses = pow(2, (32 - (int) $mask));

        // Loop through each IP address in the subnet and add it to the list
        for ($i = 0; $i < $numAddresses; $i++) {
            $ipLong = $baseLong + $i;
            $ip = long2ip($ipLong);
            $ips[] = $ip;
        }

        return $ips;
    }

    /**
     * Generates a list of IP addresses from a given CIDR address.
     *
     * @param  string  $cidr The CIDR address to generate IPs from.
     * @return string[] An array of IP addresses in the CIDR range.
     *
     * @throws InvalidArgumentException If the CIDR address is invalid.
     */
    public function generateIPv4sFromCIDR(string $cidr): array
    {
        if (! $this->isIpv4Cidr($cidr)) {
            throw new InvalidArgumentException('CIDR address is invalid IPv4 CIDR');
        }

        $ips = [];

        [$ip, $mask] = explode('/', $cidr);

        $numOfIps = pow(2, (32 - (int) $mask)) - 1;
        for ($i = 0; $i <= $numOfIps; $i++) {
            $ips[] = long2ip(ip2long($ip) + $i);
        }

        return $ips;
    }

    /**
     * Checks if the given CIDR address is a valid IPv4 CIDR.
     *
     * @param  string  $cidr The CIDR address to check
     * @return bool Returns true if the CIDR address is a valid IPv4 CIDR, false otherwise
     */
    public function isIPv4CIDR(string $cidr): bool
    {
        // Split the CIDR address into IP address and prefix length
        $parts = explode('/', $cidr);
        if (count($parts) !== 2) {
            return false;
        }

        // Check if the IP address is a valid IPv4 address
        if (! filter_var($parts[0], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return false;
        }

        // Check if the prefix length is a valid integer between 0 and 32
        $prefix = (int) $parts[1];
        if ($prefix < 0 || $prefix > 32) {
            return false;
        }

        return true;
    }
}
