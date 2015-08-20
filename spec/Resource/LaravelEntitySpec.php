<?php

namespace spec\LaraPackage\Api\Resource;

use Illuminate\Database\Eloquent\Model;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LaravelEntitySpec extends ObjectBehavior
{
    function it_allows_access_to_the_data_from_the_model(EntityModelStub $entityModelStub)
    {
        $this->beConstructedWith($entityModelStub);
        $this->getData()->shouldReturnAnInstanceOf(\LaraPackage\Api\Contracts\Entity\Entity::class);
    }

    function it_blows_up_if_it_is_passed_a_model_that_is_not_also_an_entity(Model $model)
    {
        $this->shouldThrow(\LaraPackage\Api\Exceptions\InvalidArgumentException::class)->during('__construct', [$model]);
    }

    function it_is_initializable(EntityModelStub $entityModelStub)
    {
        $this->beConstructedWith($entityModelStub);
        $this->shouldHaveType('LaraPackage\Api\Resource\LaravelEntity');
    }
}

class EntityModelStub extends Model implements \LaraPackage\Api\Contracts\Entity\Entity
{

    /**
     * @return array
     */
    public function createValidationRules()
    {
        // TODO: Implement createValidationRules() method.
    }

    /**
     * @return array
     */
    public function deleteValidationRules()
    {
        // TODO: Implement deleteValidationRules() method.
    }

    /**
     * @param array $array
     *
     * @return array
     */
    public function forwardTransformAttributeKeyNames(array $array)
    {
        // TODO: Implement forwardTransformAttributeKeyNames() method.
    }

    /**
     * @return array
     */
    public function frontEndAttributeNames()
    {
        // TODO: Implement frontEndAttributeNames() method.
    }

    /**
     * @param $id
     *
     * @return array
     */
    public function replaceValidationRules($id)
    {
        // TODO: Implement replaceValidationRules() method.
    }

    /**
     * @param $id
     *
     * @return array
     */
    public function updateValidationRules($id)
    {
        // TODO: Implement updateValidationRules() method.
    }

    /**
     * @return array
     */
    public function validationMessages()
    {
        // TODO: Implement validationMessages() method.
    }
}
