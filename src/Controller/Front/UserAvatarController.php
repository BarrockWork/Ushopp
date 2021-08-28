<?php

namespace App\Controller\Front;

use App\Entity\UserAvatar;
use App\Form\UserAvatarType;
use App\Repository\UserAvatarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\Translation\t;

/**
 * @Route("/{_locale}/avatar")
 */
class UserAvatarController extends AbstractController
{
}
