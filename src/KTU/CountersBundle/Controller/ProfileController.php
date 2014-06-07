<?php

namespace KTU\CountersBundle\Controller;

use KTU\CountersBundle\Model\CountersModel;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Controller\ProfileController as BaseController;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class ProfileController. Profile controller, which renders user's counters and statistics of them.
 * @package KTU\CountersBundle\Controller
 */
class ProfileController extends BaseController
{
    public function showAction()
    {
        $manager = $this->container->get('doctrine')->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        $counters = CountersModel::getCountersAndStatsByUser($manager, $user->getId());

        return $this->container->get('templating')->renderResponse(
            'FOSUserBundle:Profile:show.html.'.$this->container->getParameter('fos_user.template.engine'),
            array('user' => $user, 'counters' => $counters));
    }
}