<?php
namespace LaraPackage\Api\Model;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractModel extends Model
{

    /**
     * @return \LaraPackage\Api\Contracts\Entity\Transformer\Transformer
     */
    abstract protected function transformer();

    /**
     * @inheritdoc
     */
    public function forwardTransformAttributeKeyNames(array $array)
    {
        return $this->transformer()->forwardTransform($array);
    }

    /**
     * @inheritdoc
     */
    public function frontEndAttributeNames()
    {
        return $this->transformer()->mappings();
    }
}
