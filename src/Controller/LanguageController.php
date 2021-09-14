<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/{_locale}/language")
 */
class LanguageController extends AbstractController
{
    /**
     * Change the language
     *
     * @Route("/{lang}/{routeName}", name="choose_language")
     */
    public function chooseLangage(Request $request, $lang, $routeName): Response
    {
        $idItem = $request->get('idItem');

        if(strcmp($lang, 'fr') === 0)
        {
            $request->setLocale('fr');
        }else{
            $request->setLocale('en');
        }

        if($idItem){
            $currentUrlTranslate = $this->generateUrl($routeName, ['_locale' => $lang, 'id' => $idItem]);

        }else{
            $currentUrlTranslate = $this->generateUrl($routeName, ['_locale' => $lang]);

        }

        return $this->redirect($currentUrlTranslate);
    }
}
