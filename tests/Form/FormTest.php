<?php

namespace Test\Form;

use Form\Field\FieldInterface;
use Form\FormInterface;
use InvalidArgumentException;
use LogicException;
use Symfony\Component\DomCrawler\Crawler;
use Test\Acme\Entity\Foo;
use Test\TestCase;

/**
 * Class FormTest
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class FormTest extends TestCase
{
    protected $class = 'Form\Form';

    public function test_properties(): void
    {
        $this->assertProperty('name', self::PRIVATE);
        $this->assertProperty('action', self::PRIVATE);
        $this->assertProperty('data', self::PRIVATE);
        $this->assertProperty('fields', self::PRIVATE);
        $this->assertProperty('submitted', self::PRIVATE);
    }

    public function test_constructor(): void
    {
        $this->assertConstructor([
            ['name' => 'name', 'type' => 'string'],
            ['name' => 'action', 'type' => 'string', 'nullable' => true],
        ]);

        $form = $this->createForm('foo', 'test');

        $rp = $this->getReflectionProperty('name');
        $rp->setAccessible(true);
        $this->assertEquals(
            'foo', $rp->getValue($form),
            "$this->class::__construct should initialize 'name' property with the 'name' argument value"
        );

        $rp = $this->getReflectionProperty('action');
        $rp->setAccessible(true);
        $this->assertEquals(
            'test', $rp->getValue($form),
            "$this->class::__construct should initialize 'action' property with the 'action' argument value"
        );

        $rp = $this->getReflectionProperty('fields');
        $rp->setAccessible(true);
        $this->assertEquals(
            [], $rp->getValue($form),
            "$this->class::__construct should initialize 'fields' property with an empty array"
        );

        $rp = $this->getReflectionProperty('submitted');
        $rp->setAccessible(true);
        $this->assertFalse(
            $rp->getValue($form),
            "$this->class::__construct should initialize 'submitted' property with 'FALSE'"
        );
    }

    public function test_methods(): void
    {
        $this->assertMethod('addField', self::PUBLIC, [
            ['name' => 'field', 'type' => FieldInterface::class],
        ], $this->class);

        $this->assertMethod('setAction', self::PUBLIC, [
            ['name' => 'action', 'type' => 'string'],
        ], FormInterface::class);

        $this->assertMethod('setData', self::PUBLIC, [
            ['name' => 'data', 'type' => 'object'],
        ], FormInterface::class);

        $this->assertMethod('isSubmitted', self::PUBLIC, [], 'bool');

        $this->assertMethod('bindRequest', self::PUBLIC, [
            ['name' => 'request', 'type' => 'array'],
        ]);

        $this->assertMethod('render', self::PUBLIC, [], 'string');
    }

    public function test_addField(): void
    {
        $this->skipIfMethodIsNotDefined('addField');

        $form = $this->createForm('foo');
        $form->addField($this->mockField('bar'));

        $rp = $this->getReflectionProperty('fields');
        $rp->setAccessible(true);

        $fields = $rp->getValue($form);

        $this->assertCount(1, $fields);
        $field = reset($fields);

        $this->assertInstanceOf(FieldInterface::class, $field);
        /** @var FieldInterface $field */
        $this->assertEquals('bar', $field->getName());

        try {
            $form->addField($this->mockField('bar'));
        } catch(\Exception $e) {
            $this->assertInstanceOf(
                InvalidArgumentException::class, $e,
                "$this->class::addField() method should throw an InvalidArgumentException ".
                "if a field with same name has already been added."
            );
        }
    }

    public function test_setAction(): void
    {
        $this->skipIfMethodIsNotDefined('setAction');

        $form = $this->createForm('foo');
        $form->setAction('test');

        $rp = $this->getReflectionProperty('action');
        $rp->setAccessible(true);

        $this->assertEquals(
            'test', $rp->getValue($form),
            "$this->class::setAction() method should set 'action' property value."
        );
    }

    public function test_setData(): void
    {
        $this->skipIfMethodIsNotDefined('setData');

        $form = $this->createForm('foo');
        $form->setData($data = new \stdClass);

        $rp = $this->getReflectionProperty('data');
        $rp->setAccessible(true);

        $this->assertEquals(
            $data, $rp->getValue($form),
            "$this->class::setData() method should set 'data' property value."
        );
    }

    public function test_isSubmitted(): void
    {
        $this->skipIfMethodIsNotDefined('isSubmitted');

        $form = $this->createForm('foo');

        $rp = $this->getReflectionProperty('submitted');
        $rp->setAccessible(true);

        $this->assertFalse($rp->getValue($form),
            "$this->class::isSubmitted() method should return 'submitted' property value."
        );

        $rp->setValue($form, true);
        $this->assertTrue($rp->getValue($form),
            "$this->class::isSubmitted() method should return 'submitted' property value."
        );
    }

    public function test_bindRequest_while_data_is_not_set(): void
    {
        $this->skipIfMethodIsNotDefined('bindRequest');

        $form = $this->createForm('foo');
        $form->addField($this->mockField('foo'));

        $this->expectException(LogicException::class);

        $form->bindRequest([]);
    }

    public function test_bindRequest_while_fields_is_not_set(): void
    {
        $this->skipIfMethodIsNotDefined('bindRequest');

        $form = $this->createForm('foo');
        $form->setData(new \stdClass);

        $this->expectException(LogicException::class);

        $form->bindRequest([]);
    }

    public function test_bindRequest(): void
    {
        $this->skipIfMethodIsNotDefined('bindRequest');

        $form = $this->createForm('foo');

        $field1 = $this->mockField('field1');
        $field1->expects($this->once())->method('convertToPhpValue')->with('value1')->willReturn('transformed1');
        $form->addField($field1);

        $field2 = $this->mockField('field2');
        $field2->expects($this->once())->method('convertToPhpValue')->with('value2')->willReturn('transformed2');
        $form->addField($field2);

        $data = new Foo();
        $form->setData($data);

        $form->bindRequest([
            'foo'    => 'foo',
            'field1' => 'value1',
            'field2' => 'value2',
        ]);

        $this->assertEquals(
            'transformed1', $data->getField1(),
            "$this->class::bindRequest() method should set the field's transformed value into the data object."
        );

        $this->assertEquals(
            'transformed2', $data->getField2(),
            "$this->class::bindRequest() method should set the field's transformed value into the data object."
        );
    }

    public function test_render_while_data_is_not_set(): void
    {
        $this->skipIfMethodIsNotDefined('render');

        $form = $this->createForm('foo');
        $form->addField($this->mockField('foo'));

        $this->expectException(LogicException::class);

        $form->render();
    }

    public function test_render_while_fields_is_not_set(): void
    {
        $this->skipIfMethodIsNotDefined('render');

        $form = $this->createForm('foo');
        $form->setData(new \stdClass);

        $this->expectException(LogicException::class);

        $form->render();
    }

    public function test_render(): void
    {
        $this->skipIfMethodIsNotDefined('render');

        $form = $this->createForm('form_name', 'form_action');

        $field1 = $this->mockField('field1');
        $field1->expects($this->once())->method('convertToHtmlValue')->with('value1')->willReturn('transformed1');
        $field1->expects($this->once())->method('render')->with('transformed1')
            ->willReturn('<input type="text" name="field1" value="transformed1">');
        $form->addField($field1);

        $field2 = $this->mockField('field2');
        $field2->expects($this->once())->method('convertToHtmlValue')->with('value2')->willReturn('transformed2');
        $field2->expects($this->once())->method('render')->with('transformed2')
            ->willReturn('<input type="text" name="field2" value="transformed2">');
        $form->addField($field2);

        $data = new Foo();
        $data->setField1('value1');
        $data->setField2('value2');
        $form->setData($data);

        $html = $form->render();

        $crawler = new Crawler($html);

        // Form rendering
        $form = $crawler->filter('form')->eq(0);
        $this->assertEquals(
            1, $form->count(),
            "$this->class::render() form tag not found in html output."
        );
        $this->assertEquals(
            'post', $form->attr('method'),
            "$this->class::render() method should set the 'method' attribute with 'post' value to the form tag."
        );
        $this->assertEquals(
            'form_action', $form->attr('action'),
            "$this->class::render() method should set the 'action' attribute with proper value to the form tag."
        );

        // Field1 rendering
        $field = $form->filter('input[name=field1]')->eq(0);
        $this->assertEquals(
            1, $field->count(),
            "$this->class::render() method should add rendered fields as form children."
        );

        // Field2 rendering
        $field = $form->filter('input[name=field2]')->eq(0);
        $this->assertEquals(
            1, $field->count(),
            "$this->class::render() method should add rendered fields as form children."
        );

        // Hidden field
        $hidden = $form->filter('input[type=hidden]')->eq(0);
        $this->assertEquals(
            1, $hidden->count(),
            "$this->class::render() method should add the hidden field as form child."
        );
        $this->assertEquals(
            'form_name', $hidden->attr('name'),
            "$this->class::render() method should add the 'name' attribute to the hidden field with proper value (form name)."
        );
        $this->assertEquals(
            'form_name', $hidden->attr('value'),
            "$this->class::render() method should add the 'value' attribute to the hidden field with proper value (form name)."
        );

        // Submit button rendering
        $button = $form->filter('button[type=submit]')->eq(1);
        $this->assertNotNull(
            $button,
            "$this->class::render() method should add the submit button as form child."
        );
    }

    /**
     * @return \Form\Form
     */
    private function createForm(string $name, string $action = null)
    {
        $this->skipIfClassDoesNotExist();

        return new $this->class($name, $action);
    }

    /** @return FieldInterface|\PHPUnit\Framework\MockObject\MockObject */
    private function mockField(string $name): FieldInterface
    {
        $mock = $this->createMock(FieldInterface::class);
        $mock->expects($this->any())->method('getName')->willReturn($name);

        return $mock;
    }
}
