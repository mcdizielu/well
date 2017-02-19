<?php

namespace WellCommerce\Component\DoctrineEnhancer\TraitGenerator;

use gossi\codegen\generator\CodeGenerator;
use gossi\codegen\model\PhpMethod;
use gossi\codegen\model\PhpParameter;
use gossi\codegen\model\PhpProperty;
use gossi\codegen\model\PhpTrait;
use Symfony\Component\Filesystem\Filesystem;
use WellCommerce\Component\DoctrineEnhancer\Definition\MappingDefinitionCollection;
use WellCommerce\Component\DoctrineEnhancer\Definition\MappingDefinitionInterface;
use WellCommerce\Component\DoctrineEnhancer\MappingEnhancerInterface;

/**
 * Class TraitGenerator
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class TraitGenerator
{
    /**
     * @var PhpTrait
     */
    private $trait;
    
    /**
     * @var \ReflectionClass
     */
    private $reflectionClass;
    
    /**
     * @var array
     */
    private $enhancers;
    
    public function __construct(string $class, array $enhancers)
    {
        $this->reflectionClass = new \ReflectionClass($class);
        $this->enhancers       = $enhancers;
        $this->trait           = PhpTrait::fromFile($this->reflectionClass->getFileName());
        $this->trait->clearProperties();
        $this->trait->clearMethods();
    }
    
    public function generate(bool $dump = true): string
    {
        $generator = new CodeGenerator([
            'generateDocblock'        => false,
            'generateEmptyDocblock'   => false,
            'generateScalarTypeHints' => true,
            'generateReturnTypeHints' => true,
            'propertySorting'         => true,
        ]);
        
        /** @var MappingEnhancerInterface $enhancer */
        foreach ($this->enhancers as $enhancer) {
            $this->addFields($enhancer->getMappingDefinitionCollection());
        }
        
        $code = '<?php' . str_repeat(PHP_EOL, 2) . $generator->generate($this->trait);
        
        if ($dump) {
            $filesystem = new Filesystem();
            $filesystem->dumpFile($this->reflectionClass->getFileName(), $code);
        }
        
        return $code;
    }
    
    private function addFields(MappingDefinitionCollection $fields)
    {
        $fields->forAll(function (MappingDefinitionInterface $definition) {
            $this->addProperty($definition->getPropertyName());
            $this->addGetterMethod($definition->getPropertyName());
            $this->addSetterMethod($definition->getPropertyName());
        });
    }
    
    private function addProperty(string $property)
    {
        $property = PhpProperty::create($property);
        $property->setVisibility('private');
        $property->setType('string');
        $property->setValue(null);
        
        $this->trait->setProperty($property);
    }
    
    private function addGetterMethod(string $property)
    {
        $methodName   = 'get' . $this->convertToStudlyCase($property);
        $variableName = strval($property);
        $method       = PhpMethod::create($methodName);
        $method->setBody('return $this->' . $variableName . ';');
        $method->setVisibility('public');
        
        $this->trait->setMethod($method);
    }
    
    private function addSetterMethod(string $property)
    {
        $methodName   = 'set' . $this->convertToStudlyCase($property);
        $variableName = strval($property);
        $method       = PhpMethod::create($methodName);
        $method->setBody('$this->' . $variableName . ' = $' . $variableName . ';');
        $method->setVisibility('public');
        
        $parameter = new PhpParameter($variableName);
        $parameter->setType('string');
        
        $method->addParameter($parameter);
        
        $this->trait->setMethod($method);
    }
    
    private function convertToStudlyCase(string $value): string
    {
        $value = ucwords(str_replace(['-', '_'], ' ', $value));
        
        return str_replace(' ', '', $value);
    }
}
