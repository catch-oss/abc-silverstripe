<?php

namespace Azt3k\SS\Tests\GridField;

use Azt3k\SS\GridField\AbcGridFieldConfig;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Forms\GridField\GridFieldSortableHeader;
use SilverStripe\Forms\GridField\GridFieldPaginator;
use SilverStripe\Model\List\ArrayList;

class AbcGridFieldConfigTest extends SapphireTest
{
    protected $usesDatabase = false;

    public function testGetComponentsReturnsArrayList(): void
    {
        // GIVEN a fresh AbcGridFieldConfig
        $config = new AbcGridFieldConfig();

        // WHEN we get the components
        $components = $config->getComponents();

        // THEN it should return an ArrayList
        $this->assertInstanceOf(ArrayList::class, $components);
    }

    public function testAddComponentIncreasesCount(): void
    {
        // GIVEN a fresh config
        $config = new AbcGridFieldConfig();
        $initialCount = $config->getComponents()->count();

        // WHEN we add a component
        $config->addComponent(new GridFieldSortableHeader());

        // THEN the count should increase by 1
        $this->assertSame($initialCount + 1, $config->getComponents()->count());
    }

    public function testRemoveComponentDecreasesCount(): void
    {
        // GIVEN a config with a component
        $config = new AbcGridFieldConfig();
        $component = new GridFieldSortableHeader();
        $config->addComponent($component);
        $count = $config->getComponents()->count();

        // WHEN we remove the component
        $config->removeComponent($component);

        // THEN the count should decrease by 1
        $this->assertSame($count - 1, $config->getComponents()->count());
    }

    public function testGetComponentByTypeReturnsCorrectComponent(): void
    {
        // GIVEN a config with a sortable header
        $config = new AbcGridFieldConfig();
        $config->addComponent(new GridFieldSortableHeader());

        // WHEN we get the component by type
        $component = $config->getComponentByType(GridFieldSortableHeader::class);

        // THEN it should return a GridFieldSortableHeader
        $this->assertInstanceOf(GridFieldSortableHeader::class, $component);
    }

    public function testGetComponentByTypeReturnsNullWhenNotFound(): void
    {
        // GIVEN a config with no paginator
        $config = new AbcGridFieldConfig();

        // WHEN we get a component type that doesn't exist
        $component = $config->getComponentByType(GridFieldPaginator::class);

        // THEN it should return null
        $this->assertNull($component);
    }

    public function testRemoveComponentsByTypeRemovesAll(): void
    {
        // GIVEN a config with two sortable headers
        $config = new AbcGridFieldConfig();
        $config->addComponent(new GridFieldSortableHeader());
        $config->addComponent(new GridFieldSortableHeader());

        // WHEN we remove by type
        $config->removeComponentsByType(GridFieldSortableHeader::class);

        // THEN no sortable headers should remain
        $this->assertSame(0, $config->getComponentsByType(GridFieldSortableHeader::class)->count());
    }

    public function testAddComponentReturnsSelf(): void
    {
        // GIVEN a config
        $config = new AbcGridFieldConfig();

        // WHEN we add a component
        $result = $config->addComponent(new GridFieldSortableHeader());

        // THEN it should return the same config for chaining
        $this->assertSame($config, $result);
    }
}
