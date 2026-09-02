<?php

require_once __DIR__.'/ComponentTestHelpers.php';

use Illuminate\Support\MessageBag;
use JeroenNoten\LaravelAdminLte\View\Components;

/**
 * Locks the shared inheritance contract of the blade components. The concrete
 * components hold no copy of the logic of their base class anymore, they only
 * declare the tokens that make them different. These tests assert both the
 * declared tokens and the behaviour inherited from the base class.
 */
class ComponentInheritanceTest extends TestCase
{
    use ComponentTestHelpers;

    /**
     * Add a validation error into the session for the given key.
     *
     * @param  string  $key  The error key
     * @return void
     */
    protected function addErrorOnSessionFor($key)
    {
        $msgBag = new MessageBag();
        $msgBag->add($key, 'error');
        session()->put('errors', $msgBag);
    }

    /**
     * Read the value of a non public property of a component instance.
     *
     * @param  object  $component  The component instance
     * @param  string  $property  The name of the property
     * @return mixed
     */
    protected function readProperty($component, $property)
    {
        $ref = new ReflectionProperty($component, $property);
        $ref->setAccessible(true);

        return $ref->getValue($component);
    }

    /**
     * Get the name of the class declaring a method of a component instance.
     *
     * @param  object  $component  The component instance
     * @param  string  $method  The name of the method
     * @return string
     */
    protected function getDeclaringClass($component, $method)
    {
        $ref = new ReflectionMethod($component, $method);

        return $ref->getDeclaringClass()->getName();
    }

    /*
    |--------------------------------------------------------------------------
    | The profile items share an abstract base class.
    |--------------------------------------------------------------------------
    */

    public function testTheProfileItemsExtendTheSharedAbstractBase()
    {
        $base = Components\Widget\ProfileItem::class;

        $this->assertTrue((new ReflectionClass($base))->isAbstract());
        $this->assertInstanceOf($base, new Components\Widget\ProfileColItem());
        $this->assertInstanceOf($base, new Components\Widget\ProfileRowItem());
    }

    public function testTheProfileItemsInheritTheSharedMembers()
    {
        $shared = [
            'title', 'text', 'textTooltip', 'icon', 'size', 'badge', 'url',
            'urlTarget',
        ];

        foreach ($shared as $property) {
            $ref = new ReflectionProperty(
                Components\Widget\ProfileItem::class, $property
            );

            $this->assertTrue($ref->isPublic());
        }

        $items = [
            new Components\Widget\ProfileColItem(),
            new Components\Widget\ProfileRowItem(),
        ];

        foreach ($items as $item) {
            $this->assertEquals(
                Components\Widget\ProfileItem::class,
                $this->getDeclaringClass($item, 'makeTextWrapperClass')
            );
        }
    }

    public function testTheProfileItemsKeepTheirOwnDefaultSize()
    {
        $this->assertEquals(4, (new Components\Widget\ProfileColItem())->size);
        $this->assertEquals(12, (new Components\Widget\ProfileRowItem())->size);
    }

    public function testTheProfileItemsKeepTheirOwnTextWrapperClass()
    {
        $colItem = new Components\Widget\ProfileColItem();
        $rowItem = new Components\Widget\ProfileRowItem();

        $this->assertEquals('', $colItem->makeTextWrapperClass());
        $this->assertEquals('float-end', $rowItem->makeTextWrapperClass());
    }

    public function testTheProfileItemsShareTheBadgeSupport()
    {
        $colItem = new Components\Widget\ProfileColItem(
            'Title', 'Text', null, 4, 'pill-info'
        );

        $rowItem = new Components\Widget\ProfileRowItem(
            'Title', 'Text', null, 12, 'pill-info'
        );

        $this->assertEquals(
            'badge text-bg-info rounded-pill', $colItem->makeTextWrapperClass()
        );

        $this->assertEquals(
            'float-end badge text-bg-info rounded-pill',
            $rowItem->makeTextWrapperClass()
        );
    }

    public function testOnlyTheProfileRowItemHoldsTheLayoutTypeOption()
    {
        $rowItem = new Components\Widget\ProfileRowItem(
            'Title', 'Text', null, 12, null, null, 'title', null, 'nav'
        );

        $this->assertEquals('nav', $rowItem->layoutType);
        $this->assertEquals('nav-link', $rowItem->makeNavLinkClass());

        $this->assertFalse(
            property_exists(Components\Widget\ProfileColItem::class, 'layoutType')
        );

        $this->assertFalse(
            method_exists(Components\Widget\ProfileColItem::class, 'makeNavLinkClass')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | The input group components declare their invalid group class.
    |--------------------------------------------------------------------------
    */

    /**
     * Get the set of components declaring their own invalid group class,
     * together with the expected class name.
     *
     * @return array
     */
    protected function getInvalidGroupClassExpectations()
    {
        return [
            'adminlte-invalid-igroup' => [
                new Components\Form\Input('fname'),
                new Components\Form\Textarea('fname'),
                new Components\Form\Select('fname'),
                new Components\Form\Select2('fname'),
                new Components\Form\SelectBs('fname'),
                new Components\Form\InputColor('fname'),
                new Components\Form\InputDate('fname'),
                new Components\Form\InputFile('fname'),
                new Components\Form\InputFileKrajee('fname'),
                new Components\Form\DateRange('fname'),
            ],
            'adminlte-invalid-islgroup' => [
                new Components\Form\InputSlider('fname'),
            ],
            'adminlte-invalid-iswgroup' => [
                new Components\Form\InputSwitch('fname'),
            ],
            'adminlte-invalid-itegroup' => [
                new Components\Form\TextEditor('fname'),
            ],
        ];
    }

    public function testEveryComponentResolvesItsOwnInvalidGroupClass()
    {
        $this->addErrorOnSessionFor('fname');

        foreach ($this->getInvalidGroupClassExpectations() as $class => $set) {
            foreach ($set as $component) {
                $this->assertEquals(
                    $class, $this->readProperty($component, 'invalidGroupClass')
                );

                $this->assertStringContainsString(
                    $class, $component->makeInputGroupClass()
                );
            }
        }
    }

    public function testTheInvalidGroupClassIsOnlyAddedOnTheInvalidState()
    {
        foreach ($this->getInvalidGroupClassExpectations() as $class => $set) {
            foreach ($set as $component) {
                $this->assertStringNotContainsString(
                    $class, $component->makeInputGroupClass()
                );
            }
        }
    }

    public function testTheInvalidGroupClassIsWiredWithTheStylesheetOfTheView()
    {
        $templates = [
            'adminlte-invalid-igroup' => '<x-adminlte-input name="fname"/>',
            'adminlte-invalid-islgroup' => '<x-adminlte-input-slider name="fname"/>',
            'adminlte-invalid-iswgroup' => '<x-adminlte-input-switch name="fname"/>',
            'adminlte-invalid-itegroup' => '<x-adminlte-text-editor name="fname"/>',
        ];

        foreach ($templates as $class => $template) {
            $this->renderComponent($template);

            $this->assertStringContainsString(
                ".{$class}", $this->renderPushedAssets()
            );
        }
    }

    public function testEveryComponentKeepsTheSharedInputGroupClassLogic()
    {
        $this->addErrorOnSessionFor('fname');

        foreach ($this->getInvalidGroupClassExpectations() as $class => $set) {
            foreach ($set as $component) {
                $this->assertEquals(
                    Components\Form\InputGroupComponent::class,
                    $this->getDeclaringClass($component, 'makeInputGroupClass')
                );
            }
        }

        $component = new Components\Form\InputSlider(
            'fname', null, null, 'lg', null, null, 'igroup-class'
        );

        $this->assertEquals(
            'input-group input-group-lg adminlte-invalid-islgroup igroup-class',
            $component->makeInputGroupClass()
        );
    }

    /*
    |--------------------------------------------------------------------------
    | The input group components declare their item base class.
    |--------------------------------------------------------------------------
    */

    /**
     * Get the set of components declaring their own item base class, together
     * with the expected base class.
     *
     * @return array
     */
    protected function getItemBaseClassExpectations()
    {
        return [
            'form-control' => [
                new Components\Form\Input('fname'),
                new Components\Form\Textarea('fname'),
                new Components\Form\InputDate('fname'),
                new Components\Form\InputFile('fname'),
                new Components\Form\InputFileKrajee('fname'),
                new Components\Form\InputSlider('fname'),
                new Components\Form\DateRange('fname'),
            ],
            'form-control form-control-color' => [
                new Components\Form\InputColor('fname'),
            ],
            'form-select' => [
                new Components\Form\Select('fname'),
                new Components\Form\Select2('fname'),
                new Components\Form\SelectBs('fname'),
            ],
            'form-check-input' => [
                new Components\Form\InputSwitch('fname'),
            ],
        ];
    }

    public function testEveryComponentResolvesItsOwnItemBaseClass()
    {
        foreach ($this->getItemBaseClassExpectations() as $class => $set) {
            foreach ($set as $component) {
                $this->assertEquals(
                    $class,
                    implode(' ', $this->readProperty($component, 'itemBaseClass'))
                );

                $this->assertEquals($class, $component->makeItemClass());
            }
        }
    }

    public function testEveryComponentAppendsTheInvalidTokenToItsItemBaseClass()
    {
        $this->addErrorOnSessionFor('fname');

        foreach ($this->getItemBaseClassExpectations() as $class => $set) {
            foreach ($set as $component) {
                $this->assertEquals(
                    "{$class} is-invalid", $component->makeItemClass()
                );
            }
        }
    }

    public function testTheRemovedItemClassOverridesInheritTheBaseBehaviour()
    {
        $inheriting = [
            new Components\Form\InputDate('fname'),
            new Components\Form\InputFile('fname'),
            new Components\Form\InputColor('fname'),
            new Components\Form\Select('fname'),
            new Components\Form\Select2('fname'),
            new Components\Form\InputSwitch('fname'),
        ];

        foreach ($inheriting as $component) {
            $this->assertEquals(
                Components\Form\InputGroupComponent::class,
                $this->getDeclaringClass($component, 'makeItemClass')
            );
        }
    }

    public function testTheSelectFamilyKeepsTheSameItemClassContract()
    {
        $select = new Components\Form\Select('fname');
        $select2 = new Components\Form\Select2('fname');

        $this->assertEquals($select->makeItemClass(), $select2->makeItemClass());

        // The Bootstrap native select is the only one adding a size modifier,
        // which is appended to the class inherited from the base component.

        $selectBs = new Components\Form\SelectBs('fname', null, null, 'lg');

        $this->assertEquals('form-select form-select-lg', $selectBs->makeItemClass());
    }

    public function testTheTextEditorKeepsItsOwnItemClass()
    {
        // The underlying textarea of the text editor is hidden, so it never
        // holds the base class nor the invalid state of the other components.

        $this->addErrorOnSessionFor('fname');

        $component = new Components\Form\TextEditor('fname');

        $this->assertEquals('d-none', $component->makeItemClass());

        $this->assertEquals(
            Components\Form\TextEditor::class,
            $this->getDeclaringClass($component, 'makeItemClass')
        );
    }

    public function testTheItemAttributesFollowTheResolvedItemClass()
    {
        $this->addErrorOnSessionFor('fname');

        $component = new Components\Form\Select('fname');
        $attrs = $component->makeItemAttributes();

        $this->assertEquals('form-select is-invalid', $attrs['class']);
        $this->assertEquals('true', $attrs['aria-invalid']);
    }

    /*
    |--------------------------------------------------------------------------
    | The card sections share a single class builder.
    |--------------------------------------------------------------------------
    */

    public function testTheCardSectionClassBuildersKeepTheirPublicContract()
    {
        $methods = [
            'makeCardHeaderClass', 'makeCardBodyClass', 'makeCardFooterClass',
            'makeCardTitleClass',
        ];

        foreach ($methods as $method) {
            $ref = new ReflectionMethod(Components\Widget\Card::class, $method);

            $this->assertTrue($ref->isPublic());
        }
    }

    public function testTheCardSectionClassesHoldTheBaseAndTheExtraClasses()
    {
        $component = new Components\Widget\Card(
            'Title', null, null, null, 'h-cls', 'b-cls', 'f-cls', null, null,
            null, null, 't-cls'
        );

        $this->assertEquals('card-header h-cls', $component->makeCardHeaderClass());
        $this->assertEquals('card-body b-cls', $component->makeCardBodyClass());
        $this->assertEquals('card-footer f-cls', $component->makeCardFooterClass());
        $this->assertEquals('card-title t-cls', $component->makeCardTitleClass());
    }

    public function testTheCardSectionClassesHoldOnlyTheBaseClassByDefault()
    {
        $component = new Components\Widget\Card();

        $this->assertEquals('card-header', $component->makeCardHeaderClass());
        $this->assertEquals('card-body', $component->makeCardBodyClass());
        $this->assertEquals('card-footer', $component->makeCardFooterClass());
        $this->assertEquals('card-title', $component->makeCardTitleClass());
    }

    public function testTheCardHeaderClassKeepsTheTabsModifiers()
    {
        $component = new Components\Widget\Card(
            'Title', null, null, null, 'h-cls'
        );

        $this->assertEquals(
            'card-header p-0 pt-1 h-cls', $component->makeCardHeaderClass(true)
        );
    }

    /*
    |--------------------------------------------------------------------------
    | The available sizes are class constants.
    |--------------------------------------------------------------------------
    */

    public function testTheAvailableSizesAreDeclaredAsClassConstants()
    {
        $expectations = [
            Components\Widget\Progress::class => ['sm', 'xs', 'xxs'],
            Components\Widget\Ribbon::class => ['lg', 'xl'],
            Components\Widget\UserBlock::class => ['sm'],
            Components\Tool\Modal::class => [
                'sm', 'lg', 'xl', 'fullscreen', 'fullscreen-sm-down',
                'fullscreen-md-down', 'fullscreen-lg-down',
                'fullscreen-xl-down', 'fullscreen-xxl-down',
            ],
        ];

        foreach ($expectations as $class => $sizes) {
            $ref = new ReflectionClassConstant($class, 'SIZES');

            $this->assertEquals($sizes, $ref->getValue());
            $this->assertFalse(property_exists($class, 'pSizes'));
            $this->assertFalse(property_exists($class, 'rSizes'));
            $this->assertFalse(property_exists($class, 'ubSizes'));
            $this->assertFalse(property_exists($class, 'mSizes'));
        }
    }

    public function testTheAvailableSizesStillGateTheSizeModifiers()
    {
        $progress = new Components\Widget\Progress(50, null, 'xxs');
        $ribbon = new Components\Widget\Ribbon('Label', null, 'xl');
        $modal = new Components\Tool\Modal('mid', null, null, 'fullscreen');

        $this->assertStringContainsString('progress-xxs', $progress->makeProgressClass());
        $this->assertStringContainsString('ribbon-xl', $ribbon->makeWrapperClass());
        $this->assertStringContainsString('modal-fullscreen', $modal->makeModalDialogClass());

        // An unsupported size never reaches the generated markup.

        $progress = new Components\Widget\Progress(50, null, 'md');
        $ribbon = new Components\Widget\Ribbon('Label', null, 'md');
        $modal = new Components\Tool\Modal('mid', null, null, 'md');

        $this->assertStringNotContainsString('progress-md', $progress->makeProgressClass());
        $this->assertStringNotContainsString('ribbon-md', $ribbon->makeWrapperClass());
        $this->assertStringNotContainsString('modal-md', $modal->makeModalDialogClass());
    }
}
