<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Entity\ProductStock;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("{_locale}/admin/product")
 */
class ProductAdminController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    private TranslatorInterface $translator;


    public function __construct(EntityManagerInterface $em, TranslatorInterface $translator) {
        $this->em = $em;
        $this->translator = $translator;
    }

    /**
     * List of products
     *
     * @Route("/", name="admin_product_index", methods={"GET"})
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('admin/product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }

    /**
     * Add new product
     *
     * @Route("/new", name="admin_product_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Create new productStock
            $stock = $form->get('stock')->getData();
            $productStock = (new ProductStock())
            ->setQuantity($stock)
                ;
            $product->setProductStock($productStock);

            // Save in database
            $this->em->persist($product);
            $this->em->flush();

            // FLash message
            $this->addFlash(
                'success',
                $this->translator->trans('product.messages.successAdd')
            );

            return $this->redirectToRoute('admin_product_show', ['id'=>$product->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    /**
     * Show a product
     * @Route("/{id}", name="admin_product_show", methods={"GET"})
     */
    public function show(Product $product): Response
    {
        return $this->render('admin/product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * Edit a product
     * @Route("/{id}/edit", name="admin_product_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Product $product): Response
    {
        $product->setStock($product->getProductStock()->getQuantity());
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        // Save the currents images
        $originalsImages = new ArrayCollection();
        foreach ($product->getProductImages() as $productImage)
        {
            $originalsImages->add($productImage);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            // Delete an  productImage if is null or has been deleted
            foreach ($product->getProductImages() as $productImage)
            {
                if($originalsImages->contains($productImage) === true && $productImage->getImageName() === null )
                {
                    $this->em->remove($productImage);
                }
            }

            // Update productStock
            $stock = $form->get('stock')->getData();
            $product->getProductStock()->setQuantity($stock);

            $this->em->flush();

            // FLash message
            $this->addFlash(
                'success',
                $this->translator->trans('product.messages.successEdit')
            );
            return $this->redirectToRoute('admin_product_show', ['id' => $product->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);
    }

    /**
     * Delete a product
     * @Route("/{id}", name="admin_product_delete", methods={"POST"})
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $this->em->remove($product);
            $this->em->flush();

            // FLash message
            $this->addFlash(
                'success',
                $this->translator->trans('product.messages.successDelete')
            );
        }

        return $this->redirectToRoute('admin_product_index', [], Response::HTTP_SEE_OTHER);
    }
}
