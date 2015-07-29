<?php
namespace LaraPackage\Api\Contracts\Entity\Transformer;

use LaraPackage\Api\Contracts\Entity\Transformer\ForwardTransformer;
use LaraPackage\Api\Contracts\Entity\Transformer\ReverseTransformer;

interface Transformer extends ReverseTransformer, ForwardTransformer
{

}
