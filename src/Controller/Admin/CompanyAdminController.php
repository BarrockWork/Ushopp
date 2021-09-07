<?php

namespace App\Controller\Admin;

use App\Entity\Company;
use App\Form\CompanyType;
use App\Repository\CompanyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/{_locale}/admin/company")
 */
class CompanyAdminController extends AbstractController
{
    /**
     * @Route("/", name="admin_company_index", methods={"GET"})
     */
    public function index(CompanyRepository $companyRepository): Response
    {
        $company = $companyRepository->findAll();

        // S'il n'y a pas d'entreprise on redirige vers la création
        if(!$company || count($company) === 0) {
            return $this->redirectToRoute('admin_company_new');
        }

        return $this->render('admin/company/index.html.twig', [
            'company' => $company[0]
        ]);
    }

    /**
     * @Route("/new", name="admin_company_new", methods={"GET","POST"})
     */
    public function new(Request $request, TranslatorInterface $translator, CompanyRepository $companyRepository): Response
    {
        $company = $companyRepository->findAll();

        // S'il existe déjà l'entreprise on redirige vers ses informations
        if($company && count($company) >= 0) {
            return $this->redirectToRoute('admin_company_index');
        }

        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($company);
            $entityManager->flush();

            $this->addFlash(
                'success',
                $translator->trans('company.success')
            );

            return $this->redirectToRoute('admin_company_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/company/new.html.twig', [
            'company' => $company,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_company_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TranslatorInterface $translator, Company $company): Response
    {
        // Si l'utilisateur n'a pas les droits suepradmin on redirige
        if(!in_array('ROLE_SUPER_ADMIN',$this->getUser()->getRoles()))
        {
            $this->addFlash(
                'warning',
                $translator->trans('company.superAdmin')
            );
            return $this->redirectToRoute('admin_company_index');
        }

        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash(
                'success',
                $translator->trans('company.successEdit')
            );

            return $this->redirectToRoute('admin_company_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/company/edit.html.twig', [
            'company' => $company,
            'form' => $form,
        ]);
    }
}
