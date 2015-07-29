<?php

namespace spec\LaraPackage\Api\Config;

use App\Contracts;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ApiVersionSpec extends ObjectBehavior
{
    protected $version = 4;
    protected $versionArray = [4 => 'test', 10 => 'test', 1 => 'foobar'];

    function it_checks_a_version_to_make_sure_it_is_valid(\LaraPackage\Api\Contracts\Config\Api $config)
    {
        $this->configGetApiItemExpectation($config, 'versions', $this->versionArray);
        $this->isValid($this->version)->shouldReturn(true);
    }

    function it_checks_to_see_if_a_media_type_is_valid_for_the_version(\LaraPackage\Api\Contracts\Config\Api $config)
    {
        $lookFor = 'yml';
        $mediaTypes = ['json', 'yml'];
        $this->configGetApiVersionItemExpecation($config, 'media.types', $mediaTypes);
        $this->mediaTypeIsValid($lookFor, $this->version)->shouldReturn(true);
    }

    function it_gets_all_available_versions(\LaraPackage\Api\Contracts\Config\Api $config)
    {
        $expected = \array_keys($this->versionArray);
        $this->configGetApiItemExpectation($config, 'versions', $this->versionArray);
        $this->availableVersions()->shouldReturn($expected);
    }

    function it_gets_an_item_out_of_the_resource_id_map(\LaraPackage\Api\Contracts\Config\Api $config)
    {
        $idMap = [
            '/attributes/{random_id}'           => function () {
                return [5, 6];
            },
            '/catalogs/{random_id}/catalogtabs' => function () {
                return [8, 9];
            },
        ];
        $this->configGetApiVersionItemExpecation($config, 'resourceIdsMap', $idMap);
        $this->resourceIdMap('/catalogs/{random_id}/catalogtabs', 4)->shouldReturn([8, 9]);
    }

    function it_gets_the_collection_size_for_the_version(\LaraPackage\Api\Contracts\Config\Api $config)
    {
        $collectionPageSize = 10;
        $this->configGetApiVersionItemExpecation($config, 'collection.page_size', $collectionPageSize);
        $this->collectionPageSize($this->version)->shouldReturn($collectionPageSize);
    }

    function it_gets_the_current_position_string_for_a_version(\LaraPackage\Api\Contracts\Config\Api $config)
    {
        $currentPositionString = 'current';
        $this->configGetApiVersionItemExpecation($config, 'collection.current_position', $currentPositionString);
        $this->collectionCurrentPositionString($this->version)->shouldReturn($currentPositionString);
    }

    function it_gets_the_default_media_type_for_a_version(\LaraPackage\Api\Contracts\Config\Api $config)
    {
        $defaultMediaType = 'json';
        $this->configGetApiVersionItemExpecation($config, 'media.default', $defaultMediaType);
        $this->defaultMediaType($this->version)->shouldReturn($defaultMediaType);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('App\Config\ApiVersion');
    }

    function it_provides_the_version_designator(\LaraPackage\Api\Contracts\Config\Api $config)
    {
        $version = 4;
        $versionDesignator = 'v';
        $this->configGetApiItemExpectation($config, 'version_designator', $versionDesignator);
        $this->versionDesignator($version)->shouldReturn($versionDesignator.$version);
    }

    function it_returns_an_array_of_media_types_for_the_version(\LaraPackage\Api\Contracts\Config\Api $config)
    {
        $mediaTypes = ['json', 'yml'];
        $this->configGetApiVersionItemExpecation($config, 'media.types', $mediaTypes);
        $this->mediaTypes($this->version)->shouldReturn($mediaTypes);
    }

    function it_returns_false_if_an_item_is_not_found_in_the_resource_id_map(\LaraPackage\Api\Contracts\Config\Api $config)
    {
        $idMap = [
            '/attributes/{random_id}'           => [5, 6],
            '/catalogs/{random_id}/catalogtabs' => [8, 9],
        ];
        $this->configGetApiVersionItemExpecation($config, 'resourceIdsMap', $idMap);
        $this->resourceIdMap('fubar', 4)->shouldReturn(false);
    }

    function it_returns_the_latest_version(\LaraPackage\Api\Contracts\Config\Api $config)
    {
        $this->configGetApiItemExpectation($config, 'versions', $this->versionArray);
        $this->latest()->shouldReturn(10);
    }

    function it_returns_the_vendor_string_for_the_version(\LaraPackage\Api\Contracts\Config\Api $config)
    {
        $vendorString = 'vnd.wps_api.';
        $this->configGetApiVersionItemExpecation($config, 'vendor', $vendorString);
        $this->vendor($this->version)->shouldReturn($vendorString);
    }

    function let(\LaraPackage\Api\Contracts\Config\Api $config)
    {
        $this->beConstructedWith($config);
    }

    /**
     * @param \LaraPackage\Api\Contracts\Config\Api $config
     * @param                                  $key
     * @param                                  $return
     */
    protected function configGetApiItemExpectation(\LaraPackage\Api\Contracts\Config\Api $config, $key, $return)
    {
        $config->getIndex($key)->shouldBeCalled()->willReturn($return);
    }

    /**
     * @param \LaraPackage\Api\Contracts\Config\Api $config
     * @param                                  $key
     * @param                                  $return
     *
     * @internal param $this->version
     */
    protected function configGetApiVersionItemExpecation(\LaraPackage\Api\Contracts\Config\Api $config, $key, $return)
    {
        $config->getIndexForVersion($key, $this->version)->shouldBeCalled()->willReturn($return);
    }
}
