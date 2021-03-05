<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use App\Services\OrderService;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class OrderController
 * @package App\Controller
 * @Route("api")
 */
class ProductController extends ApiController
{
    /**
     * @Route("/products",name="products", methods={"GET"})
     * @param OrderRepository $orderRepository
     * @return mixed
     */
    public function getAllProducts(ProductRepository $productRepository)
    {
        $data = $productRepository->findAll();
        return $this->response($data);
    }

    /**
     * @param ProductRepository $productRepository
     * @param $id
     * @return JsonResponse
     * @Route("/products/{id}", name="products_get", methods={"GET"})
     */
    public function getProductById(ProductRepository $productRepository, $id)
    {
        $product = $productRepository->findOneBy(['id' => $id]);
        if (!$product) {
            $data = [
                'status' => 404,
                'errors' => "Product not found",
            ];
            return $this->respondNotFound($data);
        }
        return $this->response($product);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param ProductRepository $productRepository
     * @return JsonResponse
     * @throws \Exception
     * @Route("/products", name="products_add", methods={"POST"})
     */
    public function addProduct(Request $request, EntityManagerInterface $entityManager, ProductRepository $productRepository)
    {
        try {
            $request = $this->transformJsonBody($request);
            if (!$request || !$request->get('name') || !$request->request->get('brand')) {
                throw new \Exception();
            }

            $order = new Product();
            $order->setName($request->get('name'));
            $order->setBrand($request->get('brand'));
            $entityManager->persist($order);
            $entityManager->flush();

            $data = [
                'status'  => 201,
                'success' => "Product added successfully",
            ];
            return $this->respondCreated($data);

        } catch (\Exception $e) {
            $data = [
                'status' => 422,
                'errors' => "Data no valid",
            ];
            return $this->respondValidationError($data);
        }
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param ProductRepository $productRepository
     * @param $id
     * @return JsonResponse
     * @Route("/products/{id}", name="products_put", methods={"PUT"})
     */
    public function updateProduct(Request $request, EntityManagerInterface $entityManager, ProductRepository $productRepository, $id)
    {
        try {
            $product = $productRepository->find($id);
            if (!$product) {
                throw new Exception();
            }

            $request = $this->transformJsonBody($request);

            // check quantity
            if ($request->get('name')) {
                $product->setName($request->get('name'));
            }
            // check address
            if ($request->get('brand')) {
                $product->setBrand($request->get('brand'));
            }
            $entityManager->flush();

            $data = [
                'status' => 200,
                'errors' => "Product updated successfully",
            ];
            return $this->response($data);

        } catch (\Exception $e) {
            $data = [
                'status' => 404,
                'errors' => "Product:$id not found",
            ];
            return $this->respondNotFound($data);
        }

    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param ProductRepository $productRepository
     * @param $id
     * @return JsonResponse
     * @Route("/products/{id}", name="products_delete", methods={"DELETE"})
     */
    public function deleteProduct(EntityManagerInterface $entityManager, ProductRepository $productRepository, $id)
    {
        $product = $productRepository->find($id);
        if (!$product) {
            $data = [
                'status' => 404,
                'errors' => "Product not found",
            ];
            return $this->respondNotFound($data);
        }
        $entityManager->remove($product);
        $entityManager->flush();
        $data = [
            'status' => 200,
            'errors' => "Product deleted successfully",
        ];
        return $this->response($data);
    }
}
