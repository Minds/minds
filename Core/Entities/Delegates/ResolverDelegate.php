<?php
/**
 * ResolverDelegate.
 *
 * @author emi
 */

namespace Minds\Core\Entities\Delegates;

use Minds\Common\Urn;

interface ResolverDelegate
{
    /**
     * @param Urn $urn
     * @return boolean
     */
    public function shouldResolve(Urn $urn);

    /**
     * @param Urn[] $urns
     * @param array $opts
     * @return mixed
     */
    public function resolve(array $urns, array $opts = []);

    /**
     * @param mixed $entity
     * @return string|null
     */
    public function asUrn($entity);
}
